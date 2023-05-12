<?php

namespace LycheeOrg\PhpFlickrJustifiedLayout;

use Illuminate\Support\Collection;
use LycheeOrg\PhpFlickrJustifiedLayout\Configs\LayoutConfig;
use LycheeOrg\PhpFlickrJustifiedLayout\DTO\Item;
use LycheeOrg\PhpFlickrJustifiedLayout\DTO\LayoutData;
use LycheeOrg\PhpFlickrJustifiedLayout\DTO\Param;
use LycheeOrg\PhpFlickrJustifiedLayout\DTO\Result;

class Initialization
{
	/**
	 * Create a new, empty row.
	 *
	 * @param LayoutConfig $layoutConfig {Object} The layout configuration
	 * @param LayoutData   $layoutData   {Object} The current state of the layout
	 *
	 * @return Row new, empty row of the type specified by this layout
	 */
	public function createNewRow(LayoutConfig $layoutConfig, LayoutData $layoutData): Row
	{
		$isBreakoutRow = is_int($layoutConfig->fullWidthBreakoutRowCadence)
			&& (($layoutData->_rows->count() + 1) % $layoutConfig->fullWidthBreakoutRowCadence) === 0;

		$params = new Param(
			top: $layoutData->_containerHeight,
			left: $layoutConfig->containerPadding->left,
			width: $layoutConfig->containerWidth - $layoutConfig->containerPadding->left - $layoutConfig->containerPadding->right,
			spacing: $layoutConfig->boxSpacing->horizontal,
			targetRowHeight: $layoutConfig->targetRowHeight,
			targetRowHeightTolerance: $layoutConfig->targetRowHeightTolerance,
			edgeCaseMinRowHeight: intval(0.5 * $layoutConfig->targetRowHeight),
			edgeCaseMaxRowHeight: 2 * $layoutConfig->targetRowHeight,
			rightToLeft: false,
			isBreakoutRow: $isBreakoutRow,
			widowLayoutStyle: $layoutConfig->widowLayoutStyle
		);

		return new Row($params);
	}

	/**
	 * Add a completed row to the layout.
	 * Note: the row must have already been completed.
	 *
	 * @param LayoutConfig $layoutConfig
	 * @param LayoutData   $layoutData
	 * @param Row          $row
	 *
	 * @return Collection<int,Item>
	 */
	public function addRow(LayoutConfig $layoutConfig, LayoutData $layoutData, Row $row): Collection
	{
		$layoutData->_rows->add($row);
		$layoutData->_layoutItems = $layoutData->_layoutItems->concat($row->getItems()->all());

		// Increment the container height
		$layoutData->_containerHeight += $row->height + $layoutConfig->boxSpacing->vertical;

		return $row->getItems();
	}

	/**
	 * Calculate the current layout for all items in the list that require layout.
	 * "Layout" means geometry: position within container and size.
	 *
	 * @param LayoutConfig         $layoutConfig   The layout configuration
	 * @param LayoutData           $layoutData     The current state of the layout
	 * @param Collection<int,Item> $itemLayoutData Array of items to lay out, with data required to lay out each item
	 *
	 * @return Result The newly-calculated layout, containing the new container height, and lists of layout items
	 */
	public function computeLayout(LayoutConfig $layoutConfig, LayoutData $layoutData, Collection $itemLayoutData)
	{
		// Apply forced aspect ratio if specified, and set a flag.
		if ($layoutConfig->forceAspectRatio !== false) {
			$itemLayoutData->each(function (Item $itemData) use ($layoutConfig) {
				$itemData->forcedAspectRatio = true;
				$itemData->aspectRatio = $layoutConfig->forceAspectRatio;
			}
			);
		}

		/** @var ?Row $currentRow */
		$currentRow = null;
		$laidOutItems = new Collection();

		// Loop through the items
		$itemLayoutData->some(function ($itemData, $i) use (&$currentRow, $layoutData, $layoutConfig, &$laidOutItems) {
			// If not currently building up a row, make a new one.
			if ($currentRow === null) {
				$currentRow = $this->createNewRow($layoutConfig, $layoutData);
			}

			// Attempt to add item to the current row.
			$itemAdded = $currentRow->addItem($itemData);

			if ($currentRow->isLayoutComplete()) {
				// Row is filled; add it and start a new one
				$laidOutItems = $laidOutItems->concat($this->addRow($layoutConfig, $layoutData, $currentRow)->all());

				if ($layoutData->_rows->count() >= $layoutConfig->maxNumRows) {
					$currentRow = null;

					return true;
				}

				$currentRow = $this->createNewRow($layoutConfig, $layoutData);

				// Item was rejected; add it to its own row
				if (!$itemAdded) {
					$itemAdded = $currentRow->addItem($itemData);

					if ($currentRow->isLayoutComplete()) {
						// If the rejected item fills a row on its own, add the row and start another new one
						$laidOutItems = $laidOutItems->concat($this->addRow($layoutConfig, $layoutData, $currentRow)->all());
						if ($layoutData->_rows->count() >= $layoutConfig->maxNumRows) {
							$currentRow = null;

							return true;
						}
						$currentRow = $this->createNewRow($layoutConfig, $layoutData);
					}
				}
			}
		});

		// Handle any leftover content (orphans) depending on where they lie
		// in this layout update, and in the total content set.
		if ($currentRow !== null && $currentRow->getItems()->count() > 0 && $layoutConfig->showWidows) {
			// Last page of all content or orphan suppression is suppressed; lay out orphans.
			if ($layoutData->_rows->count() > 0) {
				// Only Match previous row's height if it exists and it isn't a breakout row
				if ($layoutData->_rows->last()->isBreakoutRow) {
					$nextToLastRowHeight = $layoutData->_rows->last()->targetRowHeight;
				} else {
					$nextToLastRowHeight = $layoutData->_rows->last()->height;
				}

				$currentRow->forceComplete(false, $nextToLastRowHeight);
			} else {
				// ...else use target height if there is no other row height to reference.
				$currentRow->forceComplete(false);
			}

			$laidOutItems = $laidOutItems->concat($this->addRow($layoutConfig, $layoutData, $currentRow)->all());
			$layoutData->_widowCount = $currentRow->getItems()->count();
		}

		// We need to clean up the bottom container padding
		// First remove the height added for box spacing
		$layoutData->_containerHeight = $layoutData->_containerHeight - $layoutConfig->boxSpacing->vertical;
		// Then add our bottom container padding
		$layoutData->_containerHeight = $layoutData->_containerHeight + $layoutConfig->containerPadding->bottom;

		return new Result($layoutData);
	}
}