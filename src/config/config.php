<?php

return array(

	'providers' => array(
		'youtube' => array(
			'name'    => 'YouTube',
			'type'    => 'video',
			'website' => 'http://youtube.com',
			'url'     => array(
				'^(?:https?://)?(?:www\.)?youtu\.be/([0-9a-zA-Z-_]{11})',
				'^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com/(?:embed/|v/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$'
			),
			'info'    => array(
				'id'     => '{1}',
				'url'    => 'http://youtu.be/{1}',
				'dataUrl' => 'http://gdata.youtube.com/feeds/api/videos/{1}?v=2&alt=jsonc',
				'imageRoot'   => 'http://img.youtube.com/vi/{1}/',
			),
			'render'  => array(
				// iframe attributes
				'sizeRatio' => 1.77,
				'iframe' => array(
					'src'     => 'http://www.youtube.com/embed/{1}?rel=0&wmode=transparent',
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
						'movie' => 'http://youtube.com/v/{1}?version=3&rel=0&wmode=transparent',
						'wMode' => 'transparent',
						'allowFullScreen'   => 'true',
						'allowscriptaccess' => 'always',
					),
					// embed shares same attributes as object iteslf, but may have some of it's own attributes
					'embed'   => array(
						'src'     => 'http://youtube.com/v/{1}?version=3&rel=0&wmode=transparent',
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

		'vimeo' => array(
			'name'    => 'Vimeo',
			'type'    => 'video',
			'website' => 'http://vimeo.com',
			'url'     => '(?:http://)?(?:www\.)?vimeo\.com/([0-9]+)',
			'info'    => array(
				'id'     => '{1}',
				'url'    => 'http://vimeo.com/{1}',
				'dataUrl' => 'http://vimeo.com/api/v2/video/{1}.json',
			),
			'render'  => array(
				'sizeRatio' => 1.77,
				'iframe'  => array(
					'src'     => 'http://player.vimeo.com/video/{1}',
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
			'url'     => '(?:http://)?(?:www\.)?dailymotion\.com/video/([^_]+)',
			'info'    => array(
				'id'    => '{1}',
				'url'   => 'http://dailymotion.com/video/{1}',
			),
			'render'  => array(
				'sizeRatio' => 1.77,
				'iframe'  => array(
					'src'     => 'http://www.dailymotion.com/embed/video/{1}',
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
			'url'     => '^(?:https?://)?media\.mtvnservices\.com/embed/([^"]+:)([0-9a-z-_]+)',
			'info'    => array(
				'id'    => '{1}{2}',
				'url'   => 'http://media.mtvnservices.com/embed/{1}{2}',
			),
			'render'  => array(
				'sizeRatio' => 1.77,
				'iframe'  => array(
					'src'     => 'http://media.mtvnservices.com/embed/{1}{2}',
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
			'url'     => '^(?:https?://)?(?:www\.)?ign\.com/videos/([0-9a-zA-Z-_/]+)',
			'info'    => array(
				'id'    => '{1}',
				'url'   => 'http://ign\.com/videos/{1}',
			),
			'render'  => array(
				'sizeRatio' => 1.7,
				'iframe'  => array(
					'src'     => 'http://widgets.ign.com/video/embed/content.html?url={1}',
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
			'url'     => array(
				'^(?:https?://)?(?:www\.)?vine\.co/v/([0-9a-zA-Z]+)',
				'^(?:https?://)?(?:www\.)?vine\.co/v/([0-9a-zA-Z]+)',
			),
			'info'    => array(
				'id'    => '{1}',
				'url'   => 'http://vine.co/v/{1}',
			),
			'render'  => array(
				'sizeRatio' => 0.864,
				'iframe'  => array(
					'src'     => 'http://vine.co/v/{1}/embed/postcard',
					'width'   => 600,
					'height'  => 600,
				),
			),
			'data'         => null,
			'dataCallback' => null,
		),

		'kickstarter' => array(
			'name'    => 'Kickstarter',
			'type'    => 'video',
			'website' => 'http://kickstarter.com',
			'url'     => array(
				'^(?:https?://)?(?:www\.)?kickstarter\.com/projects/([0-9a-zA-Z-_]+)/([0-9a-zA-Z-_]+)'
			),
			'info'    => array(
				'id'    => '{1}',
				'url'   => 'http://kickstarter.com/projects/{1}/{2}'
			),
			'render'  => array(
				'sizeRatio' => 1.33,
				'iframe'  => array(
					'src'     => 'http://kickstater.com/projects/{1}/{2}/widget/video.html',
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
			'url'     => array(
				'^(?:https?://)?(?:www\.)?ustream\.tv/channel/([0-9]+)'
			),
			'info'    => array(
				'id'    => '{1}',
				'url'   => 'http://ustream.tv/channel/{1}'
			),
			'render'  => array(
				'sizeRatio' => 1.64,
				'iframe'  => array(
					'src'     => 'http://ustream.tv/embed/{1}?v3&wmode=direct',
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
			'url'     => array(
				'^(?:https?://)?(?:www\.)?ustream\.tv/recorded/([0-9]+)'
			),
			'info'    => array(
				'id'    => '{1}',
				'url'   => 'http://ustream.tv/recorded/{1}'
			),
			'render'  => array(
				'sizeRatio' => 1.64,
				'iframe'  => array(
					'src'     => 'http://ustream.tv/embed/recorded/{1}?v3&wmode=direct',
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
			'url'     => array(
				'^(?:https?://)?(?:www\.)?twitch\.tv/([^"]+)/([^"]+)/([0-9]{8,11})'
			),
			'info'    => array(
				'id'    => '{1}',
				'url'   => 'http://twitch.tv/{1}/{2}/{3}'
			),
			'render'  => array(
				'sizeRatio' => 1.64,
				'object'  => array(
					'attributes' => array(
						'type'   => 'application/x-shockwave-flash',
						'data'   => 'http://twitch.tv/widgets/archive_embed_player.swf',
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
						'flashvars'         => 'archive_id={3}&channel={1}&hostname=www.twitch.tv&auto_play=false&start_volume=25',
						'movie'             => 'http://www.twitch.tv/widgets/archive_embed_player.swf'
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
			'url'     => array(
				'^(?:https?://)?(?:www\.)?twitch\.tv/([0-9a-zA-Z]+)'
			),
			'info'    => array(
				'id'    => '{1}',
				'url'   => 'http://twitch.tv/{1}'
			),
			'render'  => array(
				'sizeRatio' => 1.64,
				'object'  => array(
					'attributes' => array(
						'type'   => 'application/x-shockwave-flash',
						'data'   => 'http://twitch.tv/widgets/live_embed_player.swf?channel={1}',
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
						'movie'             => 'http://www.twitch.tv/widgets/live_embed_player.swf'
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
			'url'     => array(
				'(.*).(mp4|ogg|webm)$'
			),
			'info'    => array(
				'id'    => '{1}.{2}',
				'url'   => '{1}.{2}'
			),
			'render'  => array(
				'sizeRatio' => 1.77,
				'video' => array(
					'src'      => '{1}.{2}',
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
