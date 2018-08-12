<?php
/**
 * Part of the Joomla Framework OpenStreetMap Package
 *
 * @copyright  Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\OpenStreetMap;

/**
 * OpenStreetMap API User class for the Joomla Framework
 *
 * @since       1.0
 * @deprecated  The joomla/openstreetmap package is deprecated
 */
class User extends OpenStreetMapObject
{
	/**
	 * Method to get user details
	 *
	 * @return  array  The XML response
	 *
	 * @since   1.0
	 */
	public function getDetails()
	{
		$token = $this->oauth->getToken();

		// Set parameters.
		$parameters = array(
			'oauth_token' => $token['key'],
		);

		// Set the API base
		$base = 'user/details';

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Send the request.
		$response = $this->oauth->oauthRequest($path, 'GET', $parameters);

		return $response->body;
	}

	/**
	 * Method to get preferences
	 *
	 * @return  array  The XML response
	 *
	 * @since   1.0
	 */
	public function getPreferences()
	{
		$token = $this->oauth->getToken();

		// Set parameters.
		$parameters = array(
			'oauth_token' => $token['key'],
		);

		// Set the API base
		$base = 'user/preferences';

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Send the request.
		$response = $this->oauth->oauthRequest($path, 'GET', $parameters);

		return $response->body;
	}

	/**
	 * Method to replace user preferences
	 *
	 * @param   array  $preferences  Array of new preferences
	 *
	 * @return  array  The XML response
	 *
	 * @since   1.0
	 */
	public function replacePreferences($preferences)
	{
		$token = $this->oauth->getToken();

		// Set parameters.
		$parameters = array(
			'oauth_token' => $token['key'],
		);

		// Set the API base
		$base = 'user/preferences';

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Create a list of preferences
		$preferenceList = '';

		if (!empty($preferences))
		{
			foreach ($preferences as $key => $value)
			{
				$preferenceList .= '<preference k="' . $key . '" v="' . $value . '"/>';
			}
		}

		$xml = '<?xml version="1.0" encoding="UTF-8"?>
			<osm version="0.6" generator="JoomlaOpenStreetMap">
				<preferences>'
				. $preferenceList .
				'</preferences>
			</osm>';

		$header['Content-Type'] = 'text/xml';

		// Send the request.
		$response = $this->oauth->oauthRequest($path, 'PUT', $parameters, $xml, $header);

		return $response->body;
	}

	/**
	 * Method to change user preferences
	 *
	 * @param   string  $key         Key of the preference
	 * @param   string  $preference  New value for preference
	 *
	 * @return  array  The XML response
	 *
	 * @since   1.0
	 */
	public function changePreference($key, $preference)
	{
		$token = $this->oauth->getToken();

		// Set parameters.
		$parameters = array(
			'oauth_token' => $token['key'],
		);

		// Set the API base
		$base = 'user/preferences/' . $key;

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Send the request.
		$response = $this->oauth->oauthRequest($path, 'PUT', $parameters, $preference);

		return $response->body;
	}
}
