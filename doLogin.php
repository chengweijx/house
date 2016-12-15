<?
    require_once('lib/function.php');
    
    $user_name = isset($_POST['user_name']) ? $_POST['user_name'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $auto_login = isset($_POST['auto_login']) ? $_POST['auto_login'] : 0;
    
    // 验证登录
    $res = cwSendLogin(array('user_name' => $user_name, 'password' => $password));
    if (!$res['status']) { die(json_encode($res)); }
    
    // 登录成功后，根据是否自动登录，判断是否设置cookie
    if ($auto_login) {
        $data = cwGetLoginData();
        $user_data = $data['user_name'] . '&' . $data['password'];
        cwCookie('auto_login', 1);
        cwCookie('user_data', $user_data);
    }
    die(json_encode($res));