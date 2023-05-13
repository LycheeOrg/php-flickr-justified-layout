<?php

namespace LycheeOrg\PhpFlickrJustifiedLayout\DTO;

class HorizontalVertical
{
	public function __construct(
		public readonly int $horizontal,
		public readonly int $vertical
	) {
	}
}