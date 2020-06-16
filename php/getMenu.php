<?php
/*
 * @Author: your name
 * @Date: 2020-06-16 00:23:37
 * @LastEditTime: 2020-06-16 11:53:46
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \Javascriptd:\wwwroot\books_management\php\getMenu.php
 */ 
    include_once 'conn.php';
    header("Content-Type: application/json; charset=UTF-8");
    if(empty($_SESSION['access'])){
        header('location:login.html');
        exit;
    }
    $menu_ids_sql = "SELECT menu_ids FROM access WHERE id = '{$_SESSION['access']}'";
    $menu_ids_reslut = $mySQLi->query($menu_ids_sql);
    // echo $mySQLi->error;
    $menu_ids = $menu_ids_reslut->fetch_array(MYSQLI_NUM);
    //echo $menu_ids[0];
    $menus_sql = "SELECT * FROM menu where id in ({$menu_ids[0]})";
    $menus_reslut = $mySQLi->query($menus_sql);
    $menus = $menus_reslut->fetch_all(MYSQLI_ASSOC);
    echo json_encode($menus);
?>