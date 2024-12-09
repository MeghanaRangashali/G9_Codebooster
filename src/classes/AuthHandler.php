<?php

class AuthHandler
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    private function sanitizeInput($input)
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    private function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    private function validatePhone($phone)
    {
        // Ensure the phone number is digits only and has 10-15 digits
        return preg_match('/^[0-9]{10,15}$/', $phone);
    }

    public function signup($firstName, $lastName, $email, $password, $phone, $address)
    {
        // Sanitize inputs
        $firstName = $this->sanitizeInput($firstName);
        $lastName = $this->sanitizeInput($lastName);
        $email = $this->sanitizeInput($email);
        $phone = $this->sanitizeInput($phone);
        $address = $this->sanitizeInput($address);
        $password = trim($password);

        // Validate inputs
        if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($phone) || empty($address)) {
            return ['success' => false, 'message' => 'All fields are required.'];
        }
        if (!$this->validateEmail($email)) {
            return ['success' => false, 'message' => 'Invalid email format.'];
        }
        if (!$this->validatePhone($phone)) {
            return ['success' => false, 'message' => 'Invalid phone number. It must be 10-15 digits.'];
        }
        if (strlen($password) < 8) {
            return ['success' => false, 'message' => 'Password must be at least 8 characters long.'];
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert into the database
        $sql = "INSERT INTO customers (first_name, last_name, email, password, phone, address) 
                VALUES (:first_name, :last_name, :email, :password, :phone, :address)";
        $this->db->query($sql);
        $this->db->bind(':first_name', $firstName);
        $this->db->bind(':last_name', $lastName);
        $this->db->bind(':email', $email);
        $this->db->bind(':password', $hashedPassword);
        $this->db->bind(':phone', $phone);
        $this->db->bind(':address', $address);

        if ($this->db->execute()) {
            return ['success' => true, 'message' => 'Signup successful! Please login.'];
        } else {
            return ['success' => false, 'message' => 'Signup failed. Email may already be in use.'];
        }
    }

    public function login($email, $password)
    {
        $email = $this->sanitizeInput($email);
        $password = trim($password);

        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Email and password are required.'];
        }
        if (!$this->validateEmail($email)) {
            return ['success' => false, 'message' => 'Invalid email format.'];
        }

        $sql = "SELECT customer_id, first_name, password FROM customers WHERE email = :email";
        $this->db->query($sql);
        $this->db->bind(':email', $email);
        $result = $this->db->resultSet();

        if (!empty($result)) {
            $user = $result[0];
            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['customer_id'] = $user['customer_id'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['email'] = $email;
                return ['success' => true, 'message' => 'Login successful!'];
            } else {
                return ['success' => false, 'message' => 'Invalid password.'];
            }
        } else {
            return ['success' => false, 'message' => 'No account found with that email.'];
        }
    }
}