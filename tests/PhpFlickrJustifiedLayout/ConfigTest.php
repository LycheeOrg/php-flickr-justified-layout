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
     */
    public function testFourSquares(): void
    {

        $in = $this->toAR([1, 1, 1, 1]);
        $geometry = $this->layoutJustify->compute($in);
        self::assertEquals($this->four_squares, $geometry->toArray());
    }

    /**
     * should allow overriding of containerWidth
     */
    public function testFourSquares2(): void
    {
        $in = $this->toAR([1, 1, 1, 1]);
        $config = new LayoutConfig(containerWidth: 400);
        $geometry = $this->layoutJustify->compute($in, $config);
        self::assertEquals($this->four_squares_400, $geometry->toArray());
    }

    /**
     * should allow overriding of containerPadding
     */
    public function testPaddingInt(): void
    {

        $in = $this->toAR([1]);
        $config = new LayoutConfig(containerPadding: 20);
        $geometry = $this->layoutJustify->compute($in, $config);

        self::assertEquals(20, $geometry->boxes[0]->top);
        self::assertEquals(20, $geometry->boxes[0]->left);

    }

    /**
     * should allow overriding of containerPadding with multiple dimensions
     */
    public function testPaddingAll(): void
    {

        $in = $this->toAR([1]);
        $config = new LayoutConfig(containerPadding: new LeftRightTopBottom(5, 10, 50, 10));
        $geometry = $this->layoutJustify->compute($in, $config);

        self::assertEquals(50, $geometry->boxes[0]->top);
        self::assertEquals(5, $geometry->boxes[0]->left);
    }

    /**
     * should allow overriding of boxSpacing
     */
    public function testSpacingInt(): void
    {

        $in = $this->toAR([1, 1, 1, 1]);
        $config = new LayoutConfig(boxSpacing: 40);
        $geometry = $this->layoutJustify->compute($in, $config);

        // 10 + 320 + 40 = 370
        self::assertEquals(370, $geometry->boxes[1]->left);
        self::assertEquals(370, $geometry->boxes[3]->top);

    }

    /**
     * should allow overriding of boxSpacing with multiple dimensions
     */
    public function testSpacing(): void
    {

        $in = $this->toAR([1, 1, 1, 1]);
        $config = new LayoutConfig(boxSpacing: new HorizontalVertical(40, 5));
        $geometry = $this->layoutJustify->compute($in, $config);

        // 10 + 320 + 40 = 370
        self::assertEquals(370, $geometry->boxes[1]->left);
        // 10 + 320 + 5 = 335
        self::assertEquals(335, $geometry->boxes[3]->top);

    }

    /**
     * should allow overriding of targetRowHeight
     */
    public function testRowHeight(): void
    {

        $in = $this->toAR([1, 1, 1, 1]);
        $config = new LayoutConfig(targetRowHeight: 255, targetRowHeightTolerance: 0);
        $geometry = $this->layoutJustify->compute($in, $config);

        self::assertEquals(255, $geometry->boxes[0]->height);

    }

    /**
     * should allow overriding of targetRowHeightTolerance
     */
    public function testRowHeightTolerance(): void
    {
        $in = $this->toAR([1, 1, 1]);
        $config = new LayoutConfig(targetRowHeightTolerance: 0);
        $geometry = $this->layoutJustify->compute($in, $config);

        self::assertEquals(320, $geometry->boxes[0]->height);

    }

    /**
     * should allow overriding of maxNumRows
     */
    public function testMaxNumRow(): void
    {
        $in = $this->toAR([1, 1, 1, 1]);
        $config = new LayoutConfig(maxNumRows: 1);
        $geometry = $this->layoutJustify->compute($in, $config);

        self::assertEquals(3, $geometry->boxes->count());

    }

    /**
     * should allow overriding of forceAspectRatio
     */
    public function testForceAspectRatio(): void
    {

        $in = $this->toAR([2, 2, 2, 2]);
        $config = new LayoutConfig(forceAspectRatio: 1);
        $geometry = $this->layoutJustify->compute($in, $config);


        self::assertEquals(340, $geometry->boxes[0]->width);
        self::assertEquals(340, $geometry->boxes[0]->height);
        self::assertTrue($geometry->boxes[0]->forcedAspectRatio);

    }

    /**
     * should allow overriding of showWidows
     */
    public function testNoWidows(): void
    {

        $in = $this->toAR([1, 1, 1, 1]);
        $config = new LayoutConfig(showWidows: false);
        $geometry = $this->layoutJustify->compute($in, $config);

        self::assertEquals(3, $geometry->boxes->count());

}

    /**
     * should allow overriding of fullWidthBreakoutRowCadence
     */
    public function testFullBreakout(): void
    {
        $in = $this->toAR([1, 1, 1, 1, 2, 2, 2, 2]);
        $config = new LayoutConfig(fullWidthBreakoutRowCadence: 3);
        $geometry = $this->layoutJustify->compute($in, $config);


        self::assertEquals(1040, $geometry->boxes[5]->width);
        self::assertEquals(1243, $geometry->boxes[5]->top + $geometry->boxes[5]->height + 10);
        self::assertEquals(1243, $geometry->boxes[6]->top);
    }
}