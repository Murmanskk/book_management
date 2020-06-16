<?php
/*
 * @Author: your name
 * @Date: 2020-06-16 00:17:31
 * @LastEditTime: 2020-06-16 00:21:06
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \Javascriptd:\wwwroot\books_management\php\loginout.php
 */ 
    session_start();
    session_destroy();
    header('location:../index.php');
?>
