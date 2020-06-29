<?php
/*
 * @Author: your name
 * @Date: 2020-06-29 13:10:36
 * @LastEditTime: 2020-06-29 14:03:04
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \books_management\php\system.php
 */ 
include_once 'conn.php';
if (empty($_SESSION['access'])) {
    die(json_encode(array('code' => -1, 'msg' => '无权限')));
}
$op = $_GET['s'];
$responseData = array("code" => 0, "msg" => "", "count" => 0, "data" => "");
switch ($op) {
    case 'addNotice':
        if ($_SESSION['access']!=1) {
            die(json_encode(array('code' => -1, 'msg' => '无权限')));
        }

        $title = $_POST['title'];
        $content = $_POST['content'];
        $sql = "INSERT INTO notice(title,content,admin_id) VALUE('{$title}', '{$content}', '{$_SESSION['uid']}')";
        if($mySQLi->query($sql)){
            $responseData['msg'] = '发布成功';
            echo json_encode($responseData);
        }else{
            $responseData['code'] = '-1';
            $responseData['msg'] = '发布成功';
            echo json_encode($responseData);
        }
        break;
    case 'getNoticeList':
        $sql = "SELECT * FROM notice ORDER BY id DESC LIMIT 7";
        $res = ($mySQLi->query($sql))->fetch_all(MYSQLI_ASSOC);
        $responseData['data']=$res;
        echo json_encode($responseData);
    break;
    default:
        # code...
        break;
}
?>
