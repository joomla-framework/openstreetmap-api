<?php
/**
 * Tests for the Joomla Framework OpenStreetMap Package
 *
 * @copyright  Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\OpenStreetMap\Tests;

use Joomla\OpenStreetMap\OpenStreetMapObject;

/**
 * Test class for Joomla\OpenStreetMap\OpenStreetMapObject.
 *
 * @since  1.0
 */
class OpenStreetMapObjectTest extends Cases\OSMTestCase
{
	/**
	 * @var    OpenStreetMapObject  Object under test.
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

		$this->object = $this->getMockForAbstractClass('\\Joomla\\OpenStreetMap\\OpenStreetMapObject', array($this->options, $this->client));
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
}
