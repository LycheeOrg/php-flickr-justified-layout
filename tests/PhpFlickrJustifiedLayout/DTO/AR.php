<?php

namespace Tests\PhpFlickrJustifiedLayout\DTO;

use LycheeOrg\PhpFlickrJustifiedLayout\Contracts\AspectRatio;

class AR implements AspectRatio
{
    public function __construct(
        public float $aspect_ratio
    )
    {
    }
}