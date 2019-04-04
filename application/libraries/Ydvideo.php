<?php

class Ydvideo
{
	const BASE_URL = 'https://yadi.sk/'; // https://yadi.sk/i/
	const UA = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:11.0) Gecko Firefox/11.0';
	const PLAYER = 'https://yastatic.net/yandex-video-player-iframe-api-bundles/1.0-519/index.html?post_message_config=false&volume=50&muted=false&auto_quality=false&report=false&preload=false&stream_url={VIDEO_URL}&preview={PREVIEW_URL}';

	private $curl = null;
	
	function __construct()
	{
		$this->curl = curl_init();
	}

	public function getVideo($url, $preview = false)
	{
		if($html = $this->getVideoContent($url))
		{
			if($video = $this->parseVideoFromHtml($html))
			{
				return $video;
			}
		}

		return false;
	}

	public function parseVideoFromHtml($html, $preview = false)
	{
		$search_start = 'id="store-prefetch">';
		$search_end = '</script>';

		$start = strpos($html, $search_start);
		$html = substr($html, ($start + strlen($search_start)));
		$end = strpos($html, $search_end);
		$json = substr($html, 0, $end);
		$json = json_decode($json, true);

		if(!isset($json['resources'][$json['rootResourceId']]['meta']['mediatype']) || $json['resources'][$json['rootResourceId']]['meta']['mediatype'] !== 'video')
		{
			return false;
		}

		$last = end($json['resources'][$json['rootResourceId']]['videoStreams']['videos']);
		$result = [
			'preview' => $json['resources'][$json['rootResourceId']]['meta']['xxxlPreview'],
			'video' => $last['url'],
			'player' => self::PLAYER
		];

		if($preview)
			$result['preview'] = $preview;

		$result['player'] = str_replace('{VIDEO_URL}', urlencode($result['video']), $result['player']);
		$result['player'] = str_replace('{PREVIEW_URL}', urlencode($result['preview']), $result['player']);

		return $result;
	}
	
	public function getVideoContent($url)
	{
		curl_setopt($this->curl, CURLOPT_URL, $url); 
		//curl_setopt($this->curl, CURLOPT_USERAGENT, self::UA);
		//curl_setopt($this->curl, CURLOPT_REFERER, self::BASE_URL);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($this->curl, CURLOPT_TIMEOUT, 3);
		$html = curl_exec($this->curl);
		$code =  intval(curl_getinfo($this->curl, CURLINFO_HTTP_CODE));

		if($code ===  200)
		{
			return $html;
		}

		return false;
	}
}