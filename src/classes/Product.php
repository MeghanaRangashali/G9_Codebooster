<?php

class Product
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllProducts()
    {
        $sql = "SELECT * FROM products";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getProductsByCategory($category)
    {
        $sql = "SELECT * FROM products WHERE category = :category";
        $this->db->query($sql);
        $this->db->bind(':category', $category);
        return $this->db->resultSet();
    }

    public function getAllCategories()
    {
        $sql = "SELECT DISTINCT category FROM products";
        $this->db->query($sql);
        return array_column($this->db->resultSet(), 'category');
    }

    public function getProductById($productId)
    {
        $sql = "SELECT * FROM products WHERE product_id = :product_id";
        $this->db->query($sql);
        $this->db->bind(':product_id', $productId);
        return $this->db->single();
    }
}
