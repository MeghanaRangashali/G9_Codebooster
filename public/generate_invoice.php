<?php
require_once '../src/libs/fpdf186/fpdf.php';
require_once '../src/db_init.php';
require_once '../src/classes/OrderHandler.php';
require_once '../src/classes/InvoiceGenerator.php';

if (!isset($_GET['order_id'])) {
    die("Order ID is required.");
}

$orderHandler = new OrderHandler(new Database());
$orderId = $_GET['order_id'];

try {
    $orderDetails = $orderHandler->getOrderDetails($orderId);
    $invoice = new InvoiceGenerator($orderDetails);
    $invoice->generateInvoice();
} catch (Exception $e) {
    die("Unable to generate invoice. Please contact support.");
}
