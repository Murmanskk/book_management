<?php
/*
 * @Author: your name
 * @Date: 2020-06-15 15:27:54
 * @LastEditTime: 2020-06-15 21:26:29
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \Javascriptd:\wwwroot\books_management\php\conn.php
 */ 
    define('HOST', 'localhost'); //主机名
    define('USER', 'root');      //用户名
    define('PWD', 'pmh123');     //密码
    define('DB', 'book_manage');     //数据库名
    define('CHAR', 'utf8');      //字符集
    session_start();
    $mySQLi = new MySQLi(HOST, USER, PWD, DB);
    //判断数据库是否连接
    if ($mySQLi->connect_errno) {
        die('连接错误' . $mySQLi->connect_error);
    }
    //设置字符集
    $mySQLi->set_charset(CHAR);
?>