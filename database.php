<?php
class Database {
    private static $db_host = 'localhost';
    private static $db_user = 'root';
    private static $db_pass = '';
    private static $db_name = 'user_auth';

    private static function db_connect() {
        $conn = new mysqli(self::$db_host, self::$db_user, self::$db_pass, self::$db_name);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

    public static function authenticate_user($username, $password) {
        $conn = self::db_connect();
        $stmt = $conn->prepare("SELECT id, password, is_admin FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password, $is_admin);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                return ['id' => $id, 'is_admin' => $is_admin];
            }
        }
        $stmt->close();
        $conn->close();
        return false;
    }

    public static function get_users() {
        $conn = self::db_connect();
        $result = $conn->query("SELECT id, username FROM users");
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        $conn->close();
        return $users;
    }

    public static function update_user($user_id, $username, $password) {
        $conn = self::db_connect();
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
        $stmt->bind_param("ssi", $username, $hashed_password, $user_id);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }

    public static function user_exists($username) {
        $conn = self::db_connect();
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        $conn->close();
        return $exists;
    }

    public static function register_user($username, $password) {
        $conn = self::db_connect();
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }

    public static function admin_login($username, $password) {
        $conn = self::db_connect();
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ? AND is_admin = 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                return $id;
            }
        }
        $stmt->close();
        $conn->close();
        return false;
    }
    public static function is_admin($username) {
        $conn = self::db_connect();
        $stmt = $conn->prepare("SELECT is_admin FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($is_admin);
        $stmt->fetch();
        $stmt->close();
        $conn->close();
        return $is_admin;
    }
}

?>
