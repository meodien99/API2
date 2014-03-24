<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 3/24/14
 * Time: 10:54 AM
 */

class databasePDO extends PDO{
    public $_prefix = "";

    public function __construct($type,$host,$dbname,$user,$pass){

        parent::__construct ( $type . ':host=' . $host . ';dbname=' . $dbname, $user, $pass );
    }

    /*
     * SELECT FUNCTION BY USING QUERY
     * @param string $sql a query string
     * @param array $where array where params of array
     * @param constant $fetchMode a PDO fetch mode
     * */
    public function select_query($sql,$where = array(),$fetchMode = PDO::FETCH_ASSOC){
        $sth = $this->prepare($sql);

        if(isset($where) && !empty($where)){
            foreach($where as $key => $value) {
                $sth->bindValue(":$key",$value);
            }
        }
        $sth->execute();

        return $sth->fetchAll($fetchMode);
    }

    /*
     * SELECT FUNCTION
     * @param string $table
     * @param array/string $select
     * @param array $where
     * @param array $order_by
     * @param int $limit
     * @param constant $fetchMode
     * */
    public function select($table,$select,$where =array(),$order_by = array(),$limit = null,$fetchMode = PDO::FETCH_ASSOC){
        $sql = null;
        $table = $this->_prefix.trim($table);

        if(is_array($select) && !empty($select)){
            ksort($select);
            $s = rtrim(implode(",",$select), "," );
        } else {
            $s = $select;
        }

        $sql .="SELECT $s from `$table` ";

        if(is_array($where) && count($where) ){
            ksort($where);
            $w = null;
            foreach($where as $key => $value){
                $w .= "`$key`=:$key AND ";
            }
            $w = rtrim($w,"AND ");
            $sql .= "WHERE $w ";
        }

        if(is_array($order_by) && count($order_by)){
            ksort($order_by);
            $o = null;
            foreach($order_by as $k => $v){
                $o .= $k." ".$v;
            }
            $sql .= " ORDER BY $o";
        }

        if(!empty($limit))
            $sql .=" LIMIT ".$limit;

        $sth = $this->prepare($sql);
        if(is_array($where) && count($where)){
            foreach($where as $k=>$v){
                $sth->bindValue(":$k",$v);
            }
        }

        $sth->execute();

        return $sth->fetchAll($fetchMode);
    }

    /*
     * INSERT FUNCTION
     * @param string $table
     * @param array @data
     * @param constant $fetchMode
     * */
    public function insert($table,$data,$fetchMode = PDO::FETCH_ASSOC){
        ksort($data);
        $table = $this->_prefix.trim($table);
        $fieldName = implode("`,`",array_keys($data));
        $fieldValue = ":".implode(", :",array_keys($data));

        $sql = "INSERT INTO `$table` (`$fieldName`) VALUES ($fieldValue)";
        $sth = $this->prepare($sql);

        $sth->setFetchMode($fetchMode);
        foreach($data as $k => $v){
            $sth->bindValue(":$k",$v);
        }

        $sth->execute();
        if($sth->rowCount() > 0) {
            return true;
        } else {
            throw new Exception('Insert Occurred !!');
            return false;
        }
    }

    /*
     * UPDATE FUNCTION
     * @param string $table
     * @param array $data
     * @param array $where
     * @param constant $fetchMode
     * */
    public function update($table,$data,$where,$fetchMode = PDO::FETCH_ASSOC){
        $table = $this->_prefix.trim($table);

        $sql = null;
        $fieldDetails = null;
        if(is_array($data) && !empty($data)){
            ksort($data);
            foreach($data as $key => $value){
                $fieldDetails .= "`$key`=:$key, ";
            }
            $fieldDetails = rtrim($fieldDetails,", ");
        }
        $sql .= "UPDATE `$table` SET $fieldDetails ";
        $w = null;

        if(is_array($where) && !empty($where)){
            ksort($where);
            foreach($where as $k => $v ){
                $w .= " `$k`=:$k AND ";
            }
            $w = rtrim($w,"AND ");
        }
        $sql .= "WHERE $w";

        $sth = $this->prepare($sql);
        $sth->setFetchMode($fetchMode);
        if(is_array($data) && !empty($data)){
            foreach($data as $key => $value){
                $sth->bindValue(":$key",$value);
            }
        }
        if(is_array($where) && !empty($where)){
            foreach($where as $k => $v ){
                $sth->bindValue(":$k",$v);
            }
        }
        $sth->execute();
        if($sth->rowCount() > 0) {
            return true;
        } else {
            throw new Exception('Updated Occurred !!');
            return false;
        }
    }

    /*
     * DELETE FUNCTION
     * @param string $table
     * @param array $where
     * */
    public function delete($table,$where){
        $table = $this->_prefix.$table;
        $sql = null;
        $w = null;
        if(is_array($where) && !empty($where)){
            ksort($where);
            foreach($where as $k => $v){
                $w .= " `$k`=:$k AND ";
            }
            $w = rtrim($w,"AND ");
        }

        $sql .= "DELETE from `$table` WHERE $w";

        $sth = $this->prepare($sql);
        foreach($where as $key => $value){
            $sth->bindValue(":$key",$value);
        }
        $sth->execute();
        if($sth->rowCount() > 0) {
            return true;
        } else {
            throw new Exception('Delete Occurred !!');
            return false;
        }
    }
}