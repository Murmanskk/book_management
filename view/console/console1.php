<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>首页三</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href="../../admin/css/pearCommon.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../component/layui/css/layui.css" media="all">
	<style>
		.top-panel {
			border-radius: 4px;
			text-align: center;
		}

		.top-panel>.layui-card-body {
			height: 60px;
		}

		.top-panel-number {
			line-height: 60px;
			font-size: 20px;
			border-right: 1px solid #eceff9;
		}

		.top-panel-tips {
			padding-left: 8px;
			padding-top: 16px;
			line-height: 30px;
			font-size: 12px;
		}

		.pear-container {
			background-color: whitesmoke;
			margin: 10px;
		}

		.card {
			width: 100%;
			height: 160px;
			background-color: whitesmoke;
			border-radius: 4px;
		}

		.card .header .avatar {
			width: 28px;
			height: 28px;
			margin: 20px;
			border-radius: 50px;
		}

		.card .header {
			color: dimgray;
		}

		.card .body {
			color: gray;
		}

		.card .body {
			margin-left: 20px;
			margin-right: 20px;
		}

		.card .footer {
			margin-left: 20px;
			margin-right: 20px;
			margin-top: 20px;
			font-size: 13px;
			color: gray;
			position: absolute;
		}

		.list .list-item {
			height: 33px;
			line-height: 33px;
			color: gray;
			padding: 5px;
			padding-left: 15px;
			border-radius: 4px;
			margin-top: 5.2px;
		}

		.custom-tab .layui-tab-title {
			border-bottom-width: 0px;
			border-bottom-style: none;
		}

		.custom-tab .layui-tab-title li {
			margin-left: 10px;
		}

		.list .list-item:hover {
			background-color: whitesmoke;
		}

		.list .list-item .title {
			font-size: 13px;
			width: 100%;
		}

		.list .list-item .footer {
			position: absolute;
			right: 30px;
			font-size: 12px;
		}

		.top-panel-tips i {
			font-size: 33px;
		}

		.layuiadmin-card-status {
			padding: 0 10px 10px;
		}

		.layuiadmin-card-status dd {
			padding: 15px 0;
			border-bottom: 1px solid #EEE;
			display: -webkit-flex;
			display: flex;
		}

		.layuiadmin-card-status dd div.layui-status-img,
		.layuiadmin-card-team .layui-team-img {
			width: 32px;
			height: 32px;
			border-radius: 50%;
			background-color: #009688;
			margin-right: 15px;
		}

		.layuiadmin-card-status dd div.layui-status-img a {
			width: 100%;
			height: 100%;
			display: inline-block;
			text-align: center;
			line-height: 32px;
		}

		.layuiadmin-card-status dd div span {
			color: #BBB;
		}

		.layuiadmin-card-status dd div a {
			color: #01AAED;
		}

		.person h1 {
			margin: 30px;
			font-weight: bold;
		}

		.person h2 {
			margin: 30px;
		}

		.person button {
			margin: 0 auto;
		}
	</style>
</head>

<body class="pear-container">
	<div>
		<div class="layui-row layui-col-space10">
			<div class="layui-col-md4">
				<div class="layui-card">
					<div class="layui-card-body person" style="height: 260px; text-align:center">

						<h1>欢迎你！ <span style="color:#5FB878;"><?php echo $_SESSION['username']; ?></span></h1>
						<h2>手机号：</h2>
						<h2>邮箱：</h2>
						<button type="" class="pear-btn pear-btn-primary">修改信息</button>
					</div>

				</div>
			</div>
			<div class="layui-col-xs12 layui-col-md8">
				<div class="layui-carousel" id="test1">
					<div carousel-item>
						<div><img src="https://s1.ax1x.com/2020/05/27/tFjgYQ.jpg" alt="" style="width:100%"></div>
						<div><img src="https://s2.ax1x.com/2019/09/22/upXHYT.jpg" alt="" style="width:100%"></div>
						<div><img src="https://s2.ax1x.com/2019/09/22/upOKbt.jpg" alt="" style="width:100%"></div>
					</div>
				</div>
			</div>
			<div class="layui-col-xs6 layui-col-md4">
				<div class="layui-card top-panel">
					<div class="layui-card-header">系统公告</div>
					<div class="layui-card-body">
					</div>
				</div>
			</div>
			<div class="layui-col-xs6 layui-col-md4">
				<div class="layui-card top-panel">
					<div class="layui-card-header">馆藏图书</div>
					<div class="layui-card-body">
						<div class="layui-row layui-col-space5">
							<div class="layui-col-xs8 layui-col-md8 top-panel-number">
								6,34,4册
							</div>
							<div class="layui-col-xs4 layui-col-md4 top-panel-tips">
								<i class="layui-icon layui-icon-component" style="color: #DD4A68;"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="layui-col-xs6 layui-col-md4">
				<div class="layui-card top-panel">
					<div class="layui-card-header">可借图书</div>
					<div class="layui-card-body">
						<div class="layui-row layui-col-space5">
							<div class="layui-col-xs8 layui-col-md8 top-panel-number">
								1,34,1册
							</div>
							<div class="layui-col-xs4 layui-col-md4  top-panel-tips">
								<i class="layui-icon layui-icon-ok" style="color: #5FB878;"></i>
							</div>
						</div>
					</div>
				</div>
			</div>


		</div>
		<!--</div>-->
		<script src="../../component/layui/layui.js" charset="utf-8"></script>
		<script>
			layui.use(['layer', 'element', 'carousel'], function() {
				var $ = layui.jquery,
					layer = layui.layer;
				var carousel = layui.carousel;
				//建造实例
				carousel.render({
					elem: '#test1',
					width: '100%',
					arrow: 'hover' //始终显示箭头
					//,anim: 'updown' //切换动画方式
				});

			});
		</script>
</body>

</html>