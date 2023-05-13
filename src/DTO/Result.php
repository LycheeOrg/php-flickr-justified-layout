<?php

namespace LycheeOrg\PhpFlickrJustifiedLayout\DTO;

use Illuminate\Support\Collection;

class Result
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
}