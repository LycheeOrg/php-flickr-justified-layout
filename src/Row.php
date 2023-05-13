<?php

namespace LycheeOrg\PhpFlickrJustifiedLayout;

use Illuminate\Support\Collection;
use LycheeOrg\PhpFlickrJustifiedLayout\DTO\Item;
use LycheeOrg\PhpFlickrJustifiedLayout\DTO\Param;

class Row
{
	/**
	 * Top of row, relative to container.
	 */
	private int $top;

	/**
	 * Left side of row relative to container (equal to container left padding).
	 */
	private int $left;
	/**
	 * Width of row, not including container padding.
	 */
	private int $width;
	/**
	 * Horizontal spacing between items.
	 */
	private int $spacing;

	/**
	 * Row height calculation values.
	 */
	public int $targetRowHeight;
	private float $minAspectRatio;
	private float $maxAspectRatio;

	/**
	 * Edge case row height minimum/maximum.
	 */
	private int $edgeCaseMinRowHeight;
	private int $edgeCaseMaxRowHeight;

	/**
	 * Widow layout direction.
	 */
	private string $widowLayoutStyle;

	/**
	 * Full width breakout rows.
	 */
	public bool $isBreakoutRow;

	/**
	 * @var Collection<int,Item> store layout data for each item in row
	 */
	private Collection $items;

	/**
	 * Height remains at 0 until it's been calculated.
	 */
	public int $height;

	public function __construct(Param $param)
	{
		// Top of row, relative to container
		$this->top = $param->top;

		// Left side of row relative to container (equal to container left padding)
		$this->left = $param->left;

		// Width of row, not including container padding
		$this->width = $param->width;

		// Horizontal spacing between items
		$this->spacing = $param->spacing;

		// Row height calculation values
		$this->targetRowHeight = $param->targetRowHeight;
		$this->minAspectRatio = $this->width / $param->targetRowHeight * (1 - $param->targetRowHeightTolerance);
		$this->maxAspectRatio = $this->width / $param->targetRowHeight * (1 + $param->targetRowHeightTolerance);

		// Edge case row height minimum/maximum
		$this->edgeCaseMinRowHeight = $param->edgeCaseMinRowHeight;
		$this->edgeCaseMaxRowHeight = $param->edgeCaseMaxRowHeight;

		$this->widowLayoutStyle = $param->widowLayoutStyle;

		$this->isBreakoutRow = $param->isBreakoutRow;

		$this->items = new Collection();

		$this->height = 0;
	}

    /**
    /**
     * Attempt to add a single item to the row.
     * This is the heart of the justified algorithm.
     * This method is direction-agnostic; it deals only with sizes, not positions.
     *
     * If the item fits in the row, without pushing row height beyond min/max tolerance,
     * the item is added and the method returns true.
     *
     * If the item leaves row height too high, there may be room to scale it down and add another item.
     * In this case, the item is added and the method returns true, but the row is incomplete.
     *
     * If the item leaves row height too short, there are too many items to fit within tolerance.
     * The method will either accept or reject the new item, favoring the resulting row height closest to within tolerance.
     * If the item is rejected, left/right padding will be required to fit the row height within tolerance;
     * if the item is accepted, top/bottom cropping will be required to fit the row height within tolerance.
     *
     * @param Item $itemData Item layout data, containing item aspect ratio.
     * @return bool True if successfully added; false if rejected.
     */
	public function addItem(Item $itemData): bool
	{
		$newItems = $this->items->concat([$itemData]);

		$rowWidthWithoutSpacing = $this->width - ($newItems->count() - 1) * $this->spacing;
        $newAspectRatio = $newItems->reduce(fn (float $sum, Item $item) => $sum + $item->aspectRatio, 0);
        $targetAspectRatio = $rowWidthWithoutSpacing / $this->targetRowHeight;

		// Handle big full-width breakout photos if we're doing them
		if ($this->isBreakoutRow) {
			// Only do it if there's no other items in this row
			if ($this->items->count() === 0) {
				// Only go full width if this photo is a square or landscape
				if ($itemData->aspectRatio >= 1) {
					// Close out the row with a full width photo
					$this->items->add($itemData);
					$this->completeLayout(intval($rowWidthWithoutSpacing / $itemData->aspectRatio), 'justify');

					return true;
				}
			}
		}


		if ($newAspectRatio < $this->minAspectRatio) {
			// New aspect ratio is too narrow / scaled row height is too tall.
			// Accept this item and leave row open for more items.

			$this->items->add($itemData);

			return true;
		} elseif ($newAspectRatio > $this->maxAspectRatio) {
			// New aspect ratio is too wide / scaled row height will be too short.
			// Accept item if the resulting aspect ratio is closer to target than it would be without the item.
			// NOTE: Any row that falls into this block will require cropping/padding on individual items.

			if ($this->items->count() === 0) {
				// When there are no existing items, force acceptance of the new item and complete the layout.
				// This is the pano special case.
				$this->items->add($itemData);
				$this->completeLayout(intval($rowWidthWithoutSpacing / $newAspectRatio), 'justify');

				return true;
			}

			// Calculate width/aspect ratio for row before adding new item
			$previousRowWidthWithoutSpacing = $this->width - ($this->items->count() - 1) * $this->spacing;
			$previousAspectRatio = $this->items->reduce(fn (float $sum, Item $item) => $sum + $item->aspectRatio, 0);
			$previousTargetAspectRatio = $previousRowWidthWithoutSpacing / $this->targetRowHeight;

			if (abs($newAspectRatio - $targetAspectRatio) > abs($previousAspectRatio - $previousTargetAspectRatio)) {
				// Row with new item is us farther away from target than row without; complete layout and reject item.
				$this->completeLayout(intval($previousRowWidthWithoutSpacing / $previousAspectRatio), 'justify');

				return false;
			} else {
				// Row with new item is us closer to target than row without;
				// accept the new item and complete the row layout.
				$this->items->add($itemData);
				$this->completeLayout(intval($rowWidthWithoutSpacing / $newAspectRatio), 'justify');

				return true;
			}
		} else {
			// New aspect ratio / scaled row height is within tolerance;
			// accept the new item and complete the row layout.
			$this->items->add($itemData);
			$this->completeLayout(intval($rowWidthWithoutSpacing / $newAspectRatio), 'justify');

			return true;
		}
	}

	/**
	 * Check if a row has completed its layout.
	 *
	 * @return bool true if complete; false if not
	 */
	public function isLayoutComplete(): bool
	{
		return $this->height > 0;
	}

	/**
	 * Set row height and compute item geometry from that height.
	 * Will justify items within the row unless instructed not to.
	 *
	 * @param int    $newHeight        Set row height to this value
	 * @param string $widowLayoutStyle How should widows display? Supported: left | justify | center
	 *
	 * @return void
	 */
	public function completeLayout(int $newHeight, string $widowLayoutStyle): void
	{
		$itemWidthSum = $this->left;
		$rowWidthWithoutSpacing = $this->width - ($this->items->count() - 1) * $this->spacing;

		// Set to the Left unless explicitly specified otherwise.
		if (!in_array($widowLayoutStyle, ['justify', 'center', 'left'], true)) {
			$widowLayoutStyle = 'left';
		}

		// Clamp row height to edge case minimum/maximum.
		$clampedHeight = max($this->edgeCaseMinRowHeight, min($newHeight, $this->edgeCaseMaxRowHeight));

		if ($newHeight !== $clampedHeight) {
			// If row height was clamped, the resulting row/item aspect ratio will be off,
			// so force it to fit the width (recalculate aspectRatio to match clamped height).
			// NOTE: this will result in cropping/padding commensurate to the amount of clamping.
			$this->height = $clampedHeight;
			$clampedToNativeRatio = ($rowWidthWithoutSpacing / $clampedHeight) / ($rowWidthWithoutSpacing / $newHeight);
		} else {
			// If not clamped, leave ratio at 1.0.
			$this->height = $newHeight;
			$clampedToNativeRatio = 1.0;
		}

		// Compute item geometry based on $newHeight.
		$this->items->each(
			function (Item &$item) use ($clampedToNativeRatio, &$itemWidthSum) {
				$item->top = $this->top;
				$item->width = intval($item->aspectRatio * $this->height * $clampedToNativeRatio);
				$item->height = $this->height;

				// Left-to-right.
				// TODO right to left
				// item.left = $this->>width - itemWidthSum - item.width;
				$item->left = $itemWidthSum;

				// Increment width.
				$itemWidthSum += $item->width + $this->spacing;
			});

		// If specified, ensure items fill row and distribute error
		// caused by rounding width and height across all items.
		if ($widowLayoutStyle === 'justify') {
			$itemWidthSum -= ($this->spacing + $this->left);

			$errorWidthPerItem = ($itemWidthSum - $this->width) / $this->items->count();
			$roundedCumulativeErrors = $this->items->map(function (Item $item, int $i) use ($errorWidthPerItem) {
				return (int) round(($i + 1) * $errorWidthPerItem);
			});

			if ($this->items->count() === 1) {
				// For rows with only one item, adjust item width to fill row.
				$singleItemGeometry = $this->items->get(0);
				$singleItemGeometry->width -= (int) round($errorWidthPerItem);
			} else {
				// For rows with multiple items, adjust item width and shift items to fill the row,
				// while maintaining equal spacing between items in the row.
				$this->items->each(function (&$item, $i) use ($roundedCumulativeErrors) {
					if ($i > 0) {
						$item->left -= $roundedCumulativeErrors->get($i - 1);
						$item->width -= ($roundedCumulativeErrors->get($i) - $roundedCumulativeErrors->get($i - 1));
					} else {
						$item->width -= $roundedCumulativeErrors->get($i);
					}
				});
			}
		} elseif ($widowLayoutStyle === 'center') {
			// Center widows
			$centerOffset = ($this->width - $itemWidthSum) / 2;
			$this->items->each(fn ($item) => $item->left += $centerOffset + $this->spacing);
		}
	}

	/**
	 * Force completion of row layout with current items.
	 *
	 * @param bool     $fitToWidth Stretch current items to fill the row width. This will likely result in padding.
	 * @param int|null $rowHeight
	 *
	 * @return void
	 */
	public function forceComplete(bool $fitToWidth, ?int $rowHeight = null): void
	{
		// TODO Handle fitting to width
		// var rowWidthWithoutSpacing = this.width - (this.items.length - 1) * this.spacing,
		// 	currentAspectRatio = this.items.reduce(function (sum, item) {
		// 		return sum + item.aspectRatio;
		// 	}, 0);
		if (is_int($rowHeight)) {
			$this->completeLayout($rowHeight, $this->widowLayoutStyle);
		} else {
			// Complete using target row height.
			$this->completeLayout($this->targetRowHeight, $this->widowLayoutStyle);
		}
	}

	/**
	 * Return layout data for items within row.
	 * Note: returns actual list, not a copy.
	 *
	 * @return Collection<int,Item> data for items within row
	 */
	public function getItems(): Collection
	{
		return $this->items;
	}
}