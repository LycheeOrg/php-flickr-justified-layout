<?php

namespace Tests\PhpFlickrJustifiedLayout;

use LycheeOrg\PhpFlickrJustifiedLayout\DTO\HorizontalVertical;
use LycheeOrg\PhpFlickrJustifiedLayout\DTO\LeftRightTopBottom;
use LycheeOrg\PhpFlickrJustifiedLayout\LayoutConfig;

class ConfigTest extends LayoutJustifyTest
{
    /**
     * should return a layout without passing in a config',justifiedLayout([1, 1, 1, 1]);
     *
     * @return void
     */
    public function testFourSquares()
    {

        $in = $this->toAR([1, 1, 1, 1]);
        $geometry = $this->layoutJustify->compute($in);
        $this->assertEquals($this->four_squares, $geometry->toArray());
    }

    /**
     * should allow overriding of containerWidth
     * @return void
     */
    public function testFourSquares2()
    {
        $in = $this->toAR([1, 1, 1, 1]);
        $config = new LayoutConfig(containerWidth: 400);
        $geometry = $this->layoutJustify->compute($in, $config);
        $this->assertEquals($this->four_squares_400, $geometry->toArray());
    }

    /**
     * should allow overriding of containerPadding
     * @return void
     */
    public function testPaddingInt()
    {

        $in = $this->toAR([1]);
        $config = new LayoutConfig(containerPadding: 20);
        $geometry = $this->layoutJustify->compute($in, $config);

        $this->assertEquals(20, $geometry->boxes[0]->top);
        $this->assertEquals(20, $geometry->boxes[0]->left);

    }

    /**
     * should allow overriding of containerPadding with multiple dimensions
     * @return void
     */
    public function testPaddingAll()
    {

        $in = $this->toAR([1]);
        $config = new LayoutConfig(containerPadding: new LeftRightTopBottom(5, 10, 50, 10));
        $geometry = $this->layoutJustify->compute($in, $config);

        $this->assertEquals(50, $geometry->boxes[0]->top);
        $this->assertEquals(5, $geometry->boxes[0]->left);
    }

    /**
     * should allow overriding of boxSpacing
     * @return void
     */
    public function testSpacingInt()
    {

        $in = $this->toAR([1, 1, 1, 1]);
        $config = new LayoutConfig(boxSpacing: 40);
        $geometry = $this->layoutJustify->compute($in, $config);

        // 10 + 320 + 40 = 370
        $this->assertEquals(370, $geometry->boxes[1]->left);
        $this->assertEquals(370, $geometry->boxes[3]->top);

    }

    /**
     * should allow overriding of boxSpacing with multiple dimensions
     * @return void
     */
    public function testSpacing()
    {

        $in = $this->toAR([1, 1, 1, 1]);
        $config = new LayoutConfig(boxSpacing: new HorizontalVertical(40, 5));
        $geometry = $this->layoutJustify->compute($in, $config);

        // 10 + 320 + 40 = 370
        $this->assertEquals(370, $geometry->boxes[1]->left);
        // 10 + 320 + 5 = 335
        $this->assertEquals(335, $geometry->boxes[3]->top);

    }

    /**
     * should allow overriding of targetRowHeight
     * @return void
     */
    public function testRowHeight()
    {

        $in = $this->toAR([1, 1, 1, 1]);
        $config = new LayoutConfig(targetRowHeight: 255, targetRowHeightTolerance: 0);
        $geometry = $this->layoutJustify->compute($in, $config);

        $this->assertEquals(255, $geometry->boxes[0]->height);

    }

    /**
     * should allow overriding of targetRowHeightTolerance
     * @return void
     */
    public function testRowHeightTolerance()
    {
        $in = $this->toAR([1, 1, 1]);
        $config = new LayoutConfig(targetRowHeightTolerance: 0);
        $geometry = $this->layoutJustify->compute($in, $config);

        $this->assertEquals(320, $geometry->boxes[0]->height);

    }

    /**
     * should allow overriding of maxNumRows
     * @return void
     */
    public function testMaxNumRow()
    {
        $in = $this->toAR([1, 1, 1, 1]);
        $config = new LayoutConfig(maxNumRows: 1);
        $geometry = $this->layoutJustify->compute($in, $config);

        $this->assertEquals(3, $geometry->boxes->count());

    }

    /**
     * should allow overriding of forceAspectRatio
     * @return void
     */
    public function testForceAspectRatio()
    {

        $in = $this->toAR([2, 2, 2, 2]);
        $config = new LayoutConfig(forceAspectRatio: 1);
        $geometry = $this->layoutJustify->compute($in, $config);


        $this->assertEquals(340, $geometry->boxes[0]->width);
        $this->assertEquals(340, $geometry->boxes[0]->height);
        $this->assertTrue($geometry->boxes[0]->forcedAspectRatio);

    }

    /**
     * should allow overriding of showWidows
     * @return void
     */
    public function testNoWidows()
    {

        $in = $this->toAR([1, 1, 1, 1]);
        $config = new LayoutConfig(showWidows: false);
        $geometry = $this->layoutJustify->compute($in, $config);

        $this->assertEquals(3, $geometry->boxes->count());

}

    /**
     * should allow overriding of fullWidthBreakoutRowCadence
     * @return void
     */
    public function testFullBreakout()
    {
        $in = $this->toAR([1, 1, 1, 1, 2, 2, 2, 2]);
        $config = new LayoutConfig(fullWidthBreakoutRowCadence: 3);
        $geometry = $this->layoutJustify->compute($in, $config);


        $this->assertEquals(1040, $geometry->boxes[5]->width);
        $this->assertEquals(1243, $geometry->boxes[5]->top + $geometry->boxes[5]->height + 10);
        $this->assertEquals(1243, $geometry->boxes[6]->top);
    }
}