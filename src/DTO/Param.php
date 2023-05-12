<?php

namespace LycheeOrg\PhpFlickrJustifiedLayout\DTO;

class Param
{
	public function __construct(
		public int $top,
		public int $left,
		public int $width,
		public int $spacing,
		public int $targetRowHeight,
		public float $targetRowHeightTolerance,
		public int $edgeCaseMinRowHeight,
		public int $edgeCaseMaxRowHeight,
		public bool $rightToLeft,
		public bool $isBreakoutRow,
		public string $widowLayoutStyle,
	) {
	}
}