<?php

namespace Tests\PhpFlickrJustifiedLayout;

use LycheeOrg\PhpFlickrJustifiedLayout\DTO\Item;
use Helmich\JsonAssert\JsonAssertions;

use PHPUnit\Framework\TestCase;

use function Safe\json_encode;
class ItemTest extends TestCase
{
    use JsonAssertions;

    public function testArray(): void
    {
        $item = new Item(
            aspectRatio: 1,
            width: 340,
            height: 340,
            top: 10,
            left: 10
        );

        $this->assertJsonDocumentMatches(json_encode($item),
            [
                "aspectRatio" => 1,
                "top" => 10,
                "width" => 340,
                "height" => 340,
                "left" => 10
            ]);
    }
}