<?
session_start();

class PieAuthentication {

	static function authenticate($groupOrGroups = false) {
		if (!PieAuthentication::signedIn()) {
			header('Location: /sign-in');
			exit;
		}
		if ($groupOrGroups && !PieAuthentication::inGroup($groupOrGroups)) {
			echo 'You are not authorized to view this page.';
			Page();
		}
	}
	
	static function signedIn() {
		return isset($_SESSION['user_id']);
	}
	
	static function inGroup($groupOrGroups) {
		if (is_array($groupOrGroups)) {
			while (list(, $group) = each($groupOrGroups)) {
				if (isset($_SESSION['groups'][$group])) {
					return true;
				}
			}
		}
		else {
			return isset($_SESSION['groups'][$groupOrGroups]);
		}
		return false;
	}
	
	static function signIn() {
	
		global $ERROR_MESSAGE, $CONFIRMATION_MESSAGE;
		
		if (isset($_REQUEST['username'])) {
			if ($user = PieCaching::fetchRow('users', array('username' => $_REQUEST['username']))) {
				if ($_REQUEST['password'] == $user['password']) {
					$_SESSION['user_id'] = $user['id'];
					$_SESSION['username'] = $user['username'];
					$_SESSION['groups'] = PieDatabase::fieldsArray('name, id FROM user_groups WHERE id IN (SELECT group_id FROM user_group_users WHERE user_id = ' . $user['id'].')');
					setcookie('username_cookie', $row['username'], time() + 31536000);
					header('Location: /');
					exit;
				}
				else {
					if (strtoupper($_REQUEST['password']) == $user['password']) {
						$ERROR_MESSAGE = 'Incorrect username or password.<br>Please make sure your CAPS lock is off.';
					}
					else {
						$ERROR_MESSAGE = 'Incorrect username or password.<br>Please try again.';
					}
				}
			}
			else {
				$ERROR_MESSAGE = 'Incorrect username or password.<br>Please try again.';
			}
		}
		
		if ($_REQUEST['email']) {
			$result = PieDatabase::select('username, password FROM users WHERE email = ' . PieDatabase::quote($_REQUEST['email']));
			$signIns = '';
			if ($row = Row($result)) {
				while ($row) {
					$signIns .= "UN: {$row['Username']}\r\nPW: {$row['Password']}\r\n\r\n";
					$row = Row($result);
				}
				mail($_REQUEST['email'], 'PriceTag from Pricing Intelligence', $signIns, "From: webmaster@{$_SERVER['SERVER_NAME']}\r\nReply-To: webmaster@{$_SERVER['SERVER_NAME']}\r\nX-Mailer: PHP/" . phpversion());
				$CONFIRMATION_MESSAGE = 'Your username and password have been sent to you at <b>' . $_REQUEST['email'] . '</b>.';
				$_REQUEST['forgot'] = '';
			} else {
				$ERROR_MESSAGE = 'There is no existing user with that email address.';
			}
		}
		
		if ($_REQUEST['forgot_password']) { ?>
			<table style="width:380px;margin:32px auto;" align="center"><tr><td>
			<form name="Form" action="<?=URL('', 1)?>" method="post" class="Form">
			<table>
			<tr class="Heading"><td>Request Your Password</td></tr>
			<tr class="Row1"><td><table>
				<tr><td class="M"><b>Email</b></td><td><input type="text" name="Email" value="" size="50" maxlength="64" style="width:300px;" /><img src="/img/spacer.gif" name="Form_Email" width="1" height="1" style="display:none;" /></td></tr>
			</table></td></tr>
			<tr class="Footer"><td><input type="submit" value="Send Password" class="Button" style="margin-left:120px;" onclick="return CheckForm(this.form)" /><input type="hidden" name="NumberOfChanges" value="0"></td></tr>
			</table>
			</form>
			</table><?
		}
	}
	
	static function signOut() {
		session_start();
		session_destroy();
		header('Location: /');
	}
	
}
?>