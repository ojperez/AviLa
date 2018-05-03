<?php
/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com> 
 */

/*Variable de sesion para manejar mensajes de errores*/
//var_dump($_SESSION['current_user']); die;
$_SESSION['errorMsg']='';

if (is_user_logged_in()) //El usuario tiene sesion active
{
    if (($controller=='login')&&($action=='logout')) //El usuario quiere cerrar sesion
    {
        $_SESSION['current_user']=false;
        header("Location: "._BASE_URL);
        die;
    }    
} else
{
    
    //El usuario no tiene sesion activa
    if (($controller=='login')&&($action=='login')&&isset($_POST['user'])&&isset($_POST['password']))
    {
        load_model('user');
        $user = User::getByEmail($_POST['user']);
        if ($user)
        {
            if ($user->validPassword($_POST['password']))
            {
                $_SESSION['current_user']=$user->_toArray();
                /*Avatar generado a partir del nombre*/
    //            $displayName=$resp['first_name'].' '.$resp['last_name'];
                $displayName=$user->name;
                $color=substr(md5($displayName), 0, 6);
                $_SESSION['current_user']['avatar']='https://ui-avatars.com/api/?background='. strtoupper($color).'&color=fff&name='. urlencode($displayName);
                
                header("Location: "._BASE_URL.'?'.$_POST['path']);
                die;
            } else
                $_SESSION['errorMsg']='Error, contrase&ntilde;a inv&aacute;lida.';     
        } else
        {
            $_SESSION['errorMsg']='Error, usuario no existe.';            
        }
              
    } else
    if (($controller=='login')&&($action=='recover')&&isset($_POST['email']))    
    {
         /*Recibe el email de la pantalla de login, para generar el email y ser enviado*/
    } else
    if (($controller=='login')&&($action=='reset'))    
    {
         /*El link del email debe dirigir aca:
            if (!isset($_POST['password'])) {
          * Mostrar una vista para crear nueva contrasena (no existe aun, puede ser 'hermana' de [views]/[template]/layout y [views]/[template]/login)
          } else {
          * Procesar el cambio de clave y redireccionar a ?c=login&a=login (posteando user y password) para que quede loggeado de una vez
          }
          *           */
    }
}