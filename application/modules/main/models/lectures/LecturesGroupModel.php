<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class LecturesGroupModel extends APP_Model
{

    const TABLE = 'lectures';
    const TABLE_LECTURES_GROUPS = 'lectures_groups';
    const TABLE_COURSES_GROUPS = 'courses_groups';
    const TABLE_LECTURES_HOMEWORK = 'lectures_homework';
    const TABLE_REVIEW = 'review';

    public function __construct()
    {
        parent::__construct();
    }

    // Список всех лекций группы
    public function listForGroup($id)
    {
        $sql = 'SELECT 
					l.id, l.name, IF(lg.ts < ?, 1, 0) as active, lg.ts, l.type 
				FROM 
					' . self::TABLE_COURSES_GROUPS . ' as cg 
				LEFT JOIN 
					' . self::TABLE_LECTURES_GROUPS . ' as lg ON(lg.group_id = cg.id) 
				LEFT JOIN 
					' . self::TABLE . ' as l ON(l.id = lg.lecture_id) 
				WHERE 
					cg.id = ?
				ORDER BY
					l.type DESC, 
					l.sort ASC, 
					l.id ASC';

        $res = $this->db->query($sql, [date('Y-m-d H:i:s'), $id]);
        if ($res = $res->result_array()) {
            foreach ($res as &$val) {
                if ((int) $val['type'] === 1)
                    $val['active'] = 1;
            }

            return $res;
        }

        return [];
    }

    public function removeByLecture($id)
    {
        $this->db->where('lecture_id', $id);
        $this->db->delete(self::TABLE_LECTURES_GROUPS);

        return true;
    }

    public function removeByGroup($id)
    {
        $this->db->where('group_id', $id);
        $this->db->delete(self::TABLE_LECTURES_GROUPS);

        return true;
    }

    // список лекций группы для пользователя, со статусом ДЗ 
    public function listForUser($id, $user)
    {
        $bind = [$user, $user, $id];
        $sql = 'SELECT 
					lg.*, lh.id as homework, lr.id as review 
				FROM 
					' . self::TABLE_LECTURES_GROUPS . ' as lg 
				LEFT JOIN 
					' . self::TABLE . ' as l ON(l.id = lg.lecture_id) 
				LEFT JOIN 
					(SELECT MAX(id) as id, group_id, lecture_id, user FROM ' . self::TABLE_LECTURES_HOMEWORK . ' GROUP BY group_id, lecture_id, user) as lh ON(lh.group_id = lg.group_id AND lh.lecture_id = lg.lecture_id AND lh.user = ?) 
				LEFT JOIN 
					(SELECT MAX(id) as id, group_id, lecture_id, user FROM ' . self::TABLE_REVIEW . ' GROUP BY group_id, lecture_id, user) as lr ON(lr.group_id = lg.group_id AND lr.lecture_id = lg.lecture_id AND lr.user = ?) 
				WHERE 
					lg.group_id = ? AND 
					l.type = 0 
				ORDER BY
					l.sort ASC, lg.lecture_id ASC';

        if ($res = $this->db->query($sql, $bind)) {
            if ($res = $res->result_array()) {
                foreach ($res as $val) {
                    $val['homework'] = ((int) $val['homework'] > 0) ? true : false;
                    $val['review'] = ((int) $val['review'] > 0) ? true : false;
                }

                return $res;
            }
        }

        return [];
    }

    /**
     * 
     * @param int $group
     * @return array
     */
    public function getGroupMonthMap($group)
    {
        $sql = 'SELECT 
                cg.ts as ts_start, 
                lg.ts as ts_end
            FROM 
                lectures_groups as lg  
            LEFT JOIN 
                lectures as l ON(l.id = lg.lecture_id) 
            LEFT JOIN 
                courses_groups as cg ON(cg.id = lg.group_id) 
            WHERE 
                lg.group_id = ? 
                AND l.type = 0 
            ORDER BY 
                lg.ts ASC';

        if (($res = $this->db->query($sql, [$group]))) {
            $rows = $res->result_array();
            $chunks = array_chunk($rows, 4);
            $month = 1;
            $result = [];

            foreach ($chunks as $key => $chunk) {
                if ($key === 0) {
                    $firstItem = current($chunk);
                    $result[$month] = [
                        'number' => $month,
                        'start' => $firstItem['ts_start'],
                        'end' => $firstItem['ts_start']
                    ];
                    $month++;
                }

                $item = end($chunk);
                $result[$month] = [
                    'number' => $month,
                    'start' => $item['ts_start'],
                    'end' => $item['ts_end']
                ];
                $month++;
            }

            return $result;
        }

        return [];
    }

    /**
     * @param int $group
     * @param int $lecture
     * @param type $date
     * @return bool
     */
    public function addLectureToGroupTs($group, $lecture, $date)
    {
        $data = [
            'group_id' => (int) $group,
            'lecture_id' => (int) $lecture,
            'ts' => $date
        ];

        if ($this->db->insert(self::TABLE_LECTURES_GROUPS, $data)) {
            return $this->db->insert_id();
        }

        return false;
    }
}
