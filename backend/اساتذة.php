<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
if (isset($_SESSION['role']) && $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Read inputs from JSON body
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    // Prepare SQL query to select all rows from 'اساتذة' table
    $stmt = $pdo->prepare('SELECT * FROM اساتذة');
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($rows);
} elseif (isset($_GET['action']) && $_GET['action'] == 'get_one') {
    // Prepare SQL query to select one row from 'اساتذة' table by ID
    $stmt = $pdo->prepare('SELECT * FROM اساتذة WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($row);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not Found'));
    }
}

// Handle POST request
if (isset($_GET['action']) && $_GET['action'] == 'create') {
    // Validate and sanitize input data
    if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare SQL query to insert new row into 'اساتذة' table
    $stmt = $pdo->prepare('INSERT INTO اساتذة (name, email, phone) VALUES (:name, :email, :phone)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Created successfully'));
}

// Handle PUT request
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    // Validate and sanitize input data
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare SQL query to update existing row in 'اساتذة' table
    $stmt = $pdo->prepare('UPDATE اساتذة SET name = :name, email = :email, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Updated successfully'));
}

// Handle DELETE request
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    // Validate and sanitize input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare SQL query to delete row from 'اساتذة' table
    $stmt = $pdo->prepare('DELETE FROM اساتذة WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Deleted successfully'));
}