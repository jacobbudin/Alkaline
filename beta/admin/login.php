<?php

require_once('./../config.php');
require_once(PATH . CLASSES . 'alkaline.php');
require_once(PATH . CLASSES . 'user.php');

$alkaline = new Alkaline;
$user = new User;

@$username = strip_tags($_POST['login_user']);
@$password = strip_tags($_POST['login_pass']);
@$remember = strip_tags($_POST['login_remember']);

if($remember == 1){ $remember = true; }

if($user->perm()){
	header('Location: ' . LOCATION . BASE . ADMIN . 'dashboard/');
	exit();
}

if(!empty($username) or !empty($password)){
	if($user->auth($username, $password, $remember)){
		header('Location: ' . LOCATION . BASE . ADMIN . 'dashboard/');
		exit();
	}
	else{
		$alkaline->addNotification('Your username or password is invalid. Please try again.', 'error');
	}
}


define('TITLE', 'Alkaline Login');
define('COLUMNS', '14');
define('WIDTH', '550');

require_once(PATH . ADMIN . 'includes/header.php');

?>

<div id="content" class="span-<?php echo COLUMNS - 2; ?> prepend-1 append-1 last">
	<h2>Login</h2>
	
	<?php $alkaline->viewNotification(); ?>
	
	<form input="" method="post">
	<table>
		<tr>
			<td style="width: 40%; font-weight: bold; text-align: right;">Username:</td>
			<td>
				<input type="text" name="login_user" />
			</td>
		</tr>
		<tr>
			<td style="font-weight: bold; text-align: right;">Password:</td>
			<td>
				<input type="password" name="login_pass" />
			</td>
		</tr>
		<tr>
			<td style="text-align: right;">
				<input type="checkbox" name="login_remember" value="1" checked="checked">
			</td>
			<td style="padding-top: .65em;">Remember me on this computer.</td>
		<tr>
			<td></td>
			<td>
				<input type="submit" value="Login" />
			</td>
		</tr>
	</table>
	</form>
</div>

<?php

require_once(PATH . ADMIN . 'includes/footer.php');

?>