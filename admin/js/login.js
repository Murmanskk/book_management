/*
 * @Author: your name
 * @Date: 2020-06-15 00:20:53
 * @LastEditTime: 2020-06-16 00:20:16
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \Javascriptd:\wwwroot\books_management\js\login.js
 */
layui.use(['table', 'form', 'jquery'], function () {
    layer.ready(function name(params) {
        $ = layui.jquery;
        const inputs = document.querySelectorAll(".input");

        function focusFunction() {
            let parentNode = this.parentNode.parentNode;
            parentNode.classList.add('focus');
        }

        function blurFunction() {
            let parentNode = this.parentNode.parentNode;
            if (this.value == '') {
                parentNode.classList.remove('focus');
            }
        }

        inputs.forEach(input => {
            input.addEventListener('focus', focusFunction);
            input.addEventListener('blur', blurFunction);
        });
        function login() {
            let username = $('#username').val();
            let password = $('#password').val();
            if (username == '') {
                layer.msg('用户名不能为空');
                return;
            }
            if (password == '') {
                layer.msg('密码不能为空');
                return;
            }
            $.ajax({
                method: "post",
                url: "php/dologin.php",
                data: {
                    username,
                    password,
                },
                success: function (result) {
                    console.log(result);
                    obj = JSON.parse(result);
                    if (obj.code != 0) {
                        layer.msg(obj.msg);
                    } else {
                        layer.msg('登录成功！', {
                            shift: -1,
                            time: 1000
                        }, function () {
                            document.location.href = "index.php";
                        });

                    }
                },
                error: function () {
                    alert(msg);
                }
            })
        }
        //登录按钮
        $('#login').on('click',function () {
            login();
        })

        $(document).keyup(function (event) {
            if (event.keyCode == 13) {
                login();
            }
        });
    })
})