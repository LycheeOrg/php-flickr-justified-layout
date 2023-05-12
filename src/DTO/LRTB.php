<?php

namespace LycheeOrg\PhpFlickrJustifiedLayout\DTO;

class LRTB
{
	public function __construct(
		public int $top,
		public int $left,
		public int $right,
		public int $bottom,
	) {
	}
}