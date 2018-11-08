<link rel="stylesheet" type="text/css" href="assets/vendor/bootstrap/css/bootstrap.min.css">
<script src="assets/vendor/jquery/jquery-3.2.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="JRA/assets/admin_page.css?<?=time()?>">
<?php
$firstName = "";
if( isset($_POST['firstName'])) $firstName = $_POST['firstName'];
$lastName = "";
if( isset($_POST['lastName'])) $lastName = $_POST['lastName'];
$eMail = "";
if( isset($_POST['eMail'])) $eMail = $_POST['eMail'];
$password = "";
if( isset($_POST['password'])) $password = $_POST['password'];
require_once __DIR__ . "/JRA/userManager.php";
$errorCode = "";
if( $firstName != ""){
	$errorCode = registerUser($firstName, $lastName, $eMail, $password);
	if( $errorCode === true){
		header("Location: user_page.php");
	}
}

?>
<div class="auth_main">
	<div class="auth_block">
		<h3 style="font-size: 1.75em;text-align: center;">Sign up to Key manager</h3>
		<p <?php if($errorCode=="")echo "style='display:none;'" ?>><?= $errorCode?></p>
		<form method="post" onsubmit="return SubmitForm();">
			<table>
				<tr>
					<td><label for="firstName">First Name</label></td>
					<td><input class="form_control" type="text" name="firstName"></td>
				</tr>
				<tr>
					<td><label for="lastName">Last Name</label></td>
					<td><input class="form_control" type="text" name="lastName"></td>
				</tr>
				<tr>
					<td><label for="email">Email</label></td>
					<td><input class="form_control" type="text" name="eMail"></td>
				</tr>
				<tr>
					<td><label for="password">Password</label></td>
					<td><input class="form_control" type="password" name="password"></td>
				</tr>
				<tr>
					<td><label for="password">Confirm Password</label></td>
					<td><input class="form_control" type="password" name="confirm_password"></td>
				</tr>
				<tr>
					<td><button>Sign up</button></td>
					<td><a href="user_page.php">Log in</a></td>
				</tr>
			</table>
		</form>
	</div>
</div>
<script type="text/javascript">
	function SubmitForm(){
		if( $("input[name=firstName]").val() == "")return false;
		if( $("input[name=lastName]").val() == "")return false;
		if( $("input[name=eMail]").val() == "")return false;
		if( $("input[name=password]").val() == "")return false;
		if( $("input[name=confirm_password]").val() == "")return false;
		if( $("input[name=password]").val() != $("input[name=confirm_password]").val())return false;
		return true;
	}
</script>