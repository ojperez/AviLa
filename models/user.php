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
class User extends _CRUD_Model
{
    public $ID;
    public $active;
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $name;
    public $role;
    public static $salt='wn34jjrJJJJ';
    
    function __construct($ID, $active, $email, $password, $first_name, $last_name, $role) 
    {
        $this->ID=$ID; $this->active=$active;  $this->email=$email;
        $this->password=$password;  $this->first_name=$first_name; 
        $this->last_name=$last_name; $this->name=trim($first_name.' '.$last_name);       
        $this->role=$role;
    }
 
    public function _toArray()
    {
        load_model('role');
        return array('ID'=>  $this->ID, 'active'=>  $this->active, 'email'=>  $this->email, 'first_name'=>  $this->first_name, 'last_name'=>  $this->last_name, 'name'=>  $this->name, 'role' => Role::get($this->role)->_toArray());
    }
    protected static function getMetaKeys()
    {
        return array();
    }
    public function validPassword($password)
    {
        return (self::hash($password)==$this->password);
    }
    public static function hash($password)
    {
        return md5(self::$salt.$password);
    }
    public static function postHandler($post)
    {   
        self::_init();
        $db=self::getDB();
        $resp=array('stop'=>false, 'msgs'=>array());

        if (isset($post['ID'])) {
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'email' => array('required' => true) ,
                'first_name' => array('required' => true) ,
                'last_name' => array('required' => true)                                 
            ));

            if($validation->passed()) {                
                $id=$post['ID'];

                                
                /*If editing a user and password is empty, unset*/
                if ($id!=0)
                {
                    if ((trim($post['password'])==''))
                        unset($post['password']);
                    if (isset($post['password']))
                        $password="'".self::hash ($post['password'])."'";
                    else 
                        $password='password';                        
                    
                    $sql="UPDATE users SET email='".$post['email']."', first_name='".$post['first_name']."',last_name='".$post['last_name']."',password=".$password.", role=".$post['role']." WHERE ID=".$id;
                    $insertResult=$db->query($sql);
                } else
                {
                    $sql="INSERT INTO users(active, email, password, first_name, last_name, role) VALUES "
                            . "(1,'".$post['email']."','".self::hash($post['password'])."', '".$post['first_name']."', '".$post['last_name']."', ".$post['role'].")";
                    $insertResult=$db->query($sql);

                }
                actionCall('users', '_list');
                $resp['stop']=true;            
            } else {
                $resp['msgs']=$validation->errors();
            }            
        }
        return $resp;
    }
    
    public static function _new()
    {
        //Instanciamos un User 'vacio'
        
        return new User(0, '1','','','', '', 0);
    }
    public static function delete($id)
    {     
        self::_init();
        $db=self::getDB();   
        return $db->query('UPDATE users SET active=0 WHERE ID ='.$id);        
    }
    public static function get($id)
    {
        self::_init();
        $db=self::getDB();        
        $result=$db->query('SELECT * FROM users WHERE ID ='.$id.' AND active = 1 LIMIT 1');
        if ($db->num_rows($result)>0)
        {
            $row=$db->fetch_array($result);
            return new User($row['ID'], $row['active'], $row['email'], $row['password'], $row['first_name'], $row['last_name'], $row['role']);
        }
        return false;
    }
    public static function getByEmail($email)
    {
        self::_init();
        $db=self::getDB();        
        $result=$db->query('SELECT * FROM users WHERE email ="'.$email.'" LIMIT 1');
        if ($db->num_rows($result)>0)
        {
            $row=$db->fetch_array($result);
            return new User($row['ID'], $row['active'], $row['email'], $row['password'], $row['first_name'], $row['last_name'], $row['role']);
        }
        return false;
    }
    public static function all()
    {
        self::_init();
        $db=self::getDB();
        
        $result=$db->query('SELECT * FROM users WHERE active =1');
        
        $users=array();
        
        while($row=$db->fetch_array($result))
        {
            $users[]=new User($row['ID'], $row['active'], $row['email'], $row['password'], $row['first_name'], $row['last_name'], $row['role']);            
        }
       
        return $users;
    }
}