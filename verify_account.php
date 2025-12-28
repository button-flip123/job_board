<?php

$user_token = $_GET['token'];

require_once("includes/db.php");
require_once("includes/mail.php");

$sql = $conn->prepare("SELECT * FROM `users` WHERE `email_verification_token` = :token AND `email_verified` = 0 AND `email_verification_expires` > NOW()");

$sql->bindParam(":token", $user_token);
$sql->execute();
$user = $sql->fetch(PDO::FETCH_ASSOC);

if(!empty($user)) {
    $sql = $conn->prepare("UPDATE `users` SET `email_verification_token` = NULL, `email_verified` = 1, `email_verification_expires` = NULL WHERE `id` = :user_id");
    $sql->bindParam(":user_id", $user["id"]);
    $sql->execute();

    echo "Uspjesno verifikovan";

    header("Location: login.php");
    exit();
}
else{
    echo "token nevazeci, ubi se";
    header("Location: login.php");
    exit();
}