# Flickr's Justified Layout in PHP


[![Build Status][badge-status]][builds]
[![Coverage Status][coverage-status]][coverage]

Pass in box sizes and get back sizes and coordinates for a nice justified layout like that seen all
over Flickr. The [explore page][flickr-explore] is a great example. Here's another example using
the `fullWidthBreakoutRowCadence` option on Flickr's [album page][flickr-album].

It converts this (simplified):

```php
[0.5, 1.5, 1, 1.8, 0.4, 0.7, 0.9, 1.1, 1.7, 2, 2.1]
```

Into this (simplified):

```php
{
    "containerHeight": 1269,
    "widowCount": 0,
    "boxes": [
        {
            "aspectRatio": 0.5,
            "top": 10,
            "width": 170,
            "height": 340,
            "left": 10
        },
        {
            "aspectRatio": 1.5,
            "top": 10,
            "width": 510,
            "height": 340,
            "left": 190
        },
        ...
    ]
}
```

Which gives you everything you need to make something like this:

![Demonstration][demo-layout]

## Why?

Instead of relying on another JS dependency to apply those computations on the client side,
this allows us to do them on server side (assuming we know the intended width of the container).

It does come with the constraint that reformating the window may have uninteded effect.

## Install

`composer require lychee-org/php-flickr-justified-layout`


## Easy Usage

```php
use LycheeOrg\PhpFlickrJustifiedLayout\Contracts\AspectRatio;
use LycheeOrg\PhpFlickrJustifiedLayout\Contracts\WidthHeight;
use LycheeOrg\PhpFlickrJustifiedLayout\LayoutConfig;
use LycheeOrg\PhpFlickrJustifiedLayout\LayoutJustify;

/** @var Collection<AspectRatio>|Collection<WidthHeight> $in */
$in;
$layoutJustify = new LayoutJustify();
$config = new LayoutConfig();

$geometry = $layoutJustify->compute($in, $config);
```

Objects passed to the compute method must implement the `AspectRatio` or `WidthHeight` interface.


## Configuration

<!-- Find it here: http://flickr.github.io/justified-layout/ -->

See Config Object:

```php
public function __construct(
    null|int $containerWidth = null,
    null|int|LeftRightTopBottom $containerPadding = null,
    null|int|HorizontalVertical $boxSpacing = null,
    null|int $targetRowHeight = null,
    null|float $targetRowHeightTolerance = null,
    null|int $maxNumRows = null,
    null|false|float $forceAspectRatio = null,
    null|bool $showWidows = null,
    null|false|int $fullWidthBreakoutRowCadence = null,
    null|string $widowLayoutStyle = null,
){...}
```

| Parameter                    | Type                     | Default   | Description
| ---------------------------- | ------------------------ | --------- | ------------------
| $containerWidth              | `int`                    | `1060`    | The width that boxes will be contained within irrelevant of padding.
| $containerPadding            | `int|LeftRightTopBottom` | `10`      | Provide a single integer to apply padding to all sides or provide a LRTB object to apply individual values to each side.
| $boxSpacing                  | `int|HorizontalVertical` | `10`      | Provide a single integer to apply spacing both horizontally and vertically or provide a HV object to apply individual values to each axis.
| $targetRowHeight             | `int`                    | `320`     | It's called a target because row height is the lever we use in order to fit everything in nicely. The algorithm will get as close to the target row height as it can.
| $targetRowHeightTolerance    | `float`                  | `0.25`    | How far row heights can stray from `targetRowHeight`. `0` would force rows to be the `targetRowHeight` exactly and would likely make it impossible to justify. The value must be between `0` and `1`.
| $maxNumRows                  | `false|int`              | `false`   | Will stop adding rows at this number regardless of how many items still need to be laid out.
| $forceAspectRatio            | `false|float`            | `false`   | Provide an aspect ratio here to return everything in that aspect ratio. Makes the values in your input array irrelevant. The length of the array remains relevant.
| $showWidows                  | `bool`                   | `true`    | By default we'll return items at the end of a justified layout even if they don't make a full row. If `false` they'll be omitted from the output.
| $fullWidthBreakoutRowCadence | `false|int`              | `false`   | If you'd like to insert a full width box every `n` rows you can specify it with this parameter. The box on that row will ignore the `targetRowHeight`, make itself as wide as `containerWidth - containerPadding` and be as tall as its aspect ratio defines. It'll only happen if that item has an aspect ratio >= 1. Best to have a look at the examples to see what this does.
| $widowLayoutStyle            | `string`                 | `justify` | Justify for the widows, possible values are `left`, `center` and `justify`


## Open Source Community Support

<img src="https://resources.jetbrains.com/storage/products/company/brand/logos/PhpStorm_icon.png" alt="PhpStorm" width="50"/>

We would like to thank Jetbrains for supporting us with their [Open Source Development - Community Support][jetbrains-opensource] program.

[badge-status]: https://img.shields.io/github/actions/workflow/status/LycheeOrg/php-flickr-justified-layout/php.yml
[builds]: https://github.com/LycheeOrg/php-flickr-justified-layout/actions
[coverage-status]: https://img.shields.io/codecov/c/github/LycheeOrg/php-flickr-justified-layout
[coverage]: https://app.codecov.io/gh/LycheeOrg/php-flickr-justified-layout

[flickr-explore]: https://www.flickr.com/explore
[flickr-album]: https://www.flickr.com/photos/dataichi/albums/72157650151574962

[demo-layout]: https://cloud.githubusercontent.com/assets/43693/14033849/f5cffb58-f1da-11e5-9763-dce7e90835e1.png

[jetbrains-opensource]: https://www.jetbrains.com/community/opensource/
