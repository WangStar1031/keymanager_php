<script src="JRA/assets/jquery-3.2.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="JRA/assets/admin_page.css?<?=time()?>">
<link rel="stylesheet" href="JRA/assets/bootstrap.min.css">
<script src="JRA/assets/bootstrap.min.js"></script>

<?php
session_start();
// $_SESSION['key_admin'] = "";

require_once __DIR__ . "/JRA/userManager.php";
if( isset($_POST['userName'])){
	$userName = $_POST['userName'];
	$userPass = "";
	if( isset($_POST)){
		$userPass = $_POST['userPass'];
	}
	if( adminVerify($userName, $userPass)){
		$_SESSION['key_admin'] = $userName;
	}
}
$key_admin = "";
if( isset($_SESSION['key_admin'])) $key_admin = $_SESSION['key_admin'];
if( $key_admin != ""){
$userDatas = getAllUsers();
require_once __DIR__ . "/JRA/makeKey.php";
?>
<style type="text/css">
	
	td{ border: 1px solid #ccc; }
	.button-group button{ padding: 8px 2px; }
	.HideItem{ visibility: hidden; }
	td input{ border: none; width: 100%; }
</style>
<div class="col-lg-12">
	<div style="float: right;">
		<a href="admin_logout.php">Logout</a>
	</div>
	<h2>Joined Users<span class="btn btn-danger" style="margin-left: 20%; font-size: 15px; cursor: pointer;" data-toggle="modal" data-target="#authModal">Change Authentication</span><span class="btn btn-danger" style="margin-left: 20px; font-size: 15px; cursor: pointer;" data-toggle="modal" data-target="#paymentModal">Edit Payment</span></h2>
	<br>
	<button class="btn btn-primary" data-toggle="modal" data-target="#addNewModal">Add New</button>
	<br>
	<br>
	<table>
		<tr>
			<th>	</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Email</th>
			<th>Token</th>
			<th>Joined Date</th>
			<th>Refreshed Date</th>
			<th>Expiration Date</th>
			<th>Actions</th>
		</tr>	
	<?php
	$i = 0;
	foreach ($userDatas as $data) {
		echo "<tr>";
		$i++;
	?>
		<td><?=$i?></td>
		<td class="fName"><?=$data->firstName?></td>
		<td class="lName"><?=$data->lastName?></td>
		<td class="eMail"><?=$data->eMail?></td>
		<td>
			<div class="token HideItem" style="float: left;"><?=$data->token?></div>
			<span style="float: right;">
				<div class="button-group">
					<button class="btn btn-primary showBtn" onclick="btnShowClicked(this)" style="line-height: 2px">Show</button>
				</div>
			</span>
		</td>
		<td><?=$data->startedDate?></td>
		<td><?=$data->refreshedDate?></td>
		<td><input type="date" name="expDate" value="<?=$data->expDate?>"></td>
		<td style="text-align: center;">
			<div class="button-group">
				<button class="btn btn-success saveBtn" onclick="btnSaveClicked(this)" style="line-height: 2px">Save</button>
				<button class="btn btn-success refreshBtn" onclick="btnRefreshClicked(this)" style="line-height: 2px">Renew</button>
				<button class="btn btn-success removeBtn" onclick="btnRemoveClicked(this)" style="line-height: 2px">Remove</button>
			</div>
		</td>
	<?php
		echo "</tr>";
	}

	?>
	</table>
	<br>
	<button class="btn btn-primary" data-toggle="modal" data-target="#addNewModal">Add New</button>
</div>

<!-- Pass Change Modal -->
<div id="authModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Admin Authentication</h4>
		</div>
		<div class="modal-body">
			<p class="DispItem errorMsg" style="color: red;">Invalid parameters.</p>
			<table>
				<tr>
					<td>Current Password</td>
					<td><input type="password" name="curPass"></td>
				</tr>
				<tr>
					<td>New Passsword</td>
					<td><input type="password" name="newPass"></td>
				</tr>
				<tr>
					<td>Confirm Passsword</td>
					<td><input type="password" name="conPass"></td>
				</tr>
			</table>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-success" onclick="saveAuthentication()">Save</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div>

	</div>
</div>

<!-- Payment Modal Modal -->
<div id="paymentModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Edit Payment Method</h4>
		</div>
		<div class="modal-body">
			<p class="DispItem errorMsg" style="color: red;">Invalid parameters.</p>
			<h4>Stripe</h4>
			<table>
				<tr>
					<td>Enter Password</td>
					<td><input type="password" name="payPassword"></td>
				</tr>
				<tr>
					<td>Stripe Key</td>
					<td><input type="text" name="payStripeKey"></td>
				</tr>
				<tr>
					<td>Stripe Secret</td>
					<td><input type="text" name="payStripeSecret"></td>
				</tr>
			</table>
			<!-- <h4>Paypal</h4>
			<table>
				<tr>
					<td>API USERNAME</td>
					<td><input type="text" name="payStripeKey"></td>
				</tr>
				<tr>
					<td>API PASSWORD</td>
					<td><input type="text" name="payStripeSecret"></td>
				</tr>
				<tr>
					<td>API SIGNATURE</td>
					<td><input type="text" name="payStripeSecret"></td>
				</tr>
			</table> -->
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-success" onclick="savePaymentSettings()">Save</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div>

	</div>
</div>
<!-- Add New Modal -->
<div id="addNewModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Add New Customer</h4>
		</div>
		<div class="modal-body">
			<p class="DispItem errorMsg" style="color: red;">Invalid parameters.</p>
			<table>
				<tr>
					<td>First Name</td>
					<td><input type="text" name="firstName"></td>
				</tr>
				<tr>
					<td>Last Name</td>
					<td><input type="text" name="lastName"></td>
				</tr>
				<tr>
					<td>Email Address</td>
					<td><input type="text" name="eMail"></td>
				</tr>
			</table>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-success" onclick="addNewCustomer()">Save</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div>

	</div>
</div>

<script type="text/javascript">

</script>
<script src="JRA/assets/admin_page.js?<?=time()?>"></script>

<?php
} else{
?>
<div class="auth_main">
	<div class="auth_block">
		<h3 style="font-size: 1.75em;text-align: center;">Sign in to Key manager (admin)</h3>
		<form method="post" class="login">
			<table>
				<tr>
					<td><label for="userName">User Name</label></td>
					<td><input class="form_control" type="text" name="userName"></td>
				</tr>
				<tr>
					<td><label for="userPass">Password</label></td>
					<td><input class="form_control" type="password" name="userPass"></td>
				</tr>
				<tr>
					<td><button style="color: white;">Log in</button></td>
				</tr>
			</table>
		</form>
	</div>
</div>
<?php
}
?>