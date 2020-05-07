<?php
namespace Model\BaseModel;

class BaseModel {

    protected $db;

    protected $tableName;

    protected $pk;

    protected $db_config;

    protected $_prefix = true;

    public function __construct()
    {
        $this->db_config = Yaf\Registry::get("config");

        $db = DataBase::getInstance(
            $this->db_config->database->host,
            $this->db_config->database->user,
            $this->db_config->database->pwd,
            $this->db_config->database->name,
            $this->db_config->database->charset
        );

        $this->db = $db;

        $this->pk = 'id';

    }

    protected function all($sql)
    {
        $res = $this->db->query($sql);

        return $res;
    }

    protected function first($sql)
    {
        $res = $this->db->query($sql,'Row');

        return $res;
    }

    protected function find($id)
    {
        if($this->_prefix)
            $sql = 'SELECT * FROM '.$this->db_config->database->db_prefix.$this->tableName.' WHERE '.$this->pk.' = '.$id;
        else
            $sql = 'SELECT * FROM '.$this->tableName.' WHERE '.$this->pk.' = '.$id;

        $res = $this->db->query($sql,'Row');

        return $res;
    }

    protected function selectByFiled($filedArray)
    {

        $where = '';

        foreach ($filedArray as $key=>$value)
        {
            if (count($filedArray)>1)
            {
                $where =' WHERE '.$key.' = '.$value;
                if($key+1>2) $where .= ' AND '.$key.' = '.$value;
            }else{
                $where =' WHERE '.$key.' = '.$value;
            }

        }

        if($this->_prefix)
            $sql = 'SELECT * FROM '.$this->db_config->database->db_prefix.$this->tableName.$where;
        else
            $sql = 'SELECT * FROM '.$this->tableName.$where;

        $res = $this->db->query($sql,'Row');

        return $res;
    }

    protected function count($filed,$where)
    {
        if($this->_prefix)
            $res = $this->db->getCount($this->db_config->database->db_prefix.$this->tableName,$filed,$where);
        else
            $res = $this->db->getCount($this->tableName,$filed,$where);

        return $res;
    }

    protected function transaction($arraySql)
    {
        $this->db->execTransaction($arraySql);
    }

    protected function insert($data)
    {
        if($this->_prefix)
            $res = $this->db->insert($this->db_config->database->db_prefix.$this->tableName,$data);
        else
            $res = $this->db->insert($this->tableName,$data);

        return $res;
    }


    protected function update($data,$where)
    {
        if($this->_prefix)
            $res = $this->db->update($this->db_config->database->db_prefix.$this->tableName,$data,$where);
        else
            $res = $this->db->update($this->tableName,$data,$where);

        return $res;
    }


    protected function delete($where)
    {
        if($this->_prefix)
            $res = $this->db->delete($this->db_config->database->db_prefix.$this->tableName,$where);
        else
            $res = $this->db->delete($this->tableName,$where);

        return $res;
    }


}