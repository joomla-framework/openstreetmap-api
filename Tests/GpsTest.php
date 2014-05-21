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
use Joomla\OpenStreetMap\Gps;
use Joomla\OpenStreetMap\OAuth;
use Joomla\Registry\Registry;

/**
 * Test class for Joomla\OpenStreetMap\Gps.
 *
 * @since  1.0
 */
class GpsTest extends \PHPUnit_Framework_TestCase
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
	 * @var    Gps Object under test.
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

		$this->object = new Gps($this->options, $this->client, $this->oauth);

		$this->options->set('consumer_key', $key);
		$this->options->set('consumer_secret', $secret);
		$this->options->set('sendheaders', true);
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
		$left = '1';
		$bottom = '1';
		$right = '2';
		$top = '2';
		$page = '0';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = 'trackpoints?bbox=' . $left . ',' . $bottom . ',' . $right . ',' . $top . '&page=' . $page;

		$this->client->expects($this->once())
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
				$this->object->retrieveGps($left, $bottom, $right, $top, $page),
				$this->equalTo(new \SimpleXMLElement($this->sampleXml))
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
		$left = '1';
		$bottom = '1';
		$right = '2';
		$top = '2';
		$page = '0';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

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

		$file = '/htdocs/new_trace.gpx';
		$description = 'Test Trace';
		$tags = '';
		$public = '1';
		$visibility = '1';
		$username = 'username';
		$password = 'password';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleXml;

		$path = 'gpx/create';

		$this->client->expects($this->once())
		->method('post')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
				$this->object->uploadTrace($file, $description, $tags, $public, $visibility, $username, $password),
				$this->equalTo(new \SimpleXMLElement($this->sampleXml))
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

		$file = '/htdocs/new_trace.gpx';
		$description = 'Test Trace';
		$tags = '';
		$public = '1';
		$visibility = '1';
		$username = 'username';
		$password = 'password';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

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

		$id = '123';
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

		$this->assertThat(
				$this->object->downloadTraceMetadetails($id, $username, $password),
				$this->equalTo(new \SimpleXMLElement($this->sampleXml))
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

		$id = '123';
		$username = 'username';
		$password = 'password';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

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

		$id = '123';
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

		$this->assertThat(
				$this->object->downloadTraceMetadata($id, $username, $password),
				$this->equalTo(new \SimpleXMLElement($this->sampleXml))
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

		$id = '123';
		$username = 'username';
		$password = 'password';

		$returnData = new \stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$path = 'gpx/' . $id . '/data';

		$this->client->expects($this->once())
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->downloadTraceMetadata($id, $username, $password);
	}
}
