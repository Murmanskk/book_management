<?php
include_once 'conn.php';
if (empty($_SESSION['access'])) {
    die(json_encode(array('code' => -1, 'msg' => '请重新登录')));
}
$op = $_GET['s'];
$responseData = array("code" => 0, "msg" => "", "count" => 0, "data" => "");
switch ($op) {
    case 'getBookinfo':
        $sqlCount = "SELECT * FROM book";
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
        if ($_SESSION['access'] == 1) {
            $sql = "SELECT * FROM book LIMIT {$page},{$limit}";
        } else {
            $sql = "SELECT isbn,book_name,book_author,publisher,cate,price,quantity FROM book LIMIT {$page},{$limit}";
        }
        $res = $mySQLi->query($sql);
        $res_arr = $res->fetch_all(MYSQLI_ASSOC);
        $responseData["data"] = $res_arr;
        echo json_encode($responseData);
        break;
    case 'getCate':
        $sql = "SELECT * FROM cate";
        $res = $mySQLi->query($sql);
        $res_arr = $res->fetch_all(MYSQLI_ASSOC);
        $responseData["count"] = $res->num_rows;
        $responseData["data"] = $res_arr;
        echo json_encode($responseData);
        break;
    case 'addBook':
        $book_data = file_get_contents("php://input");
        $book_data = json_decode($book_data);
        //查询是否存在该图书，如果存在，则直接将数量加一
        $sql_c = "SELECT * FROM book WHERE isbn = '{$book_data->isbn}'";
        $result = $mySQLi->query($sql_c);
        if ($result->num_rows) {
            $sql_c = "UPDATE book SET quantity=quantity+1 WHERE isbn = '{$book_data->isbn}'";
            if ($mySQLi->query($sql_c)) {
                $responseData['msg'] = '已存在该书，系统自动将数量加一';
                echo json_encode($responseData);
            } else {
                $responseData['code'] = 1;
                $responseData['msg'] = '添加失败';
                echo json_encode($responseData);
            }
        } else {
            //不存在
            $sql = 'INSERT INTO book(isbn, book_name, book_author, publisher, cate, price, quantity) VALUES (?,?,?,?,?,?,?)';
            $stmt = $mySQLi->init();
            $stmt = $mySQLi->prepare($sql);
            $stmt->bind_param('ssssidi', $book_data->isbn, $book_data->book_name, $book_data->book_author, $book_data->publisher, $book_data->cate, $book_data->price, $book_data->quantity);
            if ($stmt->execute()) {
                $responseData['msg'] = '添加成功';
                echo json_encode($responseData);
            } else {
                $responseData['code'] = 1;
                $responseData['msg'] = '添加失败';
                echo json_encode($responseData);
            }
        }



        break;
    default:
        # code...
        break;
}
