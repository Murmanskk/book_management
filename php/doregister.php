<?php
/*
 * @Author: your name
 * @Date: 2020-06-15 21:03:12
 * @LastEditTime: 2020-06-16 00:08:18
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \Javascriptd:\wwwroot\books_management\php\doregister.php
 */ 
include_once 'conn.php';

$responseDate = array("code" => 0, "msg" => "", "uid" => "");
//echo json_encode($responseDate);
$username = $_POST['username'];
$passwordPre = $_POST['password'];
$tel = $_POST['tel'];
$email = $_POST['email'];
$password = md5($passwordPre);
$sql1 = "SELECT * FROM user WHERE user_name='{$username}' OR tel = '{$tel}' OR email = '{$email}' ";
$res1 = $mySQLi->query($sql1);
echo $mySQLi->error;
if ($num = mysqli_num_rows($res1)) {
    $responseDate["code"] = 1;
    $responseDate["msg"] = "账户已存在";
    echo json_encode($responseDate);
    exit;
} else {
    $sql2 = "INSERT INTO user (user_name, password, tel, email) VALUES ('{$username}', '$password', '{$tel}', '$email')";
    $res2 = $mySQLi->query($sql2);
    if ($res2) {
        $responseDate["msg"] = "注册成功！";
        echo json_encode($responseDate);
        exit;
    } else {
        $responseDate["code"] = 3;
        $responseDate["msg"] = "注册失败！";
        echo json_encode($responseDate);
        exit;
    }
}
