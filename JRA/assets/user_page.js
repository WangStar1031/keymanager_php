
	Stripe.setPublishableKey( stripeCode);
	function confirmClicked(){
		$("#payment-form").find(".payment-errors").hide();
		var expDate = $("input[name='expDate']").val();
		var arrDate = expDate.split("/");
		var exp_month = arrDate[0];
		var exp_year = arrDate[1];
		Stripe.card.createToken({
			number: $("input[name='cardNumber']").val(),
			cvc: $("input[name='cardCode']").val(),
			exp_month: exp_month,
			exp_year: exp_year
		}, stripeResponseHandler);
	}

	function stripeResponseHandler(status, response){
		var $form = $("#payment-form");
		console.log( status);
		console.log( response);
		if( response.error){
			$form.find(".payment-errors").html(response.error.message);
			$form.find(".payment-errors").show();
		}else{
			var token = response.id;
			$form.append($('<input type="hidden" name="stripeToken" />').val(token));
			$form.get(0).submit();
		}
	}