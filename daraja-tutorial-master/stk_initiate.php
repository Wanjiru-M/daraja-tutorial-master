<?php
if(isset($_POST['submit'])) {
    date_default_timezone_set('Africa/Nairobi');

    $consumerKey = 'EQkGT5KsLjA5zkifYGjXuqlJ2f0uqTaaPEG8pNq2DxxVUIjB'; //Fill with your app Consumer Key
    $consumerSecret = 'xSpiCarQRVaM7tsvpLTVysxPxBAKCXWusAxifG47jb3RYe1QAcoE8ID6tC9YHEx5'; // Fill with your app Secret

    $BusinessShortCode = '174379';
    $Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';  

    $PartyA = $_POST['phone']; // This is your phone number, 
    $AccountReference = '2255';
    $TransactionDesc = 'Test Payment';
    $Amount = $_POST['amount'];

    $Timestamp = date('YmdHis');

    $Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp);

    $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $initiate_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

    $CallBackURL = ' https://4954-197-237-246-224.ngrok-free.app'; // Update this with your callback URL

    $curl = curl_init($access_token_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authorization: Basic '.base64_encode($consumerKey.':'.$consumerSecret)]);
    $result = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $access_token = json_decode($result)->access_token;

    $stkheader = ['Content-Type:application/json', 'Authorization:Bearer '.$access_token];

    $curl = curl_init($initiate_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader);

    $curl_post_data = [
        "BusinessShortCode" => $BusinessShortCode,
        "Password" => $Password,
        "Timestamp" => $Timestamp,
        "TransactionType" => "CustomerPayBillOnline",
        "Amount" => $Amount,
        "PartyA" => $PartyA,
        "PartyB" => $BusinessShortCode,
        "PhoneNumber" => $PartyA,
        "CallBackURL" => $CallBackURL,
        "AccountReference" => $AccountReference,
        "TransactionDesc" => $TransactionDesc
    ];

    $data_string = json_encode($curl_post_data);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

    $curl_response = curl_exec($curl);
    echo $curl_response;

    curl_close($curl);
}
?>

