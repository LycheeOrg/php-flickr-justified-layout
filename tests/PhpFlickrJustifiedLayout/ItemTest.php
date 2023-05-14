<?php

namespace Tests\PhpFlickrJustifiedLayout;

use LycheeOrg\PhpFlickrJustifiedLayout\DTO\Item;

use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{

    public function testArray(): void
    {
        $item = new Item(
            aspectRatio: 1,
            width: 340,
            height: 350,
            top: 10,
            left: 20
        );

        self::assertEquals([
            "aspectRatio" => 1,
            "top" => 10,
            "width" => 340,
            "height" => 350,
            "left" => 20
        ], $item->toArray()
        );

        self::assertEquals("top: 10px; width: 340px; height: 350px; left: 20px;", $item->toCSS());
    }
}