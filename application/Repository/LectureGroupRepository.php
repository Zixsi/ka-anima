<?php

namespace App\Repository;

use App\core\AbstractRepository;

class LectureGroupRepository extends AbstractRepository
{
    /**
     * @var string
     */
    protected $table = 'lectures_groups';
    
    /**
     * @var array
     */
    protected $primaryKey = ['group_id', 'lecture_id'];
    
    /**
     * @param array $data
     * @return bool
     */
    public function add($data)
    {
        $binds = $this->arrayToBinds($data);
        $values = array_keys($binds);
        $keys = $this->bindsToKeys($values);

        $sql = sprintf(
            'INSERT INTO %s (`%s`) VALUES (%s)',
            $this->table,
            implode('`,`', $keys),
            implode(',', $values)
        );
        
        return ($this->getConnection()->executeStatement($sql, $binds)) ? true : false;
    }

    /**
     * @param mixed $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        return true;
    }
    
    /**
     * @param int $id
     * @return void
     */
    public function removeByLecture(int $id)
    {
        $this->getConnection()->executeStatement(
            sprintf('DELETE FROM %s WHERE lecture_id = :lecture_id', $this->table), 
            [':lecture_id' => $id]
        );
    }
    
    /**
     * @param int $id
     * @return void
     */
    public function removeByGroup($id)
    {
        $this->getConnection()->executeStatement(
            sprintf('DELETE FROM %s WHERE group_id = :group_id', $this->table), 
            [':group_id' => $id]
        );
    }
    
    /**
     * @param int $id
     * @return array
     */
    public function getListPublicLecturesForGroup($id)
    {
        $sql = sprintf(
            'SELECT 
                l.id, 
                l.name, 
                IF(lg.ts < NOW(), 1, 0) as active, 
                lg.ts, 
                l.type 
            FROM 
                %s as cg 
            LEFT JOIN 
                %s as lg ON(lg.group_id = cg.id) 
            LEFT JOIN 
                %s as l ON(l.id = lg.lecture_id) 
            WHERE 
                cg.id = :id
            ORDER BY
                l.type DESC, 
                l.sort ASC, 
                l.id ASC',
            'courses_groups',
            $this->table,
            'lectures'
        );
        
        $rows = $this->getConnection()->fetchAllAssociative($sql, [':id' => $id]);
        
        foreach ($rows as &$row) {
            if ((int) $row['type'] === 1) {
                $row['active'] = 1;
            }
        }
        
        return $rows;
    }
    
    /**
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
                lg.group_id = :group 
                AND l.type = 0 
            ORDER BY 
                lg.ts ASC';
        
        $rows = $this->getConnection()->fetchAllAssociative($sql, [':group' => $group]);

        if ($rows) {
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
}
