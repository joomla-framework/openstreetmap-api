<?php
/**
 * Tests for the Joomla Framework OpenStreetMap Package
 *
 * @copyright  Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\OpenStreetMap\Tests;

use Joomla\OpenStreetMap\OpenStreetMap;

/**
 * Test class for Joomla\OpenStreetMap\OpenStreetMap.
 *
 * @since  1.0
 */
class OpenStreetMapTest extends Cases\OSMTestCase
{
	/**
	 * @var    OpenStreetMap  Object under test.
	 * @since  1.0
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->object = new OpenStreetMap($this->oauth, $this->options, $this->client);
	}

	/**
	 * Tests the magic __get method - changesets
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetChangesets()
	{
		$this->assertInstanceOf(
			'\\Joomla\\OpenStreetMap\\Changesets',
			$this->object->changesets
		);
	}

	/**
	 * Tests the magic __get method - elements
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetElements()
	{
		$this->assertInstanceOf(
			'\\Joomla\\OpenStreetMap\\Elements',
			$this->object->elements
		);
	}

	/**
	 * Tests the magic __get method - gps
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetGps()
	{
		$this->assertInstanceOf(
			'\\Joomla\\OpenStreetMap\\Gps',
			$this->object->gps
		);
	}

	/**
	 * Tests the magic __get method - info
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetInfo()
	{
		$this->assertInstanceOf(
			'\\Joomla\\OpenStreetMap\\Info',
			$this->object->info
		);
	}

	/**
	 * Tests the magic __get method - user
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetUser()
	{
		$this->assertInstanceOf(
			'\\Joomla\\OpenStreetMap\\User',
			$this->object->user
		);
	}

	/**
	 * Tests the magic __get method - other (non existant)
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  \InvalidArgumentException
	 */
	public function test__GetOther()
	{
		$this->object->other;
	}

	/**
	 * Tests the setOption method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testSetOption()
	{
		$this->object->setOption('api.url', 'https://example.com/settest');

		$this->assertEquals(
			'https://example.com/settest',
			$this->object->getOption('api.url')
		);
	}

	/**
	 * Tests the getOption method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetOption()
	{
		$this->object->setOption('api.url', 'https://example.com/gettest');

		$this->assertEquals(
			'https://example.com/gettest',
			$this->object->getOption('api.url')
		);
	}
}
