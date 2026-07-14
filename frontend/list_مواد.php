**list_مواد.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مواد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-indigo-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">مواد</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_مواد.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم</th>
                    <th>وصف</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?php echo $record['اسم']; ?></td>
                        <td><?php echo $record['وصف']; ?></td>
                        <td>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?php echo $record['id']; ?>)">حذف</button>
                        </td>
                        <td>
                            <a href="edit_مواد.php?id=<?php echo $record['id']; ?>" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const searchInput = document.getElementById('search-input').value;
            fetch('../backend/مواد.php', {
                method: 'GET',
                params: {
                    search: searchInput
                }
            })
            .then(response => response.json())
            .then(data => {
                const recordsTable = document.getElementById('records-table');
                recordsTable.innerHTML = '';
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record['اسم']}</td>
                        <td>${record['وصف']}</td>
                        <td>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record['id']})">حذف</button>
                        </td>
                        <td>
                            <a href="edit_مواد.php?id=${record['id']}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                        </td>
                    `;
                    recordsTable.appendChild(row);
                });
            });
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/مواد.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                });
            }
        }

        function fetchRecords() {
            return fetch('../backend/مواد.php', {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => data.records);
        }
    </script>
</body>
</html>

<?php
function fetchRecords() {
    $records = array();
    // Fetch records from backend
    // ...
    return $records;
}
?>

**backend/مواد.php**

<?php
// Fetch records from database
$records = array();
// ...
header('Content-Type: application/json');
echo json_encode(array('records' => $records));
?>

Note: This code assumes that you have a backend script (`backend/مواد.php`) that fetches records from a database and returns them in JSON format. You will need to modify the `fetchRecords()` function in the frontend script to match your backend implementation.