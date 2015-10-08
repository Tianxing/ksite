<?php
class Db_Mysql {

    private $link = NULL;
    private $log = NULL;

    //构造函数
    public function __construct($item) {

        if(empty($item)) {
            return $item;
        }

        $dbConf = Conf::getConf('/db/mysql/'.$item);

        $this->log = Log::getInstance('mysql');
         
        $this->connect($dbConf["hostname"], $dbConf["username"], $dbConf["password"], $dbConf["database"], $dbConf["pconnect"]);
    }
     
    //数据库连接
    public function connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect = 0,$charset='utf8') {

        $this->link = @mysql_connect($dbhost, $dbuser, $dbpw, true);

        if(!$this->link){
            $this->log->fatal(mysql_error());
            return false;
        }

        if(!@mysql_select_db($dbname,$this->link)) {
            $this->log->fatal(mysql_error());
            return false;
        }

        @mysql_query("set names ".$charset);
    }
     
    //查询 
    public function query($sql) {

        if(!$this->link) {
            return false;
        }

        $query = mysql_query($sql,$this->link);
        if(!$query) {

            $this->log->warning(mysql_error() . " ALLSQL:" . $sql); 
            return false;
        }

        return $query;
    }
     
    //获取一条记录（MYSQL_ASSOC，MYSQL_NUM，MYSQL_BOTH）              
    public function getOne($sql,$result_type = MYSQL_ASSOC) {

        if(!$this->link) {
            return array();
        }

        $query = $this->query($sql);
        $result = mysql_fetch_array($query, $result_type);
        return $result;
    }
 
    //获取全部记录
    public function getAll($sql,$result_type = MYSQL_ASSOC) {


        if(!$this->link) {
            return array();
        }

        $query = $this->query($sql);
        $result = array();
        while($row = mysql_fetch_array($query, $result_type)) {
            $result[] = $row;
        }

        return $result;
    }
     
    //返回结果集
    public function fetchArray($query, $result_type = MYSQL_ASSOC){
        return mysql_fetch_array($query, $result_type);
    }
 
    //获取记录条数
    public function getNumRows($results) {
        return mysql_num_rows($results);
    }
 
    //获取最后插入的id
    public function getInsertId() {

        if(!$this->link) {
            return false;
        }

        return mysql_insert_id($this->link);
    }
}
