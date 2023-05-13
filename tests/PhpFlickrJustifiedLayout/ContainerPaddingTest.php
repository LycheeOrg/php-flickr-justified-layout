<?php

namespace Tests\PhpFlickrJustifiedLayout;

use LycheeOrg\PhpFlickrJustifiedLayout\LayoutConfig;

class ContainerPaddingTest extends LayoutJustifyTest
{
    /**
     * should add padding to the bottom of the container too
     */
    public function testPadding()
    {
        $in = $this->toAR([1]);
        $config = new LayoutConfig(containerPadding: 100);
        $geometry = $this->layoutJustify->compute($in, $config);

        // 100 + 320 + 100
        $this->assertEquals(520, $geometry->containerHeight);
    }

    /**
     * should handle 0 padding
     */
    public function testPaddingRowTolerance()
    {
        $in = $this->toAR([1,1,1]);
        $config = new LayoutConfig(containerPadding: 0, targetRowHeightTolerance: 0);
        $geometry = $this->layoutJustify->compute($in, $config);

        $this->assertEquals(320, $geometry->containerHeight);
    }
}