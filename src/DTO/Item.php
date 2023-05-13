<?php

namespace LycheeOrg\PhpFlickrJustifiedLayout\DTO;

use LycheeOrg\PhpFlickrJustifiedLayout\Contracts\AspectRatio;
use LycheeOrg\PhpFlickrJustifiedLayout\Contracts\WidthHeight;

class Item
{
	public function __construct(
		public float $aspectRatio,
		public ?int $width = null,
		public ?int $height = null,
		public ?int $top = null,
		public ?int $left = null,
		public bool $forcedAspectRatio = false,
	) {
	}

	public static function ofAspectRatio(AspectRatio $in): self
	{
		return new Item(aspectRatio: $in->aspect_ratio);
	}

	public static function ofWidthHeight(WidthHeight $in): self
	{
		return new Item(aspectRatio: $in->width / $in->height);
	}
}