<?php
/*
 * @Author: your name
 * @Date: 2020-06-17 14:20:03
 * @LastEditTime: 2020-06-17 23:17:32
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \Javascriptd:\wwwroot\books_management\php\lendAPI.php
 */ 

include_once 'conn.php';
if (empty($_SESSION['access'])) {
die(json_encode(array('code' => -1, 'msg' => '无权限')));
}
$op = $_GET['s'];
$responseData = array("code" => 0, "msg" => "", "count" => 0, "data" => "");

switch ($op) {
    case 'applyBook':
        $isbn = $_GET['isbn'];
        $user_id = $_SESSION['uid'];
        $sql = "INSERT INTO lend_info(user_id, isbn, status) VALUES ('{$user_id}','{$isbn}', '0')";
        if($mySQLi->query($sql)){
            $responseData['msg']='申请成功，请等待批准';
            echo json_encode($responseData);
        }else{
            $responseData['code']=-1;
            $responseData['msg']='申请失败';
            echo json_encode($responseData);
        }
        break;
    default:
        # code...
        break;
}
    
?>