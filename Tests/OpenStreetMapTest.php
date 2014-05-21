<?php
/**
 * Tests for the Joomla Framework OpenStreetMap Package
 *
 * @copyright  Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\OpenStreetMap\Tests;

use Joomla\Http\Http;
use Joomla\OpenStreetMap\OAuth;
use Joomla\OpenStreetMap\OpenStreetMap;
use Joomla\Registry\Registry;

/**
 * Test class for Joomla\OpenStreetMap\OpenStreetMap.
 *
 * @since  1.0
 */
class OpenStreetMapTest extends \PHPUnit_Framework_TestCase
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
	 * @var    OpenStreetMap  Object under test.
	 * @since  1.0
	 */
	protected $object;

	/**
	 * @var    OAuth  OAuth1 client
	 * @since  1.0
	 */
	protected $oauth;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$_SERVER['HTTP_HOST'] = 'example.com';
		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0';
		$_SERVER['REQUEST_URI'] = '/index.php';
		$_SERVER['SCRIPT_NAME'] = '/index.php';

		$this->options = new Registry;
		$this->client = $this->getMock('\\Joomla\\Http\\Http', array('get', 'post', 'delete', 'put'));

		$this->object = new OpenStreetMap($this->oauth, $this->options, $this->client);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return void
	 */
	protected function tearDown()
	{
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
			$this->options->get('api.url')
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
		$this->options->set('api.url', 'https://example.com/gettest');

		$this->assertEquals(
			'https://example.com/gettest',
			$this->object->getOption('api.url', 'https://example.com/gettest')
		);
	}
}
