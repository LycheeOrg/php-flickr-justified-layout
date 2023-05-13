<?php

namespace Tests\PhpFlickrJustifiedLayout;

use LycheeOrg\PhpFlickrJustifiedLayout\LayoutConfig;

class WidowTests extends LayoutJustifyTest
{
    /**
     * should set them at the same height as previous rows which looks nicer
     */
    public function testWidowSameHeight(): void
    {

        $in = $this->toAR([1, 1, 1, 1]);
        $geometry = $this->layoutJustify->compute($in);

        self::assertEquals(340, $geometry->boxes[0]->height);
        self::assertEquals(340, $geometry->boxes[3]->height);
    }

    /**
     * should set them at the same height as previous non-breakout row
     */
    public function testWidowSameHeightNonBreakout(): void
    {
        $in = $this->toAR([1, 1, 1, 1, 1, 1, 1, 1]);
        $config = new LayoutConfig(fullWidthBreakoutRowCadence: 3);
        $geometry = $this->layoutJustify->compute($in, $config);

        self::assertEquals(320, $geometry->boxes->last()->height);
    }

    /**
     * should return 0 value widowCount property if there are not any
     */
    public function testWidowNoWidow(): void
    {
        $in = $this->toAR([1, 1, 1]);
        $geometryNoWidows = $this->layoutJustify->compute($in);

        self::assertEquals(0, $geometryNoWidows->widowCount);
    }

    /**
     * should return the number of widowCount in widows property if there are widows
     */
    public function testWidowOneAndTwoWidow(): void
    {
        $in1 = $this->toAR([1, 1, 1, 1]);
        $geometry1Widow = $this->layoutJustify->compute($in1);
        $in2 = $this->toAR([1, 1, 1, 1, 0.5]);
        $geometry2Widow = $this->layoutJustify->compute($in2);

        self::assertEquals(1, $geometry1Widow->widowCount);
        self::assertEquals(2, $geometry2Widow->widowCount);
    }

    /**
     * should return widows with a left layout through the default
     */
    public function testWidowLeft(): void
    {
        $in1 = $this->toAR([1, 1, 1, 1]);
        $geometry1LeftWidow = $this->layoutJustify->compute($in1);

        self::assertEquals(10, $geometry1LeftWidow->boxes[0]->left);
        self::assertEquals(10, $geometry1LeftWidow->boxes[3]->left);
    }

    /**
     * should return widows with a specified left layout
     */
    public function testWidowLayoutLeft(): void
    {
        $in = $this->toAR([1, 1, 1, 1]);
        $config = new LayoutConfig(widowLayoutStyle: 'left');
        $geometry1LeftWidow = $this->layoutJustify->compute($in, $config);


        self::assertEquals(10, $geometry1LeftWidow->boxes[0]->left);
        self::assertEquals(10, $geometry1LeftWidow->boxes[3]->left);
    }

    /**
     * should return widows with a centered layout for
     */
    public function testLayoutsForCenteredWidows(): void
    {
        foreach ($this->testLayoutsForCenteredWidows as $arrIn) {
            self::assertTrue($this->isThisWidowRowCentered($this->toAR($arrIn)));
        }
    }

    /**
     * should return single widow with justified layout
     */
    public function testWidowJustified(): void
    {
        $containerWidth = 880;
        $boxSpacing = 10;

        $in = $this->toAR([1, 1, 1, 1]);
        $config = new LayoutConfig(containerWidth: $containerWidth, boxSpacing: $boxSpacing, widowLayoutStyle: 'justify');
        $geometryJustfiedWidows = $this->layoutJustify->compute($in, $config);

        $widthOfFinalJustifiedItem = $geometryJustfiedWidows->boxes->last()->width;

        // Final item (one widow) should be the width of the container minus the padding on each side
        self::assertEquals(860, $containerWidth - ($boxSpacing * 2));
        self::assertEquals(860, $widthOfFinalJustifiedItem);
    }

    /**
     * should return widows left aligned if a nonsense value is provided
     */
    public function testWidowJustifiedNonSense(): void
    {

        $boxSpacing = 10;

        $in = $this->toAR([1, 1, 1, 1, 2, 1]);
        $config = new LayoutConfig(boxSpacing: $boxSpacing, widowLayoutStyle: 'porkchop sandwiches');
        $geometry = $this->layoutJustify->compute($in, $config);

			if ($geometry->widowCount > 0) {
                $firstWidow = $geometry->boxes[$geometry->boxes->count() - $geometry->widowCount];

                // The first widow's left should be the same as the first box in the entire layout
                self::assertEquals(10, $geometry->boxes[0]->left);
                self::assertEquals(10, $firstWidow->left);
            }
            else
            {
                self::fail('should have a widow');
            }

		}
}