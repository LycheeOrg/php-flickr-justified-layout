<?php

namespace LycheeOrg\PhpFlickrJustifiedLayout\DTO;

class Item
{
	public function __construct(
		public float $aspectRatio,
		public int $top,
		public int $width,
		public int $height,
		public int $left,
		public bool $forcedAspectRatio,
	) {
	}
}