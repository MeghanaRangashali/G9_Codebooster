<?php

class AccountHandler
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getUserProfile($customerId)
    {
        $this->db->query("SELECT first_name, last_name, email, phone, address FROM customers WHERE customer_id = :customer_id");
        $this->db->bind(':customer_id', $customerId);
        return $this->db->single();
    }

    public function updateUserProfile($customerId, $data)
    {
        $this->db->query("UPDATE customers SET first_name = :first_name, last_name = :last_name, email = :email, phone = :phone, address = :address WHERE customer_id = :customer_id");
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':customer_id', $customerId);
        return $this->db->execute();
    }
}