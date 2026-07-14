**edit_طلاب.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$data = json_decode(file_get_contents('../backend/طلاب.php?id=' . $id), true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .slate-900 {
            color: #1a1d23;
        }
        .indigo-500 {
            color: #6b6bcf;
        }
    </style>
</head>
<body>
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold slate-900 mb-4">Edit Student</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium slate-900">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="<?= $data['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium slate-900">Email:</label>
                <input type="email" id="email" name="email" class="block w-full p-2 pl-10 text-sm text-gray-700 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="<?= $data['email'] ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium slate-900">Phone:</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 pl-10 text-sm text-gray-700 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="<?= $data['phone'] ?>">
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 rounded-lg hover:bg-indigo-600 focus:ring-indigo-500">Update</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/طلاب.php',
                    data: formData,
                    success: function(response) {
                        window.location.href = 'list_طلاب.php';
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/طلاب.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    echo 'Error: ID not found';
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get id from URL
$id = $_GET['id'];

// SQL query to fetch existing record details
$sql = "SELECT * FROM طلاب WHERE id = '$id'";
$result = $conn->query($sql);

// Check if data exists
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo json_encode($row);
    }
} else {
    echo 'Error: Record not found';
}

// Close connection
$conn->close();
?>