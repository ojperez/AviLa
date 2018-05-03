<?php
/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com>
 */
?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo _TITLE; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo assets_path(); ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo assets_path(); ?>bower_components/font-awesome/css/font-awesome.min.css">
   <link rel="stylesheet" href="<?php echo assets_path(); ?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo assets_path(); ?>bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo assets_path(); ?>dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
<!--  <link rel="stylesheet" href="<?php echo assets_path(); ?>dist/css/skins/skin-blue.min.css">-->
  <!--<link rel="stylesheet" href="<?php echo assets_path(); ?>dist/css/skins/skin-blue.min.css">-->
    <!--<link rel="stylesheet" href="https://raw.githubusercontent.com/almasaeed2010/AdminLTE/master/dist/css/skins/skin-blue-light.css">-->
  
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link rel="stylesheet"
        href="<?php echo _VIEWS_DIR._TEMPLATE; ?>/style.css">
</head>
<!--

-->
<body class="hold-transition skin-green-light sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="<?php echo _BASE_URL; ?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b>vL</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>A</b>vi<b>L</b>a</span>
      <!--<img src="<?php echo assets_path(); ?>img/logo.png">-->
    </a>

    <nav class="navbar navbar-static-top" role="navigation">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo $_SESSION['current_user']['avatar']; ?>" class="user-image" alt="User Image">
            
              <span class="hidden-xs"><?php echo $_SESSION['current_user']['name']; ?></span>
            </a>
            <ul class="dropdown-menu">              
              <li class="user-header">
                <img src="<?php echo $_SESSION['current_user']['avatar']; ?>" class="img-circle" alt="User Image">
                <p>
                 <?php echo $_SESSION['current_user']['name']; ?>                  
                </p>
              </li>
              <!-- Menu Body -->
            
              <!-- Menu Footer-->
              <li class="user-footer">                
                <div class="pull-right">
                    <a href="?c=login&a=logout" class="btn btn-default btn-flat">Log Out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <?php      require_once 'parts/_menu.php'; ?>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div style="max-width: 1024px; margin:0 auto;">
    <!-- Content Header (Page header) -->
    

   <?php require_once ABSPATH.'private/routes.php'; ?>
    </section>
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; <?php echo date('Y'); ?> &nbsp;<a target="_blank" href="https://ojperez.com">OJ Perez</a>.</strong> <small style='float: right;'>...let there be Light.</small>
  </footer>

  <!-- Control Sidebar -->
  
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="<?php echo assets_path(); ?>bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo assets_path(); ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo assets_path(); ?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo assets_path(); ?>dist/js/adminlte.min.js"></script>
<script src="<?php echo _VIEWS_DIR._TEMPLATE; ?>/script.js"></script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>