<?php

namespace LycheeOrg\PhpFlickrJustifiedLayout\DTO;

use Illuminate\Support\Collection;

class Result
{
	public int $containerHeight;
	public int $widowCount;

	/**
	 * @var Collection<int,Item> boxes
	 */
	public Collection $boxes;

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