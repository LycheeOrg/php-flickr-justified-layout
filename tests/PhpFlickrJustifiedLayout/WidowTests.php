<?php

namespace Tests\PhpFlickrJustifiedLayout;

class WidowTests extends LayoutJustifyTest
{
//describe('widows', function () {
//
//    it('should set them at the same height as previous rows which looks nicer', function () {
//
//        var
//        geometry = justifiedLayout([1, 1, 1, 1]);
//
//        expect(geometry . boxes[0] . height) . toEqual(geometry . boxes[3] . height);
//
//    });
//
//    it('should set them at the same height as previous non-breakout row', function () {
//
//        var
//        geometry = justifiedLayout([1, 1, 1, 1, 1, 1, 1, 1], {
//				fullWidthBreakoutRowCadence: 3
//			});
//
//			expect(geometry . boxes[geometry . boxes . length - 1] . height) . toEqual(320);
//
//		});
//
//    it('should return 0 value widowCount property if there are not any', function () {
//
//        var
//        geometryNoWidows = justifiedLayout([1, 1, 1]);
//
//        expect(geometryNoWidows . widowCount) . toEqual(0);
//
//    });
//
//    it('should return the number of widowCount in widows property if there are widows', function () {
//
//        var
//        geometry1Widow = justifiedLayout([1, 1, 1, 1]);
//        var
//        geometry2Widow = justifiedLayout([1, 1, 1, 1, 0.5]);
//
//        expect(geometry1Widow . widowCount) . toEqual(1);
//        expect(geometry2Widow . widowCount) . toEqual(2);
//
//    });
//
//    it('should return widows with a left layout through the default', function () {
//
//        var
//        geometry1LeftWidow = justifiedLayout([1, 1, 1, 1]);
//
//        expect(geometry1LeftWidow . boxes[0] . left) . toEqual(geometry1LeftWidow . boxes[3] . left);
//
//    });
//
//    it('should return widows with a specified left layout', function () {
//
//        var
//        geometry1LeftWidow = justifiedLayout([1, 1, 1, 1], {
//				widowLayoutStyle: 'left'
//			});
//
//			expect(geometry1LeftWidow . boxes[0] . left) . toEqual(geometry1LeftWidow . boxes[3] . left);
//
//		});
//
//    testLayoutsForCenteredWidows .foreach(function (layout) {
//
//        it('should return widows with a centered layout for [' + layout . toString() + ']', function () {
//            expect(isThisWidowRowCentered(layout));
//        });
//
//    }, this);
//
//		it('should return single widow with justified layout', function () {
//
//            var
//            containerWidth = 880;
//            var
//            boxSpacing = 10;
//            var
//            geometryJustfiedWidows = justifiedLayout([1, 1, 1, 2.5], {
//				containerWidth: containerWidth,
//				boxSpacing: boxSpacing,
//				widowLayoutStyle: 'justify'
//			});
//
//			var widthOfFinalJustifiedItem = geometryJustfiedWidows . boxes[geometryJustfiedWidows . boxes . length - 1] . width;
//
//			// Final item (one widow) should be the width of the container minus the padding on each side
//			expect(widthOfFinalJustifiedItem) . toEqual(containerWidth - (boxSpacing * 2));
//
//		});
//
//		it('should return widows left aligned if a nonsense value is provided', function () {
//
//            var
//            boxSpacing = 10;
//            var
//            geometry = justifiedLayout([1, 1, 1, 1, 2, 1], {
//				widowLayoutStyle: 'porkchop sandwiches',
//				boxSpacing: boxSpacing
//			});
//
//			var firstWidow;
//
//			if (geometry . widowCount > 0) {
//                firstWidow = geometry . boxes[geometry . boxes . length - geometry . widowCount];
//
//                // The first widow's left should be the same as the first box in the entire layout
//                expect(firstWidow . left) . toEqual(geometry . boxes[0] . left);
//            }
//
//		});
//
//	});
}