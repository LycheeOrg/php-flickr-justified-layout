<?php

namespace LycheeOrg\PhpFlickrJustifiedLayout\Configs;

use LycheeOrg\PhpFlickrJustifiedLayout\DTO\HV;
use LycheeOrg\PhpFlickrJustifiedLayout\DTO\LRTB;

class LayoutConfig
{
	public int $containerWidth;
	public LRTB $containerPadding;
	public HV $boxSpacing;
	public int $targetRowHeight;
	public float $targetRowHeightTolerance;
	public int $maxNumRows;
	public false|float $forceAspectRatio;
	public bool $showWidows;
	public false|int $fullWidthBreakoutRowCadence;

	public int $containerHeight = 0;
	public string $widowLayoutStyle = 'justified';

	public function __construct(
		?int $containerWidth,
		null|int|LRTB $containerPadding,
		null|int|HV $boxSpacing,
		?int $targetRowHeight,
		?float $targetRowHeightTolerance,
		?int $maxNumRows,
		null|false|float $forceAspectRatio,
		?bool $showWidows,
		null|false|int $fullWidthBreakoutRowCadence,
	) {
		$this->containerWidth = $containerWidth ?? 1060;
		$this->targetRowHeight = $targetRowHeight ?? 320;
		$this->targetRowHeightTolerance = $targetRowHeightTolerance ?? 0.25;
		$this->maxNumRows = $maxNumRows ?? -1;
		$this->forceAspectRatio = $forceAspectRatio ?? false;
		$this->showWidows = $showWidows ?? true;
		$this->fullWidthBreakoutRowCadence = $fullWidthBreakoutRowCadence ?? false;

		if (is_int($containerPadding)) {
			$this->containerPadding = new LRTB($containerPadding, $containerPadding, $containerPadding, $containerPadding);
		} else {
			$this->containerPadding = $containerPadding ?? new LRTB(10, 10, 10, 10);
		}

		if (is_int($boxSpacing)) {
			$this->boxSpacing = new HV($boxSpacing, $boxSpacing);
		} else {
			$this->boxSpacing = $boxSpacing ?? new HV(10, 10);
		}
	}
}