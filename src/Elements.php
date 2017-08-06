<?php
/**
 * Part of the Joomla Framework OpenStreetMap Package
 *
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\OpenStreetMap;

/**
 * OpenStreetMap API Elements class for the Joomla Framework
 *
 * @since  1.0
 */
class Elements extends OpenStreetMapObject
{
	/**
	 * Method to create a node
	 *
	 * @param   integer  $changeset  Changeset id
	 * @param   float    $latitude   Latitude of the node
	 * @param   float    $longitude  Longitude of the node
	 * @param   arary    $tags       Array of tags for a node
	 *
	 * @return  array  The XML response
	 *
	 * @since   1.0
	 */
	public function createNode($changeset, $latitude, $longitude, $tags)
	{
		$token = $this->oauth->getToken();

		// Set parameters.
		$parameters = array(
			'oauth_token' => $token['key']
		);

		// Set the API base
		$base = 'node/create';

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		$tagList = '';

		// Create XML node
		if (!empty($tags))
		{
			foreach ($tags as $key => $value)
			{
				$tagList .= '<tag k="' . $key . '" v="' . $value . '"/>';
			}
		}

		$xml = '<?xml version="1.0" encoding="UTF-8"?>
				<osm version="0.6" generator="JoomlaOpenStreetMap">
				<node changeset="' . $changeset . '" lat="' . $latitude . '" lon="' . $longitude . '">'
				. $tagList .
				'</node>
				</osm>';

		$header['Content-Type'] = 'text/xml';

		// Send the request.
		$response = $this->oauth->oauthRequest($path, 'PUT', $parameters, $xml, $header);

		return $response->body;
	}

	/**
	 * Method to create a way
	 *
	 * @param   integer  $changeset  Changeset id
	 * @param   array    $tags       Array of tags for a way
	 * @param   array    $nds        Node ids to refer
	 *
	 * @return  array   The XML response
	 *
	 * @since   1.0
	 */
	public function createWay($changeset, $tags, $nds)
	{
		$token = $this->oauth->getToken();

		// Set parameters.
		$parameters = array(
			'oauth_token' => $token['key']
		);

		// Set the API base
		$base = 'way/create';

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		$tagList = '';

		// Create XML node
		if (!empty($tags))
		{
			foreach ($tags as $key => $value)
			{
				$tagList .= '<tag k="' . $key . '" v="' . $value . '"/>';
			}
		}

		$ndList = '';

		if (!empty($nds))
		{
			foreach ($nds as $value)
			{
				$ndList .= '<nd ref="' . $value . '"/>';
			}
		}

		$xml = '<?xml version="1.0" encoding="UTF-8"?>
				<osm version="0.6" generator="JoomlaOpenStreetMap">
				<way changeset="' . $changeset . '">'
					. $tagList
					. $ndList .
				'</way>
			</osm>';

		$header['Content-Type'] = 'text/xml';

		// Send the request.
		$response = $this->oauth->oauthRequest($path, 'PUT', $parameters, $xml, $header);

		return $response->body;
	}

	/**
	 * Method to create a relation
	 *
	 * @param   integer  $changeset  Changeset id
	 * @param   array    $tags       Array of tags for a relation
	 * @param   array    $members    Array of members for a relation
	 *                               eg: $members = array(array("type"=>"node", "role"=>"stop", "ref"=>"123"), array("type"=>"way", "ref"=>"123"))
	 *
	 * @return  array  The XML response
	 *
	 * @since   1.0
	 */
	public function createRelation($changeset, $tags, $members)
	{
		$token = $this->oauth->getToken();

		// Set parameters.
		$parameters = array(
			'oauth_token' => $token['key']
		);

		// Set the API base
		$base = 'relation/create';

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		$tagList = '';

		// Create XML node
		if (!empty($tags))
		{
			foreach ($tags as $key => $value)
			{
				$tagList .= '<tag k="' . $key . '" v="' . $value . '"/>';
			}
		}

		// Members
		$memberList = '';

		if (!empty($members))
		{
			foreach ($members as $member)
			{
				if ($member['type'] == "node")
				{
					$memberList .= '<member type="' . $member['type'] . '" role="' . $member['role'] . '" ref="' . $member['ref'] . '"/>';
				}
				elseif ($member['type'] == "way")
				{
					$memberList .= '<member type="' . $member['type'] . '" ref="' . $member['ref'] . '"/>';
				}
			}
		}

		$xml = '<?xml version="1.0" encoding="UTF-8"?>
				<osm version="0.6" generator="JoomlaOpenStreetMap">
				<relation relation="' . $changeset . '" >'
					. $tagList
					. $memberList .
				'</relation>
			</osm>';

		$header['Content-Type'] = 'text/xml';

		// Send the request.
		$response = $this->oauth->oauthRequest($path, 'PUT', $parameters, $xml, $header);

		return $response->body;
	}

	/**
	 * Method to read an element [node|way|relation]
	 *
	 * @param   string   $element  [node|way|relation]
	 * @param   integer  $id       Element identifier
	 *
	 * @return  array  The XML response
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function readElement($element, $id)
	{
		if ($element != 'node' && $element != 'way' && $element != 'relation')
		{
			throw new \DomainException("Element should be a node, a way or a relation");
		}

		// Set the API base
		$base = $element . '/' . $id;

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Send the request.
		$xmlString = $this->sendRequest($path);

		return $xmlString->$element;
	}

	/**
	 * Method to update an Element [node|way|relation]
	 *
	 * @param   string   $element  [node|way|relation]
	 * @param   string   $xml      Full reperentation of the element with a version number
	 * @param   integer  $id       Element identifier
	 *
	 * @return  array   The xml response
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function updateElement($element, $xml, $id)
	{
		if ($element != 'node' && $element != 'way' && $element != 'relation')
		{
			throw new \DomainException("Element should be a node, a way or a relation");
		}

		$token = $this->oauth->getToken();

		// Set parameters.
		$parameters = array(
			'oauth_token' => $token['key']
		);

		// Set the API base
		$base = $element . '/' . $id;

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		$header['Content-Type'] = 'text/xml';

		// Send the request.
		$response = $this->oauth->oauthRequest($path, 'PUT', $parameters, $xml, $header);

		return $response->body;
	}

	/**
	 * Method to delete an element [node|way|relation]
	 *
	 * @param   string   $element    [node|way|relation]
	 * @param   integer  $id         Element identifier
	 * @param   integer  $version    Element version
	 * @param   integer  $changeset  Changeset identifier
	 * @param   float    $latitude   Latitude of the element
	 * @param   float    $longitude  Longitude of the element
	 *
	 * @return  array   The XML response
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function deleteElement($element, $id, $version, $changeset, $latitude = null, $longitude = null)
	{
		if ($element != 'node' && $element != 'way' && $element != 'relation')
		{
			throw new \DomainException("Element should be a node, a way or a relation");
		}

		$token = $this->oauth->getToken();

		// Set parameters.
		$parameters = array(
			'oauth_token' => $token['key']
		);

		// Set the API base
		$base = $element . '/' . $id;

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Create xml
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
				<osm version="0.6" generator="JOpenstreetmap">
				<' . $element . ' id="' . $id . '" version="' . $version . '" changeset="' . $changeset . '"';

		if (!empty($latitude) && !empty($longitude))
		{
			$xml .= ' lat="' . $latitude . '" lon="' . $longitude . '"';
		}

		$xml .= '/></osm>';

		$header['Content-Type'] = 'text/xml';

		// Send the request.
		$response = $this->oauth->oauthRequest($path, 'DELETE', $parameters, $xml, $header);

		return $response->body;
	}

	/**
	 * Method to get history of an element [node|way|relation]
	 *
	 * @param   string   $element  [node|way|relation]
	 * @param   integer  $id       Element identifier
	 *
	 * @return  array   The XML response
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function historyOfElement($element, $id)
	{
		if ($element != 'node' && $element != 'way' && $element != 'relation')
		{
			throw new \DomainException("Element should be a node, a way or a relation");
		}

		// Set the API base
		$base = $element . '/' . $id . '/history';

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Send the request.
		$xmlString = $this->sendRequest($path);

		return $xmlString->$element;
	}

	/**
	 * Method to get details about a version of an element [node|way|relation]
	 *
	 * @param   string   $element  [node|way|relation]
	 * @param   integer  $id       Element identifier
	 * @param   integer  $version  Element version
	 *
	 * @return  array    The XML response
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function versionOfElement($element, $id ,$version)
	{
		if ($element != 'node' && $element != 'way' && $element != 'relation')
		{
			throw new \DomainException("Element should be a node, a way or a relation");
		}

		// Set the API base
		$base = $element . '/' . $id . '/' . $version;

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Send the request.
		$xmlString = $this->sendRequest($path);

		return $xmlString->$element;
	}

	/**
	 * Method to get data about multiple ids of an element [node|way|relation]
	 *
	 * @param   string  $element  [nodes|ways|relations] - use plural word
	 * @param   string  $params   Comma separated list of ids belonging to type $element
	 *
	 * @return  array   The XML response
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function multiFetchElements($element, $params)
	{
		if ($element != 'nodes' && $element != 'ways' && $element != 'relations')
		{
			throw new \DomainException("Element should be nodes, ways or relations");
		}

		// Get singular word
		$singleElement = substr($element, 0, strlen($element) - 1);

		// Set the API base, $params is a string with comma seperated values
		$base = $element . '?' . $element . "=" . $params;

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Send the request.
		$xmlString = $this->sendRequest($path);

		return $xmlString->$singleElement;
	}

	/**
	 * Method to get relations for an Element [node|way|relation]
	 *
	 * @param   string   $element  [node|way|relation]
	 * @param   integer  $id       Element identifier
	 *
	 * @return  array   The XML response
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function relationsForElement($element, $id)
	{
		if ($element != 'node' && $element != 'way' && $element != 'relation')
		{
			throw new \DomainException("Element should be a node, a way or a relation");
		}

		// Set the API base
		$base = $element . '/' . $id . '/relations';

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Send the request.
		$xmlString = $this->sendRequest($path);

		return $xmlString->$element;
	}

	/**
	 * Method to get ways for a Node element
	 *
	 * @param   integer  $id  Node identifier
	 *
	 * @return  array  The XML response
	 *
	 * @since   1.0
	 */
	public function waysForNode($id)
	{
		// Set the API base
		$base = 'node/' . $id . '/ways';

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Send the request.
		$xmlString = $this->sendRequest($path);

		return $xmlString->way;
	}

	/**
	 * Method to get full information about an element [way|relation]
	 *
	 * @param   string   $element  [way|relation]
	 * @param   integer  $id       Identifier
	 *
	 * @return  array  The XML response
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function fullElement($element, $id)
	{
		if ($element != 'way' && $element != 'relation')
		{
			throw new \DomainException("Element should be a way or a relation");
		}

		// Set the API base
		$base = $element . '/' . $id . '/full';

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Send the request.
		$xmlString = $this->sendRequest($path);

		return $xmlString->node;
	}

	/**
	 * Method used by the DWG to hide old versions of elements containing data privacy or copyright infringements
	 *
	 * @param   string   $element      [node|way|relation]
	 * @param   integer  $id           Element identifier
	 * @param   integer  $version      Element version
	 * @param   integer  $redactionId  Redaction id
	 *
	 * @return  array   The xml response
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function redaction($element, $id, $version, $redactionId)
	{
		if ($element != 'node' && $element != 'way' && $element != 'relation')
		{
			throw new \DomainException("Element should be a node, a way or a relation");
		}

		$token = $this->oauth->getToken();

		// Set parameters.
		$parameters = array(
			'oauth_token' => $token['key']
		);

		// Set the API base
		$base = $element . '/' . $id . '/' . $version . '/redact?redaction=' . $redactionId;

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Send the request.
		$response = $this->oauth->oauthRequest($path, 'PUT', $parameters);

		return simplexml_load_string($response->body);
	}
}
