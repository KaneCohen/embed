<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Google API Key.
     |--------------------------------------------------------------------------
     |
     | Ensure you have enabled YouTube Data API v3.
     |
     */
    'google_api_key' => 'YOUR API KEY HERE',

    /*
     |--------------------------------------------------------------------------
     | Media Providers
     |--------------------------------------------------------------------------
     |
     | List of media providers used to construct embed elements.
     |
    */

    'providers' => [
        'youtubePlaylistVideo' => [
            'name' => 'YouTube Playlist',
            'type' => 'video',
            'website' => 'http://youtube.com',
            'ssl' => true,
            'url' => '^(https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com/watch\?v=)([0-9a-zA-Z-_]{11})(?:\S*list=)([0-9a-zA-Z-_]+)',
            'info' => [
                'id' => '{1}',
                'url' => '{protocol}://youtube.com/watch?v={1}&list={2}',
            ],
            'render' => [
                // iframe attributes
                'sizeRatio' => 1.77,
                'iframe' => [
                    'src' => '{protocol}://www.youtube.com/embed/{1}?list={2}&rel=0&wmode=transparent',
                    'width' => 560,
                    'height' => 315,
                    'allowfullscreen' => null,
                    'frameborder' => 0,
                    'sandbox' => 'allow-scripts allow-same-origin allow-presentation',
                    'layout' => 'responsive',
                ],
                'wrappedIframe' => [
                    'src' => '{protocol}://www.youtube.com/embed/{1}?list={2}&rel=0&wmode=transparent',
                    'width' => '100%',
                    'height' => '100%',
                    'allowfullscreen' => null,
                    'frameborder' => 0,
                    'sandbox' => 'allow-scripts allow-same-origin allow-presentation',
                    'layout' => 'responsive',
                    'style' => 'position: absolute;',
                ],
                'wrapper' => [
                    'start' => '<div style="left: 0; width: 100%; height: 0; position: relative; padding-bottom: 56.5%;">',
                    'end' => '</div>'
                ],
            ],
            'data' => null,
            'dataCallback' => null,
        ],

        'youtubePlaylist' => [
            'name' => 'YouTube Playlist',
            'type' => 'video',
            'website' => 'http://youtube.com',
            'ssl' => true,
            'url' => [
                '^(https?://)?(?:www\.)?youtube\.com/playlist\?list=([0-9a-zA-Z-_]+)',
            ],
            'info' => [
                'id' => '{1}',
                'url' => '{protocol}://youtube.com/playlist?list={1}',
            ],
            'render' => [
                // iframe attributes
                'sizeRatio' => 1.77,
                'iframe' => [
                    'src' => '{protocol}://www.youtube.com/embed/videoseries?list={1}&rel=0&wmode=transparent',
                    'width' => 560,
                    'height' => 315,
                    'allowfullscreen' => null,
                    'frameborder' => 0,
                    'sandbox' => 'allow-scripts allow-same-origin allow-presentation',
                    'layout' => 'responsive',
                ],
                'wrappedIframe' => [
                    'src' => '{protocol}://www.youtube.com/embed/videoseries?list={1}&rel=0&wmode=transparent',
                    'width' => '100%',
                    'height' => '100%',
                    'allowfullscreen' => null,
                    'frameborder' => 0,
                    'sandbox' => 'allow-scripts allow-same-origin allow-presentation',
                    'layout' => 'responsive',
                    'style' => 'position: absolute;',
                ],
                'wrapper' => [
                    'start' => '<div style="left: 0; width: 100%; height: 0; position: relative; padding-bottom: 56.5%;">',
                    'end' => '</div>'
                ],
            ],
            'data' => null,
            'dataCallback' => null,
        ],

        'youtube' => [
            'name' => 'YouTube',
            'type' => 'video',
            'website' => 'http://youtube.com',
            'ssl' => true,
            'url' => [
                '^(https?://)?(?:www\.)?youtu\.be/([0-9a-zA-Z-_]{11})?(?:(?:\S+)?(?:\?|&)t=([0-9hm]+s))?(?:\S+)?',
                '^(https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com/(?:embed/|v/|shorts/|watch\?v=|watch\?.+&v=))((?:\w|-){11})(?:(?:\S+)?(?:\?|&)t=([0-9hm]+s))?(?:\S+)?$',
            ],
            'timestamp' => '^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com/)(?:\S+)?(?:(?:\S+)?(?:\?|&)t=(?:([0-9]+)h)?(?:([0-9]+)m)?(?:([0-9]+)s)?)$',
            'timestampParam' => '&start=',
            'info' => [
                'id' => '{1}',
                'url' => '{protocol}://youtu.be/{1}',
                'dataUrl' => '{protocol}://www.googleapis.com/youtube/v3/videos?part=snippet&id={1}',
                'imageRoot' => '{protocol}://img.youtube.com/vi/{1}/',
            ],
            'render' => [
                // iframe attributes
                'sizeRatio' => 1.77,
                'iframe' => [
                    'src' => '{protocol}://www.youtube.com/embed/{1}?rel=0&wmode=transparent',
                    'width' => 560,
                    'height' => 315,
                    'allowfullscreen' => null,
                    'frameborder' => 0,
                    'sandbox' => 'allow-scripts allow-same-origin allow-presentation',
                    'layout' => 'responsive',
                ],
                'object' => [
                    'attributes' => [
                        'width' => 560,
                        'height' => 315,
                    ],
                    'params' => [
                        'movie' => '{protocol}://youtube.com/v/{1}?version=3&rel=0&wmode=transparent',
                        'wMode' => 'transparent',
                        'allowFullScreen' => 'true',
                        'allowscriptaccess' => 'always',
                    ],
                    // embed shares same attributes as object iteslf, but may have some of it's own attributes
                    'embed' => [
                        'src' => '{protocol}://youtube.com/v/{1}?version=3&rel=0&wmode=transparent',
                        'width' => 560,
                        'height' => 315,
                        'type' => 'application/x-shockwave-flash',
                        'allowFullScreen' => 'true',
                        'allowscriptaccess' => 'always',
                    ],
                ],
                'wrappedIframe' => [
                    'src' => '{protocol}://www.youtube.com/embed/{1}?rel=0&wmode=transparent',
                    'width' => '100%',
                    'height' => '100%',
                    'allowfullscreen' => null,
                    'frameborder' => 0,
                    'sandbox' => 'allow-scripts allow-same-origin allow-presentation',
                    'layout' => 'responsive',
                    'style' => 'position: absolute;',
                ],
                'wrapper' => [
                    'start' => '<div style="left: 0; width: 100%; height: 0; position: relative; padding-bottom: 56.5%;">',
                    'end' => '</div>'
                ],
            ],
            'data' => null,
            'dataCallback' => 'Cohensive\Embed\DataFetcher@fetchYoutube',
        ],

        'liveleak' => [
            'name' => 'LiveLeak',
            'type' => 'video',
            'website' => 'http://liveleak.com',
            'ssl' => false,
            'url' => '(https?://)?(?:www\.)?liveleak\.com/ll_embed\?f=([0-9a-z]+)',
            'info' => [
                'id' => '{1}',
                'url' => '{protocol}://liveleak.com/ll_embed?f={1}',
            ],
            'render' => [
                'sizeRatio' => 1.77,
                'iframe' => [
                    'src' => '{protocol}://liveleak.com/ll_embed?f={1}',
                    'width' => 640,
                    'height' => 360,
                    'sandbox' => 'allow-scripts allow-same-origin allow-presentation',
                    'layout' => 'responsive',
                ],
            ],
            'data' => null,
            'dataCallback' => null,
        ],

        'vimeo' => [
            'name' => 'Vimeo',
            'type' => 'video',
            'website' => 'http://vimeo.com',
            'ssl' => true,
            'url' => [
                '(https?://)?(?:www\.)?vimeo\.com/([0-9]+)',
                '(https?://)?(?:www\.)?vimeo\.com/m/([0-9]+)',
            ],
            'info' => [
                'id' => '{1}',
                'url' => '{protocol}://vimeo.com/{1}',
                'dataUrl' => '{protocol}://vimeo.com/api/v2/video/{1}.json',
            ],
            'render' => [
                'sizeRatio' => 1.77,
                'iframe' => [
                    'src' => '{protocol}://player.vimeo.com/video/{1}',
                    'width' => 500,
                    'height' => 281,
                    'allowfullscreen' => null,
                    'frameborder' => 0,
                    'sandbox' => 'allow-scripts allow-same-origin allow-presentation',
                    'layout' => 'responsive',
                ],
            ],
            'data' => null,
            'dataCallback' => 'Cohensive\Embed\DataFetcher@fetchVimeo',
        ],

        'dailymotion' => [
            'name' => 'Dailymotion',
            'type' => 'video',
            'website' => 'http://dailymotion.com',
            'ssl' => true,
            'url' => '(https?://)?(?:www\.)?dailymotion\.com/video/([^_]+)',
            'info' => [
                'id' => '{1}',
                'url' => '{protocol}://dailymotion.com/video/{1}',
            ],
            'render' => [
                'sizeRatio' => 1.77,
                'iframe' => [
                    'src' => '{protocol}://www.dailymotion.com/embed/video/{1}',
                    'width' => 520,
                    'height' => 420,
                    'sandbox' => 'allow-scripts allow-same-origin allow-presentation',
                    'layout' => 'responsive',
                ],
            ],
            'data' => null,
            'dataCallback' => null,
        ],

        'gametrailers' => [
            'name' => 'GameTrailers',
            'type' => 'video',
            'website' => 'http://gametrailers.com',
            'ssl' => false,
            'url' => '^(https?://)?media\.mtvnservices\.com/embed/([^"]+:)([0-9a-z-_]+)',
            'info' => [
                'id' => '{1}{2}',
                'url' => '{protocol}://media.mtvnservices.com/embed/{1}{2}',
            ],
            'render' => [
                'sizeRatio' => 1.77,
                'iframe' => [
                    'src' => '{protocol}://media.mtvnservices.com/embed/{1}{2}',
                    'width' => 560,
                    'height' => 315,
                    'sandbox' => 'allow-scripts allow-same-origin allow-presentation',
                    'layout' => 'responsive',
                ],
            ],
            'data' => null,
            'dataCallback' => null,
        ],

        'ign' => [
            'name' => 'IGN',
            'type' => 'video',
            'website' => 'http://ign.com',
            'ssl' => false,
            'url' => '^(https?://)?(?:www\.)?ign\.com/videos/([0-9a-zA-Z-_/]+)',
            'info' => [
                'id' => '{1}',
                'url' => '{protocol}://ign.com/videos/{1}',
            ],
            'render' => [
                'sizeRatio' => 1.77,
                'iframe' => [
                    'src' => '{protocol}://widgets.ign.com/video/embed/content.html?url={1}',
                    'width' => 560,
                    'height' => 315,
                    'sandbox' => 'allow-scripts allow-same-origin allow-presentation',
                    'layout' => 'responsive',
                ],
            ],
            'data' => null,
            'dataCallback' => null,
        ],

        'vine' => [
            'name' => 'Vine',
            'type' => 'video',
            'website' => 'http://vine.co',
            'ssl' => true,
            'url' => [
                '^(https?://)?(?:www\.)?vine\.co/v/([0-9a-zA-Z]+)',
            ],
            'info' => [
                'id' => '{1}',
                'url' => '{protocol}://vine.co/v/{1}',
            ],
            'render' => [
                'sizeRatio' => 0.864,
                'iframe' => [
                    'src' => '{protocol}://vine.co/v/{1}/embed/postcard',
                    'width' => 600,
                    'height' => 600,
                    'sandbox' => 'allow-scripts allow-same-origin allow-presentation',
                    'layout' => 'responsive',
                ],
            ],
            'data' => null,
            'dataCallback' => null,
        ],

        'coub' => [
            'name' => 'Coub',
            'type' => 'video',
            'website' => 'http://coub.com',
            'ssl' => true,
            'url' => '(https?://)?(?:www\.)?coub\.com/view/([^_]+)',
            'info' => [
                'id' => '{1}',
                'url' => '{protocol}://coub.com/view/{1}',
            ],
            'render' => [
                'sizeRatio' => 1.77,
                'iframe' => [
                    'src' => '{protocol}://coub.com/embed/{1}?muted=false&autostart=false&originalSize=false&hideTopBar=false',
                    'width' => 640,
                    'height' => 360,
                    'sandbox' => 'allow-scripts allow-same-origin allow-presentation',
                    'layout' => 'responsive',
                ],
            ],
            'data' => null,
            'dataCallback' => null,
        ],

        'kickstarter' => [
            'name' => 'Kickstarter',
            'type' => 'video',
            'website' => 'http://kickstarter.com',
            'ssl' => true,
            'url' => [
                '^(https?://)?(?:www\.)?kickstarter\.com/projects/([0-9a-zA-Z-_]+)/([0-9a-zA-Z-_]+)',
            ],
            'info' => [
                'id' => '{1}',
                'url' => '{protocol}://kickstarter.com/projects/{1}/{2}',
            ],
            'render' => [
                'sizeRatio' => 1.33,
                'iframe' => [
                    'src' => '{protocol}://kickstarter.com/projects/{1}/{2}/widget/video.html',
                    'scrolling' => 'no',
                    'width' => 640,
                    'height' => 480,
                    'sandbox' => 'allow-scripts allow-same-origin allow-presentation',
                    'layout' => 'responsive',
                ],
            ],
            'data' => null,
            'dataCallback' => null,
        ],

        'ustream' => [
            'name' => 'Ustream',
            'type' => 'video',
            'website' => 'http://ustream.tv',
            'ssl' => true,
            'url' => [
                '^(https?://)?(?:www\.)?ustream\.tv/channel/([0-9]+)',
            ],
            'info' => [
                'id' => '{1}',
                'url' => '{protocol}://ustream.tv/channel/{1}',
            ],
            'render' => [
                'sizeRatio' => 1.64,
                'iframe' => [
                    'src' => '{protocol}://ustream.tv/embed/{1}?v3&wmode=direct',
                    'scrolling' => 'no',
                    'width' => 670,
                    'height' => 390,
                    'sandbox' => 'allow-scripts allow-same-origin allow-presentation',
                    'layout' => 'responsive',
                ],
            ],
            'data' => null,
            'dataCallback' => null,
        ],

        'ustreamArchive' => [
            'name' => 'Ustream Recorded',
            'type' => 'video',
            'website' => 'http://ustream.tv',
            'ssl' => true,
            'url' => [
                '^(https?://)?(?:www\.)?ustream\.tv/recorded/([0-9]+)',
            ],
            'info' => [
                'id' => '{1}',
                'url' => '{protocol}://ustream.tv/recorded/{1}',
            ],
            'render' => [
                'sizeRatio' => 1.64,
                'iframe' => [
                    'src' => '{protocol}://ustream.tv/embed/recorded/{1}?v3&wmode=direct',
                    'scrolling' => 'no',
                    'width' => 670,
                    'height' => 390,
                    'sandbox' => 'allow-scripts allow-same-origin allow-presentation',
                    'layout' => 'responsive',
                ],
            ],
            'data' => null,
            'dataCallback' => null,
        ],

        'twitchClip' => [
            'name' => 'Twitch',
            'type' => 'video',
            'website' => 'https://clips.twitch.tv',
            'ssl' => true,
            'url' => [
                '^(https?://)?clips\.twitch\.tv/([0-9a-zA-Z-_]+)$',
                '^(https?://)?(?:www\.)?twitch\.tv/(?:[0-9a-zA-Z-_]+)/clip/([0-9a-zA-Z-_]+)',
            ],
            'info' => [
                'id' => '{1}',
                'url' => '{protocol}://clips.twitch.tv/{1}',
            ],
            'render' => [
                'sizeRatio' => 1.70,
                'iframe' => [
                    'src' => '{protocol}://clips.twitch.tv/embed?clip={1}&autoplay=false&tt_medium=clips_embed',
                    'width' => 420,
                    'height' => 237,
                    'scrolling' => 'no',
                    'allowfullscreen' => true,
                    'frameborder' => 0,
                    'sandbox' => 'allow-scripts allow-popups allow-same-origin allow-presentation',
                    'layout' => 'responsive',
                ],
            ],
            'data' => null,
            'dataCallback' => null,
        ],

        'twitchArchive' => [
            'name' => 'Twitch Archive',
            'type' => 'video',
            'website' => 'http://twitch.tv',
            'ssl' => true,
            'url' => [
                '^(https?://)?(?:www\.)?twitch\.tv/([^"]+)/b/([0-9]+)',
            ],
            'info' => [
                'id' => '{1}',
                'url' => '{protocol}://twitch.tv/{1}/b/{2}',
            ],
            'render' => [
                'sizeRatio' => 1.64,
                'object' => [
                    'attributes' => [
                        'type' => 'application/x-shockwave-flash',
                        'data' => '{protocol}://twitch.tv/widgets/archive_embed_player.swf',
                        'wmode' => 'transparent',
                        'id' => 'clip_embed_player_flash',
                        'width' => 500,
                        'height' => 350,
                    ],
                    'params' => [
                        'wMode' => 'transparent',
                        'allowFullScreen' => 'true',
                        'allowScriptAccess' => 'always',
                        'allowNetworking' => 'all',
                        'flashvars' => 'archive_id={2}&channel={1}&hostname=www.twitch.tv&auto_play=false&start_volume=25',
                        'movie' => '{protocol}://www.twitch.tv/widgets/archive_embed_player.swf',
                    ],
                ],
            ],
            'data' => null,
            'dataCallback' => null,
        ],

        'twitchArchiveChapter' => [
            'name' => 'Twitch Archive',
            'type' => 'video',
            'website' => 'http://twitch.tv',
            'ssl' => true,
            'url' => [
                '^(https?://)?(?:www\.)?twitch\.tv/([^"]+)/c/([0-9]+)',
            ],
            'info' => [
                'id' => '{1}',
                'url' => '{protocol}://twitch.tv/{1}/c/{2}',
            ],
            'render' => [
                'sizeRatio' => 1.64,
                'object' => [
                    'attributes' => [
                        'type' => 'application/x-shockwave-flash',
                        'data' => '{protocol}://twitch.tv/widgets/archive_embed_player.swf',
                        'wmode' => 'transparent',
                        'id' => 'clip_embed_player_flash',
                        'width' => 500,
                        'height' => 350,
                    ],
                    'params' => [
                        'wMode' => 'transparent',
                        'allowFullScreen' => 'true',
                        'allowScriptAccess' => 'always',
                        'allowNetworking' => 'all',
                        'flashvars' => 'chapter_id={2}&channel={1}&hostname=www.twitch.tv&auto_play=false&start_volume=25',
                        'movie' => '{protocol}://www.twitch.tv/widgets/archive_embed_player.swf',
                    ],
                ],
            ],
            'data' => null,
            'dataCallback' => null,
        ],

        'twitch' => [
            'name' => 'Twitch',
            'type' => 'video',
            'website' => 'http://twitch.tv',
            'ssl' => true,
            'url' => [
                '^(https?://)?(?:www\.)?twitch\.tv/([0-9a-zA-Z-_]+)',
            ],
            'info' => [
                'id' => '{1}',
                'url' => '{protocol}://twitch.tv/{1}',
            ],
            'render' => [
                'sizeRatio' => 1.64,
                'object' => [
                    'attributes' => [
                        'type' => 'application/x-shockwave-flash',
                        'data' => '{protocol}://twitch.tv/widgets/live_embed_player.swf?channel={1}',
                        'wmode' => 'transparent',
                        'id' => 'live_embed_player_flash',
                        'width' => 500,
                        'height' => 350,
                    ],
                    'params' => [
                        'allowFullScreen' => 'true',
                        'allowScriptAccess' => 'always',
                        'allowNetworking' => 'all',
                        'flashvars' => 'hostname=www.twitch.tv&channel={1}&auto_play=false&start_volume=25',
                        'movie' => '{protocol}://www.twitch.tv/widgets/live_embed_player.swf',
                    ],
                ],
            ],
            'data' => null,
            'dataCallback' => null,
        ],

        'html5video' => [
            'name' => 'HTML5 video',
            'type' => 'video',
            'website' => '',
            'ssl' => false,
            'url' => [
                '^(https?://)?(.*).(mp4|ogg|webm)$',
            ],
            'info' => [
                'id' => '{1}.{2}',
                'url' => '{1}.{2}',
            ],
            'render' => [
                'sizeRatio' => 1.77,
                'video' => [
                    'source' => [
                        [
                            'src' => '{protocol}://{1}.webm',
                            'type' => 'video/webm',
                        ],
                        [
                            'src' => '{protocol}://{1}.ogg',
                            'type' => 'video/ogg',
                        ],
                        [
                            'src' => '{protocol}://{1}.mp4',
                            'type' => 'video/mp4',
                        ],
                    ],
                    'width' => 560,
                    'height' => 315,
                    'controls' => 'controls',
                    'layout' => 'responsive',
                ],
            ],
            'data' => null,
            'dataCallback' => null,
        ],

        'gfycat' => [
            'name' => 'gfycat',
            'type' => 'video',
            'website' => 'http://gfycat.com',
            'ssl' => false,
            'url' => [
                '^(https?://)?(?:www\.)?gfycat\.com/([a-zA-Z]+)',
            ],
            'info' => [
                'id' => '{1}',
                'url' => '{protocol}://gfycat.com/{1}',
            ],
            'render' => [
                'sizeRatio' => 1.64,
                'iframe' => [
                    'src' => '{protocol}://gfycat.com/ifr/{1}',
                    'scrolling' => 'no',
                    'width' => 670,
                    'height' => 390,
                    'sandbox' => 'allow-scripts allow-same-origin allow-presentation',
                    'layout' => 'responsive',
                ],
            ],
            'data' => null,
            'dataCallback' => null,
        ],

        'web.tv' => [
            'name' => 'web.tv',
            'type' => 'video',
            'website' => 'http://web.tv',
            'ssl' => false,
            'url' => [
                '^(https?://)?(\w+\.?)?web\.tv/.*__([a-zA-Z0-9]+)$',
            ],
            'info' => [
                'id' => '{2}',
                'url' => '{protocol}://{1}web.tv/embed/{2}',
            ],
            'render' => [
                'sizeRatio' => 1.64,
                'iframe' => [
                    'src' => '{protocol}://{1}web.tv/embed/{2}',
                    'scrolling' => 'no',
                    'width' => 670,
                    'height' => 390,
                    'sandbox' => 'allow-scripts allow-same-origin allow-presentation',
                    'layout' => 'responsive',
                ],
            ],
            'data' => null,
            'dataCallback' => null,
        ],
    ],

];
