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
use Joomla\OpenStreetMap\Info;
use Joomla\OpenStreetMap\OAuth;
use Joomla\Registry\Registry;

/**
 * Test class for Joomla\OpenStreetMap\Info.
 *
 * @since  1.0
 */
class InfoTest extends \PHPUnit_Framework_TestCase
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
	 * @var    Input The input object to use in retrieving GET/POST data.
	 * @since  1.0
	 */
	protected $input;

	/**
	 * @var    Info Object under test.
	 * @since  1.0
	 */
	protected $object;

	/**
	 * @var    OAuth  Authentication object for the OpenStreetMap object.
	 * @since  1.0
	 */
	protected $oauth;

	/**
	 * @var    string  Sample XML.
	 * @since  1.0
	 */
	protected $sampleXml = <<<XML
<?xml version='1.0'?>
<osm></osm>
XML;

	/**
	 * @var    string  Sample XML error message.
	* @since  1.0
	*/
	protected $errorString = <<<XML
<?xml version='1.0'?>
<osm>ERROR</osm>
XML;

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
		$_SERVER['HTTP_HOST'] = 'example.com';
		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0';
		$_SERVER['REQUEST_URI'] = '/index.php';
		$_SERVER['SCRIPT_NAME'] = '/index.php';

		$key = "app_key";
		$secret = "app_secret";

		$access_token = array('key' => 'token_key', 'secret' => 'token_secret');

		$this->options = new Registry;
		$this->input = new Input;
		$this->client = $this->getMock('\\Joomla\\Http\\Http', array('get', 'post', 'delete', 'put'));
		$this->oauth = new OAuth($this->options, $this->client, $this->input);
		$this->oauth->setToken($access_token);

		$this->object = new Info($this->options, $this->client, $this->oauth);

		$this->options->set('consumer_key', $key);
		$this->options->set('consumer_secret', $secret);
		$this->options->set('sendheaders', true);
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

		$this->assertThat(
				$this->object->getCapabilities(),
				$this->equalTo(new \SimpleXMLElement($this->sampleXml))
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
		$returnData->body = $this->errorString;

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
		$left = '1';
		$bottom = '1';
		$right = '2';
		$top = '2';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = 'map?bbox=' . $left . ',' . $bottom . ',' . $right . ',' . $top;

		$this->client->expects($this->once())
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
				$this->object->retrieveMapData($left, $bottom, $right, $top),
				$this->equalTo(new \SimpleXMLElement($this->sampleXml))
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
		$left = '1';
		$bottom = '1';
		$right = '2';
		$top = '2';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

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

		$this->assertThat(
				$this->object->retrievePermissions(),
				$this->equalTo(new \SimpleXMLElement($this->sampleXml))
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
		$returnData->body = $this->errorString;

		$path = 'permissions';

		$this->client->expects($this->once())
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->retrievePermissions();
	}
}
