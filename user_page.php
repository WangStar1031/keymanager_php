<script src="JRA/assets/jquery-3.2.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="JRA/assets/admin_page.css?<?=time()?>">
<link rel="stylesheet" href="JRA/assets/bootstrap.min.css">
<script src="JRA/assets/bootstrap.min.js"></script>
<?php
session_start();
// $_SESSION['key_user'] = "";
require_once __DIR__ . "/JRA/userManager.php";
require_once __DIR__ . "/vendor/autoload.php";


if( isset($_POST['userName'])){
	$userName = $_POST['userName'];
	$userPass = "";
	if( isset($_POST)){
		$userPass = $_POST['userPass'];
	}
	if( userVerify($userName, $userPass)){
		$_SESSION['key_user'] = $userName;
	}
}
$key_user = "";
if( isset($_SESSION['key_user'])) $key_user = $_SESSION['key_user'];
if( $key_user != ""){
	if( isset($_POST['stripeToken'])){
		$stripeToken = $_POST['stripeToken'];
		if( isset($_SESSION['stripeToken'])){
			if($_SESSION['stripeToken'] != $_POST['stripeToken']){
				stripeBilling($key_user, $stripeToken);
			}
		} else{
			stripeBilling($key_user, $stripeToken);
		}
		$_SESSION['stripeToken'] = $stripeToken;
	}
	$myData = getUserInfoFromEmail($key_user);
	if( $myData == false){
		$_SESSION['key_user'] = "";
		exit();
	}
	require_once __DIR__ . "/JRA/makeKey.php";
	$stripeCode = getStripeKey();
?>
<style type="text/css">
	
</style>
<div class="col-lg-12">
	<div style="float: right;">
		<a href="user_logout.php">Logout</a>
	</div>
	<h2>My Data</h2>
	<div class="row">
		<div class="col-lg-6">
			<h3>Profile Data</h3>
			<table class="col-lg-12">
				<tr>
					<td>First Name</td>
					<td><?=$myData->firstName?></td>
				</tr>
				<tr>
					<td>Last Name</td>
					<td><?=$myData->lastName?></td>
				</tr>
				<tr>
					<td>Email</td>
					<td><?=$myData->eMail?></td>
				</tr>
				<tr>
					<td>Token</td>
					<td>
						<span><?=$myData->token?></span>
						<span>
							<div class="btn btn-success" style="line-height: 4px;padding: 8px 4px;">Renew</div>
						</span>
					</td>
				</tr>
				<tr>
					<td>Joined Date</td>
					<td><?=$myData->startedDate?></td>
				</tr>
				<tr>
					<td>Refreshed Date</td>
					<td><?=$myData->refreshedDate?></td>
				</tr>
				<tr>
					<td>Expiration Date</td>
					<td><?=$myData->expDate?></td>
				</tr>
			</table>
			<div class="row"></div>
			<!-- <div class="payment"> -->
			<h3>Subscription</h3>
			<div class="col-lg-12">
				<div class="btn btn-default btnPurchase" data-toggle="modal" data-target="#purchaseModal">PURCHASE</div>
			</div>
			<!-- </div> -->
		</div>
		<div class="col-lg-6">
			<h3>Billing History</h3>
			<table class="col-lg-12">
				<tr>
					<th>No</th>
					<th>Payment Date</th>
					<th>Payment Method</th>
					<th>Amount</th>
					<th>Expiration Date</th>
				</tr>
				<?php
				$rowIndex = 0;
				foreach ($myData->billingHistory as $bills) {
					$rowIndex++;
				?>
				<tr>
					<td><?=$rowIndex?></td>
					<td><?=$bills->payDate?></td>
					<td><?=$bills->payMethod?></td>
					<td><?=$bills->amount?></td>
					<td><?=$bills->expDate?></td>
				</tr>
				<?php
				}
				?>
			</table>
		</div>
	</div>
</div>
<style type="text/css">
	.purchaseModal{
		width: 400px !important;
	}
	.purchaseModal table label{
		color: black;
	}
	#payment-form{
		margin-bottom: 0px;
	}
	.purchaseModal .modal-title{
		color: #0079cb;
	}
</style>
<!-- Add New Modal -->
<div id="purchaseModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog purchaseModal">
		<div class="modal-content">
			<form method="post" id="payment-form">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title">Stripe</h3>
				</div>
				<div class="modal-body" style="background-color: #eff2f5;">
					<p class="payment-errors error_msg" style="display: none;"></p>
					<table style="width: 100%;">
						<tr>
							<td><label for="cardNumber">Card number : </label></td>
							<td><label for="expDate">Expires : </label></td>
						</tr>
						<tr>
							<td>
								<input type="text" name="cardNumber" data-inputmask-mask="9999 9999 9999 9999" value="">
							</td>
							<td>
								<input type="text" name="expDate" data-inputmask-alias="mm/yyyy" data-inputmask="'yearrange': { 'minyear': '@php echo date('Y');@endphp'}">
							</td>
						</tr>
						<tr>
							<td>
								<label>Card code : </label>
							</td>
						</tr>
						<tr>
							<td>
								<input type="text" name="cardCode" placeholder="CVC" data-inputmask-mask="999">
							</td>
						</tr>
					</table>
				</div>
				<div class="modal-footer" style="background-color: #eff2f5;">
					<div class="btn btn-primary" onclick="confirmClicked()" style="width: 100%;">CONFIRM</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
	var stripeCode = '<?=$stripeCode?>';
</script>
<script src="JRA/assets/user_page.js?<?=time()?>"></script>
<script type="text/javascript">

</script>
<?php
} else{
?>
<div class="auth_main">
	<div class="auth_block">
		<h3 style="font-size: 1.75em;text-align: center;">Sign in to Key manager</h3>
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
					<td><button>Log in</button></td>
					<td><a href="user_signup.php">Sign up</a></td>
				</tr>
			</table>
		</form>
	</div>
</div>
<?php
}
?>