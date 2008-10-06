<?php
session_start();

class PieAuthentication {

	static function authenticate($groupOrGroups = false) {
		if (!SignedIn()) {
			header('Location: /sign-in');
			exit;
		}
		if ($groupOrGroups && !InGroup($groupOrGroups)) {
			echo 'You are not authorized to view this page.';
			Page();
		}
	}
	
	static function signedIn() {
		return isset($_SESSION['UserId']);
	}
	
	static function inGroup($groupOrGroups) {
		if (is_array($groupOrGroups)) {
			while (list(, $group) = each($groupOrGroups)) {
				if (isset($_SESSION['Groups'][$group])) {
					return true;
				}
			}
		}
		else {
			return isset($_SESSION['Groups'][$group]);
		}
		return false;
	}
	
	static function signIn() {
	
		global $ERROR_MESSAGE, $CONFIRMATION_MESSAGE;
		
		if ($_REQUEST['Email']) {
			$result = Select('Username, Password FROM Users WHERE Email = '.Quote($_REQUEST['Email']));
			$signIns = '';
			if ($row = Row($result)) {
				while ($row) {
					$signIns .= "UN: {$row['Username']}\r\nPW: {$row['Password']}\r\n\r\n";
					$row = Row($result);
				}
				mail($_REQUEST['Email'], 'PriceTag from Pricing Intelligence', $signIns, "From: webmaster@{$_SERVER['SERVER_NAME']}\r\nReply-To: webmaster@{$_SERVER['SERVER_NAME']}\r\nX-Mailer: PHP/".phpversion());
				$CONFIRMATION_MESSAGE = 'Your username and password have been sent to you at <b>'.$_REQUEST['Email'].'</b>.';
				$_REQUEST['Forgot'] = '';
			} else {
				$ERROR_MESSAGE = 'There is no existing user with that email address.';
			}
		}
		
		if ($_REQUEST['Forgot']) { ?>
			<script>
			function CheckForm(f) {
				return DisplayError(TryEmail(f.Email, 1, '"Email" is required.', ''));
			}
			</script>
			<table style="width:380px;margin:32px auto;" align="center"><tr><td>
			<form name="Form" action="<?=URL('', 1)?>" method="post" class="Form">
			<table>
			<tr class="Heading"><td>Request Your Password</td></tr>
			<tr class="Row1"><td><table>
				<tr><td class="M"><b>Email</b></td><td><input type="text" name="Email" value="" size="50" maxlength="64" style="width:300px;" /><img src="/_/img/spacer.gif" name="Form_Email" width="1" height="1" style="display:none;" /></td></tr>
			</table></td></tr>
			<tr class="Footer"><td><input type="submit" value="Send Password" class="Button" style="margin-left:120px;" onclick="return CheckForm(this.form)" /><input type="hidden" name="NumberOfChanges" value="0"></td></tr>
			</table>
			</form>
			</table><?php
		}
	}
	
}
?>