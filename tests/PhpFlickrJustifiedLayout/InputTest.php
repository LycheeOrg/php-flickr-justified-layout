<?php

namespace Tests\PhpFlickrJustifiedLayout;

use LycheeOrg\PhpFlickrJustifiedLayout\LayoutConfig;

class InputTest extends LayoutJustifyTest
{
    /**
     * should handle width and height objects as input
     */
    public function testInputsWidthHeight()
    {
        $in = $this->toWH([[400, 400], [500, 500], [600, 600], [700, 700]]);
        $geometry = $this->layoutJustify->compute($in);

        $this->assertEquals($this->four_squares, $geometry->toArray());
    }

    /**
     * should handle an array of aspect ratios as input
     */
    public function testInputsAspectRatio()
    {
        $in = $this->toAR([1, 1, 1, 1]);
        $geometry = $this->layoutJustify->compute($in);
        $this->assertEquals($this->four_squares, $geometry->toArray());
    }
}