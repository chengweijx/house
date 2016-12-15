<?
    require_once('lib/function.php');
    
    cwCheckLogin();
    
    $l_data = cwGetLoginData();
    // 获取当前用户权限等级
    $g_sql = ' SELECT g_id, g_grade FROM cw_group WHERE g_id=' . $l_data['group_id'];
    $g_info = $db->getOne($g_sql);
    if (empty($g_info)) { die('用户错误'); }
    
    include 'template/addUser.html'; 