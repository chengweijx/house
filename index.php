<?
    require_once('lib/function.php');
    
    /**
     * @version 主页数据获取
     * @copyright (c) 2016-12-14, cwall
     * @return array    主页数据数组
     */
    function indexPage() {
        $res = array('user_alias' => '', 'group_name' => '', 'house_count' => '', 'user_count' => '', 'sex_count' => '', 'more_info' => '');
        
        $data = cwGetLoginData();
        $res['user_alias'] = $data['user_alias'];
        
        $db = cwGetMysqlDb();
        
        // 获取用户辖区名称
        $g_sql = ' SELECT g_name FROM cw_group WHERE g_id=' . $data['group_id'];
        $g_info = $db->getOne($g_sql);
        
        $res['group_name'] = $g_info['g_name'];
    
        // 获取当前用户所有辖区id
        $id_res = cwGetAllAreaId($data['group_id']);
        // 如果获取条件失败，直接返回当前组装的数组
        if (empty($id_res)) { return $res; }
        else if (is_array($id_res)) { $where = 'u_g_id IN (' . implode(',', $id_res) . ')'; }
        else { $where = 'u_g_id=' . $id_res; }
        
        // 获取房屋数量
        $h_sql = ' SELECT COUNT(u_id) AS num FROM cw_user WHERE ' . $where;
        $h_list = $db->getAll($h_sql);
        if (empty($h_list)) { $res['house_count'] = 0; }
        else { $res['house_count'] = $h_list[0]['num']; }
        
        // 获取用户数量
        $u_sql = ' SELECT COUNT(DISTINCT u_idcard) AS num FROM cw_user WHERE ' . $where;
        $u_list = $db->getAll($u_sql);
        if (empty($u_list)) { $res['user_count'] = 0; }
        else { $res['user_count'] = $u_list[0]['num']; }
        
        // 性别比例
        $s_sql = ' SELECT u_sex, COUNT(DISTINCT u_idcard) AS num FROM cw_user WHERE ' . $where . ' GROUP BY u_sex ';
        $s_list = $db->getAll($s_sql);
        if (empty($s_list)) { $res['sex_count'] = '0:0'; }
        else {
            $s_arr = array('男' => 0, '女' => 0);
            foreach($s_list as $s_info) {
                $s_arr[$s_info['u_sex']] = $s_info['num'];
            }
            $res['sex_count'] = $s_arr['男'] . ':' . $s_arr['女'];
        }
        
        return $res;
    }
    
    cwCheckLogin();
    
    $result = indexPage();
    include 'template/index.html'; 