<?php
/**
 * Tests for the Joomla Framework OpenStreetMap Package
 *
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\OpenStreetMap\Tests\Cases;

use Joomla\Http\Http;
use Joomla\Input\Input;
use Joomla\OpenStreetMap\OAuth;
use Joomla\Test\WebInspector;
use PHPUnit\Framework\TestCase;

/**
 * Abstract test case for the OpenStreetMap package.
 *
 * @since  1.0
 */
abstract class OSMTestCase extends TestCase
{
	/**
	 * @var    array  Options for the OpenStreetMap object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    Http  Mock HTTP object.
	 * @since  1.0
	 */
	protected $client;

	/**
	 * @var    Input  The input object to use in retrieving GET/POST data.
	 * @since  1.0
	 */
	protected $input;

	/**
	 * @var    WebInspector  The application object to send HTTP headers for redirects.
	 * @since  1.0
	 */
	protected $application;

	/**
	 * @var    OAuth  Authentication object for the OpenStreetMap object.
	 * @since  1.0
	 */
	protected $oauth;

	/**
	 * @var    string  Sample XML response.
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
	protected $errorXml = <<<XML
<?xml version='1.0'?>
<osm>ERROR</osm>
XML;

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

		$_SERVER['HTTP_HOST'] = 'example.com';
		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0';
		$_SERVER['REQUEST_URI'] = '/index.php';
		$_SERVER['SCRIPT_NAME'] = '/index.php';

		$key    = 'app_key';
		$secret = 'app_secret';

		$this->options     = array('consumer_key' => $key, 'consumer_secret' => $secret, 'sendheaders' => true);
		$this->input       = new Input;
		$this->client      = $this->getMockBuilder('\\Joomla\\Http\\Http')->getMock();
		$this->application = new WebInspector;
		$this->oauth       = new OAuth($this->options, $this->client, $this->input, $this->application);

		$this->oauth->setToken(array('key' => 'token_key', 'secret' => 'token_secret'));
	}
}
