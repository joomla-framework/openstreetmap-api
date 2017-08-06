<?php
/**
 * Part of the Joomla Framework OpenStreetMap Package
 *
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\OpenStreetMap;

use Joomla\Http\Response;

/**
 * OpenStreetMap API GPS class for the Joomla Framework
 *
 * @since  1.0
 */
class Gps extends OpenStreetMapObject
{
	/**
	 * Method to retrieve GPS points
	 *
	 * @param   float    $left    Left boundary
	 * @param   float    $bottom  Bottom boundary
	 * @param   float    $right   Right boundary
	 * @param   float    $top     Top boundary
	 * @param   integer  $page    Page number
	 *
	 * @return	array  The XML response containing GPS points
	 *
	 * @since	1.0
	 */
	public function retrieveGps($left, $bottom, $right, $top, $page = 0)
	{
		// Set the API base
		$base = 'trackpoints?bbox=' . $left . ',' . $bottom . ',' . $right . ',' . $top . '&page=' . $page;

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Send the request.
		$response = $this->oauth->oauthRequest($path, 'GET', array());

		return simplexml_load_string($response->body);
	}

	/**
	 * Method to upload GPS Traces
	 *
	 * @param   string   $file         File name that contains trace points
	 * @param   string   $description  Description on trace points
	 * @param   string   $tags         Tags for trace
	 * @param   integer  $public       1 for public, 0 for private
	 * @param   string   $visibility   One of the following: private, public, trackable, identifiable
	 * @param   string   $username     Username
	 * @param   string   $password     Password
	 *
	 * @return  Response  The response
	 *
	 * @since   1.0
	 */
	public function uploadTrace($file, $description, $tags, $public, $visibility, $username, $password)
	{
		// Set parameters.
		$parameters = array(
			'file' => $file,
			'description' => $description,
			'tags' => $tags,
			'public' => $public,
			'visibility' => $visibility
		);

		// Set the API base
		$base = 'gpx/create';

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		$header['Content-Type'] = 'multipart/form-data';

		$header = array_merge($header, $parameters);
		$header = array_merge($header, array('Authorization' => 'Basic ' . base64_encode($username . ':' . $password)));

		// Send the request.
		return $this->sendRequest($path, 'POST', $header, array());
	}

	/**
	 * Method to download Trace details
	 *
	 * @param   integer  $id        Trace identifier
	 * @param   string   $username  Username
	 * @param   string   $password  Password
	 *
	 * @return  array  The XML response
	 *
	 * @since   1.0
	 */
	public function downloadTraceMetadetails($id, $username, $password)
	{
		// Set the API base
		$base = 'gpx/' . $id . '/details';

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Send the request.
		return $this->sendRequest($path, 'GET', array('Authorization' => 'Basic ' . base64_encode($username . ':' . $password)));
	}

	/**
	 * Method to download Trace data
	 *
	 * @param   integer  $id        Trace identifier
	 * @param   string   $username  Username
	 * @param   string   $password  Password
	 *
	 * @return  array  The XML response
	 *
	 * @since   1.0
	 */
	public function downloadTraceMetadata($id, $username, $password)
	{
		// Set the API base
		$base = 'gpx/' . $id . '/data';

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Send the request.
		return $this->sendRequest($path, 'GET', array('Authorization' => 'Basic ' . base64_encode($username . ':' . $password)));
	}
}
