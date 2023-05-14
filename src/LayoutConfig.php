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
	public readonly string $widowLayoutStyle;

	public function __construct(
		int $containerWidth = 1060,
		int|LeftRightTopBottom $containerPadding = 10,
		int|HorizontalVertical $boxSpacing = 10,
		int $targetRowHeight = 320,
		float $targetRowHeightTolerance = 0.25,
		false|int $maxNumRows = false,
		false|float $forceAspectRatio = false,
		bool $showWidows = true,
		false|int $fullWidthBreakoutRowCadence = false,
		string $widowLayoutStyle = 'left',
	) {
		$this->containerWidth = $containerWidth;
		$this->targetRowHeight = $targetRowHeight;
		$this->targetRowHeightTolerance = $targetRowHeightTolerance;
		$this->maxNumRows = $maxNumRows;
		$this->forceAspectRatio = $forceAspectRatio;
		$this->showWidows = $showWidows;
		$this->fullWidthBreakoutRowCadence = $fullWidthBreakoutRowCadence;
		$this->widowLayoutStyle = $widowLayoutStyle;

		if (is_int($containerPadding)) {
			$containerPadding = new LeftRightTopBottom($containerPadding, $containerPadding, $containerPadding, $containerPadding);
		}

		$this->containerPadding = $containerPadding;

		if (is_int($boxSpacing)) {
			$boxSpacing = new HorizontalVertical($boxSpacing, $boxSpacing);
		}

		$this->boxSpacing = $boxSpacing;
	}
}