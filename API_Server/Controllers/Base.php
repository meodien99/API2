<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 3/24/14
 * Time: 9:44 AM
 */
include_once(LIBS_PATH."/databasePDO.class.php");
include_once(LIBS_PATH."/Array2Xml.class.php");
class Base {

    public $params;
    public $db = null;

    public function __construct($params){
        $this->db = $this->getDbo();
        $this->params = $params;
    }

    public function getDbo(){
        static $dbObject = null;
        if(null === $dbObject){
            $config = include(CONFIG_PATH."/database.php");
            $dbObject = new databasePDO($config['type'],$config['host'],$config['dbname'],$config['user'],$config['pass']);
        }

        return $dbObject;
    }

    public function readAction(){
        $type = $this->params['type'];
        $table = isset($this->params['tname']) ? trim($this->params['tname']) : "";

        $this->_cache($table,$type);
        $x = $this->_readFromFile($table,$type);
        return $x;
    }

    private function _cache($table,$type){
        $file_name = md5($table.$type);
        $dir = CACHE_PATH."/".$file_name.".txt";
        $contents = null;
        clearstatcache();
        if(file_exists($dir)){
            //if file cached before 30s
            if(filemtime($dir) < time() - 30){
                $this->_writeToFile($table,$type);
            }
        } else {
            $this->_writeToFile($table,$type);
        }
    }

    private function _writeToFile($table,$type){
        $contents = null;
        $file_name = md5($table.$type);
        $dir = CACHE_PATH."/".$file_name.".txt";
        $data = $this->db->select($table,"*");
        switch($type){
            case "json":
                $contents = json_encode($data);
                file_put_contents($dir,$contents);
                chmod($dir,0775);
                break;
            case "xml" :
                $contents = new Array2Xml($table);
                $contents->createNode($data);
                file_put_contents($dir,$contents);
                chmod($dir,0775);
                break;
            case "csv" : $contents = Array2Xml::array2csv($data);
                file_put_contents($dir,$contents);
                chmod($dir,0775);
                break;
        }
    }

    private function _readFromFile($table,$type){
        $file_name = md5($table.$type);
        $dir = CACHE_PATH."/".$file_name.".txt";
        $contents = file_get_contents($dir,FILE_USE_INCLUDE_PATH);

        return $contents;
    }

}