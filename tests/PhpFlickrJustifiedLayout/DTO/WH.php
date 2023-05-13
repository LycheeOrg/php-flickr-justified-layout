<?php

namespace Tests\PhpFlickrJustifiedLayout\DTO;

use LycheeOrg\PhpFlickrJustifiedLayout\Contracts\WidthHeight;

class WH implements WidthHeight
{
    public function __construct(
        public int $width,
        public int $height
    )
    {
    }

}