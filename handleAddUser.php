<?
    require_once('lib/function.php');
    require_once('lib/Upload.php');
    
    cwCheckLogin();
    
    $l_data = cwGetLoginData();
    $db = cwGetMysqlDb();
    
    $data = array(
        'u_name' => isset($_POST['user_name']) ? $_POST['user_name'] : '',
        'u_sex' => isset($_POST['sex']) ? $_POST['sex'] : '',
        'u_birthday' => isset($_POST['birthday']) ? $_POST['birthday'] : '',
        'u_idcard' => isset($_POST['idcard']) ? $_POST['idcard'] : '',
        'u_nation' => isset($_POST['nation']) ? $_POST['nation'] : '',
        'u_phone' => isset($_POST['phone']) ? $_POST['phone'] : '',
        'u_register_address' => isset($_POST['register_address']) ? $_POST['register_address'] : '',
        'u_local_address' => isset($_POST['local_address']) ? $_POST['local_address'] : '',
        'u_house_address' => isset($_POST['house_address']) ? $_POST['house_address'] : '',
        'u_coordinate' => isset($_POST['coordinate']) ? $_POST['coordinate'] : '',
        'u_m_id' => $l_data['id'],
        'u_create_time' => date('Y-m-d H:i:s', time())
    );
    
    // 获取当前用户权限等级
    $g_sql = ' SELECT g_id, g_grade FROM cw_group WHERE g_id=' . $l_data['group_id'];
    $g_info = $db->getOne($g_sql);
    if (empty($g_info)) { die('用户错误'); }
    
    if ($g_info['g_grade'] != 3 && !isset($_POST['area_id'])) { die('非辖区管理员，请选择下属辖区！'); }
    
    $data['u_g_id'] = isset($_POST['area_id']) ? $_POST['area_id'] : $l_data['group_id'];
    
    // 存在图片时，上传图片
    if (isset($_FILES['image'])) {
        $fu = new FileUpload();
        $path = $_SERVER['DOCUMENT_ROOT'] . '/public/Upload/';
        $fu->set('path', $path);
        $up_res = $fu->upload('image');
        if (!$up_res) { die($fu->getErrorMsg()); }
        
        $file_name = $fu->getFileName();
        $data['u_image'] = $path . $file_name;
    }
    
    $sql = cwParseInsert('cw_user', $data);
    $u_id = $db->query($sql);
    if ($u_id) { header("Location: http://" . $_SERVER['HTTP_HOST'] . "/index.php"); }
    else { die('人员采集失败'); }
    
    