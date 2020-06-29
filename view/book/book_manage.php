<?php
session_start();
if ($_SESSION['access'] != 1) {
    die(json_encode(array('code' => -1, 'msg' => '无权限')));
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title></title>
    <link href="../../component/layui/css/layui.css" rel="stylesheet" />
    <link href="../../admin/css/pearCommon.css" rel="stylesheet" />
</head>

<body class="pear-container">
    <div class="layui-card">
        <div class="layui-card-body">
            <form class="layui-form" action="">
                <div class="layui-form-item layui-input-inline">
                    <label class="layui-form-label">图书名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="book_name" placeholder="" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item layui-input-inline">
                    <label class="layui-form-label">分类</label>
                    <div class="layui-input-inline">
                        <select name="cate" id="cateList">
                            <!-- 这里动态获取下拉框信息 -->
                            <option value="0">分类1</option>
                            <option value="1">分类2</option>
                            <option value="2">分类3</option>
                        </select>
                    </div>
                </div>
                <button class="pear-btn pear-btn-md pear-btn-primary" lay-submit lay-filter="book-query">
                    <i class="layui-icon layui-icon-search"></i>
                    查询
                </button>
                <button type="reset" class="pear-btn pear-btn-md">
                    <i class="layui-icon layui-icon-refresh"></i>
                    重置
                </button>
            </form>
        </div>
    </div>
    <div class="layui-card">
        <div class="layui-card-body">
            <table id="book-table" lay-filter="book-table"></table>
        </div>
    </div>
</body>

<script type="text/html" id="book-toolbar">
    <button class="pear-btn pear-btn-primary pear-btn-md" lay-event="add">
        <i class="layui-icon layui-icon-add-1"></i>
        新增
    </button>
    <button class="pear-btn pear-btn-danger pear-btn-md" lay-event="batchRemove">
        <i class="layui-icon layui-icon-delete"></i>
        删除
    </button>
</script>

<script type="text/html" id="role-bar">
    <button class="pear-btn pear-btn-primary pear-btn-sm" lay-event="edit"><i class="layui-icon layui-icon-edit"></i></button>
    <button class="pear-btn pear-btn-danger pear-btn-sm" lay-event="remove"><i class="layui-icon layui-icon-delete"></i></button>
</script>

<script type="text/html" id="book-enable">
    <input type="checkbox" name="book-enable" value="{{d.isbn}}" lay-skin="switch" lay-text="是|否" lay-filter="book-enable" {{d.book_status== 0 ? "checked" : ""}}>
</script>


<script src="../../component/layui/layui.js"></script>
<script>
    layui.use(['table', 'form', 'jquery'], function() {
        let table = layui.table;
        let form = layui.form;
        let $ = layui.jquery;
        $.post('../../php/cateAPI.php?s=getCate', function(res) {
            $('#cateList').empty();
            $('#cateList').append("<option value=''>请选择</option>");
            res = JSON.parse(res);
            for (var k in res.data) {
                $('#cateList').append("<option value='" + res.data[k].cate_id + "'>" +
                    res.data[k]
                    .cate_name +
                    "</option>");
            }
            form.render();
        })
        //定义表格内容
        let cols = [
            [{
                    type: 'checkbox'
                },
                {
                    title: 'ISBN',
                    field: 'isbn',
                    align: 'center',
                    width: 125
                },
                {
                    title: '图书名',
                    field: 'book_name',
                    align: 'center',
                    width: 180
                },
                {
                    title: '作者',
                    field: 'book_author',
                    align: 'center',
                    width: 80
                },
                {
                    title: '出版社',
                    field: 'publisher',
                    align: 'center',
                    width: 135
                },
                {
                    title: '分类',
                    field: 'cate_name',
                    align: 'center',
                    width: 100
                },
                {
                    title: '索书号',
                    field: 'call_num',
                    align: 'center',
                    width: 75
                },
                {
                    title: '价格',
                    align: 'center',
                    field: 'price',
                    width: 70
                },

                {
                    title: '数量',
                    align: 'center',
                    field: 'quantity',
                    width: 70,
                },
                {
                    title: '已借出',
                    align: 'center',
                    field: 'brrow_nums',
                    width: 75
                },
                {
                    title: '借阅次数',
                    align: 'center',
                    field: 'lend_times',
                    width: 85
                },
                {
                    title: '是否可借',
                    align: 'center',
                    field: 'book_status',
                    templet: '#book-enable',
                    width: 100
                },
                {
                    title: '操作',
                    align: 'center',
                    toolbar: '#role-bar',
                    width: 120
                }
            ]
        ]

        table.render({
            elem: '#book-table',
            url: '../../php/bookAPI.php?s=getBookinfo',
            page: true,
            skin: 'line',
            limits: [10, 20, 30],
            limit: 10,
            cols: cols,
            toolbar: '#book-toolbar',
            defaultToolbar: [{
                layEvent: 'refresh',
                icon: 'layui-icon-refresh',
            }, 'filter', 'print', 'exports']
        });

        table.on('tool(book-table)', function(obj) {
            if (obj.event === 'remove') {
                window.remove(obj);
            } else if (obj.event === 'edit') {
                edit(obj);
            }
        });

        table.on('toolbar(book-table)', function(obj) {
            if (obj.event === 'add') {
                window.add();
            } else if (obj.event === 'refresh') {
                window.refresh();
            } else if (obj.event === 'batchRemove') {
                window.batchRemove(obj);
            }
        });

        form.on('submit(book-query)', function(data) {
            console.log(data.field);

            table.reload('book-table', {
                where: data.field,
                page: {
                    curr: 1
                } //重新从第 1 页开始
            })
            return false;
        });

        //添加图书
        window.add = function() {
            var index = layer.open({
                type: 2,
                title: '新增',
                shade: 0.1,
                area: ['300px', '450px'],
                content: 'add.html'
            });
        }
        //修改图书
        function edit(edit) {
            var index = layui.layer.open({
                type: 2,
                title: '修改',
                shade: 0.1,
                area: ['300px', '450px'],
                content: 'edit.html',
                success: function(layero, index) {
                    var body = layer.getChildFrame('body', index);
                    var iframeWin = window[layero.find('iframe')[0]['name']];
                    if (edit) {
                        data = edit.data;
                        body.find(".isbn").val(data.isbn);
                        body.find(".book_name").val(data.book_name);
                        body.find(".book_author").val(data.book_author);
                        body.find(".publisher").val(data.publisher);
                        body.find(".price").val(data.price);
                        body.find(".quantity").val(data.quantity);
                        form.render();
                    }
                    setTimeout(function() {
                        layui.layer.tips('点击此处返回图书列表',
                            '.layui-layer-setwin .layui-layer-close', {
                                tips: 3
                            });
                    }, 500)
                }

            });
        }
        //修改状态
        form.on('switch(book-enable)', function(obj) {
            status = obj.elem.checked == false ? 0 : 1;
            console.log('11');

            $.getJSON('../../php/bookAPI.php?s=updateStatus&isbn=' + obj.value + '&status=' + status,
                function(json) {
                    console.log(json);
                    if (json.code != 0) {
                        layer.open({
                            title: '提示',
                            icon: 7,
                            content: json.msg
                        });
                        obj.elem.checked = !obj.elem.checked;
                        form.render('checkbox');
                    } else {
                        if (obj.elem.checked == 1) {
                            layer.msg('该书已设置成可借', {
                                time: 1000
                            });
                        } else {
                            layer.msg('该书已设置成不可借', {
                                time: 1000
                            });
                        }
                    }

                })
        })
        //删除图书
        window.remove = function(obj) {
            layer.confirm('确定要删除该图书', {
                icon: 3,
                title: '提示'
            }, function(index) {
                layer.close(index);
                let loading = layer.load();
                $.ajax({
                    url: '',
                    dataType: 'json',
                    type: 'delete',
                    success: function(result) {
                        layer.close(loading);
                        if (result.success) {
                            layer.msg(result.msg, {
                                icon: 1,
                                time: 1000
                            }, function() {
                                obj.del();
                            });
                        } else {
                            layer.msg(result.msg, {
                                icon: 2,
                                time: 1000
                            });
                        }
                    }
                })
            });
        }

        window.batchRemove = function(obj) {
            let data = table.checkStatus(obj.config.id).data;
            if (data.length === 0) {
                layer.msg("未选中数据", {
                    icon: 3,
                    time: 1000
                });
                return false;
            }
            let ids = "";
            for (let i = 0; i < data.length; i++) {
                ids += data[i].userId + ",";
            }
            ids = ids.substr(0, ids.length - 1);
            layer.confirm('确定要删除这些书籍', {
                icon: 3,
                title: '提示'
            }, function(index) {
                layer.close(index);
                let loading = layer.load();
                $.ajax({
                    url: '',
                    dataType: 'json',
                    type: 'delete',
                    success: function(result) {
                        layer.close(loading);
                        if (result.success) {
                            layer.msg(result.msg, {
                                icon: 1,
                                time: 1000
                            }, function() {
                                table.reload('user-table');
                            });
                        } else {
                            layer.msg(result.msg, {
                                icon: 2,
                                time: 1000
                            });
                        }
                    }
                })
            });
        }

        window.refresh = function() {
            table.reload('book-table');
        }
    })
</script>
</body>

</html>