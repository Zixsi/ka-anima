<?php

use App\Entity\Lecture;
use App\Form\LectureForm;
use App\Service\LectureService;

class Lectures extends APP_Controller
{
    /**
     * @var LectureService
     */
    private $lectureService;
    
    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->lectureService = new LectureService();
    }

    /**
     * @param int $course
     * @return void
     */
    public function index($course = 0)
    {
        if ((new CoursesModel())->getByID($course) == false) {
            show_404();
        }

        $this->load->lview('lectures/index', [
            'items' => $this->lectureService->getShortListByCourse((int) $course)
        ]);
    }

    /**
     * @param int $course
     * @return void
     */
    public function add($course = 0)
    {
        $item = new Lecture();
        $item->courseId = (int) $course;
        $form = new LectureForm($item);
        
        if (cr_valid_key()) {
            $form->setRequestParams($_POST);
            
            if ($form->handle()) {
                $this->lectureService->save($item);
                header(sprintf('Location: ../edit/%s/', $item->id));
            }
        }

        $this->load->lview('lectures/add', [
            'types' => \App\Enum\LectureType::getViewList(),
            'csrf' => cr_get_key(),
            'error' => $form->getError(),
            'item' => $item->toViewArray()
        ]);
    }

    /**
     * @param int $id
     * @param int $course
     * @return void
     */
    public function edit($id = 0, $course = 0)
    {
        $item = $this->lectureService->getById((int) $id);

        if ($item === false || (int) $course !== $item->courseId) {
            show_404();
        }
        
        $form = new LectureForm($item);

        if (cr_valid_key()) {
            $form->setRequestParams($_POST);
            
            if ($form->handle()) {
               $this->lectureService->save($item);

                SetFlashMessage('success', 'Success');
            }
        }

        $this->load->lview('lectures/edit', [
            'types' => \App\Enum\LectureType::getViewList(),
            'item' => $item->toViewArray(),
            'itemFiles' => (new FilesModel())->listLinkFiles($id, 'lecture'),
            'error' => $form->getError(),
            'csrf' => cr_get_key()
        ]);
    }
    
}
