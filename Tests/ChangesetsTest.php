<?php
/**
 * Tests for the Joomla Framework OpenStreetMap Package
 *
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\OpenStreetMap\Tests;

use Joomla\OpenStreetMap\Changesets;

/**
 * Test class for Joomla\OpenStreetMap\Changesets.
 *
 * @since  1.0
 */
class ChangesetsTest extends Cases\OSMTestCase
{
	/**
	 * @var    Changesets  Object under test.
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

		$this->object = new Changesets($this->options, $this->client, $this->oauth);
	}

	/**
	 * Tests the createChangeset method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCreateChangeset()
	{
		$changeset = array
		(
			array
			(
				'comment'    => 'my changeset comment',
				'created_by' => 'Josm'
			),
			array
			(
				'A' => 'a',
				'B' => 'b'
			)
		);

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = 'changeset/create';

		$this->client->expects($this->once())
			->method('put')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			$this->sampleXml,
			$this->object->createChangeset($changeset)
		);
	}

	/**
	 * Tests the createChangeset method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 */
	public function testCreateChangesetFailure()
	{
		$changeset = array
		(
			array
			(
				'comment'    => 'my changeset comment',
				'created_by' => 'JOsm'
			),
			array
			(
				'A' => 'a',
				'B' => 'b'
			)
		);

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;

		$path = 'changeset/create';

		$this->client->expects($this->once())
			->method('put')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->createChangeset($changeset);
	}

	/**
	 * Tests the readChangeset method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testReadChangeset()
	{
		$id = '14153708';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;
		$returnData->changeset = new \SimpleXMLElement($this->sampleXml);

		$path = 'changeset/' . $id;

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			new \SimpleXMLElement($this->sampleXml),
			$this->object->readChangeset($id)
		);
	}

	/**
	 * Tests the readChangeset method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 */
	public function testReadChangesetFailure()
	{
		$id = '14153708';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;
		$returnData->changeset = new \SimpleXMLElement($this->sampleXml);

		$path = 'changeset/' . $id;

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->readChangeset($id);
	}

	/**
	 * Tests the updateChangeset method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testUpdateChangeset()
	{
		$id   = '14153708';
		$tags = array
		(
			'comment'    => 'my changeset comment',
			'created_by' => 'JOsm (en)'
		);

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = 'changeset/' . $id;

		$this->client->expects($this->once())
			->method('put')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			new \SimpleXMLElement($this->sampleXml),
			$this->object->updateChangeset($id, $tags)
		);
	}

	/**
	 * Tests the updateChangeset method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 */
	public function testUpdateChangesetFailure()
	{
		$id   = '14153708';
		$tags = array
		(
			'comment'    => 'my changeset comment',
			'created_by' => 'JOsm (en)'
		);

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;

		$path = 'changeset/' . $id;

		$this->client->expects($this->once())
			->method('put')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->updateChangeset($id, $tags);
	}

	/**
	 * Tests the closeChangeset method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCloseChangeset()
	{
		$id = '14153708';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = 'changeset/' . $id . '/close';

		$this->client->expects($this->once())
			->method('put')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertNull($this->object->closeChangeset($id));
	}

	/**
	 * Tests the closeChangeset method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 */
	public function testCloseChangesetFailure()
	{
		$id = '14153708';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;

		$path = 'changeset/' . $id . '/close';

		$this->client->expects($this->once())
			->method('put')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->closeChangeset($id);
	}

	/**
	 * Tests the downloadChangeset method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testDownloadChangeset()
	{
		$id = '123';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;
		$returnData->create = new \SimpleXMLElement($this->sampleXml);

		$path = 'changeset/' . $id . '/download';

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			new \SimpleXMLElement($this->sampleXml),
			$this->object->downloadChangeset($id)
		);
	}

	/**
	 * Tests the downloadChangeset method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 */
	public function testDownloadChangesetFailure()
	{
		$id = '123';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;
		$returnData->create = new \SimpleXMLElement($this->sampleXml);

		$path = 'changeset/' . $id . '/download';

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->downloadChangeset($id);
	}

	/**
	 * Tests the expandBBoxChangeset method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testExpandBBoxChangeset()
	{
		$id = '14153708';
		$node_list = array(
			array(4, 5),
			array(6, 7)
		);

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = 'changeset/' . $id . '/expand_bbox';

		$this->client->expects($this->once())
			->method('post')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			new \SimpleXMLElement($this->sampleXml),
			$this->object->expandBBoxChangeset($id, $node_list)
		);
	}

	/**
	 * Tests the expandBBoxChangeset method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 */
	public function testExpandBBoxChangesetFailure()
	{
		$id = '14153708';
		$node_list = array(
			array(4, 5),
			array(6, 7)
		);

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;

		$path = 'changeset/' . $id . '/expand_bbox';

		$this->client->expects($this->once())
			->method('post')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->expandBBoxChangeset($id, $node_list);
	}

	/**
	 * Tests the queryChangeset method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testQueryChangeset()
	{
		$param = 'open';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;
		$returnData->osm = new \SimpleXMLElement($this->sampleXml);

		$path = 'changesets/' . $param;

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			new \SimpleXMLElement($this->sampleXml),
			$this->object->queryChangeset($param)
		);
	}

	/**
	 * Tests the queryChangeset method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 */
	public function testQueryChangesetFailure()
	{
		$param = 'open';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;
		$returnData->osm = new \SimpleXMLElement($this->sampleXml);

		$path = 'changesets/' . $param;

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->queryChangeset($param);
	}

	/**
	 * Tests the diffUploadChangeset method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testDiffUploadChangeset()
	{
		$id = '123';
		$xml = '<osmChange>
				<modify>
				<node id="12" timestamp="2012-12-02T00:00:00.0+11:00" lat="-33.9133118622908" lon="151.117335519304">
				<tag k="created_by" v="JOsm"/>
				</node>
				</modify>
				</osmChange>';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = 'changeset/' . $id . '/upload';

		$this->client->expects($this->once())
			->method('post')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			new \SimpleXMLElement($this->sampleXml),
			$this->object->diffUploadChangeset($xml, $id)
		);
	}

	/**
	 * Tests the diffUploadChangeset method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 */
	public function testDiffUploadChangesetFailure()
	{
		$id = '123';
		$xml = '<osmChange>
				<modify>
				<node id="12" timestamp="2007-01-02T00:00:00.0+11:00" lat="-33.9133118622908" lon="151.117335519304">
				<tag k="created_by" v="JOsm"/>
				</node>
				</modify>
				</osmChange>';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;

		$path = 'changeset/' . $id . '/upload';

		$this->client->expects($this->once())
			->method('post')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->diffUploadChangeset($xml, $id);
	}
}
