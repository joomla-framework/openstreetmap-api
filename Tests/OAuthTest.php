<?php
/**
 * Tests for the Joomla Framework OpenStreetMap Package
 *
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\OpenStreetMap\Tests;

/**
 * Test class for Joomla\OpenStreetMap\OAuth.
 *
 * @since  1.0
 */
class OAuthTest extends Cases\OSMTestCase
{
	/**
	 * Provides test data for request format detection.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function seedVerifyCredentials()
	{
		// Code, body, expected
		return array(
			array(200, 'Test String', true)
		);
	}

	/**
	 * Tests the verifyCredentials method
	 *
	 * @param   integer  $code      The return code.
	 * @param   string   $body      The string.
	 * @param   boolean  $expected  Expected return value.
	 *
	 * @return  void
	 *
	 * @dataProvider seedVerifyCredentials
	 * @since   1.0
	 */
	public function testVerifyCredentials($code, $body, $expected)
	{
		$returnData = new \stdClass;
		$returnData->code = $code;
		$returnData->body = $body;

		$this->assertEquals(
			$expected,
			$this->oauth->verifyCredentials()
		);
	}
}
