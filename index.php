<?php
include_once 'php/conn.php';
if (empty($_SESSION['uid'])) {
	header('location:login.html');
	exit;
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="component/layui/css/layui.css" />
	<link rel="stylesheet" href="admin/css/pearTab.css" />
	<link rel="stylesheet" href="admin/css/pearTheme.css" />
	<link rel="stylesheet" href="admin/css/pearLoad.css" />
	<link rel="stylesheet" href="admin/css/pearFrame.css" />
	<link rel="stylesheet" href="admin/css/pearAdmin.css" />
	<link rel="stylesheet" href="admin/css/pearNotice.css" />
	<link rel="stylesheet" href="admin/css/pearSocial.css" />
	<link rel="stylesheet" href="admin/css/pearMenu.css" />
	<style id="pearone-bg-color">
		span {
			color: #5FB878;
		}
	</style>
</head>

<body class="layui-layout-body pear-admin">
	<!-- 布局框架 -->
	<div class="layui-layout layui-layout-admin">
		<div class="layui-header">
			<ul class="layui-nav layui-layout-left">
				<li class="collaspe layui-nav-item"><a href="#" class="layui-icon layui-icon-shrink-right"></a></li>
				<li class="refresh layui-nav-item"><a href="#" class="layui-icon layui-icon-refresh-1"></a></li>
			</ul>
			<div id="control" class="layui-layout-control"></div>
			<ul class="layui-nav layui-layout-right">
				<li class="layui-nav-item layui-hide-xs"><a href="#" class="fullScreen layui-icon layui-icon-screen-full"></a></li>
				<li class="layui-nav-item layui-hide-xs" id="headerNotice"></li>
				<li class="layui-nav-item" lay-unselect="">
					<a href="javascript:;"><?php echo $_SESSION['username']; ?></a>
					<dl class="layui-nav-child">
						<dd><a href="javascript:;" class="pearson">个人信息</a></dd>
						<dd><a href="php/loginout.php">注销登陆</a></dd>
					</dl>
				</li>
				<!-- <li class="setting layui-nav-item"><a href="#" class="layui-icon layui-icon-more-vertical"></a></li> -->
			</ul>
		</div>
		<div class="layui-side layui-bg-black">
			<div class="layui-logo">
				<img class="logo" src="admin/images/logo.png" />
				<span class="title">图书管理系统</span>
			</div>
			<div class="layui-side-scroll">
				<div id="sideMenu"></div>
			</div>
		</div>
		<div class="layui-body">
			<div id="content"></div>
		</div>
	</div>

	<!-- 移动端 遮盖层 -->
	<div class="pear-cover"></div>

	<!-- 初始加载 动画-->
	<div class="preloader">
		<div class="preloader-inner"></div>
	</div>

	<script src="component/layui/layui.js"></script>
	<script>
		layui.use(['pearAdmin', 'jquery', 'pearTab', 'pearNotice'], function() {
			var pearAdmin = layui.pearAdmin;
			var $ = layui.jquery;
			var pearTab = layui.pearTab;
			var pearNotice = layui.pearNotice;
			var config = {
				keepLoad: false, // 主 页 加 载 过 度 时 长 可为 false
				muiltTab: true, // 是 否 开 启 多 标 签 页 true 开启 false 关闭
				control: false, // 是 否 开 启 多 系 统 菜 单 true 开启 false 关闭
				theme: "dark-theme", // 默 认 主 题 样 式 dark-theme 默认主题 light-theme 亮主题
				select: '0',
				index: 'view/console/console1.php', // 默 认 加 载 主 页
				data: 'php/getMenu.php', // 菜 单 数 据 加 载 地 址
			};
			// var option = {
			// 	elem: 'headerNotice',
			// 	url: 'admin/data/notice.json',
			// 	height: '200px',
			// 	click: function(id, title) {
			// 		layer.msg("当前监听消息" + id + "消息标题:" + title);
			// 	}
			// }

			pearAdmin.render(config);

			// pearNotice.render(option);

			$("body").on("click", ".pearson", function() {
				pearTab.addTabOnlyByElem("content", {
					id: 7,
					title: "我的信息",
					url: "view/person/person.html",
					close: true
				})
			})

		})
	</script>
</body>

</html>