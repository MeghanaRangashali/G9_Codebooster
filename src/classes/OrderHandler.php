<?php

require_once "Product.php";
class OrderHandler
{
    private $db;
    private $productHandler;

    public function __construct($db)
    {
        $this->db = $db;
        $this->productHandler = new Product($db);
    }

    public function processOrder($customerData, $cart)
    {
        if (empty($cart)) {
            throw new Exception("Cart is empty. Cannot process the order.");
        }

        $customerId = $_SESSION['customer_id'];
        if (!$customerId) {
            throw new Exception("User not logged in or customer ID missing.");
        }

        try {
            $this->db->query("START TRANSACTION");
            $this->db->execute();

            $orderId = $this->createOrder($customerId, $cart);
            $this->addOrderItems($orderId, $cart);

            $this->db->query("COMMIT");
            $this->db->execute();

            return $orderId;
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $this->db->execute();

            throw $e;
        }
    }

    private function createOrder($customerId, $cart)
    {
        $totalAmount = 0;

        foreach ($cart as $item) {
            $product = $this->productHandler->getProductById($item['product_id']);
            if (!$product) {
                throw new Exception("Product with ID {$item['product_id']} not found.");
            }
            $totalAmount += $product['price'] * $item['quantity'];
        }

        $this->db->query("INSERT INTO orders (customer_id, total_amount) VALUES (:customer_id, :total_amount)");
        $this->db->bind(':customer_id', $customerId);
        $this->db->bind(':total_amount', $totalAmount);
        $this->db->execute();

        $this->db->query("SELECT LAST_INSERT_ID() as id");
        $result = $this->db->single();

        return $result['id'];
    }

    private function addOrderItems($orderId, $cart)
    {
        foreach ($cart as $item) {
            $product = $this->productHandler->getProductById($item['product_id']);
            if (!$product) {
                throw new Exception("Product with ID {$item['product_id']} not found.");
            }

            $this->db->query("INSERT INTO order_items (order_id, product_id, quantity, price) 
                              VALUES (:order_id, :product_id, :quantity, :price)");
            $this->db->bind(':order_id', $orderId);
            $this->db->bind(':product_id', $item['product_id']);
            $this->db->bind(':quantity', $item['quantity']);
            $this->db->bind(':price', $product['price']);
            $this->db->execute();
        }
    }

    public function getOrdersByCustomerId()
    {
        $customerId = $_SESSION['customer_id'];
        if (!$customerId) {
            throw new Exception("User not logged in or customer ID missing.");
        }

        $this->db->query("SELECT o.order_id, o.order_date, o.total_amount, oi.product_id, oi.quantity, oi.price
                          FROM orders o
                          JOIN order_items oi ON o.order_id = oi.order_id
                          WHERE o.customer_id = :customer_id");
        $this->db->bind(':customer_id', $customerId);
        $orders = $this->db->resultSet();

        $groupedOrders = [];
        foreach ($orders as $order) {
            $groupedOrders[$order['order_id']]['order_date'] = $order['order_date'];
            $groupedOrders[$order['order_id']]['total_amount'] = $order['total_amount'];
            $groupedOrders[$order['order_id']]['items'][] = [
                'product_id' => $order['product_id'],
                'quantity' => $order['quantity'],
                'price' => $order['price'],
            ];
        }

        return $groupedOrders;
    }

    public function cancelOrder($orderId, $sevenDaysAgo)
    {
        $customerId = $_SESSION['customer_id'];
        if (!$customerId) {
            throw new Exception("User not logged in or customer ID missing.");
        }

        $this->db->query("SELECT order_date FROM orders WHERE order_id = :order_id AND customer_id = :customer_id");
        $this->db->bind(':order_id', $orderId);
        $this->db->bind(':customer_id', $customerId);
        $order = $this->db->single();

        if (!$order) {
            throw new Exception("Order not found or you do not have permission to cancel this order.");
        }

        if ($order['order_date'] < $sevenDaysAgo) {
            throw new Exception("Order cannot be canceled as it is older than 7 days.");
        }

        $this->db->query("DELETE FROM order_items WHERE order_id = :order_id");
        $this->db->bind(':order_id', $orderId);
        $this->db->execute();

        $this->db->query("DELETE FROM orders WHERE order_id = :order_id");
        $this->db->bind(':order_id', $orderId);
        $this->db->execute();
    }

    public function getOrderDetails($orderId)
    {
        $this->db->query("SELECT o.order_id, o.order_date, o.total_amount, c.first_name, c.last_name,
                      oi.product_id, p.name AS product_name, oi.quantity, oi.price
                      FROM orders o
                      JOIN customers c ON o.customer_id = c.customer_id
                      JOIN order_items oi ON o.order_id = oi.order_id
                      JOIN products p ON oi.product_id = p.product_id
                      WHERE o.order_id = :order_id");
        $this->db->bind(':order_id', $orderId);
        $result = $this->db->resultSet();

        if (empty($result)) {
            throw new Exception("Order not found");
        }

        $order = [
            'order_id' => $result[0]['order_id'],
            'order_date' => $result[0]['order_date'],
            'customer_name' => $result[0]['first_name'] . ' ' . $result[0]['last_name'],
            'total_amount' => $result[0]['total_amount'],
            'items' => []
        ];

        foreach ($result as $item) {
            $order['items'][] = [
                'product_name' => $item['product_name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ];
        }

        return $order;
    }
}
