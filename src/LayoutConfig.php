<?php

namespace LycheeOrg\PhpFlickrJustifiedLayout;

use LycheeOrg\PhpFlickrJustifiedLayout\DTO\HorizontalVertical;
use LycheeOrg\PhpFlickrJustifiedLayout\DTO\LeftRightTopBottom;

class LayoutConfig
{
	public readonly int $containerWidth;
	public readonly LeftRightTopBottom $containerPadding;
	public readonly HorizontalVertical $boxSpacing;
	public readonly int $targetRowHeight;
	public readonly float $targetRowHeightTolerance;
	public readonly false|int $maxNumRows;
	public readonly false|float $forceAspectRatio;
	public readonly bool $showWidows;
	public readonly false|int $fullWidthBreakoutRowCadence;
	public string $widowLayoutStyle;

	public function __construct(
		null|int $containerWidth = null,
		null|int|LeftRightTopBottom $containerPadding = null,
		null|int|HorizontalVertical $boxSpacing = null,
		null|int $targetRowHeight = null,
		null|float $targetRowHeightTolerance = null,
		null|false|int $maxNumRows = null,
		null|false|float $forceAspectRatio = null,
		null|bool $showWidows = null,
		null|false|int $fullWidthBreakoutRowCadence = null,
		null|string $widowLayoutStyle = null,
	) {
		$this->containerWidth = $containerWidth ?? 1060;
		$this->targetRowHeight = $targetRowHeight ?? 320;
		$this->targetRowHeightTolerance = $targetRowHeightTolerance ?? 0.25;
		$this->maxNumRows = $maxNumRows ?? false;
		$this->forceAspectRatio = $forceAspectRatio ?? false;
		$this->showWidows = $showWidows ?? true;
		$this->fullWidthBreakoutRowCadence = $fullWidthBreakoutRowCadence ?? false;
		$this->widowLayoutStyle = $widowLayoutStyle ?? 'left';

		if (is_int($containerPadding)) {
			$containerPadding = new LeftRightTopBottom($containerPadding, $containerPadding, $containerPadding, $containerPadding);
		}

		$this->containerPadding = $containerPadding ?? new LeftRightTopBottom(10, 10, 10, 10);

		if (is_int($boxSpacing)) {
			$boxSpacing = new HorizontalVertical($boxSpacing, $boxSpacing);
		}

		$this->boxSpacing = $boxSpacing ?? new HorizontalVertical(10, 10);
	}
}