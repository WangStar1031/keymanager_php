<?php

	require_once __DIR__ . "/makeKey.php";
	function reNewToken($_email){
		$_token = makeEncryptKey($_email);
		$fName = __DIR__ . "/logs/users/" . $_email;
		if( file_exists($fName)){
			$userInfo = json_decode(file_get_contents($fName));
			$userInfo->token = $_token;
			$userInfo->refreshedDate = date("Y-m-d");
			file_put_contents($fName, json_encode($userInfo));
			$retVal = new \stdClass;
			$retVal->token = $_token;
			$retVal->refreshedDate = $userInfo->refreshedDate;
			return $retVal;
		}
		return false;
	}
	function changeExpDate($_email, $_expDate){
		$dir = __DIR__ . "/logs/users/";
		$fName = $dir . $_email;
		if( file_exists($fName)){
			$userInfo = json_decode(file_get_contents($fName));
			$userInfo->expDate = $_expDate;
			file_put_contents($fName, json_encode($userInfo));
			return true;
		}
		return false;
	}
	function adminVerify($_userName, $_userPass){
		$fName = __DIR__ . "/logs/users/admin";
		if( !file_exists($fName)){
			$data = new \stdClass;
			$data->userName = "admin";
			$data->userPass = "admin";
			file_put_contents($fName, json_encode($data));
		}
		$contents = file_get_contents($fName);
		$userInfo = json_decode($contents);
		if( strcasecmp($_userName, $userInfo->userName) == 0){
			if( strcasecmp($_userPass, $userInfo->userPass) == 0){
				return true;
			}
		}
		return false;
	}
	function changeAdminPassword($_curPass, $_newPass){
		$fName = __DIR__ . "/logs/users/admin";
		if( !file_exists($fName)){
			$data = new \stdClass;
			$data->userName = "admin";
			$data->userPass = "admin";
			file_put_contents($fName, json_encode($data));
		}
		$contents = file_get_contents($fName);
		$userInfo = json_decode($contents);
		if( strcasecmp($_curPass, $userInfo->userPass) == 0){
			$userInfo->userPass = $_newPass;
			file_put_contents($fName, json_encode($userInfo));
			return true;
		}
		return false;

	}
	function userVerify($_userName, $_userPass){
		$lstUsers = getAllUsers();
		foreach ($lstUsers as $user) {
			if( strcasecmp($_userName, $user->eMail) == 0 && strcasecmp($_userPass, $user->userPass) == 0){
				return true;
			}
		}
		return false;
	}

	function registerUser($_firstName, $_lastName, $_eMail, $_password){
		$fName = __DIR__ . "/logs/users/" . $_eMail;
		if( file_exists($fName)){
			return "Already exist.";
		}
		$user = new \stdClass;
		$user->firstName = $_firstName;
		$user->lastName = $_lastName;
		$user->eMail = $_eMail;
		$user->userPass = $_password;
		$user->token = makeEncryptKey($_eMail);
		$user->startedDate = date("Y-m-d");
		$user->expDate = date("Y-m-d");
		$user->refreshedDate = date("Y-m-d");
		$user->billingHistory = [];
		file_put_contents(__DIR__ . "/logs/users/" . $_eMail, json_encode($user));
		$msg = "<!DOCTYPE html><html lang='en'><body><h1>Hello, <span style='font-weight:bolder;'> $_firstName! </span></h1><br><br> Thank you for joining our Token management system. <br> Your code is as follow. <br><br>$user->token</body></html>";
		$headers = "From: support@hpbots.com" . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		ini_set("SMTP","ssl://smtp-mail.outlook.com");
		ini_set("smtp_port","587");
		if( mail($_eMail, "Welcome to Token management system.", $msg, $headers)){
			echo "OK Sent.";
		} else{
			echo "Not Send.";
		}
		return true;
	}
	function getAllUsers(){
		$retVal = [];
		$dir = __DIR__ . "/logs/users/";
		$files = scandir($dir);
		foreach ($files as $value) {
			if( is_dir($value) || $value == "admin")
				continue;
			$fContents = file_get_contents($dir . $value);
			$data = json_decode($fContents);
			$retVal[] = $data;
		}
		return $retVal;
	}
	function getStripeKey(){
		$fName = __DIR__ . "/logs/users/admin";
		$contents = file_get_contents($fName);
		$userInfo = json_decode($contents);
		if( isset($userInfo->stripeKey))return $userInfo->stripeKey;
		return false;
	}
	function getStripeSecret(){
		$fName = __DIR__ . "/logs/users/admin";
		$contents = file_get_contents($fName);
		$userInfo = json_decode($contents);
		if( isset($userInfo->stripeSecret))return $userInfo->stripeSecret;
		return false;
	}
	function getUserInfoFromEmail($_email){
		$dir = __DIR__ . "/logs/users/";
		$fName = $dir . $_email;
		// echo($fName);
		if( file_exists($fName)){
			return json_decode(file_get_contents($fName));
		}
		return false;
	}
	function setUserInfo($_userInfo){
		$dir = __DIR__ . "/logs/users/";
		$fName = $dir . $_userInfo->eMail;
		file_put_contents($fName, json_encode($_userInfo));
	}
	function getUserInfoFromCode($_token){
		$dir = __DIR__ . "/logs/users/";
		$files = scandir($dir);
		foreach ($files as $value) {
			if( is_dir($value) || $value == "admin")
				continue;
			$fContents = file_get_contents($dir . $value);
			$data = json_decode($fContents);
			if( strcasecmp( $data->token, $_token) === 0)
				return $data;
		}
		return false;
	}
	function verifyAdminPassword($_curPass){
		$fName = __DIR__ . "/logs/users/admin";
		if( !file_exists($fName)){
			$data = new \stdClass;
			$data->userName = "admin";
			$data->userPass = "admin";
			file_put_contents($fName, json_encode($data));
		}
		$contents = file_get_contents($fName);
		$userInfo = json_decode($contents);
		if( strcasecmp($_curPass, $userInfo->userPass) == 0){
			return true;
		}
		return false;
	}
	function changeStripeSettings($_stripeKey, $_stripeSecret){
		$fName = __DIR__ . "/logs/users/admin";
		if( !file_exists($fName)){
			$data = new \stdClass;
			$data->userName = "admin";
			$data->userPass = "admin";
			file_put_contents($fName, json_encode($data));
		}
		$contents = file_get_contents($fName);
		$userInfo = json_decode($contents);
		$userInfo->stripeKey = $_stripeKey;
		$userInfo->stripeSecret = $_stripeSecret;
		file_put_contents($fName, json_encode($userInfo));
		return true;
	}
	function stripeBilling($_key_user, $_stripeToken){
	// \Stripe\Stripe::setApiKey("sk_test_4eC39HqLyjWDarjtT1zdp7dc");
		$_stripeSecret = getStripeSecret();
		\Stripe\Stripe::setApiKey($_stripeSecret);
		$charge = \Stripe\Charge::create([
			'card' => $_stripeToken,
			'amount' => 2000,
			'currency' => 'USD',
			// "source" => "tok_visa", // obtained with Stripe.js
			'description' => 'Add in wallet'
		]);
		if($charge['status'] == 'succeeded'){
			$userInfo = getUserInfoFromEmail($_key_user);
			$expDate = $userInfo->expDate;
			$date = date('Y-m-d', strtotime(date("Y-m-d", strtotime($expDate)) . " + 1 year"));
			$userInfo->expDate = $date;
			$billingHistory = $userInfo->billingHistory;
			$bill = new \stdClass;
			$bill->payDate = date("Y-m-d");
			$bill->payMethod = "Stripe";
			$bill->amount = 20.00;
			$bill->expDate = $date;
			$userInfo->billingHistory[] = $bill;
			// Write Here your Database insert logic.
			setUserInfo($userInfo);
		} else{
			// \Session::put('error', 'Money not add in wallet!!');
			// return view('subscription', ['email'=>$email, 'isActive'=>$billingData->isActive, 'errMsg'=>'* Money not add in wallet. *', 'expDate'=>$billingData->ExpirationDate]);
		}
		// print_r($_charge);
	}
?>