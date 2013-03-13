<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>LFPR: Looking for Pull Requests</title>
	<script type="text/javascript" src="/javascripts/jquery-1.8.2.min.js" ></script>
	<script type="text/javascript" src="/javascripts/jquery-ui-1.8.23.custom.min.js" ></script>
	<script type="text/javascript" src="/javascripts/jquery.ui.datepicker-es.js" ></script>
	<script type="text/javascript" src="/javascripts/jquery.timePicker.js" ></script>
	<script type="text/javascript" src="/javascripts/bootstrap.js" ></script>
	<script type="text/javascript" src="/javascripts/application.js" ></script>
	<script type="text/javascript" src="/javascripts/jquery.dropkick-1.0.0.js" ></script>
	<link rel="stylesheet" href="/stylesheets/bootstrap.css" type="text/css" />
	<link rel="stylesheet" href="/stylesheets/flat-ui.css" type="text/css" />
	<link rel="stylesheet" href="/stylesheets/styles.css" type="text/css" />
	<link rel="stylesheet" href="/stylesheets/timePicker.css" type="text/css" />
	<link rel="stylesheet" href="/stylesheets/ui-lightness/jquery-ui-1.8.23.custom.css" type="text/css" />
</head>
<body>
	<div class="container">
		
		<div id="main-wrapper">
		<?php
		$flash_msg = $this->flash->getSuccess();
		if($flash_msg != "") {
		?>
			<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<h4>Success!</h4> <?=$flash_msg?>
			</div>
		<?php
		}

		$flash_error_msg = $this->flash->getError();
		if($flash_error_msg != "") {
		?>
			<div class="alert alert-error">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<h4>Error!</h4> <?=$flash_error_msg?>
			</div>
		<?php
		}
		?>
		<div class="row">
			<?= $this->renderView(); ?>
		</div>
		</div>
	</div>
	
	<footer>
		<div class="navbar navbar-inverse ">
			<div class="navbar-inner">
				<ul class="nav">
					<li> 
						<a href="/">
							Looking for Pull Requests
						</a>
					</li>
				</ul>
			</div>
		</div>
	</footer>
</body>
</html>
