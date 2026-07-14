**edit_مقررات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get the ID from URL
$id = $_GET['id'];

// Fetch existing record details
$url = '../backend/مقررات.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data is available
if ($data) {
    $title = $data['title'];
    $description = $data['description'];
} else {
    header('Location: list_مقررات.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit مقررات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Edit مقررات</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-slate-900">Title</label>
                <input type="text" id="title" name="title" value="<?= $title ?>" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $description ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مقررات.php',
                    data: formData,
                    success: function(response) {
                        window.location.href = 'list_مقررات.php';
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>


**مقررات.php (backend)**

<?php
// Check if ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Fetch existing record details
    $query = "SELECT * FROM مقررات WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    echo json_encode($data);
} else {
    // Handle error
    echo json_encode(array('error' => 'ID not provided'));
}
?>


**مقررات.php (backend) for update**

<?php
// Check if ID and data are provided
if (isset($_GET['id']) && isset($_POST['title']) && isset($_POST['description'])) {
    $id = $_GET['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    // Update existing record
    $query = "UPDATE مقررات SET title = '$title', description = '$description' WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo json_encode(array('success' => 'Record updated successfully'));
    } else {
        echo json_encode(array('error' => 'Error updating record'));
    }
} else {
    // Handle error
    echo json_encode(array('error' => 'ID or data not provided'));
}
?>