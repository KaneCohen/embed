<?php

	return array(

		'youtube' => array(
			'name'    => 'YouTube',
			'type'		=> 'video',
			'website' => 'http://youtube.com',
			'url'     => array(
				'(?:http://)?(?:www\.)?youtu\.be/([0-9a-zA-Z-_]{11})',
				'(?:http://)?(?:(?:www|[a-z]*)\.)?youtube\.com(?:[^"]+?)?(?:&|&amp;|/|/embed/|\?|;)(?:v=)?([0-9a-z-_]{11})',
			),
			'info'    => array(
				'id'     => '{1}',
				'dataUrl' => 'http://gdata.youtube.com/feeds/api/videos/{1}?v=2&alt=jsonc',
				'imageRoot'   => 'http://img.youtube.com/vi/{1}/',
			),
			'render'  => array(
				// iframe attributes
				'iframe' => array(
					'src'     => 'http://www.youtube.com/embed/{1}?rel=0',
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
						'movie' => 'http://youtube.com/v/{1}?version=3&rel=0',
						'allowFullScreen' => true,
						'allowscriptaccess' => 'always',
					),
					// embed shares same attributes as object iteslf, but may have some of it's own attributes
					'embed'   => array(
						'src'     => 'http://youtube.com/v/{1}?version=3&rel=0',
						'width'   => 560,
						'height'  => 315,
						'type' => 'application/x-shockwave-flash',
						'allowFullScreen' => true,
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
			'type'		=> 'video',
			'website' => 'http://vimeo.com',
			'url'     => '(?:http://)?(?:www\.)?vimeo\.com/([0-9])',
			'info'    => array(
				'id'     => '{1}',
				'dataUrl' => 'http://vimeo.com/api/v2/video/{1}.json',
			),
			'render'  => array(
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
				$response = json_decode(file_get_contents($url));
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
			'type'		=> 'video',
			'website' => 'http://dailymotion.com',
			'url'     => '(?:http://)?(?:www\.)?dailymotion\.com/video/([^_]+)',
			'info'    => array(
				'id'    => '{1}',
			),
			'render'  => array(
				'iframe'  => array(
					'src'     => 'http://www.dailymotion.com/video/{1}',
					'width'   => 520,
					'height'  => 420,
				),
			),
			'data'         => null,
			'dataCallback' => null,
		),

		'gametrailers' => array(
			'name'    => 'GameTrailers',
			'type'		=> 'video',
			'website' => 'http://gametrailers.com',
			'url'     => 'http://media\.mtvnservices\.com/embed/([^"]+:)([0-9a-z-_]+)',
			'info'    => array(
				'id'    => '{1}{2}',
			),
			'render'  => array(
				'iframe'  => array(
					'src'     => 'http://media.mtvnservices.com/embed/{1}{2}',
					'width'   => 560,
					'height'  => 315,
				),
			),
			'data'         => null,
			'dataCallback' => null,
		),

	);
