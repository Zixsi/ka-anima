<?php

use App\Service\GroupService;
use App\Service\LectureService;

class Groups extends APP_Controller
{

    public $user;

    public function __construct()
    {
        parent::__construct();
        if (!$this->Auth->isActive()) {
            header('Location: /');
        }

        $this->user = $this->Auth->user();
    }

    public function index()
    {
        if ($this->Auth->isTeacher()) {
            $this->indexTeacher();
        } else {
            $this->indexUser();
        }
    }

    // список групп для преподователя
    private function indexTeacher()
    {
        $data = [];
        $filter = [
            'with_subscribed' => true, // с подписанными пользователями
        ];
        $data['items'] = $this->GroupsModel->getTeacherGroups($this->user['id'], false, $filter);
        $withoutReviewGroup = $this->LecturesHomeworkModel->getGroupsWithoutReviewHomework();

        foreach ($data['items'] as &$row) {
            $row['review_mark'] = in_array($row['id'], $withoutReviewGroup);
        }

        $this->GroupsHelper->prepareListForTeacher($data['items'], $filter);

        $this->load->lview('groups/index_teacher', $data);
    }

    // список групп пользователя
    private function indexUser()
    {
        header('Location: /subscription/');
        // $data = [];
        // $data['items'] = $this->SubscriptionModel->groupsList($this->user['id']);
        // $this->load->lview('groups/index_user', $data);
    }

    public function item($code, $lecture = 0)
    {
        if (($item = $this->GroupsModel->getByCode($code)) === false) {
            show_404();
        }

        if ($this->Auth->isTeacher()) {
            $this->itemTeacher($item);
        } else {
            $this->itemUser($item, $lecture);
        }
    }

    private function itemTeacher($item)
    {
        $data = [];
        $data['item'] = $item;
        $data['pageTitle'] = $data['item']['name'];
        $params = $this->input->get(null, true);

        // ученики группы
        $data['users'] = $this->SubscriptionModel->getGroupUsers($data['item']['id'], $data['item']['type']);
        $this->GroupsHelper->setUsersHomeworkStatus($data['item']['id'], $data['users']);

        // лекции группы
        $lectures = (new GroupService())->getListPublicLecturesForGroup((int) $data['item']['id']);

        $data['item']['current_week'] = $this->currentGroupWeek($lectures);

        // дз ученика
        $data['user'] = null;
        $data['homeworks'] = [];
        $data['total_not_verified_works'] = 0;
        $users_without_hw_review = $this->LecturesHomeworkModel->getUsersIdWithoutReviewHomework($data['item']['id']);

        foreach ($data['users'] as &$row) {
            $row['mark'] = null;
            $data['total_not_verified_works'] += $row['homeworks'] - $row['reviews'];
            if (in_array($row['id'], $users_without_hw_review)) {
                $row['mark'] = 'warning';
            }
        }

        if (isset($params['user']) && array_key_exists($params['user'], $data['users'])) {
            $data['user'] = $data['users'][$params['user']];

            // список файлов пользователя
            $user_homeworks = $this->LecturesHomeworkModel->userFilesForGroup($data['user']['id'], $data['item']['id']);
            // список ревью для пользователя
            $user_reviews = $this->ReviewModel->getByGroup($data['item']['id'], ['user' => $data['user']['id']]);

            $data['homeworks'] = $this->GroupsHelper->buildHomeworkInfo($lectures, $user_homeworks, $user_reviews);
            unset($lectures, $user_homeworks, $user_reviews);

            $this->listenUserActions($data['item'], $params, $data);
        }

        $data['wall'] = $this->WallModel->list($data['item']['id']);

        $this->load->lview('groups/item_teacher', $data);
    }

    // главная страница группы для ученика
    private function itemUser($item, $lecture = 0)
    {
        $data = [];
        $data['error'] = null;
        $data['section'] = 'index';
        $data['group'] = $item;
        $data['pageTitle'] = $data['group']['name'];
        $groupId = $data['group']['id'];

        $data['subscr'] = $this->checkSubscr($groupId);
        $data['subscr_is_active'] = (($data['subscr']['active'] ?? false)) ? true : false;

        $data['lectures'] = (new GroupService())->getListPublicLecturesForGroup((int) $groupId);
        $data['lecture_id'] = ((int) $lecture === 0) ? current($data['lectures'])['id'] : $lecture;
        $last_active_lecture = $this->prepareLectures($data['lectures'], $data['subscr']);
        $data['lectures_is_active'] = ($last_active_lecture == 0) ? false : true;

        if ($data['lectures_is_active'] && $data['subscr_is_active']) {
            if (empty($data['lectures'][$data['lecture_id']]) or $data['lectures'][$data['lecture_id']]['active'] == 0) {
                header('Location: /groups/' . $data['group']['code'] . '/lecture/' . $last_active_lecture);
            }

            $lecture = (new LectureService())->getById($data['lecture_id']);
            $data['lecture'] = $lecture->toViewArray();
            $data['lecture']['ts'] = $data['lectures'][$data['lecture_id']]['ts'];
            $data['lecture']['can_upload_files'] = true;

            $data['lecture']['video_code'] = '';
            if ($res = $this->VideoModel->bySource($data['lecture']['id'])) {
                $data['lecture']['iframe_url'] = getVideoIframeUrl($res);
                $data['lecture']['video_code'] = $res['video_code'];
            }

            //

            if (cr_valid_key()) {
                $this->uploadHomeWork($data);
            }

            $data['csrf'] = cr_get_key();
            $data['lecture_homework'] = $this->LecturesHomeworkModel->getListForUsers($groupId, $data['lecture_id']);
            $data['lecture']['files'] = $this->FilesModel->listLinkFiles($data['lecture_id'], 'lecture');
        }

        $this->setHomeworkStatus($groupId, $this->user['id'], $data['lectures']);
        $this->notifications->changeTragetTypeStatus('subscription', $data['subscr']['id']);

        // debug($data);

        $this->load->lview('groups/item_user', $data);
    }

    public function group($group = '')
    {
        $data = [];
        $data['error'] = null;
        $data['section'] = 'group';
        $data['group'] = $this->CoursesGroupsModel->getByCode($group);
        $groupId = (int) ($data['group']['id']);

        if (($data['subscr'] = $this->checkSubscr($groupId)) === false || ($data['subscr']['active'] ?? false) === false) {
            header('Location: /groups/' . $data['group']['code'] . '/');
        }

        $data['pageTitle'] = $data['group']['name'];

        $data['lectures'] = (new GroupService())->getListPublicLecturesForGroup($groupId);
        $this->prepareLectures($data['lectures'], $data['subscr']);
        $data['group']['current_week'] = $this->currentGroupWeek($data['lectures']);
        $data['teacher'] = $this->UserModel->getById($data['group']['teacher']);
        $data['users'] = $this->SubscriptionModel->getGroupUsers($groupId);
        $data['images'] = $this->GroupsModel->getImageFiles($groupId);
        $data['videos'] = $this->GroupsModel->getVideoFiles($groupId);

        $data['wall'] = $this->WallModel->list($groupId);
        // debug($data['wall']); die();
        // debug($data); die();
        $this->load->lview('groups/group', $data);
    }

    public function review($group = '', $review = 0)
    {
        $data = [];
        $data['section'] = 'review';
        $data['group'] = $this->CoursesGroupsModel->getByCode($group);
        $groupId = (int) ($data['group']['id']);

        if (($data['subscr'] = $this->checkSubscr($groupId)) === false || ($data['subscr']['active'] ?? false) === false) {
            header('Location: /groups/' . $data['group']['code'] . '/');
        }

        if (($data['subscr']['type'] ?? '') === 'standart') {
            header('Location: /groups/' . $data['group']['code'] . '/');
        }

        $data['pageTitle'] = $data['group']['name'];
        $data['review_item'] = false;
        if ($review > 0) {
            $review_item = $this->ReviewModel->getByID($review);
            // Если юзер не просматривал новое
            if (intval($review_item['item_is_viewed'] ?? 1) === 0 && intval($review_item['user'] ?? 1) === (int) $this->user['id']) {
                $this->ReviewModel->setViewedStatus($review_item['id'], true);
            }

            if ($user = $this->UserModel->getByID($review_item['user'])) {
                $review_item['user_name'] = $user['full_name'];
            }

            $data['review_item'] = $review_item;
            $data['review_item']['iframe_url'] = getVideoIframeUrl(['code' => $review_item['video_url'], 'video_code' => $review_item['video_code']]);
        }

        $filter = $this->input->get('filter', true);
        $data['filter_url'] = http_build_query(['filter' => $filter]);
        $data['items'] = $this->ReviewModel->getByGroup($groupId, $filter);
        $data['lectures'] = [];
        $lectures = (new GroupService())->getListPublicLecturesForGroup($groupId);

        foreach ($lectures as $key => $row) {
            $row['index'] = $key;
            $data['lectures'][$row['id']] = $row;
        }

        $data['users'] = $this->SubscriptionModel->getGroupUsers($groupId);
        $data['not_viewed'] = $this->ReviewModel->notViewedItems((int) $this->user['id'], $groupId);

        // debug($data['review_item']); die();
        $this->load->lview('groups/reviews', $data);
    }

    public function stream($group = '', $item = 0)
    {
        $data = [];
        $data['section'] = 'stream';
        $data['group'] = $this->CoursesGroupsModel->getByCode($group);
        $group_id = (int) ($data['group']['id']);

        if (($data['subscr'] = $this->checkSubscr($group_id)) === false || ($data['subscr']['active'] ?? false) === false) {
            header('Location: /groups/' . $data['group']['code'] . '/');
        }

        $data['group'] = $this->CoursesGroupsModel->getByID($group_id);
        if (($data['subscr']['type'] ?? '') === 'standart') {
            header('Location: /groups/' . $data['group']['code'] . '/');
        }

        $data['pageTitle'] = $data['group']['name'];
        $data['list'] = $this->StreamsModel->byGroupList($group_id);
        $data['item'] = false;
        $streams_id = $this->getStreamsIds($data['list']);

        if (count($streams_id)) {
            if ($item > 0) {
                $data['item'] = $this->StreamsModel->getByID($item);
            } else {
                $data['item'] = $this->StreamsModel->byGroup($group_id);
            }

            if (empty($data['item'])) {
                $data['item'] = current($data['list']);
            }

            if (!in_array($data['item']['id'], $streams_id)) {
                header('Location: /groups/' . $data['group']['code'] . '/stream/');
            }

            $this->load->library(['youtube']);
            $data['item']['video_code'] = $this->youtube->extractVideoId($data['item']['url']);
            $data['item']['started'] = boolval(time() >= strtotime($data['item']['ts']));

            $data['item']['chat'] = $this->youtube->getLiveChatUrl($data['item']['video_code']);
        }

        $this->load->lview('groups/stream', $data);
    }

    // проверка подписки
    private function checkSubscr($id)
    {
        return $this->SubscriptionModel->get($this->user['id'], $id, 'course');
    }

    // подготовка лекций
    private function prepareLectures(&$data, $subscr = null)
    {
        $last_active_id = 0;
        $subscrTsEnd = null;
        $isStandart = false;
        if (isset($subscr['id']) && (int) $subscr['id'] > 0) {
            $isStandart = ($subscr['type'] === 'standart');
            $subscrTsEnd = strtotime($subscr['ts_end']);
        }


        if ($data) {
            $tmp_data = $data;
            $data = [];

            foreach ($tmp_data as $val) {
                if ($isStandart && strtotime($val['ts']) <= $subscrTsEnd) {
                    $val['active'] = 1;
                }

                $data[$val['id']] = $val;
                if ($val['active'] == 1) {
                    $last_active_id = $val['id'];
                }
            }

            unset($tmp_data);
        }

        return $last_active_id;
    }

    private function currentGroupWeek($list)
    {
        $cnt = 0;
        if ($list && is_array($list)) {
            foreach ($list as $val) {
                $cnt += ($val['active'] == 1 && (int) $val['type'] === 0) ? 1 : 0;
            }
        }

        return $cnt;
    }

    private function setHomeworkStatus($group_id, $user_id, &$data)
    {
        if (($list = $this->LecturesHomeworkModel->listLecturesIdWithHomework($group_id, $user_id)) !== false) {
            if ($data && is_array($data)) {
                foreach ($data as &$val) {
                    $val['homework_fail'] = false;
                    if ($val['active'] == 1 && $val['type'] == 0 && !in_array($val['id'], $list)) {
                        $val['homework_fail'] = true;
                    }
                }
            }
        }
    }

    private function uploadHomeWork(&$data)
    {
        $comment = $this->input->post('text', true);
        $this->load->config('upload');
        $upload_config = $this->config->item('upload_homework');
        $this->load->library('upload', $upload_config);

        if ($this->upload->do_upload('file') == false) {
            $data['error'] = $this->upload->display_errors();
        } else {
            $file_data = $this->upload->data();
            if ($file_id = $this->FilesModel->saveFileArray($file_data)) {
                if ($this->LecturesHomeworkModel->add($data['group']['id'], $data['lecture_id'], $this->user['id'], $file_id, $comment)) {
                    $this->FilesModel->createThumb($file_data);

                    $actionParams = [
                        'group_code' => $data['group']['code'],
                        'lecture_name' => $data['lecture']['name'],
                        'file_name' => $file_data['orig_name']
                    ];

                    Action::send(Action::HOMEWORK_UPLOAD, [$actionParams]);
                }
            }

            $data['error'] = $this->FilesModel->getLastError();
        }
    }

    private function getStreamsIds($data = [])
    {
        $result = [];
        if (is_array($data)) {
            foreach ($data as $val) {
                $result[] = $val['id'];
            }
        }

        return $result;
    }

    private function listenUserActions($group, $params, $data = [])
    {
        if (!isset($params['action']) || empty($params['action'])) {
            return;
        }

        switch ($params['action']) {
            case 'download':
                if (isset($params['target']) && $params['target'] == 'homework') {
                    $lecture = (new LectureService())->getById((int) $params['lecture']);

                    $actionParams = [
                        'group_code' => $group['code'],
                        'lecture_name' => $lecture->name,
                        'user_id' => $data['user']['id'],
                        'user_name' => $data['user']['full_name']
                    ];

                    Action::send(Action::HOMEWORK_DOWNLOAD, [$actionParams]);

                    $this->HomeworkHelper->download($group['id'], $params['lecture'], $params['user']);
                }
                break;
            default:
                // empty
                break;
        }
    }

}
