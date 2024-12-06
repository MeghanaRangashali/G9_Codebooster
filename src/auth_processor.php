<?php
require_once 'db_init.php';
require_once './classes/db.php';
require_once './classes/AuthHandler.php';

$db = new Database();
$authHandler = new AuthHandler($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();

    if (isset($_POST['signup'])) {
        $result = $authHandler->signup(
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'],
            $_POST['password'],
            phone: $_POST['phone'],
            address: $_POST['address']
        );
        header("Location: ../public/index.php");
        exit;
    }

    if (isset($_POST['login'])) {
        $result = $authHandler->login(
            $_POST['email'],
            $_POST['password']
        );
        if ($result['success']) {
            header("Location: ../public/index.php");
        } else {
            header("Location: ../public/index.php");
        }
        exit;
    }
}
