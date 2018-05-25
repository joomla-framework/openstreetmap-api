## The OpenStreetMap Package [![Build Status](https://travis-ci.org/joomla-framework/openstreetmap-api.png?branch=master)](https://travis-ci.org/joomla-framework/openstreetmap-api)

### Deprecated

The `joomla/openstreetmap` package is deprecated with no further updates planned.

### Using the OpenStreetMap Package
The intention of the OpenStreetMap package is to provide an easy straightforward interface to work with OpenStreetMap. This is based on version 0.6 of the OpenStreetMap API. You can find more information about the OpenStreetMap API at [https://wiki.openstreetmap.org/wiki/API_v0.6](https://wiki.openstreetmap.org/wiki/API_v0.6).
The OpenStreetMap package is built upon the `Joomla\OAuth1` package which provides OAuth 1.0 security infrastructure for the communications. The `Joomla\Http` package is also used as an easy way for the non-secure information exchanges.

### Initiating the OpenStreetMap class
Initiating OpenStreetMap is just a couple lines of code:

```php
use Joomla\OpenStreetMap\OpenStreetMap;

$osm = new OpenStreetMap;
```

This creates basic OpenStreetMap object which can access publically available GET methods.
But when you want to send data or get private data, you need to use the `Joomla\OpenStreetMap\OAuth` object too.

To initialise the `Joomla\OpenStreetMap\OAuth` object, you must supply an options array, a `Joomla\Http\Http` instance for HTTP requests, a `Joomla\Input\Input` instance to process request data from OAuth requests, and a `Joomla\Application\AbstractWebApplication` instance to handle OAuth requests.

```php
use Joomla\Http\Http;
use Joomla\OpenStreetMap\OAuth;
use Joomla\OpenStreetMap\OpenStreetMap;

$key    = 'your_key';
$secret = 'your_secret';

$options = array('consumer_key' => $key, 'consumer_secret' => $secret, 'sendheaders' => true);

$client = new Http;

$application = $this->getApplication();

$oauth = new OAuth($options, $client, $application->input, $application);
$oauth->authenticate();

$osm = new OpenStreetMap($oauth);
```

To obtain a key and secret, you have to obtain an account at OpenStreetMap. Through your account you need to [register](https://www.openstreetmap.org/user/username/oauth_clients/new) your application along with a callback URL.

### Accessing OpenStreetMap API
This API will do all types of interactions with OpenStreetMap API. This has been categorized in to 5 main sections: Changeset, Element, GPS, Info and User. All those inherit from `Joomla\OpenStreetMap\OpenStreetMapObject` and can be initiated through the magic `__get` method of the OpenStreetMap class. Methods contained in each type of object are closely related to the OpenStreetMap API calls.

### General Usage
For an example, to get an element with a known identifier you need to just add following two lines additionally after creating `$osm` .

```php
$element = $osm->elements;
$result = $element->readElement('node', 123);

// To view the \SimpleXMLElement object
print_r($result);
```

For sending information to server you must use OAuth authentication. Following is a complete sample application of creating a new changeset. Later you can use your own changeset to add elements you want.

```php
use Joomla\Http\Http;
use Joomla\OpenStreetMap\OAuth;
use Joomla\OpenStreetMap\OpenStreetMap;

$key    = 'your_key';
$secret = 'your_secret';

$options = array('consumer_key' => $key, 'consumer_secret' => $secret, 'sendheaders' => true);

$client = new Http;

$application = $this->getApplication();

$oauth = new OAuth($options, $client, $application->input, $application);
$oauth->authenticate();

$osm = new OpenStreetMap($oauth);

$changeset = $osm->changesets;

$changesets = array(
	'comment'    => 'My First Changeset',
	'created_by' => 'JoomlaOpenStreetMap'
);

$result = $changeset->createChangeset($changesets);

// Returned value contains the identifier of new changeset
print_r($result);
```

### More Information
Following resources contain more information: [OpenStreetMap API](https://wiki.openstreetmap.org/wiki/API)

### Installation via Composer
Add `"joomla/openstreetmap": "2.0.*@dev"` to the require block in your composer.json and then run `composer install`.

```json
{
	"require": {
		"joomla/openstreetmap": "2.0.*@dev"
	}
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer require joomla/openstreetmap "2.0.*@dev"
```
