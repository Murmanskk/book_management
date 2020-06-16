<?php
/*
 * @Author: your name
 * @Date: 2020-06-15 15:26:13
 * @LastEditTime: 2020-06-16 00:11:27
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \Javascriptd:\wwwroot\books_management\php\dologin.php
 */
include_once 'conn.php';

$responseDate = array("code" => 0, "msg" => "");
$reader_name = $_POST['username'];
$pwdPre = $_POST['password'];
$pwd = md5($pwdPre);
$role = $_POST['role'];
$sql = "SELECT * FROM user where user_name='{$reader_name}' AND password='{$pwd}'";
$result = $mySQLi->query($sql);
//echo $pwd;
if (mysqli_num_rows($result)) {
    $responseDate['msg'] = '登录成功';
    $result = mysqli_fetch_array($result);
    $_SESSION['uid'] = $result['user_id'];
    $_SESSION['access'] = $result['access_id'];
    $_SESSION['username'] = $result['user_name'];
    echo json_encode($responseDate);
    exit;
} else {
    $responseDate['code'] = 1;
    $responseDate['msg'] = '用户名或密码错误！';
    echo json_encode($responseDate);
    exit;
}
?>
