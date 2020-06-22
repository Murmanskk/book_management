<?php
/*
 * @Author: your name
 * @Date: 2020-06-21 19:32:54
 * @LastEditTime: 2020-06-22 16:37:38
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \books_management\php\userAPI.php
 */ 
include_once 'conn.php';
if (empty($_SESSION['access'])||$_SESSION['access']!=1) {
    die(json_encode(array('code' => -1, 'msg' => '无权限')));
}
$op = $_GET['s'];
$responseData = array("code" => 0, "msg" => "", "count" => 0, "data" => "");

switch ($op) {
    case 'getUserInfo':
        $tel = $_GET['tel'];
        if ($tel) {
            $sqlCount = "SELECT * FROM user where tel like '%{$tel}%' AND access_id = 2";
        }else {
            $sqlCount = "SELECT * FROM user WHERE access_id = 2";
        }
        $resCount = $mySQLi->query($sqlCount);
        //$resCount = $resCount->fetch_all();
        $responseData["count"] = $resCount->num_rows;

        if ($_GET['limit']) {
            $limit = $_GET['limit'];
        } else {
            $limit = 20;
        }
        if ($_GET['page']) {
            $page = ($_GET['page'] - 1) * $limit;
        } else {
            $page = 0;
        }
        if($tel){
            $sql = "SELECT * FROM user WHERE tel like '%{$tel}%' AND access_id = 2 LIMIT {$page}, {$limit}";
        }else{
            $sql = "SELECT * FROM user WHERE access_id = 2 LIMIT {$page}, {$limit}";
        }
        if($res = $mySQLi->query($sql)){
            $res_arr = $res->fetch_all(MYSQLI_ASSOC);
            $responseData['data'] = $res_arr;
            echo json_encode($responseData);
        }else{
            $responseData['code']=-1;
            $responseData['msg'] = '请求失败';
            echo json_encode($responseData);
        }
        
        
        # code...
        break;
    case 'updateUser':
        if ($_SESSION['access'] != 1) {
            die(json_encode(array('code' => -1, 'msg' => '无权限')));
        }
        $user_data = file_get_contents("php://input");
        $user_data = json_decode($user_data);
        $sql = "UPDATE user SET user_name = '{$user_data->user_name}',tel = '{$user_data->tel}',email = '{$user_data->email}'
                            WHERE user_id = {$user_data->user_id}";
        if ($mySQLi->query($sql)) {
            $responseData['msg'] = '修改信息成功';
            echo json_encode($responseData);
        } else {
            // echo $sql;
            // echo $mySQLi->error;
            $responseData['code'] = 1;
            $responseData['msg'] = '修改信息失败';
            echo json_encode($responseData);
        }
        break;
    default:
        # code...
        break;
}
?>
