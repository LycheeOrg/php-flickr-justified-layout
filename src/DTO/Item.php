<?php

namespace LycheeOrg\PhpFlickrJustifiedLayout\DTO;

use Illuminate\Contracts\Support\Arrayable;
use LycheeOrg\PhpFlickrJustifiedLayout\Contracts\AspectRatio;
use LycheeOrg\PhpFlickrJustifiedLayout\Contracts\WidthHeight;

class Item implements Arrayable
{
    public function __construct(
        public float $aspectRatio,
        public ?int  $width = null,
        public ?int  $height = null,
        public ?int  $top = null,
        public ?int  $left = null,
        public bool  $forcedAspectRatio = false,
    )
    {
    }

    public static function ofAspectRatio(AspectRatio $in): self
    {
        return new Item(aspectRatio: $in->aspect_ratio);
    }

    public static function ofWidthHeight(WidthHeight $in): self
    {
        return new Item(aspectRatio: $in->width / $in->height);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'aspectRatio' => $this->aspectRatio,
            'top' => $this->top,
            'width' => $this->width,
            'height' => $this->height,
            'left' => $this->left,
        ];
    }
}