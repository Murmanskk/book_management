<?php
/*
 * @Author: your name
 * @Date: 2020-06-21 17:35:15
 * @LastEditTime: 2020-06-30 13:30:06
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \books_management\view\book\cate_manage.php
 */
session_start();
if ($_SESSION['access'] != 1) {
    die(json_encode(array('code' => -1, 'msg' => '无权限')));
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>分类管理</title>
    <link href="../../component/layui/css/layui.css" rel="stylesheet" />
    <link href="../../admin/css/pearCommon.css" rel="stylesheet" />
</head>

<body class="pear-container">
    <blockquote class="layui-elem-quote">
        <div class="layui-inline" style='margin-left: 20px;'>
            <a class="layui-btn layui-btn-normal addCate_btn">添加分类</a>
        </div>
    </blockquote>
    <div class="layui-card">
        <div class="layui-card-body">
            <table id="cateList" lay-filter="cateList"></table>
        </div>
    </div>
</body>
<script type="text/html" id="cate-bar">
    <button class="pear-btn pear-btn-primary pear-btn-sm" lay-event="edit"><i class="layui-icon layui-icon-edit"></i></button>

    <button class="pear-btn pear-btn-danger pear-btn-sm" lay-event="remove"><i class="layui-icon layui-icon-delete"></i></button>
</script>
<script src="../../component/layui/layui.js"></script>
<script type="text/javascript">
    layui.use(['form', 'layer', 'table', 'laytpl'], function() {
        let table = layui.table;
        let form = layui.form;
        let $ = layui.jquery;
        //加载数据表格
        var tableIns = table.render({
            elem: '#cateList',
            url: '../../php/cateAPI.php?s=getCate',
            cellMinWidth: 80,
            page: true,
            skin: 'line',
            height: "full-125",
            limits: [20, 40, 80, 120],
            limit: 20,
            id: "cateList",
            cols: [
                [{
                        field: 'cate_id',
                        title: '分类ID',
                        align: "center",
                    },
                    {
                        field: 'cate_name',
                        title: '分类名',
                        align: "center",
                    },
                    {
                        field: 'total',
                        title: '书籍数量',
                        align: "center",
                        templet: function(d) {
                            if (d.total === null) {
                                return 0;
                            } else {
                                return d.total;
                            }
                        }
                    },
                    {
                        title: '操作',
                        fixed: "right",
                        align: "center",
                        toolbar: '#cate-bar'
                    }
                ]
            ]
        });


        table.on('tool(cateList)', function(obj) {
            if (obj.event === 'edit') {
                layer.prompt({
                    title: '将分类名“' + obj.data.cate_name + '”修改为：'
                }, function(val, index) {
                    layer.confirm('确定修改？', function() {
                        //do something
                        $.post('../../php/cateAPI.php?s=editCate', {
                            cateName: val,
                            cateId: obj.data.cate_id
                        }, function(res) {
                            res = JSON.parse(res);
                            if (res.code == 0) {
                                layer.msg(res.msg);
                                refresh();
                            } else {
                                layer.msg(res.msg);
                                refresh();
                            }
                        })
                        layer.close(index);
                    });
                });

            } else if (obj.event === 'remove') {
                layer.confirm('确定要删除该分类', {
                    icon: 3,
                    title: '提示'
                }, function(index) {
                    $.ajax({
                        url: '../../php/cateAPI.php?s=delCate&cate_id=' + obj.data.cate_id,
                        type: 'delete',
                        dataType: 'json',
                        success: function(result) {
                            if (result.code == 0) {
                                layer.msg(result.msg, {
                                    icon: 1,
                                    time: 1000
                                });
                                refresh();
                            } else {
                                layer.msg(result.msg, {
                                    icon: 2,
                                    time: 1000
                                });
                                refresh();
                            }
                        }
                    })
                });
            }
        });
        $(".addCate_btn").click(function() {
            layer.prompt({
                title: '请输入分类名'
            }, function(val, index) {
                layer.confirm('确定添加分类：‘' + val + '’?', function() {
                    //do something
                    $.post('../../php/cateAPI.php?s=addCate', {
                        cateName: val
                    }, function(res) {
                        res = JSON.parse(res);
                        if (res.code == 0) {
                            layer.msg(res.msg);
                            refresh();
                        } else {
                            layer.msg(res.msg);
                            refresh();
                        }
                    })
                    layer.close(index);
                });
            });
        });
        window.refresh = function() {
            table.reload('cateList');
        }
    });
</script>
</body>

</html>