<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Workshop extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data = [];
		$userId = $this->Auth->getUserId();
		$subscriptions = $this->SubscriptionModel->getList($userId, 'workshop');
		$subscriptionsId = extractItemId($subscriptions, 'target');
		if(count($subscriptionsId))
			$subscriptionsId = array_map(function($val){return (int) $val;}, $subscriptionsId);

		$filter = [
			'ignore' => $subscriptionsId,
			'status' => true
		];
		$data['items'] = $this->WorkshopModel->getList($filter);
		$data['types'] = $this->WorkshopModel->getTypes();
		// debug($data); die();

		$this->load->lview('workshop/index', $data);
	}

	public function item($id = 0, $videoCode = null)
	{
		$data = [];
		if(($data['item'] = $this->WorkshopModel->getByField('code', $id)) === null)
			show_404();

		$data['pageTitle'] = $data['item']['title'];
		$this->load->library(['youtube']);
		$userId = $this->Auth->getUserId();

		$data['error'] = null;
		$data['item']['video_code'] = $this->youtube->extractVideoId($data['item']['video']);
		$data['item']['chat'] = $this->youtube->getLiveChatUrl($data['item']['video_code']);
		$data['videos'] = $this->VideoModel->getList(['source_id' => $data['item']['id'], 'source_type' => 'workshop']);
		$this->VideoHelper->prepareVideoList($data['videos']);
		$data['totalDuration'] = $this->VideoHelper->getTotalDuration($data['videos']);
		$data['currentVideo'] = $this->getCurrentVideo($data['videos'], $videoCode);
		$data['access'] = $this->SubscriptionModel->check($userId, $data['item']['id'], 'workshop');
		$data['isStarted'] = (time() >= strtotime($data['item']['date']));
		$data['showRightColl'] = (($data['item']['type'] === 'webinar' && $data['access'] && $data['isStarted']) || $data['item']['type'] === 'collection');
		$data['teacher'] = null;
		if($data['item']['teacher'])
		{
			$data['teacher'] = $this->UserModel->getByID($data['item']['teacher']);
			$this->UserModel->prepareUser($data['teacher']);
		}
		
		if(empty($videoCode) === false && empty($data['currentVideo']))
			show_404();

		if($data['currentVideo'] !== null)
			$data['currentVideo']['iframe_url'] = getVideoIframeUrl($data['currentVideo']);
		// debug($data['currentVideo']); die();
                
                
                $subscr = $this->SubscriptionModel->get($userId, $data['item']['id'], 'workshop');
                $this->notifications->changeTragetTypeStatus('subscription', (int) $subscr['id']);

		$this->load->lview('workshop/item', $data);
	}
	
	private function getCurrentVideo($videos, $code)
	{
		$result = null;

		if(is_array($videos) && empty($code) === false)
		{
			foreach($videos as $row)
			{
				if($row['video_code'] !== $code)
					continue;
				$result = $row;
			}
		}

		return $result;
	}
}