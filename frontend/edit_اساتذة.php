**edit_اساتذة.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the ID from URL
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = json_decode(file_get_contents('../backend/اساتذة.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    header('Location: list_اساتذة.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Asatidha</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-slate-900 mb-4">Edit Asatidha</h1>
        <form id="edit-asatidha-form" class="bg-white p-4 rounded shadow-md">
            <input type="hidden" id="id" name="id" value="<?= $id ?>">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 border border-gray-300 rounded-md" value="<?= $existingRecord['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-slate-900">Email</label>
                <input type="email" id="email" name="email" class="block w-full p-2 border border-gray-300 rounded-md" value="<?= $existingRecord['email'] ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-slate-900">Phone</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 border border-gray-300 rounded-md" value="<?= $existingRecord['phone'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-asatidha-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/اساتذة.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_اساتذة.php';
                        } else {
                            alert('Error updating asatidha');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/اساتذة.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    header('Location: list_اساتذة.php');
    exit;
}

// Get the ID
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = getAsatidha($id);

// Return JSON response
echo json_encode($existingRecord);

function getAsatidha($id) {
    // Your database query to fetch asatidha details
    // For example:
    $db = new PDO('sqlite:asatidha.db');
    $stmt = $db->prepare('SELECT * FROM asatidha WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $existingRecord = $stmt->fetch();
    $db = null;
    return $existingRecord;
}
?>