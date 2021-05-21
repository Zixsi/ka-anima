<?php

use App\Service\NewsService;
use App\Transformer\NewsTransformer;

class News extends APP_Controller
{

    /**
     * @param int $id
     * @return void
     */
    public function item($id = 0)
    {
        $item = (new NewsService())->getById((int) $id);
        
        if ($item === false) {
            show_404();
        }
        
        $this->load->lview('news/item', [
            'item' => (new NewsTransformer())->runItem($item->toDbArray())
        ]);
    }

}
