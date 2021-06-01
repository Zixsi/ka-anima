<?php

namespace App\Form;

use App\core\AbstractForm;
use App\Entity\Lecture;
use App\Validator\LectureValidator;
use FilesModel;
use VideoModel;

class LectureForm extends AbstractForm
{
    /**
     * @var Lecture
     */
    private $item;
    
    /**
     * @param Lecture $item
     */
    public function __construct(Lecture $item)
    {
        $this->item = $item;
    }
    
    /**
     * @return bool
     */
    public function handle()
    {
        $this->item->fill($this->requestParams);
        $validator = new LectureValidator();
        
        if ($validator->run($this->item->toDbArray()) === false) {
            $this->error = $validator->getError();
            
            return false;
        }
        
        $filesModel = new FilesModel();
        
        if ($filesModel->filesUpload('files', $this->item->id, 'lecture', 'upload_lectures') === false) {
            $this->error = $filesModel->getLastError();
            
            return false;
        }
        
        (new VideoModel())->prepareAndSet($this->item->id, 'lecture', $this->item->video);
        
        if ($this->item->id > 0) {
            $filesModel->deleteLinkFile(($this->requestParams['del_files'] ?? []), $this->item->id, 'lecture');
        }
        
        return true;
    }
    
}
