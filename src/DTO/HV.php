<?php

namespace LycheeOrg\PhpFlickrJustifiedLayout\DTO;

class HV
{
	public function __construct(
		public int $horizontal,
		public int $vertical
	) {
	}
}