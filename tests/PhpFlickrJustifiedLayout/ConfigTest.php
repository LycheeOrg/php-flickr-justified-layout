<?php

namespace Tests\PhpFlickrJustifiedLayout;

use LycheeOrg\PhpFlickrJustifiedLayout\LayoutConfig;
use function Safe\json_encode;

class ConfigTest extends LayoutJustifyTest
{
    /**
     * should return a layout without passing in a config',justifiedLayout([1, 1, 1, 1]);
     * @return void
     */
    public function testFourSquares()
    {

        $in = $this->toAR([1,1,1,1]);
        $result = $this->layoutJustify->compute($in);
        $this->assertJsonDocumentMatches(json_encode($result), $this->four_squares);
//        $this->assertJsonDocumentMatches(
//            $result,
//
//        );
    }

//    /**
//     * should allow overriding of containerWidth
//     * @return void
//     */
//    public function testFourSquares2()
//    {
//        $this->assertEquals(justifiedLayout([1, 1, 1, 1], {
//				containerWidth: 400
//			})).toEqual(fourSquares400);
//    }
//
//    /**
//     * should allow overriding of containerPadding
//     * @return void
//     */
//    public function testPaddingInt()
//    {
//
//        $geometry = justifiedLayout([1], {
//				containerPadding: 20
//			});
//
//			$this->assertEquals($geometry->boxes[0]->top) . toEqual(20);
//			$this->assertEquals($geometry->boxes[0]->left) . toEqual(20);
//
//}
//
//    /**
//     * should allow overriding of containerPadding with multiple dimensions
//     * @return void
//     */
//    public function testPaddingAll()
//    {
//
//        $geometry = justifiedLayout([1], {
//				containerPadding: {
//        top:
//        50,
//					left: 5,
//					bottom: 10,
//					right: 10
//				}
//			});
//
//			$this->assertEquals($geometry->boxes[0]->top) . toEqual(50);
//			$this->assertEquals($geometry->boxes[0]->left) . toEqual(5);
//
//}
//
//    /**
//     * should allow overriding of boxSpacing
//     * @return void
//     */
//    public function testSpacingInt()
//    {
//
//        $geometry = justifiedLayout([1, 1, 1, 1], {
//				boxSpacing: 40
//			});
//
//			// 10 + 320 + 40 = 370
//			$this->assertEquals($geometry->boxes[1]->left) . toEqual(370);
//			$this->assertEquals($geometry->boxes[3]->top) . toEqual(370);
//
//}
//
//    /**
//     * should allow overriding of boxSpacing with multiple dimensions
//     * @return void
//     */
//    public function testSpacing()
//    {
//
//        $geometry = justifiedLayout([1, 1, 1, 1], {
//				boxSpacing: {
//        horizontal:
//        40,
//					vertical: 5
//				}
//			});
//
//			// 10 + 320 + 40 = 370
//			$this->assertEquals($geometry->boxes[1]->left) . toEqual(370);
//			// 10 + 320 + 5 = 335
//			$this->assertEquals($geometry->boxes[3]->top) . toEqual(335);
//
//}
//
//    /**
//     * should allow overriding of targetRowHeight
//     * @return void
//     */
//    public function testRowHeight()
//    {
//
//        $geometry = justifiedLayout([1, 1, 1, 1], {
//				targetRowHeight: 255,
//				targetRowHeightTolerance: 0
//			});
//
//			$this->assertEquals($geometry->boxes[0]->height) . toEqual(255);
//
//}
//
//    /**
//     * should allow overriding of targetRowHeightTolerance
//     * @return void
//     */
//    public function testRowHeightTolerance()
//    {
//
//        $geometry = justifiedLayout([1, 1, 1], {
//				targetRowHeightTolerance: 0
//			});
//
//			$this->assertEquals($geometry->boxes[0]->height) . toEqual(320);
//
//}
//
//    /**
//     * should allow overriding of maxNumRows
//     * @return void
//     */
//    public function testMaxNumRow()
//    {
//
//        $geometry = justifiedLayout([1, 1, 1, 1, 1], {
//				maxNumRows: 1
//			});
//
//			$this->assertEquals($geometry->boxes->count()) . toEqual(3);
//
//}
//
//    /**
//     * should allow overriding of forceAspectRatio
//     * @return void
//     */
//    public function testForceAspectRatio()
//    {
//
//        $geometry = justifiedLayout([2, 2, 2, 2], {
//				forceAspectRatio: 1
//			});
//
//			$this->assertEquals($geometry->boxes[0]->width) . toEqual(340);
//			$this->assertEquals($geometry->boxes[0]->height) . toEqual(340);
//			$this->assertEquals($geometry->boxes[0]->forcedAspectRatio) . toEqual(true);
//
//}
//
//    /**
//     * should allow overriding of showWidows
//     * @return void
//     */
//    public function testNoWidows()
//    {
//
//        $geometry = justifiedLayout([1, 1, 1, 1], {
//				showWidows: false
//			});
//
//			$this->assertEquals($geometry->boxes->count()) . toEqual(3);
//
//}
//
//    /**
//     * should allow overriding of fullWidthBreakoutRowCadence
//     * @return void
//     */
//    public function testFullBreakout()
//    {
//
//        $geometry = justifiedLayout([1, 1, 1, 1, 2, 2, 2, 2], {
//				fullWidthBreakoutRowCadence: 3
//			});
//
//			$this->assertEquals($geometry->boxes[5]->width) . toEqual(1040);
//			$this->assertEquals($geometry->boxes[6]->top) . toEqual($geometry->boxes[5]->top + $geometry->boxes[5]->height + 10);
//
//}

}