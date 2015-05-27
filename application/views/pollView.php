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

        <script src="<?php echo base_url() ?>/scripts/jquery-1.11.3.min.js"></script>
		
		<!-- Bootstrap -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

        <!-- Custom Styles -->
		<link rel="stylesheet" href="<?php echo base_url() ?>/css/styles.css">

        <!-- Bootstrap JS -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

        <!-- Angular -->
		<script src="<?php echo base_url() ?>/scripts/angular.js"></script>
		<script src="<?php echo base_url() ?>/scripts/angular-route.js"></script>

        <!-- Controllers and App -->
		<script src="<?php echo base_url() ?>/js/app.js"></script>
		<script src="<?php echo base_url() ?>/js/controllers.js"></script>
	</head>
	<body>
        <nav class="navbar navbar-default">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#/polls">Polls</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="#/about">About</a></li>
                </ul>
            </div>
        </nav>

		<div ng-view class="page"></div>

	</body>
</html>
