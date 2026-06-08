<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();


require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Connect/connecDB.php';
require_once __DIR__ . '/config-google.php';

echo "<pre>";
echo "CODE = " . $_GET['code'] . "\n\n";
echo "REDIRECT = " . $client->getRedirectUri() . "\n\n";

$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

if (!isset($token['access_token'])) {
    die("Không lấy được access token");
}

$client->setAccessToken($token['access_token']);

$oauth = new Google_Service_Oauth2($client);

$googleUser = $oauth->userinfo->get();

$name = $googleUser->name;
$email = $googleUser->email;

$stmt = $conn->prepare(
    "SELECT * FROM nguoidung WHERE email = ?"
);

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $user = $result->fetch_assoc();
} else {

    $stmt = $conn->prepare(
        "INSERT INTO nguoidung
        (ten,email,cccd,sdt,ngay_sinh,mat_khau)
        VALUES (?, ?, NULL, NULL, NULL, NULL)"
    );

    $stmt->bind_param("ss", $name, $email);
    $stmt->execute();

    $user = [
        'id_user' => $conn->insert_id,
        'ten' => $name,
        'email' => $email
    ];
}

$_SESSION['user'] = [
    'id' => $user['id_user'],
    'name' => $user['ten'],
    'email' => $user['email'],
    'role' => 'user',
    'type' => 'google'
];

header("Location: ../Pages/trangChu.php");
exit;
