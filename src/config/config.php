<?php

return array(

	/*
	 |--------------------------------------------------------------------------
	 | SSL
	 |--------------------------------------------------------------------------
	 |
	 | By default (false), Embed will use SSL (https) based on specific
	 | provider support for SSL and input URL. Input URL must explicitly
	 | use "https" to enable SSL for resulting embed element.
	 |
	 | If ssl is set to true, then Embed will try to use SSL on any provider
	 | that supports it.
	 |
	*/

	'ssl' => false,

	/*
	 |--------------------------------------------------------------------------
	 | Media Providers
	 |--------------------------------------------------------------------------
	 |
	 | List of media providers used to construct embed elements.
	 |
	*/

	'providers' => array(
		'youtubePlaylistVideo' => array(
			'name'    => 'YouTube Playlist',
			'type'    => 'video',
			'website' => 'http://youtube.com',
			'ssl'     => true,
			'url'     => '^(https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com/watch\?v=)([0-9a-zA-Z-_]{11})(?:\S*list=)([0-9a-zA-Z-_]+)',
			'info'    => array(
				'id'     => '{1}',
				'url'    => '{protocol}://youtube.com/watch?v={1}&list={2}',
			),
			'render'  => array(
				// iframe attributes
				'sizeRatio' => 1.77,
				'iframe' => array(
					'src'     => '{protocol}://www.youtube.com/embed/{1}?list={2}&rel=0&wmode=transparent',
					'width'   => 560,
					'height'  => 315,
					'allowfullscreen' => null,
					'frameborder'     => 0,
				),
			),
			'data' => null,
			'dataCallback' => null,
		),

		'youtubePlaylist' => array(
			'name'    => 'YouTube Playlist',
			'type'    => 'video',
			'website' => 'http://youtube.com',
			'ssl'     => true,
			'url'     => array(
				'^(https?://)?(?:www\.)?youtube\.com/playlist\?list=([0-9a-zA-Z-_]+)',
			),
			'info'    => array(
				'id'     => '{1}',
				'url'    => '{protocol}://youtube.com/playlist?list={1}',
			),
			'render'  => array(
				// iframe attributes
				'sizeRatio' => 1.77,
				'iframe' => array(
					'src'     => '{protocol}://www.youtube.com/embed/videoseries?list={1}&rel=0&wmode=transparent',
					'width'   => 560,
					'height'  => 315,
					'allowfullscreen' => null,
					'frameborder'     => 0,
				),
			),
			'data' => null,
			'dataCallback' => null,
		),

		'youtube' => array(
			'name'    => 'YouTube',
			'type'    => 'video',
			'website' => 'http://youtube.com',
			'ssl'     => true,
			'url'     => array(
				'^(https?://)?(?:www\.)?youtu\.be/([0-9a-zA-Z-_]{11})',
				'^(https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com/(?:embed/|v/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$'
			),
			'info'    => array(
				'id'     => '{1}',
				'url'    => '{protocol}://youtu.be/{1}',
				'dataUrl' => '{protocol}://gdata.youtube.com/feeds/api/videos/{1}?v=2&alt=jsonc',
				'imageRoot'   => '{protocol}://img.youtube.com/vi/{1}/',
			),
			'render'  => array(
				// iframe attributes
				'sizeRatio' => 1.77,
				'iframe' => array(
					'src'     => '{protocol}://www.youtube.com/embed/{1}?rel=0&wmode=transparent',
					'width'   => 560,
					'height'  => 315,
					'allowfullscreen' => null,
					'frameborder'     => 0,
				),
				'object'  => array(
					'attributes' => array(
						'width'   => 560,
						'height'  => 315,
					),
					'params'  => array(
						'movie' => '{protocol}://youtube.com/v/{1}?version=3&rel=0&wmode=transparent',
						'wMode' => 'transparent',
						'allowFullScreen'   => 'true',
						'allowscriptaccess' => 'always',
					),
					// embed shares same attributes as object iteslf, but may have some of it's own attributes
					'embed'   => array(
						'src'     => '{protocol}://youtube.com/v/{1}?version=3&rel=0&wmode=transparent',
						'width'   => 560,
						'height'  => 315,
						'type' => 'application/x-shockwave-flash',
						'allowFullScreen'   => 'true',
						'allowscriptaccess' => 'always',
					),
				),
			),
			'data' => null,
			'dataCallback' => function($embed) {
				$provider = $embed->getProvider();
				$url = $provider['info']['dataUrl'];
				$response = json_decode(file_get_contents($url));
				return array(
					'title'  => $response->data->title,
					'description' => $response->data->description,
					'created_at'  => $response->data->uploaded,
					'image' => array(
						'small'  => $response->data->thumbnail->sqDefault,
						'medium' => $provider['info']['imageRoot'].'mqdefault.jpg',
						'large'  => $response->data->thumbnail->hqDefault,
						'max'	   => $provider['info']['imageRoot'].'maxresdefault.jpg',
					),
					'full' => $response,
				);
			},
		),

		'liveleak' => array(
			'name'    => 'LiveLeak',
			'type'    => 'video',
			'website' => 'http://liveleak.com',
			'ssl'     => false,
			'url'     => '(https?://)?(?:www\.)?liveleak\.com/ll_embed\?f=([0-9a-z]+)',
			'info'    => array(
				'id'    => '{1}',
				'url'   => '{protocol}://liveleak.com/ll_embed?f={1}',
			),
			'render'  => array(
				'sizeRatio' => 1.77,
				'iframe'  => array(
					'src'     => '{protocol}://liveleak.com/ll_embed?f={1}',
					'width'   => 640,
					'height'  => 360,
				),
			),
			'data'         => null,
			'dataCallback' => null,
		),

		'vimeo' => array(
			'name'    => 'Vimeo',
			'type'    => 'video',
			'website' => 'http://vimeo.com',
			'ssl'     => false,
			'url'     => '(https?://)?(?:www\.)?vimeo\.com/([0-9]+)',
			'info'    => array(
				'id'     => '{1}',
				'url'    => '{protocol}://vimeo.com/{1}',
				'dataUrl' => '{protocol}://vimeo.com/api/v2/video/{1}.json',
			),
			'render'  => array(
				'sizeRatio' => 1.77,
				'iframe'  => array(
					'src'     => '{protocol}://player.vimeo.com/video/{1}',
					'width'   => 500,
					'height'  => 281,
					'allowfullscreen' => null,
					'frameborder'     => 0,
				),
			),
			'data' => null,
			'dataCallback' => function($embed) {
				$url = $embed->getProvider()['info']['dataUrl'];
				$response = json_decode(file_get_contents($url))[0];
				return array(
					'title'  => $response->title,
					'description' => $response->description,
					'created_at'  => $response->upload_date,
					'image' => array(
						'small'  => $response->thumbnail_small,
						'medium' => $response->thumbnail_medium,
						'large'  => $response->thumbnail_large,
						'max'  => $response->thumbnail_large,
					),
					'full' => $response,
				);
			},
		),

		'dailymotion' => array(
			'name'    => 'Dailymotion',
			'type'    => 'video',
			'website' => 'http://dailymotion.com',
			'ssl'     => true,
			'url'     => '(https?://)?(?:www\.)?dailymotion\.com/video/([^_]+)',
			'info'    => array(
				'id'    => '{1}',
				'url'   => '{protocol}://dailymotion.com/video/{1}',
			),
			'render'  => array(
				'sizeRatio' => 1.77,
				'iframe'  => array(
					'src'     => '{protocol}://www.dailymotion.com/embed/video/{1}',
					'width'   => 520,
					'height'  => 420,
				),
			),
			'data'         => null,
			'dataCallback' => null,
		),

		'gametrailers' => array(
			'name'    => 'GameTrailers',
			'type'    => 'video',
			'website' => 'http://gametrailers.com',
			'ssl'     => false,
			'url'     => '^(https?://)?media\.mtvnservices\.com/embed/([^"]+:)([0-9a-z-_]+)',
			'info'    => array(
				'id'    => '{1}{2}',
				'url'   => '{protocol}://media.mtvnservices.com/embed/{1}{2}',
			),
			'render'  => array(
				'sizeRatio' => 1.77,
				'iframe'  => array(
					'src'     => '{protocol}://media.mtvnservices.com/embed/{1}{2}',
					'width'   => 560,
					'height'  => 315,
				),
			),
			'data'         => null,
			'dataCallback' => null,
		),

		'ign' => array(
			'name'    => 'IGN',
			'type'    => 'video',
			'website' => 'http://ign.com',
			'ssl'     => false,
			'url'     => '^(https?://)?(?:www\.)?ign\.com/videos/([0-9a-zA-Z-_/]+)',
			'info'    => array(
				'id'    => '{1}',
				'url'   => '{protocol}://ign.com/videos/{1}',
			),
			'render'  => array(
				'sizeRatio' => 1.77,
				'iframe'  => array(
					'src'     => '{protocol}://widgets.ign.com/video/embed/content.html?url={1}',
					'width'   => 560,
					'height'  => 315,
				),
			),
			'data'         => null,
			'dataCallback' => null,
		),

		'vine' => array(
			'name'    => 'Vine',
			'type'    => 'video',
			'website' => 'http://vine.co',
			'ssl'     => true,
			'url'     => array(
				'^(https?://)?(?:www\.)?vine\.co/v/([0-9a-zA-Z]+)'
			),
			'info'    => array(
				'id'    => '{1}',
				'url'   => '{protocol}://vine.co/v/{1}',
			),
			'render'  => array(
				'sizeRatio' => 0.864,
				'iframe'  => array(
					'src'     => '{protocol}://vine.co/v/{1}/embed/postcard',
					'width'   => 600,
					'height'  => 600,
				),
			),
			'data'         => null,
			'dataCallback' => null,
		),

		'coub' => array(
			'name'    => 'Coub',
			'type'    => 'video',
			'website' => 'http://coub.com',
			'ssl'     => true,
			'url'     => '(https?://)?(?:www\.)?coub\.com/view/([^_]+)',
			'info'    => array(
				'id'    => '{1}',
				'url'   => '{protocol}://coub.com/view/{1}',
			),
			'render'  => array(
				'sizeRatio' => 1.77,
				'iframe'  => array(
					'src'     => '{protocol}://coub.com/embed/{1}?muted=false&autostart=false&originalSize=false&hideTopBar=false',
					'width'   => 640,
					'height'  => 360,
				),
			),
			'data'         => null,
			'dataCallback' => null,
		),

		'kickstarter' => array(
			'name'    => 'Kickstarter',
			'type'    => 'video',
			'website' => 'http://kickstarter.com',
			'ssl'     => true,
			'url'     => array(
				'^(https?://)?(?:www\.)?kickstarter\.com/projects/([0-9a-zA-Z-_]+)/([0-9a-zA-Z-_]+)'
			),
			'info'    => array(
				'id'    => '{1}',
				'url'   => '{protocol}://kickstarter.com/projects/{1}/{2}'
			),
			'render'  => array(
				'sizeRatio' => 1.33,
				'iframe'  => array(
					'src'     => '{protocol}://kickstarter.com/projects/{1}/{2}/widget/video.html',
					'scrolling' => 'no',
					'width'   => 640,
					'height'  => 480,
				),
			),
			'data'         => null,
			'dataCallback' => null,
		),

		'ustream' => array(
			'name'    => 'Ustream',
			'type'    => 'video',
			'website' => 'http://ustream.tv',
			'ssl'     => true,
			'url'     => array(
				'^(https?://)?(?:www\.)?ustream\.tv/channel/([0-9]+)'
			),
			'info'    => array(
				'id'    => '{1}',
				'url'   => '{protocol}://ustream.tv/channel/{1}'
			),
			'render'  => array(
				'sizeRatio' => 1.64,
				'iframe'  => array(
					'src'     => '{protocol}://ustream.tv/embed/{1}?v3&wmode=direct',
					'scrolling' => 'no',
					'width'   => 670,
					'height'  => 390,
				),
			),
			'data'         => null,
			'dataCallback' => null,
		),

		'ustreamArchive' => array(
			'name'    => 'Ustream Recorded',
			'type'    => 'video',
			'website' => 'http://ustream.tv',
			'ssl'     => true,
			'url'     => array(
				'^(https?://)?(?:www\.)?ustream\.tv/recorded/([0-9]+)'
			),
			'info'    => array(
				'id'    => '{1}',
				'url'   => '{protocol}://ustream.tv/recorded/{1}'
			),
			'render'  => array(
				'sizeRatio' => 1.64,
				'iframe'  => array(
					'src'     => '{protocol}://ustream.tv/embed/recorded/{1}?v3&wmode=direct',
					'scrolling' => 'no',
					'width'   => 670,
					'height'  => 390,
				),
			),
			'data'         => null,
			'dataCallback' => null,
		),

		'twitchArchive' => array(
			'name'    => 'Twitch Archive',
			'type'    => 'video',
			'website' => 'http://twitch.tv',
			'ssl'     => false,
			'url'     => array(
				'^(https?://)?(?:www\.)?twitch\.tv/([^"]+)/b/([0-9]+)'
			),
			'info'    => array(
				'id'    => '{1}',
				'url'   => '{protocol}://twitch.tv/{1}/b/{2}'
			),
			'render'  => array(
				'sizeRatio' => 1.64,
				'object'  => array(
					'attributes' => array(
						'type'   => 'application/x-shockwave-flash',
						'data'   => '{protocol}://twitch.tv/widgets/archive_embed_player.swf',
						'wmode'  => 'transparent',
						'id'     => 'clip_embed_player_flash',
						'width'  => 500,
						'height' => 350,
					),
					'params'  => array(
						'wMode' => 'transparent',
						'allowFullScreen'   => 'true',
						'allowScriptAccess' => 'always',
						'allowNetworking'   => 'all',
						'flashvars'         => 'archive_id={2}&channel={1}&hostname=www.twitch.tv&auto_play=false&start_volume=25',
						'movie'             => '{protocol}://www.twitch.tv/widgets/archive_embed_player.swf'
					),
				),
			),
			'data'         => null,
			'dataCallback' => null,
		),

		'twitchArchiveChapter' => array(
			'name'    => 'Twitch Archive',
			'type'    => 'video',
			'website' => 'http://twitch.tv',
			'ssl'     => false,
			'url'     => array(
				'^(https?://)?(?:www\.)?twitch\.tv/([^"]+)/c/([0-9]+)'
			),
			'info'    => array(
				'id'    => '{1}',
				'url'   => '{protocol}://twitch.tv/{1}/c/{2}'
			),
			'render'  => array(
				'sizeRatio' => 1.64,
				'object'  => array(
					'attributes' => array(
						'type'   => 'application/x-shockwave-flash',
						'data'   => '{protocol}://twitch.tv/widgets/archive_embed_player.swf',
						'wmode'  => 'transparent',
						'id'     => 'clip_embed_player_flash',
						'width'  => 500,
						'height' => 350,
					),
					'params'  => array(
						'wMode' => 'transparent',
						'allowFullScreen'   => 'true',
						'allowScriptAccess' => 'always',
						'allowNetworking'   => 'all',
						'flashvars'         => 'chapter_id={2}&channel={1}&hostname=www.twitch.tv&auto_play=false&start_volume=25',
						'movie'             => '{protocol}://www.twitch.tv/widgets/archive_embed_player.swf'
					),
				),
			),
			'data'         => null,
			'dataCallback' => null,
		),

		'twitch' => array(
			'name'    => 'Twitch',
			'type'    => 'video',
			'website' => 'http://twitch.tv',
			'ssl'     => false,
			'url'     => array(
				'^(https?://)?(?:www\.)?twitch\.tv/([0-9a-zA-Z-_]+)'
			),
			'info'    => array(
				'id'    => '{1}',
				'url'   => '{protocol}://twitch.tv/{1}'
			),
			'render'  => array(
				'sizeRatio' => 1.64,
				'object'  => array(
					'attributes' => array(
						'type'   => 'application/x-shockwave-flash',
						'data'   => '{protocol}://twitch.tv/widgets/live_embed_player.swf?channel={1}',
						'wmode'  => 'transparent',
						'id'     => 'live_embed_player_flash',
						'width'  => 500,
						'height' => 350,
					),
					'params'  => array(
						'allowFullScreen'   => 'true',
						'allowScriptAccess' => 'always',
						'allowNetworking'   => 'all',
						'flashvars'         => 'hostname=www.twitch.tv&channel={1}&auto_play=false&start_volume=25',
						'movie'             => '{protocol}://www.twitch.tv/widgets/live_embed_player.swf'
					),
				),
			),
			'data'         => null,
			'dataCallback' => null,
		),

		'html5video' => array(
			'name'    => 'HTML5 video',
			'type'    => 'video',
			'website' => '',
			'ssl'     => true,
			'url'     => array(
				'^(https?://)?(.*).(mp4|ogg|webm)$'
			),
			'info'    => array(
				'id'    => '{1}.{2}',
				'url'   => '{1}.{2}'
			),
			'render'  => array(
				'sizeRatio' => 1.77,
				'video' => array(
					'source'  => array(
						array(
							'type' => 'video/webm',
							'src'  => '{protocol}://{1}.webm',
						),
						array(
							'type' => 'video/ogg',
							'src'  => '{protocol}://{1}.ogg',
						),
						array(
							'type' => 'video/mp4',
							'src'  => '{protocol}://{1}.mp4',
						)
					),
					'width'    => 560,
					'height'   => 315,
					'controls' => 'controls',
				),
			),
			'data'         => null,
			'dataCallback' => null,
		),
	)

);
