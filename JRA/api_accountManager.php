<?php

$_action = "";
if( isset($_POST['action'])) $_action = $_POST['action'];
require_once __DIR__ . "/userManager.php";

switch ($_action) {
	case 'save':
		$eMail = "";
		if( isset($_POST['eMail'])) $eMail = $_POST['eMail'];
		$product_name = "";
		if( isset($_POST['product_name'])) $product_name = $_POST['product_name'];
		$expDate = "";
		if( isset($_POST['expDate'])) $expDate = $_POST['expDate'];
		if( $eMail != ""){
			updateExpDate($eMail, $product_name, $expDate);
		}
		break;
	case 'removeToken':
		$eMail = "";
		if( isset($_POST['eMail'])) $eMail = $_POST['eMail'];
		$product_name = "";
		if( isset($_POST['product_name'])) $product_name = $_POST['product_name'];
		if( $eMail != ""){
			removeToken($eMail, $product_name);
		}
		break;
	case 'getProducts':
		getAllProducts();
		break;
	case 'setProducts':
		$data = "";
		if( isset($_POST['data'])) $data = $_POST['data'];
		$products = json_encode($data);
		setAllProducts($products);
		break;
	case 'login':
		$_token = "";
		if( isset($_POST['token'])) $_token = $_POST['token'];
		$_productName = "";
		if( isset($_POST['productName'])) $_productName = $_POST['productName'];
		$_email = "";
		if( isset($_POST['eMail'])) $_email = $_POST['eMail'];
		if( $_token != ""){
			if(canLogin($_email, $_productName, $_token) == true){
				echo "login";
			} else{
				echo "not login";
			}
		}
		break;
	case 'logout':
		$_productName = "";
		if( isset($_POST['productName'])) $_productName = $_POST['productName'];
		$_email = "";
		if( isset($_POST['eMail'])) $_email = $_POST['eMail'];
		if( Logout($_email, $_productName))
			echo "loged out";
		break;
	case 'canUseProduct':
		$_productName = "";
		if( isset($_POST['productName'])) $_productName = $_POST['productName'];
		$_email = "";
		if( isset($_POST['eMail'])) $_email = $_POST['eMail'];
		canUseProduct( $_email, $_productName);
		break;
	case 'verifyToken':
		$_token = "";
		if( isset($_POST['token'])) $_token = $_POST['token'];
		if( $_token != ""){
			$userInfo = getUserInfoFromCode($_token);
			if( $userInfo != false){
				echo $userInfo->eMail;
			}
		}
		break;
	case 'renew':
		$_email = "";
		if( isset($_POST['email'])) $_email = $_POST['email'];
		$ret = reNewToken($_email);
		if( $ret != false) echo json_encode($ret);
		break;
	case 'update':
		$_email = "";
		if( isset($_POST['email'])) $_email = $_POST['email'];
		$_expDate = "";
		if( isset($_POST['expDate'])) $_expDate = $_POST['expDate'];
		if( changeExpDate($_email, $_expDate) == true){
			echo "YES";
		}
		break;
	case 'payment':
		$_curPass = "";
		if( isset($_POST['payPassword'])) $_curPass = $_POST['payPassword'];
		if( verifyAdminPassword($_curPass) == true){
			$_stripeKey = "";
			if( isset($_POST['stripeKey'])) $_stripeKey = $_POST['stripeKey'];
			$_stripeSecret = "";
			if( isset($_POST['stripeSecret'])) $_stripeSecret = $_POST['stripeSecret'];
			if( changeStripeSettings($_stripeKey, $_stripeSecret) == true){
				echo "YES";
			}
		}
		break;
	case 'changePass':
		$_curPass = "";
		if( isset($_POST['curPass'])) $_curPass = $_POST['curPass'];
		$_newPass = "";
		if( isset($_POST['newPass'])) $_newPass = $_POST['newPass'];
		if( changeAdminPassword($_curPass, $_newPass) == true){
			echo "YES";
		} else{
			echo "NO";
		}
		break;
	case 'remove':
		$_email = "";
		if( isset($_POST['email'])) $_email = $_POST['email'];
		unlink(__DIR__ . "/logs/users/" . $_email);
		break;
	case 'create':
		$_firstName = "";
		if( isset($_POST['firstName'])) $_firstName = $_POST['firstName'];
		$_lastName = "";
		if( isset($_POST['lastName'])) $_lastName = $_POST['lastName'];
		$_eMail = "";
		if( isset($_POST['eMail'])) $_eMail = $_POST['eMail'];
		$_pass = $_eMail;
		if( $_eMail != ""){
			if( registerUser( $_firstName, $_lastName, $_eMail, $_pass) === true){
				echo "YES";
			}
		}
		break;
	case 'refresh':
		$_curDate = date("Y-m-d");
		break;
}
?>