<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

	<style type="text/css">
		body {
			background: #d8d8d8;
		}

		#body {
			padding: 20px 36px;
			background: #fff;
			box-shadow: 0 0 4px 2px rgba(0, 0, 0, 0.09);
			border-radius: 10px;
			width: 450px;
			margin: 0 auto;
		}

		#form-login {
			display: flex;
			flex-wrap: wrap;
		}

		#form-login > input {
			width: 100%;
			margin: 10px 0 0 0;
		}

		#form-login > input[type=text],
		#form-login > input[type=password] {
			padding: 12px 20px;
			border: none;
			border-bottom: 1px solid #c7c7c7;
			border-radius: 5px;
			color: #555;
		}

		#form-login > input[type=text]:focus,
		#form-login > input[type=password]:focus {
			border: none;
			border-bottom: 1px solid rgb(0, 147, 255);
			outline: 0;
		}

		#form-login > input[type=submit] {
			padding: 12px 20px;
			border: none;
			background: rgb(0, 147, 255);
			color: #fff;
			border-radius: 8px;
			font-weight: bold;
			font-size: 15px;
			margin: 40px 0 0 0;
			cursor: pointer;
		}

		#form-login > input[type=submit]:hover {
			background: rgb(88, 47, 255);
		}

		#form-login > label {
			display: block;
			padding: 12px 0;
			font-size: 15px;
			color: #333;
		}
	</style>
</head>
<body>

<div id="container">
	<div id="body">
		<form id='form-login' action="#">
			<input id='user-name' type="text" placeholder="username">
			<input id='password' type="password" placeholder="password">
			<label id="er"></label>
			<input type="submit" value="Submit" onclick="doLogin()">
		</form>
	</div>
</div>

</body>
</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
	document.getElementById('form-login').addEventListener('submit', function(e){
		e.preventDefault();
	});

	function doLogin() {
		const payload = {
			user_name: document.getElementById('user-name').value,
			password: document.getElementById('password').value
		}

		actionLogin(payload);
	}

	function actionLogin(payload) {
		// const api = "<?php echo base_url(); ?>";
		const api = 'index.php/api/login';
		$.ajax({
			url: api,
			type: 'POST',
			data: payload,
			beforeSend: function() {

			},
			success: function(response) {
				const { data, status, message } = response;

				if (status) {
					document.getElementById('er').innerHTML = message;
				}

				document.getElementById('er').innerHTML = message;
			},
			error: function(e) {
				const { responseJSON } = e;
				const { message } = responseJSON;

				document.getElementById('er').innerHTML = message;	
			},
			complete: function() {

			} 
		})

	}
</script>