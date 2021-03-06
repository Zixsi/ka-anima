<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LecturesHomeworkModel extends APP_Model
{
    private const TABLE_LECTURES_HOMEWORK = 'lectures_homework';
    private const TABLE_USERS = 'users';
    private const TABLE_FILES = 'files';

    // type 0 - д/з, 1 - ревью

    public function __construct()
    {
        parent::__construct();
    }

    // добавить файл д/з к лекции
    public function add($group_id, $lecture_id, $user_id, $file_id, $comment = '')
    {
        $data = [
            'user' => $user_id,
            'group_id' => $group_id,
            'lecture_id' => $lecture_id,
            'file' => $file_id,
            'type' => 0, // д/з
            'comment' => $comment
        ];

        if ($this->db->insert(self::TABLE_LECTURES_HOMEWORK, $data)) {
            return $this->db->insert_id();
        }

        return false;
    }

    public function listUsersForLecture($group_id, $lecture_id)
    {
        $sql = 'SELECT 
					hw.user, CONCAT_WS(\' \', u.name, u.lastname) as full_name 
				FROM 
					'.self::TABLE_LECTURES_HOMEWORK.' as hw 
				LEFT JOIN 
					'.self::TABLE_USERS.' as u ON(u.id = hw.user)  
				WHERE 
					hw.group_id = ? AND hw.lecture_id = ? 
				GROUP BY 
					hw.user 
				ORDER BY 
					hw.user ASC';

        if ($res = $this->db->query($sql, [$group_id, $lecture_id])->result_array()) {
            return $res;
        }

        return false;
    }

    // список файлов пользователя определенной лекции
    public function getListUserFilesForLecture($group_id, $lecture_id, $user)
    {
        $bind = [$group_id, $lecture_id, $user];
        $sql = 'SELECT 
					f.full_path as src
				FROM 
					'.self::TABLE_LECTURES_HOMEWORK.' as hw 
				LEFT JOIN 
					'.self::TABLE_FILES.' as f  ON(f.id = hw.file) 
				WHERE 
					hw.group_id = ? AND hw.lecture_id = ? AND hw.user = ? AND hw.type = 0 
				ORDER BY 
					hw.id DESC';

        if ($res = $this->db->query($sql, $bind)) {
            if ($res = $res->result_array()) {
                $result = [];
                foreach ($res as $val) {
                    $result[] = $val['src'];
                }

                return $result;
            }
        }

        return [];
    }

    // Список загруженных юзерами файлов в лекции
    public function getListForUsers($group_id, $lecture_id)
    {
        $sql = 'SELECT 
					hw.*, f.orig_name as name, CONCAT_WS(\' \', u.name, u.lastname) as full_name
				FROM 
					'.self::TABLE_LECTURES_HOMEWORK.' as hw 
				LEFT JOIN 
					'.self::TABLE_FILES.' as f  ON(f.id = hw.file) 
				LEFT JOIN 
					'.self::TABLE_USERS.' as u ON(u.id = hw.user)  
				WHERE 
					hw.group_id = ? AND hw.lecture_id = ? AND hw.type = 0 
				ORDER BY 
					hw.id DESC';

        if ($res = $this->db->query($sql, [$group_id, $lecture_id])->result_array()) {
            return $res;
        }

        return false;
    }

    public function listLecturesIdWithHomework($group_id, $user)
    {
        $result = [];
        $sql = 'SELECT lecture_id FROM '.self::TABLE_LECTURES_HOMEWORK.' WHERE group_id = ? AND user = ? AND type = 0 GROUP BY lecture_id';
        if ($res = $this->db->query($sql, [$group_id, $user])->result_array()) {
            foreach ($res as $val) {
                $result[] = $val['lecture_id'];
            }
        }

        return $result;
    }

    // Список загруженных юзерами файлов в лекции для преподователя
    public function getListForTeaches($group_id, $lecture_id)
    {
        $sql = 'SELECT 
					hw.*, f.orig_name as name, CONCAT_WS(\' \', u.name, u.lastname) as full_name
				FROM 
					'.self::TABLE_LECTURES_HOMEWORK.' as hw 
				LEFT JOIN 
					'.self::TABLE_FILES.' as f ON(f.id = hw.file) 
				LEFT JOIN 
					'.self::TABLE_USERS.' as u ON(u.id = hw.user)  
				WHERE 
					hw.group_id = ? AND hw.lecture_id = ? 
				ORDER BY 
					hw.id DESC';

        if ($res = $this->db->query($sql, [$group_id, $lecture_id])->result_array()) {
            foreach ($res as &$val) {
                if ($val['type'] == 1) {
                    $val['name'] = 'Review '.date('Y-m-d', strtotime($val['ts']));
                }
            }

            return $res;
        }

        return false;
    }

    // добавить ревью к лекции
    public function addReview($group_id, $lecture_id, $user_id, $url)
    {
        $data = [
            'user' => $user_id,
            'group_id' => $group_id,
            'lecture_id' => $lecture_id,
            'video_url' => $url,
            'type' => 1 // ревью
        ];

        if ($this->db->insert(self::TABLE_LECTURES_HOMEWORK, $data)) {
            return $this->db->insert_id();
        }

        return false;
    }

    // список файлов пользователя в группе
    public function userFilesForGroup($user_id, $group_id)
    {
        $result = [];
        $sql = 'SELECT 
					hw.*, f.orig_name as name 
				FROM 
					'.self::TABLE_LECTURES_HOMEWORK.' as hw 
				LEFT JOIN 
					'.self::TABLE_FILES.' as f ON(f.id = hw.file) 
				WHERE 
					hw.user = ? AND hw.group_id = ? 
				ORDER BY 
					hw.id ASC';

        if ($res = $this->db->query($sql, [$user_id, $group_id])) {
            $result = $res->result_array();
        }

        return $result;
    }

    public function getUsersIdWithoutReviewHomework($group_id)
    {
        $result = [];
        $sql = 'SELECT 
        			DISTINCT lh.user 
        		FROM 
        			lectures_homework as lh 
        		LEFT JOIN 
        			review as r 
        		ON(
        			r.group_id = lh.group_id AND 
        			r.lecture_id = lh.lecture_id AND 
        			r.user = lh.user
        		) 
        		WHERE 
        			lh.group_id = ? AND 
        			lh.ts > r.ts';

        if ($res = $this->db->query($sql, [$group_id])) {
            $res = $res->result_array();
            foreach ($res as $row) {
                $result[] = $row['user'];
            }
        }

        return $result;
    }
    
    public function getGroupsWithoutReviewHomework()
    {
        $result = [];
        $sql = 'SELECT 
                    DISTINCT lh.group_id as id 
                FROM 
                    lectures_homework as lh 
                LEFT JOIN 
                    review as r 
                ON(
                    r.group_id = lh.group_id AND 
                    r.lecture_id = lh.lecture_id AND 
                    r.user = lh.user
                ) 
                WHERE 
                    lh.ts > r.ts
                    OR r.ts IS NULL';

        if ($res = $this->db->query($sql, [$group_id])) {
            $res = $res->result_array();
            
            foreach ($res as $row) {
                $result[] = $row['id'];
            }
        }

        return $result;
    }
}
