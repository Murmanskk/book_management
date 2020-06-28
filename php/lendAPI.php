<?php
/*
 * @Author: your name
 * @Date: 2020-06-17 14:20:03
 * @LastEditTime: 2020-06-28 23:18:35
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
        $sql_jug = "SELECT isbn, quantity, brrow_nums, quantity-brrow_nums AS now_nums FROM book WHERE isbn = '{$isbn}' AND book_status = 0";
        $now_nums = $mySQLi->query($sql_jug);
        $now_nums = $now_nums->fetch_all(MYSQLI_ASSOC);

        if ($now_nums[0]['now_nums'] <= 0) {
            $responseData['code'] = 1;
            $responseData['msg'] = '当前书籍不可借或已全部借出';
            echo json_encode($responseData);
        } else {
            $mySQLi->autocommit(false);
            $mySQLi->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $sql = "INSERT INTO lend_info(user_id, isbn, lend_status) VALUES ('{$user_id}','{$isbn}', '1')";
            $sql_update = "UPDATE book SET brrow_nums=brrow_nums+1 WHERE isbn = '{$isbn}'"; //更新已借出数量
            if($mySQLi->query($sql)&&$mySQLi->query($sql_update)){
                $mySQLi->commit();
                $responseData['msg'] = '申请成功，请等待批准';
                echo json_encode($responseData);
            }else{
                $mySQLi->rollback();
                $responseData['code'] = '-1';
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
            $user_name = $_GET['user_name'];
            $lend_status = $_GET['lend_status'];
            if ($user_name && !$lend_status) {
                $sqlCount = "SELECT l.lend_id,
                                    u.user_name,
                                    l.isbn,
                                    b.book_name,
                                    b.price,
                                    l.lend_time,
                                    l.lend_status 
                                FROM lend_info AS l 
                                left JOIN user AS u USING(user_id) 
                                left JOIN book AS b on l.isbn = b.isbn 
                                where l.user_id IN (SELECT user_id FROM user WHERE user_name LIKE '%{$user_name}%')";
            } else if ($user_name && $lend_status) {
                $sqlCount = "SELECT l.lend_id,
                                    u.user_name,
                                    l.isbn,
                                    b.book_name,
                                    b.price,
                                    l.lend_time,
                                    l.lend_status 
                                FROM lend_info AS l 
                                left JOIN user AS u on l.user_id = u.user_id 
                                left JOIN book AS b on l.isbn = b.isbn 
                                where l.user_id IN (SELECT user_id FROM user WHERE user_name LIKE '%{$user_name}%') AND l.lend_status = '{$lend_status}'";
            } else if (!$user_name && $lend_status) {
                $sqlCount = "SELECT l.lend_id,
                                    u.user_name,
                                    l.isbn,
                                    b.book_name,
                                    b.price,
                                    l.lend_time,
                                    l.lend_status 
                                FROM lend_info AS l 
                                left JOIN user AS u on l.user_id = u.user_id 
                                left JOIN book AS b on l.isbn = b.isbn 
                                where l.lend_status = '{$lend_status}'";
            } else {
                $sqlCount = "SELECT l.lend_id,
                                    u.user_name,
                                    l.isbn,
                                    b.book_name,
                                    b.price,
                                    l.lend_time,
                                    l.lend_status 
                                FROM lend_info AS l 
                                left JOIN user AS u on l.user_id = u.user_id 
                                left JOIN book AS b on l.isbn = b.isbn";
            }
            $resCount = $mySQLi->query($sqlCount);
            //$resCount = $resCount->fetch_all();
            $responseData["count"] = $resCount->num_rows;
            if ($user_name && !$lend_status) {
                $sql = "SELECT l.lend_id,
                                    u.user_name,
                                    l.isbn,
                                    b.book_name,
                                    b.price,
                                    l.lend_time,
                                    l.lend_status 
                                FROM lend_info AS l 
                                left JOIN user AS u on l.user_id = u.user_id 
                                left JOIN book AS b on l.isbn = b.isbn 
                                where l.user_id IN (SELECT user_id FROM user WHERE user_name LIKE '%{$user_name}%')  
                                ORDER BY l.lend_status ASC
                                LIMIT {$page},{$limit}";
            } else if ($user_name && $lend_status) {
                $sql = "SELECT l.lend_id,
                                    u.user_name,
                                    l.isbn,
                                    b.book_name,
                                    b.price,
                                    l.lend_time,
                                    l.lend_status 
                                FROM lend_info AS l 
                                left JOIN user AS u on l.user_id = u.user_id 
                                left JOIN book AS b on l.isbn = b.isbn 
                                where l.user_id IN (SELECT user_id FROM user WHERE user_name LIKE '%{$user_name}%') AND l.lend_status = '{$lend_status}' 
                                ORDER BY l.lend_status ASC
                                LIMIT {$page},{$limit}";
            } else if (!$user_name && $lend_status) {
                $sql = "SELECT l.lend_id,
                                    u.user_name,
                                    l.isbn,
                                    b.book_name,
                                    b.price,
                                    l.lend_time,
                                    l.lend_status 
                                FROM lend_info AS l 
                                left JOIN user AS u on l.user_id = u.user_id 
                                left JOIN book AS b on l.isbn = b.isbn 
                                where l.lend_status = '{$lend_status}' 
                                ORDER BY l.lend_status ASC
                                LIMIT {$page},{$limit}";
            } else {
                $sql = "SELECT l.lend_id,
                                    u.user_name,
                                    l.isbn,
                                    b.book_name,
                                    b.price,
                                    l.lend_time,
                                    l.lend_status 
                                FROM lend_info AS l 
                                left JOIN user AS u on l.user_id = u.user_id 
                                left JOIN book AS b on l.isbn = b.isbn 
                                ORDER BY l.lend_status ASC
                                LIMIT {$page},{$limit}";
            }
            $res = $mySQLi->query($sql);
            echo $mySQLi->error;
            $res_arr = $res->fetch_all(MYSQLI_ASSOC);
            $responseData["count"] = $res->num_rows;
            $responseData["data"] = $res_arr;
            echo json_encode($responseData);
        } else if ($_SESSION['access'] == 2) {
            $sql = "SELECT * FROM lend_info LEFT JOIN book ON lend_info.isbn = book.isbn WHERE lend_info.user_id = '{$_SESSION['uid']}'";
            $res = $mySQLi->query($sql);
            $res_arr = $res->fetch_all(MYSQLI_ASSOC);
            $res_nums = $res->num_rows;
            $responseData['data'] = $res_arr;
            $responseData['count'] = $res_nums;
            echo json_encode($responseData);
        }
        break;
    case 'agreeApply':
        if ($_SESSION['access'] != 1) {
            die(json_encode(array('code' => -1, 'msg' => '无权限')));
        }
        $lend_id = $_GET['lend_id'];
        $sql = "UPDATE lend_info SET lend_status = 2,admin_id = '{$_SESSION['uid']}' WHERE lend_id = '{$lend_id}'";
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
        $mySQLi->autocommit(false);
        $mySQLi->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
        $sql = "UPDATE lend_info SET lend_status = 3,admin_id = '{$_SESSION['uid']}' WHERE lend_id = '{$lend_id}'";
        $sql2 = "UPDATE book SET brrow_nums=brrow_nums-1 WHERE isbn IN (SELECT isbn FROM lend_info WHERE lend_id = '{$lend_id}')";
        if ($mySQLi->query($sql)&&$mySQLi->query($sql2)) {
            $mySQLi->commit();
            $responseData['msg'] = '已拒绝';
            echo json_encode($responseData);
        } else {
            $mySQLi->rollback();
            $responseData['code'] = -1;
            $responseData['msg'] = '批准失败';
            echo json_encode($responseData);
        }
        break;
    case 'returnBook':
        if ($_SESSION['access'] == 2) {
            //判断是否是本人归还
            $lend_id = $_GET['lend_id'];
            $sql = "SELECT * FROM lend_info WHERE lend_id = '{$lend_id}' AND user_id = '{$_SESSION['uid']}'";
            $res = $mySQLi->query($sql);
            if ($res->num_rows) {
                $sql = "UPDATE lend_info SET lend_status = 4 WHERE lend_id = '{$lend_id}'";
                if ($mySQLi->query($sql)) {
                    $responseData['msg'] = '已申请，请等待管理员确认';
                    echo json_encode($responseData);
                } else {
                    $responseData['code'] = -1;
                    $responseData['msg'] = '申请失败';
                    echo json_encode($responseData);
                }
            } else {
                $responseData['code'] = -1;
                $responseData['msg'] = '无权限';
                echo json_encode($responseData);
            }
        } else if ($_SESSION['access'] == 1) {
            $lend_id = $_GET['lend_id'];
            $mySQLi->autocommit(false);
            $mySQLi->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $sql = "UPDATE lend_info SET lend_status = 5 WHERE lend_id = '{$lend_id}'";
            $sql1 = "UPDATE book SET brrow_nums = brrow_nums-1 WHERE isbn IN (SELECT isbn FROM lend_info WHERE lend_id = '{$lend_id}')";
            $sql2 = "UPDATE user SET borrow_times = borrow_times+1 WHERE user_id in(SELECT user_id FROM lend_info WHERE lend_id = '{$lend_id}')";
            if ($mySQLi->query($sql)&& $mySQLi->query($sql1)&& $mySQLi->query($sql2)) {
                $mySQLi->commit();
                $responseData['msg'] = '已确认';
                echo json_encode($responseData);
            } else {
                $mySQLi->rollback();
                $responseData['code'] = -1;
                $responseData['msg'] = '失败';
                echo json_encode($responseData);
            }
        }
        break;
    case 'cancelApply':
        $lend_id = $_GET['lend_id'];
        $sql = "SELECT * FROM lend_info WHERE lend_id = '{$lend_id}' AND user_id = '{$_SESSION['uid']}'";
        $res = $mySQLi->query($sql);
        if ($res->num_rows) {
            $res_data = $res->fetch_assoc();
            if($res_data['lend_status'] == 1){
                $sql = "UPDATE lend_info SET lend_status = 6 WHERE lend_id = '{$lend_id}'";
                $sql1 = "UPDATE book SET brrow_nums = brrow_nums-1 WHERE isbn IN (SELECT isbn FROM lend_info WHERE lend_id = '{$lend_id}')";
                $mySQLi->autocommit(false);
                $mySQLi->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
                if ($mySQLi->query($sql) && $mySQLi->query($sql1)) {
                    $mySQLi->commit();
                    $responseData['msg'] = '已取消';
                    echo json_encode($responseData);
                }else{
                    $mySQLi->rollback();
                    $responseData['code'] = -1;
                    $responseData['msg'] = '取消失败';
                    echo json_encode($responseData);
                }
            }else if($res_data['lend_status'] == 4){
                $sql = "UPDATE lend_info SET lend_status = 2 WHERE lend_id = '{$lend_id}'";
                if ($mySQLi->query($sql)) {
                    $responseData['msg'] = '已取消';
                    echo json_encode($responseData);
                } else {
                    $responseData['code'] = -1;
                    echo $mySQLi->error;
                    $responseData['msg'] = '取消失败';
                    echo json_encode($responseData);
                }
            }else if($res_data['lend_status'] == 2 || $res_data['lend_status'] == 5){
                $responseData['code'] = 1;
                $responseData['msg'] = '取消失败，当前申请已批准！';
                echo json_encode($responseData);
            }else if($res_data['lend_status'] == 3){
                $responseData['code'] = 1;
                $responseData['msg'] = '取消失败，当前申请已拒绝！';
                echo json_encode($responseData);
            }
        } else {
            $responseData['code'] = -1;
            $responseData['msg'] = '无权限';
            echo json_encode($responseData);
        }
        break;
    default:
        # code...
        break;
}
