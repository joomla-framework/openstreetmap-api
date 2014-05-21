<?php
/**
 * Part of the Joomla Framework OpenStreetMap Package
 *
 * @copyright  Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\OpenStreetMap;

use Joomla\Http\Http;
use Joomla\Http\Response;
use Joomla\Input\Input;
use Joomla\Oauth1\Client;

/**
 * Joomla Framework class for generating the OpenStreetMap API access token.
 *
 * @since  1.0
 */
class OAuth extends Client
{
	/**
	 * Constructor.
	 *
	 * @param   array                   $options      OAuth options object.
	 * @param   Http                    $client       The HTTP client object.
	 * @param   Input                   $input        The Input object
	 * @param   AbstractWebApplication  $application  The application object.
	 *
	 * @since   1.0
	 */
	public function __construct($options = array(), Http $client, Input $input, AbstractWebApplication $application)
	{
		// Setup the access token URL if not already set.
		if (!isset($options['accessTokenURL']))
		{
			$options['accessTokenURL'] = 'http://www.openstreetmap.org/oauth/access_token';
		}

		// Setup the authorisation URL if not already set.
		if (!isset($options['authoriseURL']))
		{
			$options['authoriseURL'] = 'http://www.openstreetmap.org/oauth/authorize';
		}

		// Setup the request token URL if not already set.
		if (!isset($options['requestTokenURL']))
		{
			$options['requestTokenURL'] = 'http://www.openstreetmap.org/oauth/request_token';
		}

		// Call the OAuth1\Client constructor to setup the object.
		parent::__construct($options, $client, $input, $application, '1.0');
	}

	/**
	 * Method to verify if the access token is valid by making a request to an API endpoint.
	 *
	 * @return  boolean  Returns true if the access token is valid and false otherwise.
	 *
	 * @since   1.0
	 */
	public function verifyCredentials()
	{
		return true;
	}

	/**
	 * Method to validate a response.
	 *
	 * @param   string    $url       The request URL.
	 * @param   Response  $response  The response to validate.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function validateResponse($url, $response)
	{
		if ($response->code != 200)
		{
			$error = htmlspecialchars($response->body);

			throw new \DomainException($error, $response->code);
		}
	}
}
