<?
    require_once('lib/function.php');
    
    cwClearLogout();
    
    header("Location: http://" . $_SERVER['HTTP_HOST'] . "/login.php");