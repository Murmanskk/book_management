<?php
/*
 * @Author: your name
 * @Date: 2020-06-27 21:00:35
 * @LastEditTime: 2020-06-27 22:36:04
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \books_management\php\personAPI.php
 */ 
include_once 'conn.php';
if (empty($_SESSION['uid'])) {
    die(json_encode(array('code' => -1, 'msg' => '请登录')));
}
$op = $_GET['s'];
$responseData = array("code" => 0, "msg" => "", "count" => 0, "data" => "");
switch ($op) {
    case 'getMyInfo':
        $sql = "SELECT user_name,tel,email,borrow_times,overdue_times FROM user WHERE user_id = '{$_SESSION['uid']}'";
        if ($res = $mySQLi->query($sql)) {
            $res_info = $res->fetch_all(MYSQLI_ASSOC);
            $responseData['data'] = $res_info;
            echo json_encode($responseData);
        } else {
            $responseData['code'] = 1;
            $responseData['msg'] = '拉取个人信息失败';
            echo json_encode($responseData);
        }
        break;
    case 'updateInfo':
        $user_name = $_POST['user_name'];
        $email = $_POST['email'];
        $tel = $_POST['tel'];
        $sql = "UPDATE user SET user_name = '{$user_name}', email = '{$email}', tel = '{$tel}' WHERE user_id = '{$_SESSION['uid']}'";
        //echo $sql;
        if($mySQLi->query($sql)){
            $responseData['msg'] = '更新个人信息成功';
            echo json_encode($responseData);
        }else{
            $responseData['code'] = 1;
            $responseData['msg'] = '更新个人信息失败';
            echo json_encode($responseData);
        }
        break;
    default:
        # code...
        break;
}
?>