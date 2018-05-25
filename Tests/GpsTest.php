<?php
/**
 * Tests for the Joomla Framework OpenStreetMap Package
 *
 * @copyright  Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\OpenStreetMap\Tests;

use Joomla\OpenStreetMap\Gps;

/**
 * Test class for Joomla\OpenStreetMap\Gps.
 *
 * @since  1.0
 */
class GpsTest extends Cases\OSMTestCase
{
	/**
	 * @var    Gps  Object under test.
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

		$this->object = new Gps($this->options, $this->client, $this->oauth);
	}

	/**
	 * Tests the retrieveGps method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testRetrieveGps()
	{
		$left   = '1';
		$bottom = '1';
		$right  = '2';
		$top    = '2';
		$page   = '0';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = 'trackpoints?bbox=' . $left . ',' . $bottom . ',' . $right . ',' . $top . '&page=' . $page;

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			new \SimpleXMLElement($this->sampleXml),
			$this->object->retrieveGps($left, $bottom, $right, $top, $page)
		);
	}

	/**
	 * Tests the retrieveGps method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 */
	public function testRetrieveGpsFailure()
	{
		$left   = '1';
		$bottom = '1';
		$right  = '2';
		$top    = '2';
		$page   = '0';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;

		$path = 'trackpoints?bbox=' . $left . ',' . $bottom . ',' . $right . ',' . $top . '&page=' . $page;

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->retrieveGps($left, $bottom, $right, $top, $page);
	}

	/**
	 * Tests the uploadTrace method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testUploadTrace()
	{
		$file        = '/htdocs/new_trace.gpx';
		$description = 'Test Trace';
		$tags        = '';
		$public      = '1';
		$visibility  = '1';
		$username    = 'username';
		$password    = 'password';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = 'gpx/create';

		$this->client->expects($this->once())
			->method('post')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			new \SimpleXMLElement($this->sampleXml),
			$this->object->uploadTrace($file, $description, $tags, $public, $visibility, $username, $password)
		);
	}

	/**
	 * Tests the uploadTrace method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 */
	public function testUploadTraceFailure()
	{
		$file        = '/htdocs/new_trace.gpx';
		$description = 'Test Trace';
		$tags        = '';
		$public      = '1';
		$visibility  = '1';
		$username    = 'username';
		$password    = 'password';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;

		$path = 'gpx/create';

		$this->client->expects($this->once())
			->method('post')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->uploadTrace($file, $description, $tags, $public, $visibility, $username, $password);
	}

	/**
	 * Tests the downloadTraceMetadetails method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testDownloadTraceMetadetails()
	{
		$id       = '123';
		$username = 'username';
		$password = 'password';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = 'gpx/' . $id . '/details';

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			new \SimpleXMLElement($this->sampleXml),
			$this->object->downloadTraceMetadetails($id, $username, $password)
		);
	}

	/**
	 * Tests the downloadTraceMetadetails method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 */
	public function testDownloadTraceMetadetailsFailure()
	{
		$id       = '123';
		$username = 'username';
		$password = 'password';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;

		$path = 'gpx/' . $id . '/details';

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->downloadTraceMetadetails($id, $username, $password);
	}

	/**
	 * Tests the downloadTraceMetadata method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testDownloadTraceMetadata()
	{
		$id       = '123';
		$username = 'username';
		$password = 'password';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = 'gpx/' . $id . '/data';

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			new \SimpleXMLElement($this->sampleXml),
			$this->object->downloadTraceMetadata($id, $username, $password)
		);
	}

	/**
	 * Tests the downloadTraceMetadata method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 */
	public function testDownloadTraceMetadataFailure()
	{
		$id       = '123';
		$username = 'username';
		$password = 'password';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;

		$path = 'gpx/' . $id . '/data';

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->downloadTraceMetadata($id, $username, $password);
	}
}
