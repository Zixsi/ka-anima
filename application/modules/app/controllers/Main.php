<?php

use App\Service\NewsService;

class Main extends APP_Controller
{

    /**
     * @return void
     */
    public function index()
    {
        $this->load->lview('main/index', [
            'news' => (new NewsService())->getListLatestByTimestamp()
        ]);
    }

}
