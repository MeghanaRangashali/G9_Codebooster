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

    public function signup($firstName, $lastName, $email, $password, $phone = null, $address = null)
    {
        $this->db->query("SELECT email FROM customers WHERE email = :email");
        $this->db->bind(':email', $email);
        $existingUser = $this->db->single();

        if ($existingUser) {
            return [
                'success' => false,
                'message' => 'A user with this email already exists. Please try another email id.'
            ];
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $this->db->query("INSERT INTO customers (first_name, last_name, email, password, phone, address) VALUES (:first_name, :last_name, :email, :password, :phone, :address)");
        $this->db->bind(':first_name', $firstName);
        $this->db->bind(':last_name', $lastName);
        $this->db->bind(':email', $email);
        $this->db->bind(':password', $hashedPassword);
        $this->db->bind(':phone', $phone);
        $this->db->bind(':address', $address);

        if ($this->db->execute()) {
            return [
                'success' => true,
                'message' => 'Signup successful! You can now log in.'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'An error occurred while creating your account. Please try again.'
            ];
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