# Embed

Generate media HTML (YouTube, Vimeo, Kickstarter etc.) based on the URL.

## Installation

`composer require cohensive/embed`

Or add following require to your `composer.json` file:

For Laravel 5/6:

```json
"cohensive/embed": "dev-master"
```

or
```json
"cohensive/embed": "5.5.*"
```

For Laravel 4:

```json
"cohensive/embed": "4.3.*"
```

Then run `composer install` or `composer update` to download it and autoload.

If you're using Laravel 5.5+ you can ignore the next step because the Service Provider is autoloaded.

For Laravel < 5.5, in the `providers` array in `config/app.php` you need to add the following:

```php
'providers' => array(

	//...
	'Cohensive\Embed\EmbedServiceProvider',
	//...

)
```

In aliases:

```php
'aliases' => array(

	//...
	'Embed' => Cohensive\Embed\Facades\Embed::class
	//...

)
```

## Usage

```php
$embed = Embed::make('http://youtu.be/uifYHNyH-jA')->parseUrl()
// Will return Embed class if provider is found. Otherwise will return false - not found. No fancy errors for now.
if ($embed) {
	// Set width of the embed.
	$embed->setAttribute(['width' => 600]);

	// Print html: '<iframe width="600" height="338" src="//www.youtube.com/embed/uifYHNyH-jA" frameborder="0" allowfullscreen></iframe>'.
	// Height will be set automatically based on provider width/height ratio.
	// Height could be set explicitly via setAttr() method.
	echo $embed->getHtml();
}
```
