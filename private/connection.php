<?php
/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com> 
 */
/**
 * Clase para manejar coneccion a BD,
 * Existe variable global $DB.
 * No es necesario instanciar esta clase mas de una vez
 * para la misma BD.
 */
class DBConnection
{    
    public $link;    
    public $queryCnt=0;
    public $dbhost;
    public $dbuser;
    public $dbname;
    public $connected;

    function __construct($host='', $user='', $pass='', $dbase='')
    {       
        $this->connected=false;
        if ((trim($host)!='')&&(trim($user)!='')&&(trim($dbase)!=''))
            @$this->connect($host, $user, $pass, $dbase);
    }

    function connect($host, $user, $pass, $dbase)
    {
        $this->link = mysqli_connect($host, $user, $pass);	
//        var_dump($host, $user, $pass);
//        var_dump($this->link);
	mysqli_set_charset($this->link, "utf8");
        if(!$this->link)
            return false;        
        else        
        {
            mysqli_select_db($this->link, $dbase);     
            $this->dbhost=$host;
            $this->dbuser=$user;
            $this->dbname=$dbase;
            $this->connected=true;
        }
        return true;
    }
    function clean($str)
    {
        if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
            $str = stripslashes($str);
        return mysqli_real_escape_string($this->link, $str);
    }
    function close()
    {
        mysqli_close($this->link);    
        $this->connected=false;
    }
    function query($query)
    {    
        $this->queryCnt++;        
        $result = mysqli_query($this->link, $query);   
        if (_DEBUG&&(!$result))
        {
            add_alert ('Query failed: '.var_export($query, true).' <br> '.mysqli_error ($this->link).' <br> '.var_export(debug_backtrace(), true), 'danger');
            
        }
        return $result;       
    }
    function multi_query($query)
    {    
        
        $this->queryCnt+=substr($query,-1) == ';'?substr_count($query, ';'):substr_count($query, ';')+1;        
        $result = mysqli_multi_query($this->link, $query);   
        if (_DEBUG&&(!$result))
        {
            add_alert ('Query failed: '.var_export($query, true).' <br> '.mysqli_error ($this->link).' <br> '.var_export(debug_backtrace(), true), 'danger');
        }
        while($this->link->more_results()){
            $this->link->next_result();
            $this->link->use_result();
        }
        return $result;       
    }
    function fetch_array($result)
    {
        return @mysqli_fetch_array($result,MYSQLI_ASSOC);        
    }
    function fetch_field($query, $offset)
    {
        $result = mysqli_fetch_field($query, $offset);
        return $result;
    }
    function getKeyColName($tableName)
    {
        $query = "SELECT `COLUMN_NAME` FROM `information_schema`.`COLUMNS` WHERE "
                    . "(`TABLE_SCHEMA` = '".$this->dbname."') AND (`TABLE_NAME`"
                        . " = '".$tableName."') AND (`COLUMN_KEY` = 'PRI');";
        $colName = @mysqli_fetch_array($this->query($query), MYSQLI_BOTH);
        return $colName['COLUMN_NAME'];
    }    
	
    function fetch_row($query)
    {
        return mysqli_fetch_row($query);        
    }
    function update_cell($table,$set,$where)
    {
        return $this->query("UPDATE $table SET $set WHERE $where");        
    }
    
    function field_name($query, $offset)
    {
        return mysqli_field_name($query, $offset);        
    }
    function free_result($query)
    {
        mysqli_free_result($query);        
    }
    function insert_id()
    {
        return mysqli_insert_id($this->link);        
    }
    function num_fields($query)
    {
        return mysqli_num_fields($query);        
    }
    function num_rows($query)
    {
        return mysqli_num_rows($query);        
    }
    function real_escape_string($string)
    {
        return mysqli_real_escape_string($this->link, $string);          
    }
    function get_row($query)
    {
        return @mysqli_fetch_array($this->query($query),MYSQLI_BOTH);
    }
    function get_cell($query)
    {
        $query = @mysqli_fetch_array($this->query($query),MYSQLI_BOTH);
        return is_array($query) ? $query[0] : "";        
    }
    function countResults($query)
    {
        $count = @mysqli_fetch_array($this->query($query),MYSQLI_NUM);
        return $count[0];        
    }
    function getTables($dbase)
    {
        mysqli_select_db($this->link, $dbase);
        $listdbtables = array_column(mysqli_fetch_all($this->link->query('SHOW TABLES')),0);
        return $listdbtables;        
    }

    function affected_rows()
    {
        return mysqli_affected_rows($this->link);
    }
    function escape_string($string)
    {
        return mysqli_escape_string($string);        
    }
    function listUnique($db,$table,$col)
    {
        $arrayOfUniqueValuesInColumn = array();
        $theseValues = @mysqli_fetch_array($this->query('SELECT DISTINCT '.$col.' FROM '.$db.'.'.$table),MYSQLI_NUM);
        if(is_array($theseValues) && count($theseValues) > 0) {
            $arrayOfUniqueValuesInColumn = $theseValues;            
        }
        return $arrayOfUniqueValuesInColumn;
    }

}