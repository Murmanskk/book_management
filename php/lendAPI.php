<?php
/*
 * @Author: your name
 * @Date: 2020-06-17 14:20:03
 * @LastEditTime: 2020-06-18 22:57:45
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
        //检查当前图书是否全部借出
        $sql_jug = "SELECT isbn, quantity, brrow_nums, quantity-brrow_nums AS now_nums FROM book WHERE isbn = '{$isbn}'";
        $now_nums = $mySQLi->query($sql_jug);
        $now_nums = $now_nums->fetch_all(MYSQLI_ASSOC);

        if ($now_nums[0]['now_nums'] <= 0) {
            $responseData['code'] = 1;
            $responseData['msg'] = '当前书籍已全部借出';
            echo json_encode($responseData);
        } else {
            $sql = "INSERT INTO lend_info(user_id, isbn, status) VALUES ('{$user_id}','{$isbn}', '0')";
            if ($mySQLi->query($sql)) {
                $sql_update = "UPDATE book SET brrow_nums=brrow_nums+1 WHERE isbn = '{$isbn}'"; //更新已借出数量
                if ($mySQLi->query($sql_update)) {
                    $responseData['msg'] = '申请成功，请等待批准';
                    echo json_encode($responseData);
                } else {
                    $responseData['code'] = -1;
                    $responseData['msg'] = '申请失败';
                    echo json_encode($responseData);
                }
            } else {
                $responseData['code'] = -1;
                $responseData['msg'] = '申请失败';
                echo json_encode($responseData);
            }
        }
        break;
    case 'getLendInfo':
        if ($_SESSION['access'] == 1) {
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
            $sql = "SELECT * FROM lend_info left JOIN user on lend_info.user_id = user.user_id left JOIN book on lend_info.isbn = book.isbn";
            $res = $mySQLi->query($sql);
            $res_arr = $res->fetch_all(MYSQLI_ASSOC);
            $responseData["count"] = $res->num_rows;
            $responseData["data"] = $res_arr;
            echo json_encode($responseData);
        }
        break;
    case 'agreeApply':
        if ($_SESSION['access'] != 1) {
            die(json_encode(array('code' => -1, 'msg' => '无权限')));
        }
        $lend_id = $_GET['lend_id'];
        $sql = "UPDATE lend_info SET lend_status = 1,admin_id = '{$_SESSION['uid']}' WHERE lend_id = '{$lend_id}'";
        if ($mySQLi->query($sql)) {
            $responseData['msg'] = '已批准';
            echo json_encode($responseData);
        } else {
            $responseData['code'] = -1;
            $responseData['msg'] = '批准失败';
            echo json_encode($responseData);
        }
        break;
    case 'rejectApply':
        if ($_SESSION['access'] != 1) {
            die(json_encode(array('code' => -1, 'msg' => '无权限')));
        }
        $lend_id = $_GET['lend_id'];
        $sql = "UPDATE lend_info SET lend_status = 2,admin_id = '{$_SESSION['uid']}' WHERE lend_id = '{$lend_id}'";
        if ($mySQLi->query($sql)) {
            $responseData['msg'] = '已拒绝';
            echo json_encode($responseData);
        } else {
            $responseData['code'] = -1;
            $responseData['msg'] = '批准失败';
            echo json_encode($responseData);
        }
        break;
    default:
        # code...
        break;
}
