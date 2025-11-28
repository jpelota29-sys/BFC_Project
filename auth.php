<?php
session_start();
include 'db.php'; // database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST['action']; // login OR register

    // ============================
    // REGISTER
    // ============================
    if ($action == "register") {

        $username = $_POST['username'];
        $contact = $_POST['contact'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Check if email exists
        $check = $conn->query("SELECT * FROM users WHERE email='$email'");
        if ($check->num_rows > 0) {
            echo "<script>alert('Email already exists. Try logging in.'); window.location='index.php';</script>";
            exit;
        }

        // Save to DB
        $sql = "INSERT INTO users (username, contact, address, email, password)
                VALUES ('$username', '$contact', '$address', '$email', '$password')";
        
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Account created! Please login.'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Error: ".$conn->error."'); window.location='index.php';</script>";
        }
        exit;
    }

    // ============================
    // LOGIN
    // ============================
    if ($action == "login") {

        $email = $_POST['email'];
        $password = $_POST['password'];

        $result = $conn->query("SELECT * FROM users WHERE email='$email' LIMIT 1");

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                echo "<script>alert('Login successful!'); window.location='products.php';</script>";
                exit;
            } else {
                echo "<script>alert('Incorrect password.'); window.history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('User not found.'); window.history.back();</script>";
            exit;
        }
    }
}
?>

<?php
session_start();
session_destroy();
header("Location: index.php");
exit();
?>
