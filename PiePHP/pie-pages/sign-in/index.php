<?php
require $_SERVER['DOCUMENT_ROOT'].'/../app-init/common.php';

if (isset($_REQUEST['Username'])) {
	if ($User = PieCaching::fetchRow('Users', array('Username' => $_REQUEST['Username']))) {
		if ($_REQUEST['Password'] == $User['Password']) {
			$_SESSION['UserId'] = $User['Id'];
			$_SESSION['Username'] = $User['Username'];
			$_SESSION['Groups'] = FieldsArray('Name, Id FROM UserGroups WHERE Id IN (SELECT GroupId FROM UserGroupUsers WHERE UserId = '.$User['Id'].')');
			setcookie('UsernameCookie', $Row['Username'], time() + 31536000);
			header('Location: /');
			exit;
		}
		else {
			if (strtoupper($_REQUEST['Password']) == $User['Password']) {
				$ERROR_MESSAGE = PieSay::get('Incorrect username or password.') . '<br>' . PieSay::get('Make sure your CAPS lock is off.');
			}
			else {
				$ERROR_MESSAGE = PieSay::get('Incorrect username or password.');
			}
		}
	}
	else {
		$ERROR_MESSAGE = PieSay::get('Incorrect username or password.');
	}
}

?>

<p><?=$ERROR_MESSAGE?></p>

<form method="post" action="/sign-in/" class="box" style="width:320px;">
<table>
	<tr>
		<th><?php PieSay::say('Username'); ?></th>
		<td><input type="text" name="Username" value="<?php echo $_COOKIE['UsernameCookie']; ?>" style="width:200px;"></td>
	</tr>
	<tr>
		<th><?php PieSay::say('Password'); ?></th>
		<td><input type="password" name="Password" value="" style="width:200px;"></td>
	</tr>
	<tr>
		<th></th>
		<td><button type="submit"><?php PieSay::say('Sign In'); ?></button></td>
	</tr>
</table>
</form>
<br clear="all">

<?
PieLayout::renderPage();
?>