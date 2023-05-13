<?php

namespace LycheeOrg\PhpFlickrJustifiedLayout\DTO;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class Result implements Arrayable
{
	public readonly int $containerHeight;
	public readonly int $widowCount;

	/**
	 * @var Collection<int,Item> boxes
	 */
	public readonly Collection $boxes;

	/**
	 * Result object of the computations.
	 *
	 * @param LayoutData $layoutData
	 */
	public function __construct(LayoutData $layoutData)
	{
		$this->containerHeight = $layoutData->_containerHeight;
		$this->boxes = $layoutData->_layoutItems;
		$this->widowCount = $layoutData->_widowCount;
	}

    /**
     * Arrayify the result
     *
     * @return array
     */
    public function toArray(): array {
        return [
            'containerHeight' => $this->containerHeight,
            'widowCount' => $this->widowCount,
            'boxes' => $this->boxes->toArray(),
        ];
    }
}