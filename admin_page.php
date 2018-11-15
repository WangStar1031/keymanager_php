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
	<h2>Joined Users
		<span class="btn btn-danger" style="margin-left: 20%; font-size: 15px; cursor: pointer;" data-toggle="modal" data-target="#authModal">Change Authentication</span>
		<span class="btn btn-danger" style="margin-left: 20px; font-size: 15px; cursor: pointer;" data-toggle="modal" data-target="#settingModal">Product Settings</span>
		<!-- <span class="btn btn-danger" style="margin-left: 20px; font-size: 15px; cursor: pointer;" data-toggle="modal" data-target="#paymentModal">Edit Payment</span> --></h2>
	<br>
	<!-- <button class="btn btn-primary" data-toggle="modal" data-target="#addNewModal">Add New</button> -->
	<br>
	<br>
	<table>
		<tr>
			<th>	</th>
			<!-- <th>First Name</th> -->
			<!-- <th>Last Name</th> -->
			<th>Email</th>
			<th>Product Name</th>
			<th>Token Code</th>
			<!-- <th>Refreshed Date</th> -->
			<th>Expiration Date</th>
			<!-- <th>Actions</th> -->
		</tr>	
	<?php
	$userNumber = 0;
	foreach ($userDatas as $data) {
		$userNumber++;
		$count = count($data->arrTokens);
		for( $i = 0; $i < $count; $i++){
			echo "<tr>";
			if( $i == 0){
	?>
		<td rowspan="<?=$count?>"><?=$userNumber?></td>
		<td class="eMail" rowspan="<?=$count?>"><?=$data->eMail?></td>
	<?php			
			}
			$value = $data->arrTokens[$i];
	?>
		<td class="product_name"><?=$value->product_name?></td>
		<td><?=$value->token?></td>
		<td><input type="date" name="date" value="<?=$value->expDate?>"></td>
		<td><button class="btn btn-success" onclick="onSave(this)">Save</button><button class="btn btn-danger" onclick="onRemove(this)">Remove</button></td>
	<?php
		}
	?>
	<?php
		echo "</tr>";
	}

	?>
	</table>
	<br>
	<!-- <button class="btn btn-primary" data-toggle="modal" data-target="#addNewModal">Add New</button> -->
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
<!-- Pass Change Modal -->
<div id="settingModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Product Settings</h4>
		</div>
		<div class="modal-body">
			<table>
				<tr>
					<th>Product Name</th>
					<th>Expiration Period</th>
					<th>Action</th>
				</tr>
			</table>
			<br>
			<button class="btn btn-primary" onclick="addNewProduct()">Add New</button>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-success" onclick="saveSettings()">Save</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div>

	</div>
</div><!-- Payment Modal Modal -->
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
	function onSave(_this){
		var curRow = $(_this).parent().parent();
		var eMail = curRow.find(".eMail").eq(0).text();
		var product_name = curRow.find(".product_name").eq(0).text();
		var expDate = curRow.find("input[name=date]").eq(0).val();
		$.post("./JRA/api_accountManager.php", {action:"save", eMail: eMail, product_name: product_name, expDate: expDate}, function(data){
			document.location.reload();
		})
	}
	function onRemove(_this){
		var curRow = $(_this).parent().parent();
		var eMail = curRow.find(".eMail").eq(0).text();
		var product_name = curRow.find(".product_name").eq(0).text();
		$.post("./JRA/api_accountManager.php", {action:"removeToken", eMail: eMail, product_name: product_name}, function(data){
			document.location.reload();
		})
	}
	function loadingProducts(){
		$.post("./JRA/api_accountManager.php", {action:"getProducts"}, function(data){
			if( !data) return;
			var products = JSON.parse(data);
			console.log(products);
			strHtml = "";
			// debugger;
			for( var i = 0; i < products.length; i++){
				var product = products[i];
				strHtml += '<tr>';
					strHtml += '<td><input type="text" name="product_name" value="' + product.product_name + '"></td>';
					strHtml += '<td>';
						strHtml += '<span>';
							strHtml += '<select>';
								for( var j = 1; j <= 12; j++){
									strHtml += '<option';
									if( j == product.expPeriod){
										strHtml += ' selected';
									}
									strHtml += '>' + j + '</option>';
								}
							strHtml += '</select>';
						strHtml += '</span>months';
					strHtml += '</td>';
					strHtml += '<td><button class="btn btn-success" onclick="deleteProduct(this)">Delete</button></td>';
				strHtml += '</tr>';
			}
			$("#settingModal table").append(strHtml);
		});
	}
	loadingProducts();
	function deleteProduct(_this){
		$(_this).parent().parent().remove();
	}
	function addNewProduct(){
		var strHtml = "";
		strHtml += '<tr>';
			strHtml += '<td><input type="text" name="product_name"></td>';
			strHtml += '<td>';
				strHtml += '<span>';
					strHtml += '<select>';
						for( var i = 1; i <= 12; i++){
							strHtml += '<option>' + i + '</option>';
						}
					strHtml += '</select>';
				strHtml += '</span>months';
			strHtml += '</td>';
			strHtml += '<td><button class="btn btn-success" onclick="deleteProduct(this)">Delete</button></td>';
		strHtml += '</tr>';
		$("#settingModal table").append(strHtml);
	}
	function saveSettings(){
		var products = $("#settingModal table tr");
		var arrProducts = [];
		for( var i = 1; i < products.length; i++){
			var curTr = products.eq(i);
			var product_name = curTr.find("td input").eq(0).val();
			if( !product_name)continue;
			var expPeriod = curTr.find("td select").eq(0).find(":selected").text();
			arrProducts.push({product_name: product_name, expPeriod: expPeriod});
		}
		// debugger;
		$.post("./JRA/api_accountManager.php", {action:"setProducts", data: arrProducts}, function(data){
			console.log(data);
			document.location.reload();
		});
	}
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