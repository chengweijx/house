<?
    require_once('lib/function.php');
    
    cwCheckLogin();
    
    /**
     * @version 获取指定身份号用户信息结果数组
     * @copyright (c) 2016-12-14, cwall
     * @param string $idcard 指定身份证号
     * @return array         结果数组
     */
    function getIdCardInfo($idcard = '') {
        if (empty($idcard)) { return array('status' => 0, 'msg' => '身份证号不为空！'); }
        
        $db = cwGetMysqlDb();
        
        $sql = ' SELECT u_name AS user_name, u_sex AS sex, u_birthday AS birthday, u_nation AS nation, u_phone AS phone, u_register_address AS register_address, u_local_address AS local_address ';
        $sql .= ' FROM cw_user WHERE u_idcard="' . $idcard . '" ORDER BY u_id DESC ';
        $info = $db->getOne($sql);
        if (empty($info)) { return array('status' => 0, 'msg' => '身份号未登记！'); }
        
        return array('status' => 1, 'msg' => '获取成功！', 'data' => $info);
    }
    
    $idcard = isset($_POST['idcard']) ? $_POST['idcard'] : '';
    $res = getIdCardInfo($idcard);
    die(json_encode($res));