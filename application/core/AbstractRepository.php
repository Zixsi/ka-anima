<?php

namespace App\core;

use Doctrine\DBAL\Connection;
use function dbConnection;

class AbstractRepository
{
    /**
     * @var Connection
     */
    private $connection;
    
    /**
     * @var string
     */
    protected $table;
    
    /**
     * @var array
     */
    protected $primaryKey = ['id'];

    /**
     * @param Connection2 $connection
     */
    public function __construct()
    {
        $this->connection = dbConnection();
    }
    
    /**
     * @param array $data
     * @return bool
     */
    public function add($data)
    {
        $data = $this->removePrimaryKeyFromFields($data);
        $binds = $this->arrayToBinds($data);
        $values = array_keys($binds);
        $keys = $this->bindsToKeys($values);

        $sql = sprintf(
            'INSERT INTO %s (`%s`) VALUES (%s)',
            $this->table,
            implode('`,`', $keys),
            implode(',', $values)
        );
        
        return ($this->connection->executeStatement($sql, $binds)) ? true : false;
    }

    /**
     * @param mixed $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {   
        $data = $this->removePrimaryKeyFromFields($data);
        $binds = $this->arrayToBinds($data);
        $keys = array_keys($binds);
        $set = array_map(
            function($key) {
                return sprintf('`%s` = %s', substr($key, 1), $key);
            }, 
            $keys
        );

        $binds = array_merge($binds, $this->idToBinds($id));

        if (empty($set) === false) {
            $sql = sprintf(
                'UPDATE %s SET %s WHERE %s',
                $this->table,
                implode(' , ', $set),
                $this->primaryKeyToCondition()
            );
            $this->connection->executeStatement($sql, $binds);
        }

        return true;
    }
    
    /**
     * @param mixed $id
     * @return bool
     */
    public function delete($id)
    {
        $this->connection->executeStatement(
            sprintf(
                'DELETE FROM %s WHERE %s', 
                $this->table, 
                $this->primaryKeyToCondition()
            ), 
            $this->idToBinds($id)
        );

        return true;
    }

    /**
     * @param mixed $id
     * @return array | false
     */
    public function find($id)
    {
        $sql = sprintf(
            'SELECT * FROM %s WHERE %s',
            $this->table,
            $this->primaryKeyToCondition()
        );
        
        return $this->connection->fetchAssociative($sql, $this->idToBinds($id));
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $sql = sprintf('SELECT * FROM %s',$this->table);
        
        return $this->connection->fetchAllAssociative($sql);
    }
    
    /**
     * @return int
     */
    public function getLastInsertId()
    {
        return $this->connection->lastInsertId();
    }
    
    /**
     * @param array $data
     * @return array
     */
    public function arrayToBinds($data)
    {
        $result = [];
        
        foreach ($data as $key => $val) {
            if (is_array($val) || is_object($val)) {
                $val = json_encode($val);
            }

            $result[':' . $key] = $val;
        }

        return $result;
    }

    /**
     * @param array $data
     * @return array
     */
    public function bindsToKeys($data)
    {
        return array_map(
            function($val) {
                return substr($val, 1);
            }, 
            $data
        );
    }
    
    /**
     * @return Connection2
     */
    public function getConnection()
    {
        return $this->connection;
    }
    
    /**
     * @return array
     */
    protected function getKeyName()
    {
        return $this->primaryKey;
    }
    
    /**
     * @param array $fields
     * @return array
     */
    protected function removePrimaryKeyFromFields($fields)
    {
        foreach ($this->getKeyName() as $key) {
            if (isset($fields[$key])) {
                unset($fields[$key]);
            }
        }
        
        return $fields;
    }
    
    /**
     * @param mixed $value
     * @return array
     */
    protected function idToBinds($value)
    {
        $result = [];
        
        if (!is_array($value)) {
            $firstKey = current($this->getKeyName());
            $value = [$firstKey => $value];
        }
        
        foreach ($this->getKeyName() as $key) {
            $result[$key] = ($value[$key] ?? null);
        }
        
        return $this->arrayToBinds($result);
    }
    
    /**
     * @return string
     */
    protected function primaryKeyToCondition()
    {
        $condition = [];
        
        foreach ($this->getKeyName() as $key) {
            $condition[] = sprintf('%s = :%s', $key, $key);
        }
        
        return implode(' AND ', $condition);
    }
}
