<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/classes/db.php';

$db = new Database();
$db->query(file_get_contents(__DIR__ . '/../database/queries.sql'));
$db->execute();
