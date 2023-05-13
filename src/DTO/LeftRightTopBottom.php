<?php

namespace LycheeOrg\PhpFlickrJustifiedLayout\DTO;

class LeftRightTopBottom
{
	public function __construct(
		public readonly int $left,
		public readonly int $right,
		public readonly int $top,
		public readonly int $bottom,
	) {
	}
}