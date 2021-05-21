<?php

use App\Form\NewsForm;
use App\Service\NewsService;

class News extends APP_Controller
{
    /**
     * @var NewsService
     */
    private $newsService;
    
    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->newsService = new NewsService();
    }
    
    /**
     * @return void
     */
    public function index()
    {
        $this->load->lview('news/index', [
            'items' => $this->newsService->getListForAdmin()
        ]);
    }

    /**
     * @return void
     */
    public function add()
    {
        $item = new \App\Entity\News();
        $error = null;
        
        if (cr_valid_key()) {
            $form = (new NewsForm($item))->setRequestParams($this->input->post(null, true));
            
            if ($form->handle()) {
                $this->newsService->save($item);
                redirect(sprintf('/admin/news/edit/%d/', $item->id));
            } else {
                $error = $form->getError();
            }
        }

        $this->load->lview('news/add', [
            'item' => $item->toDbArray(),
            'error' => $error,
            'csrf' => cr_get_key()
        ]);
    }

    /**
     * 
     * @param int $id
     * @return void
     */
    public function edit($id = 0)
    {
        $item = $this->newsService->getById((int) $id);
        
        if ($item === false) {
            show_404();
        }
        
        $error = null;
        
        if (cr_valid_key()) {
            $form = (new NewsForm($item))->setRequestParams($this->input->post(null, true));
            
            if ($form->handle()) {
                $this->newsService->save($item);
            } else {
                $error = $form->getError();
            }
        }

        $this->load->lview('news/edit', [
            'error' => $error,
            'item' => $item->toDbArray(),
            'csrf' => cr_get_key()
        ]);
    }

    /**
     * @param int $id
     * @return void
     */
    public function delete($id = 0)
    {
        $item = $this->newsService->getById((int) $id);
        
        if ($item) {
            $this->newsService->delete((int) $id);
        }
        
        redirect('/admin/news/');
    }
}
