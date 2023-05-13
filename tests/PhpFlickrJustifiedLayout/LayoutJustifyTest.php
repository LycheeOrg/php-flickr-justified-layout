<?php

namespace Tests\PhpFlickrJustifiedLayout;

use LycheeOrg\PhpFlickrJustifiedLayout\LayoutConfig;

class LayoutJustifyTest extends BaseTestCase
{

    /**
     * should create additional rows if it won\'t fit within constraints
     */
    public function testJustifyConstraints(): void {

        $in = $this->toAR([1,2]);
        $config = new LayoutConfig(containerWidth: 200, targetRowHeight: 100);
        $geometry = $this->layoutJustify->compute($in, $config);

		self::assertEquals(10, $geometry->boxes[0]->top);
		self::assertEquals(200, $geometry->boxes[1]->top);

	}

    /**
     * should not add the row if we are limiting it with maxNumRows
     */
     public function testJustifyMaxNumRows(): void {

        $in = $this->toAR([1,2, 1]);
        $config = new LayoutConfig(containerWidth: 200, targetRowHeight: 100, maxNumRows: 2);
        $geometry = $this->layoutJustify->compute($in, $config);

		self::assertEquals(2, $geometry->boxes->count());

	}

    /**
     * should handle a panorama as only row item
     */
    public function testJustifyPanorama(): void {

        $in = $this->toAR([5]);
        $geometry = $this->layoutJustify->compute($in);

        self::assertEquals(1, $geometry->boxes->count());

    }

    /**
     * should allow new item added to the row to get closer to the targetRowHeight
     */
     public function testJustifyTargetRowHeight(): void {

         $in = $this->toAR([1,4, 1.1]);
         $config = new LayoutConfig(containerWidth:1000, targetRowHeight: 250);
         $geometry = $this->layoutJustify->compute($in, $config);

		self::assertEquals(194, $geometry->boxes[0]->height);
		self::assertEquals(194, $geometry->boxes[1]->height);
		self::assertEquals(194, $geometry->boxes[2]->height);

	}
}