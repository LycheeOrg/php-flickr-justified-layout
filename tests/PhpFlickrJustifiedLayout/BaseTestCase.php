<?php

namespace Tests\PhpFlickrJustifiedLayout;

use Illuminate\Support\Collection;
use Helmich\JsonAssert\JsonAssertions;
use LycheeOrg\PhpFlickrJustifiedLayout\LayoutConfig;
use LycheeOrg\PhpFlickrJustifiedLayout\LayoutJustify;
use PHPUnit\Framework\TestCase;
use Tests\PhpFlickrJustifiedLayout\DTO\AR;
use Tests\PhpFlickrJustifiedLayout\DTO\WH;

//use function Safe\file_get_contents;
use function Safe\json_decode;
use function Safe\realpath;
use function Safe\file_get_contents;

class BaseTestCase extends TestCase
{
    use JsonAssertions;

    protected LayoutJustify $layoutJustify;
    protected array $four_squares = [
        "containerHeight" => 710,
        "widowCount" => 1,
        "boxes" => [
            [
                "aspectRatio" => 1,
                "top" => 10,
                "width" => 340,
                "height" => 340,
                "left" => 10
            ],
            [
                "aspectRatio" => 1,
                "top" => 10,
                "width" => 340,
                "height" => 340,
                "left" => 360
            ],
            [
                "aspectRatio" => 1,
                "top" => 10,
                "width" => 340,
                "height" => 340,
                "left" => 710
            ],
            [
                "aspectRatio" => 1,
                "top" => 360,
                "width" => 340,
                "height" => 340,
                "left" => 10
            ]
        ]
    ];

    protected array $four_squares_400 = [
        "containerHeight" => 1570,
        "widowCount" => 0,
        "boxes" => [
            [
                "aspectRatio" => 1,
                "top" => 10,
                "width" => 380,
                "height" => 380,
                "left" => 10
            ],
            [
                "aspectRatio" => 1,
                "height" => 380,
                "left" => 10,
                "top" => 400,
                "width" => 380
            ],
            [
                "aspectRatio" => 1,
                "height" => 380,
                "left" => 10,
                "top" => 790,
                "width" => 380
            ],
            [
                "aspectRatio" => 1,
                "top" => 1180,
                "width" => 380,
                "height" => 380,
                "left" => 10
            ]
        ]
    ];

    protected array $testLayoutsForCenteredWidows = [
        [1, 1, 1, 1], // 1 widow
        [1, 1, 1, 1, 1], // 2 widows
        [1.6, 1, 2.3, 1.2, 0.1] // 1 widow
    ];

    public function setUp(): void
    {
        $this->layoutJustify = new LayoutJustify();

        parent::setUp();
    }

    /**
     * @param Collection<int,AR>|Collection<int,WH> $layout
     * @return bool
     */
    protected function isThisWidowRowCentered(Collection $layout): bool
    {
        $containerWidth = 1060;
        $boxSpacing = 10;

        $layoutConfig = new LayoutConfig(
            containerWidth: 1060,
            boxSpacing: $boxSpacing,
            widowLayoutStyle: 'center'
        );

        $geometryCenteredWidows = $this->layoutJustify->compute(
            input: $layout,
            config: $layoutConfig
        );


        $widowRowWidth = 0;
        $totalBoxCount = $geometryCenteredWidows->boxes->count();

        // Determine width of widow row
        for ($n = $totalBoxCount - 1; $n >= $totalBoxCount - $geometryCenteredWidows->widowCount; $n--) {
            $widowRowWidth += $geometryCenteredWidows->boxes->get($n)->width + $boxSpacing;
        }

        // Account for right amount of spacing in there, one less than the number of widows
        $widowRowWidth -= $boxSpacing;

        // "Left" of the widowed row based on the width of the container and number of widows
        $centeredRowOffset = ($containerWidth / 2) - ($widowRowWidth / 2);
        $leftOfFirstWidow = $geometryCenteredWidows->boxes->get($geometryCenteredWidows->boxes->count() - $geometryCenteredWidows->widowCount)->left;

        return $centeredRowOffset === $leftOfFirstWidow;
    }

    /**
     * convert an array of ratios into a collection of Aspect Ratio
     *
     * @param array<int,int> $in
     * @return Collection<int,AR> Collection of Aspect Ratio
     */
    protected function toAR(array $in): Collection
    {
        return collect($in)->map(fn($v) => new AR($v));
    }

    /**
     * convert an array of dimensions into a collection of Width Height
     *
     * @param array<int,array<int,int>> $in
     * @return Collection<int,WH>
     */
    protected function toWH(array $in): Collection
    {
        return collect($in)->map(fn($v) => new WH($v[0], $v[1]));
    }
}