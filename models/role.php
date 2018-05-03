<?php
/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com> 
 */
/**
 * Modelo para manejar usuarios de la aplicacion
 */
require_once '_model.php';
class Role extends _CRUD_Model
{
    public $ID;
    public $name;
    public $active;
    public $permissions;    
    public $permissions_by_id;  
    public static $all_permissions;
    
    function __construct($ID, $name, $active, $permissions) 
    {
        $this->ID=$ID; $this->active=$active;  $this->name=$name;
        $this->permissions=$permissions; $this->permissions_by_id=false;
        
    }
    protected static function _init()
    {
        global $DB;        
        self::$db=$DB;
        self::$all_permissions=false;
    }  
    public function _toArray()
    {
        return array('ID'=>  $this->ID, 'active'=>  $this->active, 'name'=>  $this->name, 'permissions'=> $this->permissions);
    }
    protected static function getMetaKeys()
    {
        return array();
    }
    public function getPermissionsById()
    {
        if ($this->permissions_by_id===false)
        {
            $db=self::getDB();
            $this->permissions_by_id = [];
            $resultP = $db->query('SELECT * FROM x_role_permissions, permissions WHERE permissions.ID = x_role_permissions.permission AND x_role_permissions.role='.$this->ID);
            while ($rowP = $db->fetch_array($resultP))
                $this->permissions_by_id[$rowP['ID']] = $rowP['name'];       
                    
            
        }
        return $this->permissions_by_id;
    }
    public static function getAllPermissionsById()
    {
        if (self::$all_permissions===false)
        {
            $db=self::getDB();
            self::$all_permissions = [];
            $resultP = $db->query('SELECT * FROM permissions');
            while ($rowP = $db->fetch_array($resultP))
                self::$all_permissions[$rowP['ID']] = $rowP['name'];       
                    
            
        }
        return self::$all_permissions;
    }
    public static function _findInTree($tree, $id)
    {
        $rv = [];
        foreach($tree as $k => $node)
        {
            if (count($rv) == 0)
            {
                if (count($node['children']) != 0)
                {
                    $r = _findInTree($node['children'], $id);
                    if (count($r) > 0)
                        $rv[] = $r;
                }
                else
                {
                    if ($k == $id)
                        $rv[] = $k; 
                }
            }
        }
        return $rv;
    }
    public static function getAllPermissionsGroupedById()
    {
//        if (self::$all_permissions===false)
//        {
            $db=self::getDB();
            self::$all_permissions = [];
            $resultP = $db->query('SELECT * FROM permissions');
            $AP = [];
            $rows = [];
            while ($rowP = $db->fetch_array($resultP))
                    $rows[] = $rowP;
            $go = true;
            $cnt = 0;
            while(count($rows)>0&&$go)
            {
                $cnt++;
                $didunset = false;
                foreach($rows as $k => $row)
                {
                    $unset = false;
                    if ($row['parent'] == 0)
                    {                        
                        $AP[intval($row['ID'])] = ['slug' => $row['slug'], 'name'=> $row['name'], 'children' => []];
                        $unset = true;
                    } else
                    {
//                        echo '//2//';
                        if (isset($AP[intval($row['parent'])]))
                        {
//                            echo '//3//';
                            $AP[intval($row['parent'])]['children'][intval($row['ID'])] = ['slug' => $row['slug'], 'name'=> $row['name'], 'children' => []];
//                            var_dump($AP[intval($row['parent'])]['children'][intval($row['ID'])]);
                            $unset = true; 
                        } else
                        {
                            $path = self::_findInTree($AP, $row['parent']);
//                            echo '//1//';
//                            var_dump($path);
                            if (is_array($path)&&count($path)>0)
                            {
                                $path = array_reverse($path, true);
                                $tmp = $AP;
                                foreach($path as $p)
                                    $tmp = $tmp[$p];
                                $tmp['children'][$row['ID']] = ['slug' => $row['slug'], 'name'=> $row['name'], 'children' => []];
                                $unset = true;
                            }
                            
                        }
                    }
                    if ($unset)
                    {
                        $didunset = true;
                        unset($rows[$k]);
                    }
//                    var_dump($AP);
                }
                if (!$didunset)
                    $go=false;
                
            }
//                self::$all_permissions[$rowP['ID']] = $rowP['name'];       
                    
            
       // }
        return $AP;
    }
    
    public static function postHandler($post)
    {   
        self::_init();
        $db=self::getDB();
        $resp=array('stop'=>false, 'msgs'=>array());

        if (isset($post['ID'])) {
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'name' => array('required' => true)                 
            ));

            if($validation->passed()) {                
                $id=$post['ID'];
                if ($id!=0)
                {   
                    $sql="UPDATE roles SET name='".$post['name']."' WHERE ID=".$id;
                    $insertResult=$db->query($sql);
                    $permissions = Role::get($id)->getPermissionsById();
                } else
                {
                    $sql="INSERT INTO roles(active, name) VALUES (1,'".$post['name']."')";
                    $insertResult=$db->query($sql);
                    $permissions = [];
                    $id = $db->insert_id();
                }
                $all_permissions = self::getAllPermissionsById();
                foreach($all_permissions as $k => $p)
                {
                    if (isset($permissions[$k])&&!isset($post['perm'][$k]))
                    {
                        $sql = 'DELETE FROM x_role_permissions where role='.$id.' AND permission='.$k;
                        @$db->query($sql);
                    } else
                    if (!isset($permissions[$k])&&isset($post['perm'][$k]))
                    {
                        $sql = 'INSERT INTO x_role_permissions (role, permission) VALUES('.$id.','.$k.')';
                        @$db->query($sql);                        
                    }
                }
                
                actionCall('roles', '_list');
                $resp['stop']=true;            
            } else {
                $resp['msgs']=$validation->errors();
            }            
        }
        return $resp;
    }
    
    public static function _new()
    {
 
        
        return new Role(0, '','1', array());
    }
    public static function delete($id)
    {     
        self::_init();
        $db=self::getDB();   
        return $db->query('UPDATE roles SET active=0 WHERE ID ='.$id);        
    }
    public static function get($id)
    {
        self::_init();
        $db=self::getDB();        
        $result=$db->query('SELECT * FROM roles WHERE ID ='.$id.' AND active = 1 LIMIT 1');
        if ($db->num_rows($result)>0)
        {
            $row=$db->fetch_array($result);
            $permissions = [];
            $resultP = $db->query('SELECT * FROM x_role_permissions, permissions WHERE permissions.ID = x_role_permissions.permission AND x_role_permissions.role='.$id);
            while ($rowP = $db->fetch_array($resultP))
                $permissions[$rowP['slug']] = $rowP['permission'];
            
            return new Role($row['ID'], $row['name'], $row['active'], $permissions);
        }
        return false;
    }
    
    public static function all()
    {
        self::_init();
        $db=self::getDB();
        
        $result=$db->query('SELECT * FROM roles WHERE active =1');
        
        $roles=array();
        
        while($row=$db->fetch_array($result))
        {
            $permissions = [];
            $resultP = $db->query('SELECT * FROM x_role_permissions, permissions WHERE permissions.ID = x_role_permissions.permission AND x_role_permissions.role='.$row['ID']);
            while ($rowP = $db->fetch_array($resultP))
                $permissions[$rowP['slug']] = $rowP['permission'];
            $roles[$row['ID']]=new Role($row['ID'], $row['name'], $row['active'], $permissions);     
        }
       
        return $roles;
    }
}