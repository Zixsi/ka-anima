<?php

namespace App\Form;

use App\Entity\News;
use App\Validator\NewsValidator;

class NewsForm
{
    /**
     * @var News
     */
    private $item;
    
    /**
     * @var string
     */
    private $error;
    
    /**
     * @var array
     */
    private $requestParams = [];


    /**
     * @param News $item
     */
    public function __construct(News $item)
    {
        $this->item = $item;
    }
    
    /**
     * @return bool
     */
    public function handle()
    {
        $this->item->fill($this->requestParams);
        $validator = new NewsValidator();
        
        if ($validator->run($this->item->toDbArray()) === false) {
            $this->error = $validator->getError();
            return false;
        }
        
        if ($this->handleUploadImg() === false) {
            return false;
        }
        
        return true;
    }
    
    /**
     * @return string
     */
    public function getError()
    {
        return (string) $this->error;
    }
    
    /**
     * @param array $params
     * @return this
     */
    public function setRequestParams(array $params = [])
    {
        $this->requestParams = $params;
        
        return $this;
    }
    
    /**
     * @return bool
     */
    private function handleUploadImg()
    {
        if(empty(($_FILES['img'] ?? null)) || (int) $_FILES['img']['size'] === 0) {
            return true;
        }
        
        try {
            $data = uploadFile('img', 'upload_news');
            $this->item->img = '/' . get_rel_path($data['full_path']);
            
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
        
        return false;
    }
}
