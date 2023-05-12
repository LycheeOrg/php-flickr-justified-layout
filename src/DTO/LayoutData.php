<?php

namespace LycheeOrg\PhpFlickrJustifiedLayout\DTO;

use Illuminate\Support\Collection;
use LycheeOrg\PhpFlickrJustifiedLayout\Row;

class LayoutData
{
	/**
	 * @var int
	 */
	public int $_containerHeight;
	/**
	 * @var Collection<int,Row>
	 */
	public Collection $_rows;
	/**
	 * @var Collection<int,Item>
	 */
	public Collection $_layoutItems;

	/**
	 * @var int Number of widow at the end of the computation
	 */
	public int $_widowCount;

	public function __construct()
	{
		$this->_containerHeight = 0;
		$this->_rows = new Collection();
		$this->_layoutItems = new Collection();
		$this->_widowCount = 0;
	}
}