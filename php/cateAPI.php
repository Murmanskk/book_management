<?php
/*
 * @Author: your name
 * @Date: 2020-06-26 19:36:43
 * @LastEditTime: 2020-06-30 13:29:30
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \books_management\php\cateAPI.php
 */ 
include_once 'conn.php';
if (empty($_SESSION['access'])) {
    die(json_encode(array('code' => -1, 'msg' => '无权限')));
}
$op = $_GET['s'];
$responseData = array("code" => 0, "msg" => "", "count" => 0, "data" => "");
switch ($op) {
    case 'getCate':
        $sql = "SELECT 
                cate_name, cate_id, SUM(quantity) AS total
                FROM
                cate
                    LEFT JOIN
                book USING (cate_id)
                GROUP BY cate_id";
        $res = $mySQLi->query($sql);
        $res_arr = $res->fetch_all(MYSQLI_ASSOC);
        $responseData["count"] = $res->num_rows;
        $responseData["data"] = $res_arr;
        echo json_encode($responseData);
        break;
    case 'addCate':
        $cateName = $_POST['cateName'];
        $sql = 'INSERT INTO cate(cate_name) VALUES (?)';
        $stmt = $mySQLi->init();
        $stmt = $mySQLi->prepare($sql);
        $stmt->bind_param('s', $cateName);
        if ($stmt->execute()) {
            $responseData['msg'] = '添加成功';
            echo json_encode($responseData);
        } else {
            $responseData['code'] = -1;
            $responseData['msg'] = '添加失败';
            //echo $mySQLi->error;
            echo json_encode($responseData);
        }
        break;
    case 'editCate':
            $cate_name = $_POST['cateName'];
            $cate_id = $_POST['cateId'];
            $sql = "UPDATE cate SET cate_name = '{$cate_name}' WHERE cate_id = '{$cate_id}'";
            if($mySQLi->query($sql)){
                $responseData['msg'] = '修改成功';
                echo json_encode($responseData);
            } else {
                $responseData['code'] = -1;
                $responseData['msg'] = '修改失败';
                //echo $mySQLi->error;
                echo json_encode($responseData);
            }
        break;
    case 'delCate':
        $cate_id = $_GET['cate_id'];
        $sql = "SELECT * FROM book WHERE cate_id = '{$cate_id}'";
        $res = $mySQLi->query($sql);
        $count = $res->num_rows;
        if($count){
            $responseData['code'] = -1;
            $responseData['msg'] = '删除失败，当前分类下有书籍！';
            echo json_encode($responseData);
        }else{
            //删除语句
            $responseData['msg'] = '删除成功';
            echo json_encode($responseData);
        }
        break;
    default:
        # code...
        break;
}   
?>
