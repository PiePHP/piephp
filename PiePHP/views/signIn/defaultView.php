<?php
if (is_ajax()) {
	?>
	<var title="action"><?php echo $HTTPS_ROOT; ?>sign_in/</var>
	<?php
}
if (!is_dialog()) {
	?>
	<form method="post" action="<?php echo $HTTPS_ROOT; ?>sign_in/">
	<?php
}
?>
<fieldset>
<h2>Sign in</h2>
<div>
	<label>Username</label>
	<input type="text" name="username" maxlength="32" class="required username" value="">
	<div class="advice">Please enter your username.</div>
</div>
<div>
	<label>Password</label>
	<input type="password" name="password" class="required password" value="">
	<div class="advice">Please enter your password</div>
</div>
<div>
	<label></label>
	<fieldset>
		<label class="option">
			<input type="checkbox" name="keepSignedIn">Keep me signed in
		</label>
	</fieldset>
</div>
<div class="actions">
	<div>
		<button type="submit" class="main"><b>Sign In</b></button>
	</div>
</div>
</fieldset>
<?php
if (!is_dialog()) {
	?>
	</form>
	<?php
}
?>