<?php
$customerKey = "000000";
$apiKey = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";

/**
 * https://www.miniorange.com/step-by-step-guide-to-set-up-otp-verification
 * 
 * https://login.xecurify.com/moas/login
 * apiKey && customerKey get in Settings after registration
 */


$_GET['phone'] = $_GET['phone'] ?? '';
$_GET['txId'] = $_GET['txId'] ?? '';
$_GET['token'] = $_GET['token'] ?? '';

function send()
{
    global $customerKey;
    global $apiKey;
    $generateUrl = "https://login.xecurify.com/moas/api/auth/challenge";
    $currentTimeInMillis = round(microtime(true) * 1000);
    $stringToHash = $customerKey . number_format($currentTimeInMillis, 0, '', '') . $apiKey;
    $hashValue = hash("sha512", $stringToHash);
    $jsonRequest = array(
        "customerKey" => $customerKey,
        "phone" => $_GET['phone'],
        "authType" => "SMS",
        "transactionName" => "CUSTOM-OTP-VERIFICATION"
    );
    $jsonRequestString = json_encode($jsonRequest);
    $customerKeyHeader = "Customer-Key: " . $customerKey;
    $timestampHeader = "Timestamp: " . number_format($currentTimeInMillis, 0, '', '');
    $authorizationHeader = "Authorization: " . $hashValue;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json", $customerKeyHeader, $timestampHeader, $authorizationHeader]);
    curl_setopt($ch, CURLOPT_URL, $generateUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonRequestString);
    curl_setopt($ch, CURLOPT_POST, 1);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        print curl_error($ch);
    } else {
        curl_close($ch);
    }
    $response = (array)json_decode($result);
    return $response;
}


function check()
{
    global $customerKey;
    global $apiKey;
    $validateUrl = "https://login.xecurify.com/moas/api/auth/validate";
    $currentTimeInMillis = round(microtime(true) * 1000);
    $stringToHash = $customerKey . number_format($currentTimeInMillis, 0, '', '') . $apiKey;
    $hashValue = hash("sha512", $stringToHash);
    $jsonRequest = [
        'txId' => $_GET['txId'],
        'token' => $_GET['token']
    ];
    $jsonRequestString = json_encode($jsonRequest);
    $customerKeyHeader = "Customer-Key: " . $customerKey;
    $timestampHeader = "Timestamp: " . number_format($currentTimeInMillis, 0, '', '');
    $authorizationHeader = "Authorization: " . $hashValue;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json", $customerKeyHeader, $timestampHeader, $authorizationHeader]);
    curl_setopt($ch, CURLOPT_URL, $validateUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonRequestString);
    curl_setopt($ch, CURLOPT_POST, 1);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        print curl_error($ch);
    } else {
        curl_close($ch);
    }
    $response = (array)json_decode($result);
    return $response;
}

$r = [];
if ($_GET['phone']) $r = send();
elseif ($_GET['txId'] && $_GET['token']) $r = check();
header('Content-Type: application/json');
print_r(json_encode($r));
die;
