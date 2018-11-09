
	function btnSaveClicked(_this){
		var curRow = $(_this).parent().parent().parent();
		var email = curRow.find(".eMail").html();
		var expDate = curRow.find("input[name=expDate]").val();
		$.post("JRA/api_accountManager.php", {action:"update", email:email, expDate:expDate}, function(data){

		});
	}
	function btnRemoveClicked(_this){
		var curRow = $(_this).parent().parent().parent();
		var email = curRow.find(".eMail").html();
		if( confirm("Are you sure remove this account?") == true){
			$.post("JRA/api_accountManager.php", {action:"remove", email:email}, function(date){
				document.location.reload();
			});
		}
	}
	function btnRefreshClicked(_this){
		var curRow = $(_this).parent().parent().parent();
		var email = curRow.find(".eMail").html();
		$.post("JRA/api_accountManager.php", {action:"renew", email: email}, function(data){
			if( data){
				var retVal = JSON.parse(data);
				curRow.find(".token").html(retVal.token);
				curRow.find(".refreshedDate").html(retVal.refreshedDate);
			}
			// document.location.reload();
		});
	}
	function btnShowClicked(_this){
		var curRow = $(_this).parent().parent().parent().parent();
		curRow.find(".token").toggleClass("HideItem");
		if( $(_this).html() == "Show"){
			$(_this).html("Hide");
		} else{
			$(_this).html("Show");
		}
	}

	function savePaymentSettings(){
		debugger;
		var stripeKey = $("#paymentModal input[name=payStripeKey]").val();
		var stripeSecret = $("#paymentModal input[name=payStripeSecret]").val();
		var payPassword = $("#paymentModal input[name=payPassword]").val();
		if( stripeKey == "" || stripeSecret == "" || payPassword == ""){
			$("#paymentModal p.errorMsg").removeClass("DispItem");
			return;
		}
		$("#paymentModal p.errorMsg").addClass("DispItem");
		$.post("JRA/api_accountManager.php", {action:"payment", stripeKey:stripeKey, stripeSecret: stripeSecret, payPassword: payPassword}, function(data){
			if( data == "YES"){
				// $("#paymentModal p.errorMsg").removeClass("DispItem");
				document.location.reload();
			} else{
				$("#paymentModal p.errorMsg").removeClass("DispItem");
			}
		});
	}
	function addNewCustomer(){
		var firstName = $("#addNewModal input[name=firstName]").val();
		var lastName = $("#addNewModal input[name=lastName]").val();
		var eMail = $("#addNewModal input[name=eMail]").val();
		if( firstName == "" || lastName == "" || eMail == "" ){
			$("#addNewModal p.errorMsg").removeClass("DispItem");
			return;
		}
		$("#addNewModal p.errorMsg").addClass("DispItem");
		$.post("JRA/api_accountManager.php", {action:"create", firstName: firstName, lastName: lastName, eMail: eMail}, function(data){
			if( data == "YES"){
				$("#addNewModal").modal("hide");
				document.location.reload();
			} else{
				$("#addNewModal p.errorMsg").removeClass("DispItem");
			}
		});
	}
	function saveAuthentication(){
		var curPass = $("#authModal input[name=curPass]").val();
		var newPass = $("#authModal input[name=newPass]").val();
		var conPass = $("#authModal input[name=conPass]").val();
		if( curPass == "" || newPass == "" || newPass != conPass){
			$("#authModal p.errorMsg").removeClass("DispItem");
			return;
		}
		$("#authModal p.errorMsg").addClass("DispItem");
		$.post("JRA/api_accountManager.php", {action:"changePass", curPass:curPass, newPass: newPass}, function(data){
			if( data == "YES"){
				$("#authModal").modal("hide");
			} else{
				$("#authModal p.errorMsg").removeClass("DispItem");
			}
		});
	}