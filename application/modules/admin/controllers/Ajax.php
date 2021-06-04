<?php

use App\Handler\LectureRemoveHandler;

defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends APP_Controller
{

    public function __construct()
    {
        // Проверка Ajax запроса
        if (!$this->input->is_ajax_request())
            $this->jsonajax->error(Jsonajax::CODE_ACCESS_DENIED);

        // Проверка авторизации
        if ((int) ($this->Auth->userID() ?? 0) === 0)
            $this->jsonajax->error(Jsonajax::CODE_NOT_AUTHORIZED);
    }

    public function index()
    {
        // разбираем запрос
        $data = file_get_contents("php://input");
        if (empty($data) && !empty($_POST['query']))
            $data = $_POST['query'];

        $this->request = json_decode($data, true);
        $this->user = $this->Auth->user();

        try {
            switch (($this->request['method'] ?? '')) {
                case 'group.create':
                    $this->groupCreate();
                    break;
                case 'group.remove':
                    $this->groupRemove();
                    break;
                case 'group.user.add':
                    $this->groupUserAdd();
                    break;
                case 'group.user.move':
                    $this->groupUserMove();
                    break;

                case 'setGroupTeacher':
                    $this->setGroupTeacher();
                    break;
                case 'removeGroupUser':
                    $this->removeGroupUser();
                    break;
                case 'lecture.remove':
                    $this->lectureRemove();
                    break;
                case 'user.add':
                    $this->userAdd();
                    break;
                case 'user.edit':
                    $this->userEdit();
                    break;
                // case 'user.remove':
                // 	$this->userRemove();
                // break;
                case 'user.sendConfirmEmail':
                    $this->userSendConfirmEmail();
                    break;
                case 'user.block':
                    $this->userBlock();
                    break;
                case 'user.unblock':
                    $this->userUnBlock();
                    break;
                case 'wall.message':
                    $this->wallMessage();
                    break;
                case 'wall.message.list':
                    $this->wallMessageList();
                    break;
                case 'wall.message.child':
                    $this->wallMessageChild();
                    break;
                case 'addVideoWorkshop':
                    $this->addVideoWorkshop();
                    break;
                case 'updateVideoWorkshop':
                    $this->updateVideoWorkshop();
                    break;
                case 'deleteVideoWorkshop':
                    $this->deleteVideoWorkshop();
                    break;
                case 'addUserWorkshop':
                    $this->addUserWorkshop();
                    break;
                case 'removeSubscription':
                    $this->removeSubscription();
                    break;
                default:
                    throw new Exception('method not found', 1);
                    break;
            }
        } catch (Exception $e) {
            $this->jsonajax->error(Jsonajax::CODE_INTERNAL_ERROR, $e->getMessage());
        }

        $this->jsonajax->result(false);
    }

    // создание группы
    private function groupCreate()
    {
        $this->GroupsHelper->add(($this->request['params'] ?? []));
        $this->jsonajax->result('Успешно');
    }

    // удаление группы
    private function groupRemove()
    {
        $this->GroupsHelper->remove((int) ($this->request['params']['id'] ?? 0));
        $this->jsonajax->result('Успешно');
    }

    private function groupUserAdd()
    {
        $this->GroupsHelper->userAdd(($this->request['params'] ?? []));
        $this->jsonajax->result('Успешно');
    }

    private function groupUserMove()
    {
        $this->GroupsHelper->moveToGroup(
            (int) ($this->request['params']['user'] ?? 0),
            (int) ($this->request['params']['group'] ?? 0),
            (int) ($this->request['params']['new_group'] ?? 0)
        );
        $this->jsonajax->result('Успешно');
    }

    private function setGroupTeacher()
    {
        $teacherId = (int) ($this->request['params']['teacher'] ?? 0);
        $groupId = (int) ($this->request['params']['group'] ?? 0);
        $this->GroupsHelper->setTeacher($groupId, $teacherId);
        $this->jsonajax->result('Успешно');
    }

    private function removeGroupUser()
    {
        $userId = (int) ($this->request['params']['user'] ?? 0);
        $groupId = (int) ($this->request['params']['group'] ?? 0);
        $this->GroupsHelper->removeUser($groupId, $userId);
        $this->jsonajax->result('Успешно');
    }

    // удаление лекции
    private function lectureRemove()
    {
        (new LectureRemoveHandler())->handle((int) ($this->request['params'] ?? 0));
        $this->jsonajax->result('Успешно');
    }

    // создание пользователя
    private function userAdd()
    {
        $this->UsersHelper->add(($this->request['params'] ?? []));
        $this->jsonajax->result('Успешно');
    }

    // редактирование пользователя
    private function userEdit()
    {
        $params = $this->request['params'] ?? [];
        if ($img = $this->UsersHelper->prepareProfileImg('img'))
            $params['img'] = '/' . $img;

        $this->UsersHelper->edit(($this->request['params']['id'] ?? 0), $params);
        $this->jsonajax->result('Успешно');
    }

    // повторная отправка письма о регитрации
    private function userSendConfirmEmail()
    {
        $this->UsersHelper->sendConfirmEmail(($this->request['params'] ?? 0));
        $this->jsonajax->result('Успешно');
    }

    // удаление пользователя
    private function userRemove()
    {
        $this->UsersHelper->remove(($this->request['params'] ?? 0));
        $this->jsonajax->result('Успешно');
    }

    // заблокировать пользователя
    private function userBlock()
    {
        $this->UsersHelper->block(($this->request['params'] ?? 0));
        $this->jsonajax->result('Успешно');
    }

    // разблокировать пользователя
    private function userUnBlock()
    {
        $this->UsersHelper->unblock(($this->request['params'] ?? 0));
        $this->jsonajax->result('Успешно');
    }

    // добавить сообщение на стену 
    private function wallMessage()
    {
        $params = ($this->request['params'] ?? []);
        $params['user'] = $this->user['id'];

        if ($this->WallHelper->add($params) === false)
            throw new Exception($this->WallHelper->getLastError(), 1);

        $this->jsonajax->result(true);
    }

    // список сообщений стены
    private function wallMessageList()
    {
        $params = ($this->request['params'] ?? []);
        if (($res = $this->WallHelper->list($params)) === false)
            throw new Exception($this->WallHelper->getLastError(), 1);

        $this->jsonajax->result($res);
    }

    // список дочерних сообщений
    private function wallMessageChild()
    {
        $params = ($this->request['params'] ?? []);
        if (($res = $this->WallHelper->child($params)) === false)
            throw new Exception($this->WallHelper->getLastError(), 1);

        $this->jsonajax->result($res);
    }

    private function addVideoWorkshop()
    {
        $params = $this->request['params'];
        $params['video_code'] = $this->VideoModel->makeCode();
        $this->form_validation->reset_validation();
        $this->form_validation->set_data($params);
        if ($this->form_validation->run('video_workshop') === false)
            throw new Exception($this->form_validation->error_string(), 1);

        // if($video = $this->ydvideo->getVideo($params['code']))
        // {
        // 	$params['video_url'] = $video['video'];
        // 	$params['duration'] = floor($video['duration'] / 1000);
        // 	$params['ts'] = date(DATE_FORMAT_DB_FULL);
        // }

        $params['ts'] = date(DATE_FORMAT_DB_FULL);

        $this->VideoModel->add($params);
        $this->jsonajax->result('Успешно');
    }

    private function updateVideoWorkshop()
    {
        $params = $this->request['params'];
        $this->form_validation->reset_validation();
        $this->form_validation->set_rules('title', 'Название', 'trim|required|min_length[3]|max_length[255]');
        $this->form_validation->set_data($params);
        if ($this->form_validation->run() === false)
            throw new Exception($this->form_validation->error_string(), 1);

        $this->VideoModel->update($params['id'], $params);
        $this->jsonajax->result('Успешно');
    }

    private function deleteVideoWorkshop()
    {
        $params = $this->request['params'];
        if ($this->VideoModel->getItem($params['id'])) {
            $this->VideoModel->delete($params['id']);
            $this->jsonajax->result('Успешно');
        }

        $this->jsonajax->result('Элемент не найден');
    }

    private function addUserWorkshop()
    {
        $params = $this->request['params'];
        $this->SubscriptionHelper->subscribeWorkshop(($params['id'] ?? 0), ($params['user'] ?? 0));
        $this->jsonajax->result('Успешно');
    }

    private function removeSubscription()
    {
        $params = $this->request['params'];
        $this->SubscriptionModel->remove($params['id']);
        $this->jsonajax->result('Успешно');
    }

}
