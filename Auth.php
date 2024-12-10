<?php
session_start();

class Auth {
    public static function isLoggedIn() {
        return isset($_SESSION['email']);
    }

    public static function isAdmin() {
      return $_SESSION['isAdmin'];
    }

    public static function login($email) {
        $_SESSION['email'] = $email;
    }

    public static function logout() {
        unset($_SESSION['email']);
    }

    public static function getUserEmail() {
        return $_SESSION['email'] ?? null;
    }
}
?>
