<?php
/**
 * Tests for the Joomla Framework OpenStreetMap Package
 *
 * @copyright  Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\OpenStreetMap\Tests;

use Joomla\OpenStreetMap\User;

/**
 * Test class for Joomla\OpenStreetMap\User.
 *
 * @since  1.0
 */
class UserTest extends Cases\OSMTestCase
{
	/**
	 * @var    User  Object under test.
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

		$this->object = new User($this->options, $this->client, $this->oauth);
	}

	/**
	 * Tests the getDetails method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetDetails()
	{
		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = 'user/details';

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			$this->sampleXml,
			$this->object->getDetails()
		);
	}

	/**
	 * Tests the getDetails method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 */
	public function testGetDetailsFailure()
	{
		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;

		$path = 'user/details';

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->getDetails();
	}

	/**
	 * Tests the getPreferences method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetPreferences()
	{
		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = 'user/preferences';

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			$this->sampleXml,
			$this->object->getPreferences()
		);
	}

	/**
	 * Tests the getPreferences method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 */
	public function testGetPreferencesFailure()
	{
		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;

		$path = 'user/preferences';

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->getPreferences();
	}

	/**
	 * Tests the replacePreferences method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testReplacePreferences()
	{
		$preferences = array('A' => 'a');

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = 'user/preferences';

		$this->client->expects($this->once())
			->method('put')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			$this->sampleXml,
			$this->object->replacePreferences($preferences)
		);
	}

	/**
	 * Tests the replacePreferences method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 */
	public function testReplacePreferencesFailure()
	{
		$preferences = array('A' => 'a');

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;

		$path = 'user/preferences';

		$this->client->expects($this->once())
			->method('put')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->replacePreferences($preferences);
	}

	/**
	 * Tests the changePreference method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testChangePreference()
	{
		$key        = 'A';
		$preference = 'a';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = 'user/preferences/' . $key;

		$this->client->expects($this->once())
			->method('put')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertEquals(
			$this->sampleXml,
			$this->object->changePreference($key, $preference)
		);
	}

	/**
	 * Tests the changePreference method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException \DomainException
	 */
	public function testChangePreferenceFailure()
	{
		$key        = 'A';
		$preference = 'a';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorXml;

		$path = 'user/preferences/' . $key;

		$this->client->expects($this->once())
			->method('put')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->changePreference($key, $preference);
	}
}
