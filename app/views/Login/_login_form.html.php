<?= form_tag(login_sign_in_path(), "post") ?>
	<legend>Login into the system</legend>
	<label for="email">E-mail</label>
	<input type="text" name="email" placeholder="Your e-mail">
	<br>
	<label for="pwd">Clave</label>
	<input type="password" name="pwd" placeholder="Your password here!">
	<br>
	<input type="submit" class="btn btn-primary" value="Log in">
<?= form_end_tag();?>
