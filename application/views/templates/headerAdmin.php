<?php
  if( !isset($_SESSION)){
    session_start();
    $_SESSION['user'] = "prueba";
  	$_SESSION['status'] = true;
  }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $title ?></title>
    <link rel="stylesheet" type="text/css" href="<?echo base_url(); ?>css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?echo base_url(); ?>css/encuesta.css">
    <link rel="stylesheet" type="text/css" href="<?echo base_url(); ?>fonts/fonts.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body id="page-top" class="index Flama">
  <!-- Aqui empieza el body de la pagina -->