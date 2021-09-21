# New Version

If you're running PHP 8+ I highly recommend using new version of the library: [OEmbed](https://github.com/KaneCohen/oembed).  
It has more features while still working almost the same way as this one.

# Embed

Generate media html (YouTube, Vimeo, Kickstarter etc.) based on the url.

## Installation

Add following require to your `composer.json` file:

For Laravel 5:

~~~
    "cohensive/embed": "dev-master"
    // or
    "cohensive/embed": "5.5.*"
~~~

For Laravel 4:

~~~
    "cohensive/embed": "4.3.*"
~~~

Then run `composer install` or `composer update` to download it and autoload.

In `providers` array you need to add new package:

~~~
'providers' => array(

	//...
	'Cohensive\Embed\EmbedServiceProvider',
	//...

)
~~~

In aliases:

~~~
'aliases' => array(

	//...
	'Embed' => 'Cohensive\Embed\Facades\Embed'
	//...

)
~~~

## Usage

~~~
$embed = Embed::make('http://youtu.be/uifYHNyH-jA')->parseUrl();
// Will return Embed class if provider is found. Otherwie will return false - not found. No fancy errors for now.
if ($embed) {
	// Set width of the embed.
	$embed->setAttribute(['width' => 600]);

	// Print html: '<iframe width="600" height="338" src="//www.youtube.com/embed/uifYHNyH-jA" frameborder="0" allowfullscreen></iframe>'.
	// Height will be set automatically based on provider width/height ratio.
	// Height could be set explicitly via setAttr() method.
	echo $embed->getHtml();
}
~~~
