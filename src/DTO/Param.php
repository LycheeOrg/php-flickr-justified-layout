<?php

namespace LycheeOrg\PhpFlickrJustifiedLayout\DTO;

final class Param
{
	public function __construct(
		public readonly int $top,
		public readonly int $left,
		public readonly int $width,
		public readonly int $spacing,
		public readonly int $targetRowHeight,
		public readonly float $targetRowHeightTolerance,
		public readonly int $edgeCaseMinRowHeight,
		public readonly int $edgeCaseMaxRowHeight,
		public readonly bool $rightToLeft,
		public readonly bool $isBreakoutRow,
		public readonly string $widowLayoutStyle,
	) {
	}
}