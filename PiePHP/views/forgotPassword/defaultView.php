<?php
if (is_ajax()) {
	?>
	<var title="action"><?= $url ?></var>
	<?php
}
if (!is_dialog()) {
	?>
	<form method="post" action="<?= $url ?>">
	<?php
}
?>
<fieldset class="forgot">
	<h2>Forgot Your Password?</h2>
	<p class="explaination">
		Enter your username and email address below to have your password
		reset and sent to the email address you have on file.
	</p>
	<div>
		<label>Username</label>
		<input type="text" name="username" maxlength="32" class="required username" value="" />
		<div class="advice">Please enter your username.</div>
	</div>
	<div>
		<label>Email</label>
		<input type="text" name="email" maxlength="32" class="required email" value="" />
		<div class="advice">Please enter your email.</div>
	</div>
	<div class="actions">
		<div>
			<button type="submit" class="main"><b>Reset Password</b></button>
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