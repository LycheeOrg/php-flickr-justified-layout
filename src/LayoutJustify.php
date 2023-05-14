<?php

namespace LycheeOrg\PhpFlickrJustifiedLayout;

use Illuminate\Support\Collection;
use LycheeOrg\PhpFlickrJustifiedLayout\Contracts\AspectRatio;
use LycheeOrg\PhpFlickrJustifiedLayout\Contracts\WidthHeight;
use LycheeOrg\PhpFlickrJustifiedLayout\DTO\Item;
use LycheeOrg\PhpFlickrJustifiedLayout\DTO\LayoutData;
use LycheeOrg\PhpFlickrJustifiedLayout\DTO\Param;
use LycheeOrg\PhpFlickrJustifiedLayout\DTO\Geometry;

class LayoutJustify
{
	/** @var LayoutData contains the current state of the layout */
	private LayoutData $layoutData;

	/** @var LayoutConfig Contains the Layout configuration */
	private LayoutConfig $layoutConfig;

	/**
	 * Create a new, empty row.
	 *
	 * @return Row new, empty row of the type specified by this layout
	 */
	private function createNewRow(): Row
	{
		$isBreakoutRow = is_int($this->layoutConfig->fullWidthBreakoutRowCadence)
			&& (($this->layoutData->_rows->count() + 1) % $this->layoutConfig->fullWidthBreakoutRowCadence) === 0;

		$params = new Param(
			top: $this->layoutData->_containerHeight,
			left: $this->layoutConfig->containerPadding->left,
			width: $this->layoutConfig->containerWidth - $this->layoutConfig->containerPadding->left - $this->layoutConfig->containerPadding->right,
			spacing: $this->layoutConfig->boxSpacing->horizontal,
			targetRowHeight: $this->layoutConfig->targetRowHeight,
			targetRowHeightTolerance: $this->layoutConfig->targetRowHeightTolerance,
			edgeCaseMinRowHeight: intval(0.5 * $this->layoutConfig->targetRowHeight),
			edgeCaseMaxRowHeight: 2 * $this->layoutConfig->targetRowHeight,
			rightToLeft: false,
			isBreakoutRow: $isBreakoutRow,
			widowLayoutStyle: $this->layoutConfig->widowLayoutStyle
		);

		return new Row($params);
	}

	/**
	 * Add a completed row to the layout.
	 * Note: the row must have already been completed.
	 *
	 * @param Row $row
	 *
	 * @return Collection<int,Item>
	 */
	private function addRow(Row $row): Collection
	{
		$this->layoutData->_rows->add($row);
		$this->layoutData->_layoutItems = $this->layoutData->_layoutItems->concat($row->getItems()->all());

		// Increment the container height
		$this->layoutData->_containerHeight += $row->height + $this->layoutConfig->boxSpacing->vertical;

		return $row->getItems();
	}

	/**
	 * Calculate the current layout for all items in the list that require layout.
	 * "Layout" means geometry: position within container and size.
	 *
	 * @param Collection<int,Item> $itemLayoutData Array of items to lay out, with data required to lay out each item
	 *
	 * @return Geometry The newly-calculated layout, containing the new container height, and lists of layout items
	 */
	private function computeLayout(Collection $itemLayoutData)
	{
		// Apply forced aspect ratio if specified, and set a flag.
		if ($this->layoutConfig->forceAspectRatio !== false) {
			$itemLayoutData->each(function (Item &$itemData) {
				$itemData->forcedAspectRatio = true;
				$itemData->aspectRatio = $this->layoutConfig->forceAspectRatio; // @phpstan-ignore-line false positive
			});
		}

		$currentRow = $this->createNewRow();
		$laidOutItems = new Collection();

		// Loop through the items
		$itemLayoutData->some(function ($itemData, $i) use (&$currentRow, &$laidOutItems) {
			// Attempt to add item to the current row.
			$itemAdded = $currentRow->addItem($itemData);

			if ($currentRow->isLayoutComplete()) {
				// Row is filled; add it and start a new one
				$laidOutItems = $laidOutItems->concat($this->addRow($currentRow)->all());

				if ($this->layoutConfig->maxNumRows !== false && $this->layoutData->_rows->count() >= $this->layoutConfig->maxNumRows) {
					$currentRow = null;

					return true;
				}

				$currentRow = $this->createNewRow();

				// Item was rejected; add it to its own row
				if (!$itemAdded) {
					$itemAdded = $currentRow->addItem($itemData);

					if ($currentRow->isLayoutComplete()) {
						// If the rejected item fills a row on its own, add the row and start another new one
						$laidOutItems = $laidOutItems->concat($this->addRow($currentRow)->all());
						if ($this->layoutConfig->maxNumRows !== false && $this->layoutData->_rows->count() >= $this->layoutConfig->maxNumRows) {
							$currentRow = null;

							return true;
						}
						$currentRow = $this->createNewRow();
					}
				}
			}

			return false;
		});

		// Handle any leftover content (orphans) depending on where they lie
		// in this layout update, and in the total content set.
		if ($currentRow !== null && $currentRow->getItems()->count() > 0 && $this->layoutConfig->showWidows) {
			// Last page of all content or orphan suppression is suppressed; lay out orphans.
			if ($this->layoutData->_rows->count() > 0) {
				// Only Match previous row's height if it exists, and it isn't a breakout row
				if ($this->layoutData->_rows->last()->isBreakoutRow) {
					$nextToLastRowHeight = $this->layoutData->_rows->last()->targetRowHeight;
				} else {
					$nextToLastRowHeight = $this->layoutData->_rows->last()->height;
				}

				$currentRow->forceComplete(false, $nextToLastRowHeight);
			} else {
				// ...else use target height if there is no other row height to reference.
				$currentRow->forceComplete(false);
			}

			$laidOutItems = $laidOutItems->concat($this->addRow($currentRow)->all());
			$this->layoutData->_widowCount = $currentRow->getItems()->count();
		}

		// We need to clean up the bottom container padding
		// First remove the height added for box spacing
		$this->layoutData->_containerHeight = $this->layoutData->_containerHeight - $this->layoutConfig->boxSpacing->vertical;
		// Then add our bottom container padding
		$this->layoutData->_containerHeight = $this->layoutData->_containerHeight + $this->layoutConfig->containerPadding->bottom;

		return new Geometry($this->layoutData);
	}

	/**
	 * Takes in a bunch of box data and config. Returns
	 * geometry to lay them out in a justified view.
	 *
	 * @param Collection<int,AspectRatio>|Collection<int,WidthHeight> $input  Array of objects with widths and heights
	 * @param LayoutConfig                                            $config Configuration
	 *
	 * @return Geometry A list of aspect ratios
	 */
	public function compute(Collection $input, LayoutConfig $config = new LayoutConfig()): Geometry
	{
		$this->layoutConfig = $config;
		$this->layoutData = new LayoutData();
		$this->layoutData->_containerHeight = $config->containerPadding->top;

		// Convert widths and heights to aspect ratios if we need to
		$items = $input->map(fn (AspectRatio|WidthHeight $it) => ($it instanceof WidthHeight) ? Item::ofWidthHeight($it) : Item::ofAspectRatio($it));

		return $this->computeLayout($items);
	}
}