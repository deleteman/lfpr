<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<META NAME="Description" CONTENT="Find open source projects hosted on Github that you'd like to contribute to, or publish your own and find help here!">
	<META NAME="Keywords" CONTENT="github, project, open source, pull request, contributing, programming">

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
	<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-39365402-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<!-- UserVoice JavaScript SDK (only needed once on a page) -->
<script>(function(){var uv=document.createElement('script');uv.type='text/javascript';uv.async=true;uv.src='//widget.uservoice.com/8topNa84Z8EH4uZJOeNVw.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(uv,s)})()</script>

<!-- A tab to launch the Classic Widget -->
<script>
UserVoice = window.UserVoice || [];
UserVoice.push(['showTab', 'classic_widget', {
  mode: 'full',
  primary_color: '#cc6d00',
  link_color: '#007dbf',
  default_mode: 'feedback',
  forum_id: 198730,
  tab_label: 'Feedback & Support',
  tab_color: '#cc6d00',
  tab_position: 'middle-right',
  tab_inverted: false
}]);
</script>
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
				<ul class="nav footer-nav">
					<li> 
						<a href="/">
							Looking for Pull Requests
						</a>
						<a href="https://twitter.com/lookingfor_pr" class="twitter-follow-button" data-show-count="false">Follow @lookingfor_pr</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
					</li>
					<li class="pull-right">
						<a href="http://www.dad.uy" target="_blank">
							<img id="logo-dad" src="/img/dadmonkey_group.png" alt="Powered by the DadMonkey Group" title="Powered by the DadMonkey Group" />
						</a>
						<a href="https://twitter.com/dad_monkey" class="twitter-follow-button" data-show-count="false">Follow @dad_monkey</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
					</li>
					<li class="pull-right">
						
					</li>
				</ul>
			</div>
		</div>
	</footer>
</body>
</html>
