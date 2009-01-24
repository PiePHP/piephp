<?
require $_SERVER['DOCUMENT_ROOT'] . '/../initialization/common.php';

if (isset($_REQUEST['username'])) {
	if ($user = PieCaching::fetchRow('Users', array('Username' => $_REQUEST['username']))) {
		if ($_REQUEST['password'] == $user['Password']) {
			$_SESSION['user_id'] = $user['id'];
			$_SESSION['username'] = $user['username'];
			$_SESSION['groups'] = FieldsArray('name, id FROM user_groups WHERE id IN (SELECT group_id FROM user_group_users WHERE user_id = ' . $user['id'].')');
			setcookie('username_cookie', $row['username'], time() + 31536000);
			header('Location: /');
			exit;
		}
		else {
			if (strtoupper($_REQUEST['password']) == $user['password']) {
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
		<th><? PieSay::say('Username'); ?></th>
		<td><input type="text" name="Username" value="<? echo $_COOKIE['UsernameCookie']; ?>" style="width:200px;"></td>
	</tr>
	<tr>
		<th><? PieSay::say('Password'); ?></th>
		<td><input type="password" name="Password" value="" style="width:200px;"></td>
	</tr>
	<tr>
		<th></th>
		<td><button type="submit"><? PieSay::say('Sign In'); ?></button></td>
	</tr>
</table>
</form>
<br clear="all">

<?
PieLayout::renderPage();
?>