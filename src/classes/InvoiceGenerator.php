<?php
require_once '../src/libs/fpdf186/fpdf.php';

class InvoiceGenerator extends FPDF
{
    private $orderDetails;

    public function __construct($orderDetails)
    {
        parent::__construct();
        $this->orderDetails = $orderDetails;
    }

    public function generateInvoice()
    {
        $this->AddPage();
        $this->Image('./assests/images/codebooster-logo.png', 10, 10, 30);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(40);
        $this->Cell(0, 10, 'Codebooster Grillers And Toasters', 0, 1, 'C');
        $this->SetFont('Arial', '', 12);
        $this->Cell(40);
        $this->Cell(0, 10, '299 Doon Valley Drive, Kitchener, ON', 0, 1, 'C');
        $this->Ln(20);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Invoice', 0, 1, 'C');
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 10, 'Order ID: ' . $this->orderDetails['order_id'], 0, 1);
        $this->Cell(0, 10, 'Date: ' . $this->orderDetails['order_date'], 0, 1);
        $this->Cell(0, 10, 'Customer: ' . $this->orderDetails['customer_name'], 0, 1);
        $this->Ln(10);
        $this->Cell(80, 10, 'Product', 1);
        $this->Cell(30, 10, 'Quantity', 1);
        $this->Cell(30, 10, 'Price', 1);
        $this->Cell(50, 10, 'Total', 1);
        $this->Ln();
        foreach ($this->orderDetails['items'] as $item) {
            $this->Cell(80, 10, $item['product_name'], 1);
            $this->Cell(30, 10, $item['quantity'], 1);
            $this->Cell(30, 10, '$' . number_format($item['price'], 2), 1);
            $this->Cell(50, 10, '$' . number_format($item['quantity'] * $item['price'], 2), 1);
            $this->Ln();
        }
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Total Amount: $' . number_format($this->orderDetails['total_amount'], 2), 0, 1, 'R');
        $this->Output('I', 'Invoice_Order_' . $this->orderDetails['order_id'] . '.pdf');
    }
}