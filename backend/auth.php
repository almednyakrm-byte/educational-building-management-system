<?php
// Start the session to handle user authentication
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, return a JSON response indicating success
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'success', 'message' => 'User is already logged in'));
    exit;
}

// Handle the login action
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    // Check if the required fields are present
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        // If the required fields are missing, return a JSON response indicating failure
        header('Content-Type: application/json');
        echo json_encode(array('status' => 'failure', 'message' => 'Missing required fields'));
        exit;
    }

    // Sanitize the input fields
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // Prepare the SQL query to select the user
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows == 0) {
        // If the user does not exist, return a JSON response indicating failure
        header('Content-Type: application/json');
        echo json_encode(array('status' => 'failure', 'message' => 'Invalid username or password'));
        exit;
    }

    // Fetch the user data
    $user = $result->fetch_assoc();

    // Verify the password
    if (password_verify($password, $user['password'])) {
        // If the password is correct, log the user in
        $_SESSION['user_id'] = $user['id'];
        header('Content-Type: application/json');
        echo json_encode(array('status' => 'success', 'message' => 'User logged in successfully'));
        exit;
    } else {
        // If the password is incorrect, return a JSON response indicating failure
        header('Content-Type: application/json');
        echo json_encode(array('status' => 'failure', 'message' => 'Invalid username or password'));
        exit;
    }
}

// Handle the register action
if (isset($_POST['action']) && $_POST['action'] == 'register') {
    // Check if the required fields are present
    if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['confirm_password'])) {
        // If the required fields are missing, return a JSON response indicating failure
        header('Content-Type: application/json');
        echo json_encode(array('status' => 'failure', 'message' => 'Missing required fields'));
        exit;
    }

    // Sanitize the input fields
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $confirm_password = filter_var($_POST['confirm_password'], FILTER_SANITIZE_STRING);

    // Check if the password and confirm password match
    if ($password !== $confirm_password) {
        // If the passwords do not match, return a JSON response indicating failure
        header('Content-Type: application/json');
        echo json_encode(array('status' => 'failure', 'message' => 'Passwords do not match'));
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query to insert the user
    $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    $stmt->execute();

    // Check if the user was inserted successfully
    if ($stmt->affected_rows == 1) {
        // If the user was inserted successfully, log them in
        $_SESSION['user_id'] = $mysqli->insert_id;
        header('Content-Type: application/json');
        echo json_encode(array('status' => 'success', 'message' => 'User registered and logged in successfully'));
        exit;
    } else {
        // If the user was not inserted successfully, return a JSON response indicating failure
        header('Content-Type: application/json');
        echo json_encode(array('status' => 'failure', 'message' => 'Failed to register user'));
        exit;
    }
}

// Handle the logout action
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Destroy the session to log the user out
    session_destroy();
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'success', 'message' => 'User logged out successfully'));
    exit;
}

// Handle the check session status action
if (isset($_GET['action']) && $_GET['action'] == 'check_session') {
    // Check if the user is logged in
    if (isset($_SESSION['user_id'])) {
        // If the user is logged in, return a JSON response indicating success
        header('Content-Type: application/json');
        echo json_encode(array('status' => 'success', 'message' => 'User is logged in'));
        exit;
    } else {
        // If the user is not logged in, return a JSON response indicating failure
        header('Content-Type: application/json');
        echo json_encode(array('status' => 'failure', 'message' => 'User is not logged in'));
        exit;
    }
}