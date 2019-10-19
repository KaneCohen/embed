<?php

use Cohensive\Embed\Embed;

class EmbedTest extends PHPUnit_Framework_TestCase {

    protected $embed;

    public function setUp()
    {
        $providers = require('src/config/config.php');
        $this->embed = new Embed();
        $this->embed->setProviders($providers['providers']);
    }

    public function testEmbedBasicConstructor()
    {
        $embed = new Embed('http://youtu.be/dQw4w9WgXcQ', array(
            'params' => array(
                'width' => 100
            )
        ));

        $this->assertInstanceOf('Cohensive\Embed\Embed', $embed);
        $this->assertEquals('http://youtu.be/dQw4w9WgXcQ', $embed->getUrl());
        $this->assertEquals(['width' => 100], $embed->getParams());
    }


    public function testEmbedUrlSetting()
    {
        $embed = new Embed('http://youtu.be/dQw4w9WgXcQ');

        $embed->setUrl('http://youtube.com/watch?v=QH2-TGUlwu4');
        $this->assertEquals('http://youtube.com/watch?v=QH2-TGUlwu4', $embed->getUrl());
    }


    public function testEmbedUrlSettingWithTimestamp()
    {
        $params = $this->embed->setUrl('http://youtu.be/dQw4w9WgXcQ?t=2m10s')
            ->parseUrl()
            ->getProvider();
        $this->assertEquals('https://www.youtube.com/embed/dQw4w9WgXcQ?rel=0&wmode=transparent&start=130', $params->render->iframe->src);

        $params = $this->embed->setUrl('http://youtu.be/dQw4w9WgXcQ?t=56s')
            ->parseUrl()
            ->getProvider();
        $this->assertEquals('https://www.youtube.com/embed/dQw4w9WgXcQ?rel=0&wmode=transparent&start=56', $params->render->iframe->src);

        $params = $this->embed->setUrl('http://youtu.be/dQw4w9WgXcQ?t=1h20m57s')
            ->parseUrl()
            ->getProvider();
        $this->assertEquals('https://www.youtube.com/embed/dQw4w9WgXcQ?rel=0&wmode=transparent&start=4857', $params->render->iframe->src);


        $params = $this->embed->setUrl('http://youtu.be/dQw4w9WgXcQ?t=1h20m57s')
            ->parseUrl()
            ->getProvider();
        $this->assertEquals('https://www.youtube.com/embed/dQw4w9WgXcQ?rel=0&wmode=transparent&start=4857', $params->render->iframe->src);

        $params = $this->embed->setUrl('http://youtu.be/dQw4w9WgXcQ?x=1&t=24m10s')
            ->parseUrl()
            ->getProvider();
        $this->assertEquals('https://www.youtube.com/embed/dQw4w9WgXcQ?rel=0&wmode=transparent&start=1450', $params->render->iframe->src);

        $params = $this->embed->setUrl('http://youtube.com/watch?v=dQw4w9WgXcQ&t=24m10s')
            ->parseUrl()
            ->getProvider();
        $this->assertEquals('https://www.youtube.com/embed/dQw4w9WgXcQ?rel=0&wmode=transparent&start=1450', $params->render->iframe->src);

        $params = $this->embed->setUrl('http://youtube.com/v/dQw4w9WgXcQ?t=24m10s')
            ->parseUrl()
            ->getProvider();
        $this->assertEquals('https://www.youtube.com/embed/dQw4w9WgXcQ?rel=0&wmode=transparent&start=1450', $params->render->iframe->src);
    }


    public function testEmbedParamAndAttributeSetting()
    {
        $embed = new Embed('http://youtu.be/dQw4w9WgXcQ', array(
            'params' => array(
                'width' => 100
            )
        ));
        $embed->setAttribute('height', 100);
        $embed->setParam('width', 200);

        $this->assertEquals(['height' => 100], $embed->getAttributes());
        $this->assertEquals(['width' => 200], $embed->getParams());
    }


    public function testEmbedProviderSetting()
    {
        $embed = new Embed();
        $providers = require('src/config/config.php');
        $embed->setProviders($providers['providers']);
        $this->assertEquals($providers['providers'], $embed->getProviders());
    }


    public function testEmbedUrlParsing()
    {
        $this->embed->setUrl('http://youtu.be/dQw4w9WgXcQ');
        $this->assertInstanceOf('Cohensive\Embed\Embed', $this->embed->parseUrl());

        $this->embed->setUrl('http://hello.world/videoID');
        $this->assertFalse($this->embed->parseUrl());
    }


    public function testEmbedWithSSLAndSSLProvider()
    {
        $this->embed->setUrl('http://youtu.be/dQw4w9WgXcQ')->parseUrl();
        $this->assertEquals('https://youtu.be/dQw4w9WgXcQ', $this->embed->getProvider()->info->url);
    }

    public function testEmbedWithSSLAndNonSSLProvider()
    {
        $this->embed->setUrl('http://twitch.tv/day9tv')->parseUrl();
        $this->assertEquals('https://twitch.tv/day9tv', $this->embed->getProvider()->info->url);
    }

    public function testEmbedHTMLGeneration()
    {
        $this->embed->setUrl('http://youtu.be/dQw4w9WgXcQ')->parseUrl();

        $this->assertEquals('<iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ?rel=0&wmode=transparent" width="560" height="315" allowfullscreen="" frameborder="0" sandbox="allow-scripts allow-same-origin allow-presentation" layout="responsive"></iframe>', $this->embed->getIframe());
    }

    public function testHTML5VideoGeneration()
    {
        $this->embed->setUrl('http://example.com/hello.mp4')->parseUrl();

        $this->assertEquals('<video width="560" height="315" controls="controls" layout="responsive"><source src="http://example.com/hello.webm" type="video/webm"></source><source src="http://example.com/hello.ogg" type="video/ogg"></source><source src="http://example.com/hello.mp4" type="video/mp4"></source></video>', $this->embed->getHtml());
    }

    public function testAMPEmbedHTMLGeneration()
    {
        $this->embed->setUrl('http://youtu.be/dQw4w9WgXcQ')->parseUrl();
        $this->embed->enableAmpMode();

        $this->assertEquals('<amp-iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ?rel=0&wmode=transparent" width="560" height="315" allowfullscreen="" frameborder="0" sandbox="allow-scripts allow-same-origin allow-presentation" layout="responsive"></amp-iframe>', $this->embed->getIframe());
    }

    public function testAMPHTML5VideoGeneration()
    {
        $this->embed->setUrl('http://example.com/hello.mp4')->parseUrl();

        $this->assertEquals('<amp-video width="560" height="315" controls="controls" layout="responsive"><source src="http://example.com/hello.webm" type="video/webm"></source><source src="http://example.com/hello.ogg" type="video/ogg"></source><source src="http://example.com/hello.mp4" type="video/mp4"></source></amp-video>', $this->embed->getAmpHtml());
    }

    public function testAMPHTML5SSLVideoGeneration()
    {
        $this->embed->setUrl('https://example.com/hello.mp4')->parseUrl();

        $this->assertEquals('<amp-video width="560" height="315" controls="controls" layout="responsive"><source src="https://example.com/hello.webm" type="video/webm"></source><source src="https://example.com/hello.ogg" type="video/ogg"></source><source src="https://example.com/hello.mp4" type="video/mp4"></source></amp-video>', $this->embed->getAmpHtml());
    }

    public function testTwitchParseData()
    {
        $this->embed->setUrl('https://www.twitch.tv/clintstevens/clip/CrispyEndearingCiderBibleThump')->parseUrl();
        $expected = '<iframe src="https://clips.twitch.tv/embed?clip=CrispyEndearingCiderBibleThump&autoplay=false&tt_medium=clips_embed" width="420" height="237" scrolling="no" allowfullscreen="1" frameborder="0" sandbox="allow-scripts allow-popups allow-same-origin allow-presentation" layout="responsive"></iframe>';

        $this->assertEquals($expected, $this->embed->getHtml());
    }

    public function testVimeoParseData()
    {
        $this->embed->setUrl('https://vimeo.com/73116214')->parseUrl()->fetchData();

        $this->assertTrue(is_object($this->embed->getProvider()->data));
        $this->assertEquals('The Mayor of Times Square', $this->embed->getProvider()->data->title);
    }

    public function testYoutubeParseData()
    {
        $config = ['google_api_key' => '123456'];
        $this->embed->setConfig($config)->setUrl('http://youtu.be/dQw4w9WgXcQ')->parseUrl()->fetchData();

        // Expect fail since google api key is incorrect.
        $this->assertFalse(is_object($this->embed->getProvider()->data));
    }
}
