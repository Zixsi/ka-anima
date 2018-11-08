<?php

class Youtube
{
	private const BASE_URL = 'https://www.youtube.com/';
	private const INFO_URL = self::BASE_URL . "get_video_info?video_id=%s&el=embedded&ps=default&eurl=&hl=en_US";
	private const INFO_ALT = self::BASE_URL . "oembed?url=%s&format=json";
	private const UA = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:11.0) Gecko Firefox/11.0';

	private $curl = null;

	private $formats = [
		/*'242' => ['webm', 144, 11],
		'243' => ['webm', 240, 10],
		'244' => ['webm', 360, 9],
		'247' => ['webm', 480, 8],
		'248' => ['webm', 720, 7],
		'160' => ['mp4', 144, 6],
		'133' => ['mp4', 240, 5],
		'134' => ['mp4', 360, 4],
		'135' => ['mp4', 480, 3],
		'136' => ['mp4', 720, 2],
		'137' => ['mp4', 1080, 1]*/
		'22' => ['mp4', '720'],
		'18' => ['mp4', '480'],
		'43' => ['webm', '480']
	];
	
	function __construct()
	{
		$this->curl = curl_init();
	}

	public function extractVideoId($url)
	{
		preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);

		return $match[1] ?? '';
	}

	public function getVideo($code)
	{
		$info = $this->getInfo($code);
		return $this->getUrlMap($info);
	}

	public function getInfo($id)
	{
		$url = sprintf(self::INFO_URL, $id);
		return $this->curlGet($url);
	}

	public function prepareData($code)
	{
		$data = $this->getInfo($code);
		$data = urldecode($data);
		$i = 0;
		while(strpos($data, 'url=https%3A'))
		{
			$data = $this->str_replace_once('url=https%3A', '&video[url]['.$i.']=https%3A', $data);
			$i++;
		}

		parse_str($data, $data);

		return $data;
	}

	public function getVideoFromData($data)
	{
		$result = [];
		$video = $data['video']['url'] ?? [];
		unset($data);
		
		foreach($video as $item)
		{
			parse_str(parse_url($item, PHP_URL_QUERY), $item_arr);
			if(isset($this->formats[$item_arr['itag']]))
			{
				$meta = $this->formats[$item_arr['itag']];
				$item = ($pos = strpos($item, ','))?substr($item, 0, $pos):$item;
				$result[$meta[0]][$meta[1]] = $item;
			}
		}

		return $result;
	}

	public function getImgFromData($data)
	{
		if($data = ($data['player_response'] ?? null))
		{
			$data = json_decode($data, true);
			if($images = ($data['videoDetails']['thumbnail']['thumbnails'] ?? []))
			{
				$img = end($images);
				return $img['url'] ?? false;
			}
		}

		return false;
	}

	private function getUrlMap($data)
	{
		$result = [];
		$data = urldecode($data);
		$i = 0;
		while(strpos($data, 'url=https%3A'))
		{
			$data = $this->str_replace_once('url=https%3A', '&video[url]['.$i.']=https%3A', $data);
			$i++;
		}

		parse_str($data, $data);
		$video = isset($data['video']['url'])?$data['video']['url']:[];
		unset($data);

		foreach($video as $item)
		{
			parse_str(parse_url($item, PHP_URL_QUERY), $item_arr);
			if(isset($this->formats[$item_arr['itag']]))
			{
				$meta = $this->formats[$item_arr['itag']];
				$item = ($pos = strpos($item, ','))?substr($item, 0, $pos):$item;
				$result[$meta[0]][$meta[1]] = $item;
			}
		}

		return $result;
	}

	private function curlGet($url)
	{
		curl_setopt($this->curl, CURLOPT_URL, $url); 
		curl_setopt($this->curl, CURLOPT_USERAGENT, self::UA);
		curl_setopt($this->curl, CURLOPT_REFERER, self::BASE_URL);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);

		return curl_exec($this->curl);
	}

	private function curlHttpStatus($url)
	{
		curl_setopt($this->curl, CURLOPT_URL, $url); 
		curl_setopt($this->curl, CURLOPT_USERAGENT, self::UA);
		curl_setopt($this->curl, CURLOPT_REFERER, self::BASE_URL);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->curl, CURLOPT_NOBODY, 1);
		curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 5);
		$res = curl_exec($this->curl);
		
		return intval(curl_getinfo($this->curl, CURLINFO_HTTP_CODE));
	}

	private function str_replace_once($search, $replace, $text) 
	{
		$pos = strpos($text, $search); 
		return $pos!==false ? substr_replace($text, $replace, $pos, strlen($search)) : $text; 
	}
}