<?php
/**
 * Tests for the Joomla Framework OpenStreetMap Package
 *
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\OpenStreetMap\Tests;

use Joomla\OpenStreetMap\Elements;

/**
 * Test class for Joomla\OpenStreetMap\Elements.
 *
 * @since  1.0
 */
class ElementsTest extends Cases\OSMTestCase
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

		$this->object = new Elements($this->options, $this->client, $this->oauth);
	}

	/**
	 * Provides test data for element type.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function seedElement()
	{
		// Element type
		return array(
			array('node'),
			array('way'),
			array('relation')
		);
	}

	/**
	 * Provides test data for element type - faliures
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function seedElementFailure()
	{
		// Element type
		return array(
			array('node'),
			array('way'),
			array('relation'),
			array('other')
		);
	}

	/**
	 * Provides test data for element type.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function seedElements()
	{
		// Elements type
		return array(
			array('nodes'),
			array('ways'),
			array('relations')
		);
	}

	/**
	 * Provides test data for element type - faliures
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function seedElementsFailure()
	{
		// Elements type
		return array(
			array('nodes'),
			array('ways'),
			array('relations'),
			array('others')
		);
	}

	/**
	 * Provides test data for full element type.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function seedFullElement()
	{
		// Full element type
		return array(
			array('way'),
			array('relation')
		);
	}

	/**
	 * Provides test data for full element type - faliures
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function seedFullElementFailure()
	{
		// Full element type
		return array(
			array('way'),
			array('relation'),
			array('other')
		);
	}

	/**
	 * Tests the createNode method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCreateNode()
	{
		$changeset = '123';
		$latitude  = '2';
		$longitude = '2';
		$tags      = array('A' => 'a','B' => 'b');

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = 'node/create';

		$this->client->expects($this->once())
			->method('put')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			$this->sampleXml,
			$this->object->createNode($changeset, $latitude, $longitude, $tags)
		);
	}

	/**
	 * Tests the createNode method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 */
	public function testCreateNodeFailure()
	{
		$changeset = '123';
		$latitude = '2';
		$longitude = '2';
		$tags = array('A' => 'a','B' => 'b');

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;

		$path = 'node/create';

		$this->client->expects($this->once())
			->method('put')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->createNode($changeset, $latitude, $longitude, $tags);
	}

	/**
	 * Tests the createWay method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCreateWay()
	{
		$changeset = '123';
		$tags      = array('A' => 'a','B' => 'b');
		$nds       = array('a', 'b');

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = 'way/create';

		$this->client->expects($this->once())
			->method('put')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			$this->sampleXml,
			$this->object->createWay($changeset, $tags, $nds)
		);
	}

	/**
	 * Tests the createWay method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 */
	public function testCreateWayFailure()
	{
		$changeset = '123';
		$tags      = array('A' => 'a','B' => 'b');
		$nds       = array('a', 'b');

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;

		$path = 'way/create';

		$this->client->expects($this->once())
			->method('put')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->createWay($changeset, $tags, $nds);
	}

	/**
	 * Tests the createRelation method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCreateRelation()
	{
		$changeset = '123';
		$tags      = array('A' => 'a','B' => 'b');
		$members   = array(
			array('type' => 'node', 'role' => 'stop', 'ref' => '123'),
			array('type' => 'way', 'ref' => '123')
		);

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = 'relation/create';

		$this->client->expects($this->once())
			->method('put')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			$this->sampleXml,
			$this->object->createRelation($changeset, $tags, $members)
		);
	}

	/**
	 * Tests the createRelation method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 */
	public function testCreateRelationFailure()
	{
		$changeset = '123';
		$tags      = array('A' => 'a','B' => 'b');
		$members   = array(
			array('type' => 'node', 'role' => 'stop', 'ref' => '123'),
			array('type' => 'way', 'ref' => '123')
		);

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;

		$path = 'relation/create';

		$this->client->expects($this->once())
			->method('put')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->createRelation($changeset, $tags, $members);
	}

	/**
	 * Tests the readElement method
	 *
	 * @param   string  $element  Element type
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @dataProvider seedElement
	 */
	public function testReadElement($element)
	{
		$id = '123';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;
		$returnData->$element = new \SimpleXMLElement($this->sampleXml);

		$path = $element . '/' . $id;

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			new \SimpleXMLElement($this->sampleXml),
			$this->object->readElement($element, $id)
		);
	}

	/**
	 * Tests the readElement method - failure
	 *
	 * @param   string  $element  Element type
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 * @dataProvider seedElementFailure
	 */
	public function testReadElementFailure($element)
	{
		$id = '123';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;
		$returnData->$element = new \SimpleXMLElement($this->sampleXml);

		$path = $element . '/' . $id;

		$this->client->expects($this->any())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->readElement($element, $id);
	}

	/**
	 * Tests the updateElement method
	 *
	 * @param   string  $element  Element type
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @dataProvider seedElement
	 */
	public function testUpdateElement($element)
	{
		$id = '123';
		$xml = "<?xml version='1.0'?><osm><element></element></osm>";

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = $element . '/' . $id;

		$this->client->expects($this->once())
			->method('put')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			$this->sampleXml,
			$this->object->updateElement($element, $xml, $id)
		);
	}

	/**
	 * Tests the updateElement method - failure
	 *
	 * @param   string  $element  Element type
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 * @dataProvider seedElementFailure
	 */
	public function testUpdateElementFailure($element)
	{
		$id = '123';
		$xml = "<?xml version='1.0'?><osm><element></element></osm>";

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;

		$path = $element . '/' . $id;

		$this->client->expects($this->any())
			->method('put')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->updateElement($element, $xml, $id);
	}

	/**
	 * Tests the deleteElement method
	 *
	 * @param   string  $element  Element type
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @dataProvider seedElement
	 */
	public function testDeleteElement($element)
	{
		$id        = '123';
		$version   = '1.0';
		$changeset = '123';
		$latitude  = '2';
		$longitude = '2';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = $element . '/' . $id;

		$this->client->expects($this->once())
			->method('delete')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			$this->sampleXml,
			$this->object->deleteElement($element, $id, $version, $changeset, $latitude, $longitude)
		);
	}

	/**
	 * Tests the deleteElement method - failure
	 *
	 * @param   string  $element  Element type
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 * @dataProvider seedElementFailure
	 */
	public function testDeleteElementFailure($element)
	{
		$id        = '123';
		$version   = '1.0';
		$changeset = '123';
		$latitude  = '2';
		$longitude = '2';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;

		$path = $element . '/' . $id;

		$this->client->expects($this->any())
			->method('delete')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->deleteElement($element, $id, $version, $changeset, $latitude, $longitude);
	}

	/**
	 * Tests the historyOfElement method
	 *
	 * @param   string  $element  Element type
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @dataProvider seedElement
	 */
	public function testHistoryOfElement($element)
	{
		$id = '123';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;
		$returnData->$element = new \SimpleXMLElement($this->sampleXml);

		$path = $element . '/' . $id . '/history';

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			new \SimpleXMLElement($this->sampleXml),
			$this->object->historyOfElement($element, $id)
		);
	}

	/**
	 * Tests the historyOfElement method - failure
	 *
	 * @param   string  $element  Element type
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 * @dataProvider seedElementFailure
	 */
	public function testHistoryOfElementFailure($element)
	{
		$id = '123';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;
		$returnData->$element = new \SimpleXMLElement($this->sampleXml);

		$path = $element . '/' . $id . '/history';

		$this->client->expects($this->any())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->historyOfElement($element, $id);
	}

	/**
	 * Tests the versionOfElement method
	 *
	 * @param   string  $element  Element type
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @dataProvider seedElement
	 */
	public function testVersionOfElement($element)
	{
		$id      = '123';
		$version = '1';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;
		$returnData->$element = new \SimpleXMLElement($this->sampleXml);

		$path = $element . '/' . $id . '/' . $version;

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			new \SimpleXMLElement($this->sampleXml),
			$this->object->versionOfElement($element, $id, $version)
		);
	}

	/**
	 * Tests the versionOfElement method - failure
	 *
	 * @param   string  $element  Element type
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 * @dataProvider seedElementFailure
	 */
	public function testVersionOfElementFailure($element)
	{
		$id      = '123';
		$version = '1';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;
		$returnData->$element = new \SimpleXMLElement($this->sampleXml);

		$path = $element . '/' . $id . '/' . $version;

		$this->client->expects($this->any())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->versionOfElement($element, $id, $version);
	}

	/**
	 * Tests the multiFetchElements method
	 *
	 * @param   string  $element  Element type
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @dataProvider seedElements
	 */
	public function testMultiFetchElements($element)
	{
		$params         = '123,456,789';
		$single_element = substr($element, 0, strlen($element) - 1);

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;
		$returnData->$single_element = new \SimpleXMLElement($this->sampleXml);

		$path = $element . '?' . $element . '=' . $params;

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			new \SimpleXMLElement($this->sampleXml),
			$this->object->multiFetchElements($element, $params)
		);
	}

	/**
	 * Tests the multiFetchElements method - failure
	 *
	 * @param   string  $element  Element type
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 * @dataProvider seedElementsFailure
	 */
	public function testMultiFetchElementsFailure($element)
	{
		$params         = '123,456,789';
		$single_element = substr($element, 0, strlen($element) - 1);

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;
		$returnData->$single_element = new \SimpleXMLElement($this->sampleXml);

		$path = $element . '?' . $element . '=' . $params;

		$this->client->expects($this->any())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->multiFetchElements($element, $params);
	}

	/**
	 * Tests the relationsForElement method
	 *
	 * @param   string  $element  Element type
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @dataProvider seedElement
	 */
	public function testRelationsForElement($element)
	{
		$id = '123';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;
		$returnData->$element = new \SimpleXMLElement($this->sampleXml);

		$path = $element . '/' . $id . '/relations';

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			new \SimpleXMLElement($this->sampleXml),
			$this->object->relationsForElement($element, $id)
		);
	}

	/**
	 * Tests the relationsForElement method - failure
	 *
	 * @param   string  $element  Element type
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 * @dataProvider seedElementFailure
	 */
	public function testRelationsForElementFailure($element)
	{
		$id = '123';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;
		$returnData->$element = new \SimpleXMLElement($this->sampleXml);

		$path = $element . '/' . $id . '/relations';

		$this->client->expects($this->any())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->relationsForElement($element, $id);
	}

	/**
	 * Tests the waysForNode method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testWaysForNode()
	{
		$id = '123';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;
		$returnData->way  = new \SimpleXMLElement($this->sampleXml);

		$path = 'node/' . $id . '/ways';

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			new \SimpleXMLElement($this->sampleXml),
			$this->object->waysForNode($id)
		);
	}

	/**
	 * Tests the waysForNode method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 */
	public function testWaysForNodeFailure()
	{
		$id = '123';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;
		$returnData->way  = new \SimpleXMLElement($this->sampleXml);

		$path = 'node/' . $id . '/ways';

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->waysForNode($id);
	}

	/**
	 * Tests the fullElement method
	 *
	 * @param   string  $element  Element type
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @dataProvider seedFullElement
	 */
	public function testFullElement($element)
	{
		$id = '123';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;
		$returnData->node = new \SimpleXMLElement($this->sampleXml);

		$path = $element . '/' . $id . '/full';

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			new \SimpleXMLElement($this->sampleXml),
			$this->object->fullElement($element, $id)
		);
	}

	/**
	 * Tests the fullElement method - failure
	 *
	 * @param   string  $element  Element type
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 * @dataProvider seedFullElementFailure
	 */
	public function testFullElementFailure($element)
	{
		$id = '123';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;
		$returnData->node = new \SimpleXMLElement($this->sampleXml);

		$path = $element . '/' . $id . '/full';

		$this->client->expects($this->any())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->fullElement($element, $id);
	}

	/**
	 * Tests the redaction method
	 *
	 * @param   string  $element  Element type
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @dataProvider seedElement
	 */
	public function testRedaction($element)
	{
		$id           = '123';
		$version      = '1';
		$redaction_id = '1';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = $element . '/' . $id . '/' . $version . '/redact?redaction=' . $redaction_id;

		$this->client->expects($this->once())
			->method('put')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			new \SimpleXMLElement($this->sampleXml),
			$this->object->redaction($element, $id, $version, $redaction_id)
		);
	}

	/**
	 * Tests the redaction method - failure
	 *
	 * @param   string  $element  Element type
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 * @dataProvider seedElementFailure
	 */
	public function testRedactionFailure($element)
	{
		$id           = '123';
		$version      = '1';
		$redaction_id = '1';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;

		$path = $element . '/' . $id . '/' . $version . '/redact?redaction=' . $redaction_id;

		$this->client->expects($this->any())
			->method('put')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->redaction($element, $id, $version, $redaction_id);
	}
}
