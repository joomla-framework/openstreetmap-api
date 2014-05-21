<?php
/**
 * Tests for the Joomla Framework OpenStreetMap Package
 *
 * @copyright  Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\OpenStreetMap\Tests;

use Joomla\Http\Http;
use Joomla\Input\Input;
use Joomla\OpenStreetMap\OpenStreetMapObject;
use Joomla\Registry\Registry;

/**
 * Test class for Joomla\OpenStreetMap\OpenStreetMapObject.
 *
 * @since  1.0
 */
class OpenStreetMapObjectTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    Registry  Options for the OpenStreetMap object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    Http  Mock HTTP object.
	 * @since  1.0
	 */
	protected $client;

	/**
	 * @var    OpenStreetMapObject  Object under test.
	 * @since  1.0
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$this->options = new Registry;
		$this->client = $this->getMock('\\Joomla\\Http\\Http', array('get', 'post', 'delete', 'put'));

		$this->object = $this->getMockForAbstractClass('\\Joomla\\OpenStreetMap\\OpenStreetMapObject', array($this->options, $this->client));
	}

	/**
	 * Tests the setOption method
	 *
	 * @return void
	 *
	 * @since 1.0
	 */
	public function testSetOption()
	{
		$this->object->setOption('api.url', 'https://example.com/settest');

		$this->assertEquals(
			'https://example.com/settest',
			$this->options->get('api.url')
		);
	}
}
