<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Booking Form</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			background-color: #f2f2f2;
			margin: 0;
			padding: 0;
		}
		
		h1 {
			text-align: center;
			color: #555;
			margin: 20px 0;
		}
		
		form {
			max-width: 600px;
			margin: 0 auto;
			background-color: #fff;
			padding: 20px;
			border-radius: 5px;
			box-shadow: 0px 0px 10px #888888;
		}
		
		label {
			display: block;
			margin-bottom: 10px;
			color: #666;
		}
		
		input[type="text"], input[type="email"], select, textarea {
			width: 100%;
			padding: 10px;
			border: 1px solid #ccc;
			border-radius: 3px;
			margin-bottom: 20px;
			box-sizing: border-box;
			font-size: 16px;
			font-family: Arial, sans-serif;
			color: #666;
		}
		
		input[type="date"], input[type="time"] {
			width: 48%;
			padding: 10px;
			border: 1px solid #ccc;
			border-radius: 3px;
			margin-bottom: 20px;
			box-sizing: border-box;
			font-size: 16px;
			font-family: Arial, sans-serif;
			color: #666;
		}
        .button{
            width: 100%;
			padding: 10px;
			border: 1px solid #ccc;
			border-radius: 3px;
			margin-bottom: 20px;
			box-sizing: border-box;
			font-size: 16px;
			font-family: Arial, sans-serif;
			color: black;
            background-color: goldenrod;
        }
		.button:hover{
            background-color: black;
            color: white;
            cursor: pointer;
        }
		select {
			width: 100%;
		}
		
		textarea {
			resize: vertical;
			min-height: 100px;
		}
		
		input[type="submit"] {
			background-color: #4CAF50;
			color: #fff;
			padding: 12px 20px;
			border: none;
			border-radius: 3px;
			cursor: pointer;
			font-size: 16px;
			font-family: Arial, sans-serif;
		}
		
		input[type="submit"]:hover {
			background-color: #3e8e41;
		}
		
		@media screen and (max-width: 600px) {
			form {
				padding: 10px;
			}
			
			input[type="date"], input[type="time"] {
				width: 100%;
			}
		}
	</style>
</head>
<body>
	<h1>Payment Form</h1>
	<form method="post"> <!--action="wedding_submit.php"--> 
		<label for="name">Name:</label>
		<input type="text" id="name" name="name" placeholder="Enter your full name" required>
		
		<label for="phone">Phone:</label>
		<input type="text" id="phone" name="phone" placeholder="Enter your phone number" required>

		<label for="price">Amount Payment:</label>
		<input type="text" id="phone" name="price" placeholder="Enter your the Amount" required>

        <input type="submit" value="pay now" name="pay now"class="button">

       <!--<input type="submit" value="submit" name="pay"class="button">-->
		
	    </form>
		<?php
    // Connect to database
//INCLUDE THE ACCESS TOKEN FILE
include 'accessToken.php';
date_default_timezone_set('Africa/Nairobi');
$processrequestUrl = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
$callbackurl = 'https://mydomain.com/path';
$passkey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
$BusinessShortCode = '174379';
$Timestamp = date('YmdHis');
// ENCRIPT  DATA TO GET PASSWORD
$Password = base64_encode($BusinessShortCode . $passkey . $Timestamp);
$phone = '254745418244';
$money = '1';
$PartyA = $phone;
$PartyB = '254705384322';
$AccountReference = 'MINISELLERS';
$TransactionDesc = 'stkpush test';
$Amount = $money;
$stkpushheader = ['Content-Type:application/json', 'Authorization:Bearer ' . $access_token];

//INITIATE CURL
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $processrequestUrl);
curl_setopt($curl, CURLOPT_HTTPHEADER, $stkpushheader); //setting custom header
$curl_post_data = array(
  //Fill in the request parameters with valid values
  'BusinessShortCode' => $BusinessShortCode,
  'Password' => $Password,
  'Timestamp' => $Timestamp,
  'TransactionType' => 'CustomerPayBillOnline',
  'Amount' => $Amount,
  'PartyA' => $PartyA,
  'PartyB' => $BusinessShortCode,
  'PhoneNumber' => $PartyA,
  'CallBackURL' => $callbackurl,
  'AccountReference' => $AccountReference,
  'TransactionDesc' => $TransactionDesc
);

$data_string = json_encode($curl_post_data);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
echo $curl_response = curl_exec($curl);
//ECHO  RESPONSE
$data = json_decode($curl_response);
$CheckoutRequestID = $data->CheckoutRequestID;
$ResponseCode = $data->ResponseCode;
if ($ResponseCode == "0") {
 // echo "The CheckoutRequestID for this transaction is : " . $CheckoutRequestID;
}