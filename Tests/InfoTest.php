<?php
/**
 * Tests for the Joomla Framework OpenStreetMap Package
 *
 * @copyright  Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\OpenStreetMap\Tests;

use Joomla\OpenStreetMap\Info;

/**
 * Test class for Joomla\OpenStreetMap\Info.
 *
 * @since  1.0
 */
class InfoTest extends Cases\OSMTestCase
{
	/**
	 * @var    Info  Object under test.
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

		$this->object = new Info($this->options, $this->client, $this->oauth);
	}

	/**
	 * Tests the getCapabilities method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetCapabilities()
	{
		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = 'capabilities';

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			new \SimpleXMLElement($this->sampleXml),
			$this->object->getCapabilities()
		);
	}

	/**
	 * Tests the getCapabilities method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 */
	public function testGetCapabilitiesFailure()
	{
		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;

		$path = 'capabilities';

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->getCapabilities();
	}

	/**
	 * Tests the retrieveMapData method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testRetrieveMapData()
	{
		$left   = '1';
		$bottom = '1';
		$right  = '2';
		$top    = '2';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = 'map?bbox=' . $left . ',' . $bottom . ',' . $right . ',' . $top;

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			new \SimpleXMLElement($this->sampleXml),
			$this->object->retrieveMapData($left, $bottom, $right, $top)
		);
	}

	/**
	 * Tests the retrieveMapData method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 */
	public function testRetrieveMapDataFailure()
	{
		$left   = '1';
		$bottom = '1';
		$right  = '2';
		$top    = '2';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;

		$path = 'map?bbox=' . $left . ',' . $bottom . ',' . $right . ',' . $top;

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->retrieveMapData($left, $bottom, $right, $top);
	}

	/**
	 * Tests the retrievePermissions method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testRetrievePermissions()
	{
		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = 'permissions';

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			new \SimpleXMLElement($this->sampleXml),
			$this->object->retrievePermissions()
		);
	}

	/**
	 * Tests the retrievePermissions method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 */
	public function testRetrievePermissionsFailure()
	{
		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;

		$path = 'permissions';

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->retrievePermissions();
	}
}
