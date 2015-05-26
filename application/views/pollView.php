<!doctype html>
<!--
	An Angular JS app for viewing polls
	Created for SENG365 Assignment 2
	in 2015
 -->
<html lang="en" ng-app="pollsApp">
	<head>
		<meta charset="utf-8">
		<title>Polls</title>
		
		<!-- Bootstrap -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css"> 
		<link rel="stylesheet" href="<?php echo base_url() ?>/css/styles.css">
		 
		<script src="<?php echo base_url() ?>/scripts/angular.js"></script>
		<script src="<?php echo base_url() ?>/scripts/angular-route.js"></script>
		<script src="<?php echo base_url() ?>/js/app.js"></script>
		<script src="<?php echo base_url() ?>/js/controllers.js"></script>
	</head>
	<body>

		<div ng-view class="page"></div>

	</body>
</html>
