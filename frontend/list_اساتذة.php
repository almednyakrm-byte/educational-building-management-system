**list_اساتذة.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اساتذة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-slate-900 {
            background-color: #1A1D23;
        }
        .text-indigo-500 {
            color: #6B7280;
        }
    </style>
</head>
<body class="bg-slate-900">
    <div class="container mx-auto p-4">
        <header class="flex justify-between items-center mb-4">
            <a href="index.php" class="text-indigo-500 hover:text-white">Back to Index</a>
            <div class="flex items-center">
                <span class="text-indigo-500 mr-2">Welcome, <?= $_SESSION['username'] ?></span>
                <a href="logout.php" class="text-indigo-500 hover:text-white">Logout</a>
            </div>
        </header>
        <main class="bg-white rounded-lg shadow-md p-4">
            <h2 class="text-indigo-500 mb-4">List of Asatidha</h2>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_اساتذة.php'">Add New Item</button>
            <div class="flex justify-between items-center mb-4">
                <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700" placeholder="Search...">
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">Search</button>
            </div>
            <table class="w-full border-collapse border border-slate-400">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody id="records">
                    <?php
                    // Fetch records from backend
                    $records = json_decode(file_get_contents('../backend/اساتذة.php'), true);
                    foreach ($records as $record) {
                        ?>
                        <tr>
                            <td class="px-4 py-2"><?= $record['name'] ?></td>
                            <td class="px-4 py-2">
                                <a href="edit_اساتذة.php?id=<?= $record['id'] ?>" class="text-indigo-500 hover:text-white">Edit</a>
                                <button class="text-red-500 hover:text-white" onclick="deleteRecord(<?= $record['id'] ?>)">Delete</button>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>

    <script>
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchValue = searchInput.value.trim();
            if (searchValue) {
                fetch('../backend/اساتذة.php?search=' + searchValue)
                    .then(response => response.json())
                    .then(data => {
                        const records = document.getElementById('records');
                        records.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="px-4 py-2">${record.name}</td>
                                <td class="px-4 py-2">
                                    <a href="edit_اساتذة.php?id=${record.id}" class="text-indigo-500 hover:text-white">Edit</a>
                                    <button class="text-red-500 hover:text-white" onclick="deleteRecord(${record.id})">Delete</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/اساتذة.php')
                    .then(response => response.json())
                    .then(data => {
                        const records = document.getElementById('records');
                        records.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="px-4 py-2">${record.name}</td>
                                <td class="px-4 py-2">
                                    <a href="edit_اساتذة.php?id=${record.id}" class="text-indigo-500 hover:text-white">Edit</a>
                                    <button class="text-red-500 hover:text-white" onclick="deleteRecord(${record.id})">Delete</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    });
            }
        }

        function deleteRecord(id) {
            if (confirm('Are you sure you want to delete this record?')) {
                fetch('../backend/اساتذة.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Record deleted successfully!');
                        searchRecords();
                    } else {
                        alert('Error deleting record!');
                    }
                });
            }
        }
    </script>
</body>
</html>

**backend/اساتذة.php**

<?php
// Fetch records from database
$records = array(
    array('id' => 1, 'name' => 'John Doe'),
    array('id' => 2, 'name' => 'Jane Doe'),
    array('id' => 3, 'name' => 'Bob Smith')
);

if (isset($_GET['search'])) {
    $searchValue = $_GET['search'];
    $records = array_filter($records, function($record) use ($searchValue) {
        return strpos($record['name'], $searchValue) !== false;
    });
}

header('Content-Type: application/json');
echo json_encode($records);
?>

Note: This code assumes that you have a database setup and the `backend/اساتذة.php` file is connected to it. The `backend/اساتذة.php` file is a simple example and you should replace it with your actual database connection and query.