<?php
/**
 * Part of the Joomla Framework OpenStreetMap Package
 *
 * @copyright  Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\OpenStreetMap;

use Joomla\Http\Http;
use Joomla\Registry\Registry;

/**
 * OpenStreetMap API object class for the Joomla Framework
 *
 * @since  1.0
 */
abstract class OpenStreetMapObject
{
	/**
	 * Options for the OpenStreetMap object.
	 *
	 * @var    Registry
	 * @since  1.0
	 */
	protected $options;

	/**
	 * The HTTP client object to use in sending HTTP requests.
	 *
	 * @var    Http
	 * @since  1.0
	 */
	protected $client;

	/**
	 * The OAuth client.
	 *
	 * @var    OAuth
	 * @since  1.0
	 */
	protected $oauth;

	/**
	 * Constructor
	 *
	 * @param   Registry  &$options  OpenStreetMap options object.
	 * @param   Http      $client    The HTTP client object.
	 * @param   OAuth     $oauth     OpenStreetMap OAuth client
	 *
	 * @since   1.0
	 */
	public function __construct(Registry &$options = null, Http $client = null, OAuth $oauth = null)
	{
		$this->options = isset($options) ? $options : new Registry;
		$this->client = isset($client) ? $client : new Http($this->options);
		$this->oauth = $oauth;
	}

	/**
	 * Get an option from the OpenStreetMapObject instance.
	 *
	 * @param   string  $key  The name of the option to get.
	 *
	 * @return  mixed  The option value.
	 *
	 * @since   1.0
	 */
	public function getOption($key)
	{
		return $this->options->get($key);
	}

	/**
	 * Set an option for the OpenStreetMapObject instance.
	 *
	 * @param   string  $key    The name of the option to set.
	 * @param   mixed   $value  The option value to set.
	 *
	 * @return  OpenStreetMapObject  This object for method chaining.
	 *
	 * @since   1.0
	 */
	public function setOption($key, $value)
	{
		$this->options->set($key, $value);

		return $this;
	}

	/**
	 * Method to send the request which does not require authentication.
	 *
	 * @param   string  $path     The path of the request to make
	 * @param   string  $method   The request method.
	 * @param   array   $headers  The headers passed in the request.
	 * @param   mixed   $data     Either an associative array or a string to be sent with the post request.
	 *
	 * @return  \SimpleXMLElement  The XML response
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function sendRequest($path, $method = 'GET', $headers = array(), $data = '')
	{
		// Send the request.
		switch ($method)
		{
			case 'GET':
				$response = $this->client->get($path, $headers);

				break;

			case 'POST':
				$response = $this->client->post($path, $data, $headers);

				break;
		}

		// Validate the response code.
		if ($response->code != 200)
		{
			$error = htmlspecialchars($response->body);

			throw new \DomainException($error, $response->code);
		}

		$xml_string = simplexml_load_string($response->body);

		return $xml_string;
	}
}
