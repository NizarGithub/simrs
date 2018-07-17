<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Akuntansi - Account Login Page</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Admin Panel Template">
<meta name="author" content="Westilian: Kamrujaman Shohel">
<!-- styles -->
<link href="<?=base_url();?>material/css/bootstrap.css" rel="stylesheet">
<link href="<?=base_url();?>material/css/bootstrap-responsive.css" rel="stylesheet">
<link rel="stylesheet" href="<?=base_url();?>material/css/font-awesome.css">
<!--[if IE 7]>
            <link rel="stylesheet" href="css/font-awesome-ie7.min.css">
        <![endif]-->
<link href="<?=base_url();?>material/css/styles.css" rel="stylesheet">
<link href="<?=base_url();?>material/css/theme-wooden.css" rel="stylesheet">

<!--[if IE 7]>
            <link rel="stylesheet" type="text/css" href="css/ie/ie7.css" />
        <![endif]-->
<!--[if IE 8]>
            <link rel="stylesheet" type="text/css" href="css/ie/ie8.css" />
        <![endif]-->
<!--[if IE 9]>
            <link rel="stylesheet" type="text/css" href="css/ie/ie9.css" />
        <![endif]-->
<link href="<?=base_url();?>material/css/aristo-ui.css" rel="stylesheet">
<link href="<?=base_url();?>material/css/elfinder.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Dosis' rel='stylesheet' type='text/css'>
<!--fav and touch icons -->
<link rel="shortcut icon" href="<?=base_url();?>ico/favicon.ico">

<!--============j avascript===========-->
<script src="<?=base_url();?>material/js/jquery.js"></script>
<script src="<?=base_url();?>material/js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="<?=base_url();?>material/js/bootstrap.js"></script>
</head>
<body>
<div class="layout">
	<!-- Navbar================================================== -->
	<!-- <div class="navbar navbar-inverse top-nav">
		<div class="navbar-inner">
			<div class="container" style="background:#FFF;">		
				<center>
					<a class="" href="<?=base_url();?>"><img src="<?=base_url();?>external/jtech-logo.png" width="103" height="50" alt="Falgun"></a>
				</center>		
			</div>
		</div>
	</div> -->
	<div class="container">
		<form class="form-signin" method="post" action="<?=base_url();?>">
			<h3 class="form-signin-heading">Silahkan Login </h3>
			<div class="controls input-icon">
				<i class=" icon-user-md"></i>
				<input type="text" name="username" class="input-block-level" placeholder="Username anda">
			</div>
			<div class="controls input-icon">
				<i class=" icon-key"></i><input name="password" type="password" class="input-block-level" placeholder="Password anda">
			</div>
			<label class="checkbox">
			<input type="checkbox" value="remember-me"> Remember me </label>
			<button class="btn btn-success btn-block" type="submit">Sign in</button>

		</form>
	</div>
</div>
</body>
</html>