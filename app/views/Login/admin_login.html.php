<h1>Admin login screen</h1>
<div class="well">
	<?=form_tag(login_admin_login_path(), "post")?>
		<?=text_field_tag("username", "username", null, array("placeholder" => "Username"))?>
		<?=password_field_tag("password", "pwd", array("placeholder" => "Password"))?>
		<?=submit("Login", array("class" => "btn btn-primary"))?>
	<?=form_end_tag()?>
</div>