<?php

namespace Tests\PhpFlickrJustifiedLayout;

use LycheeOrg\PhpFlickrJustifiedLayout\LayoutConfig;

class WidowTests extends LayoutJustifyTest
{
    /**
     * should set them at the same height as previous rows which looks nicer
     */
    public function testWidowSameHeight()
    {

        $in = $this->toAR([1, 1, 1, 1]);
        $geometry = $this->layoutJustify->compute($in);

        $this->assertEquals(340, $geometry->boxes[0]->height);
        $this->assertEquals(340, $geometry->boxes[3]->height);
    }

    /**
     * should set them at the same height as previous non-breakout row
     */
    public function testWidowSameHeightNonBreakout()
    {
        $in = $this->toAR([1, 1, 1, 1, 1, 1, 1, 1]);
        $config = new LayoutConfig(fullWidthBreakoutRowCadence: 3);
        $geometry = $this->layoutJustify->compute($in, $config);

        $this->assertEquals(320, $geometry->boxes->last()->height);
    }

    /**
     * should return 0 value widowCount property if there are not any
     */
    public function testWidowNoWidow()
    {
        $in = $this->toAR([1, 1, 1]);
        $geometryNoWidows = $this->layoutJustify->compute($in);

        $this->assertEquals(0, $geometryNoWidows->widowCount);
    }

    /**
     * should return the number of widowCount in widows property if there are widows
     */
    public function testWidowOneAndTwoWidow()
    {
        $in1 = $this->toAR([1, 1, 1, 1]);
        $geometry1Widow = $this->layoutJustify->compute($in1);
        $in2 = $this->toAR([1, 1, 1, 1, 0.5]);
        $geometry2Widow = $this->layoutJustify->compute($in2);

        $this->assertEquals(1, $geometry1Widow->widowCount);
        $this->assertEquals(2, $geometry2Widow->widowCount);
    }

    /**
     * should return widows with a left layout through the default
     */
    public function testWidowLeft()
    {
        $in1 = $this->toAR([1, 1, 1, 1]);
        $geometry1LeftWidow = $this->layoutJustify->compute($in1);

        $this->assertEquals(10, $geometry1LeftWidow->boxes[0]->left);
        $this->assertEquals(10, $geometry1LeftWidow->boxes[3]->left);
    }

    /**
     * should return widows with a specified left layout
     */
    public function testWidowLayoutLeft()
    {
        $in = $this->toAR([1, 1, 1, 1]);
        $config = new LayoutConfig(widowLayoutStyle: 'left');
        $geometry1LeftWidow = $this->layoutJustify->compute($in, $config);


        $this->assertEquals(10, $geometry1LeftWidow->boxes[0]->left);
        $this->assertEquals(10, $geometry1LeftWidow->boxes[3]->left);
    }

    /**
     * should return widows with a centered layout for
     */
    public function testLayoutsForCenteredWidows()
    {
        foreach ($this->testLayoutsForCenteredWidows as $arrIn) {
            $this->assertTrue($this->isThisWidowRowCentered($this->toAR($arrIn)));
        }
    }

    /**
     * should return single widow with justified layout
     */
    public function testWidowJustified()
    {
        $containerWidth = 880;
        $boxSpacing = 10;

        $in = $this->toAR([1, 1, 1, 1]);
        $config = new LayoutConfig(containerWidth: $containerWidth, boxSpacing: $boxSpacing, widowLayoutStyle: 'justify');
        $geometryJustfiedWidows = $this->layoutJustify->compute($in, $config);

        $widthOfFinalJustifiedItem = $geometryJustfiedWidows->boxes->last()->width;

        // Final item (one widow) should be the width of the container minus the padding on each side
        $this->assertEquals(860, $containerWidth - ($boxSpacing * 2));
        $this->assertEquals(860, $widthOfFinalJustifiedItem);
    }

    /**
     * should return widows left aligned if a nonsense value is provided
     */
    public function testWidowJustifiedNonSense()
    {

        $boxSpacing = 10;

        $in = $this->toAR([1, 1, 1, 1, 2, 1]);
        $config = new LayoutConfig(boxSpacing: $boxSpacing, widowLayoutStyle: 'porkchop sandwiches');
        $geometry = $this->layoutJustify->compute($in, $config);

			if ($geometry->widowCount > 0) {
                $firstWidow = $geometry->boxes[$geometry->boxes->count() - $geometry->widowCount];

                // The first widow's left should be the same as the first box in the entire layout
                $this->assertEquals(10, $geometry->boxes[0]->left);
                $this->assertEquals(10, $firstWidow->left);
            }
            else
            {
                $this->fail('should have a widow');
            }

		}
}