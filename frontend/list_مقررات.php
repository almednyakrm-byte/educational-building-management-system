**list_مقررات.php**

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
    <title>مقررات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f2937;
            color: #ffffff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #ffffff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ffffff;
            text-decoration: underline;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: center;
        }
        .table th {
            background-color: #1f2937;
            color: #ffffff;
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
            box-shadow: 0 0 0 0.25rem rgba(13, 30, 41, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">مقررات</span>
        <span class="float-right">
            <a href="profile.php"><?= $_SESSION['username'] ?></a>
            <a href="logout.php">تسجيل خروج</a>
        </span>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h1 class="text-3xl font-bold">مقررات</h1>
            <a href="create_مقررات.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة جديد</a>
        </div>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم المقرر</th>
                    <th>اسم المقرر</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = json_decode(file_get_contents('../backend/مقررات.php'), true);
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?= $record['id'] ?></td>
                        <td><?= $record['name'] ?></td>
                        <td>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?= $record['id'] ?>)">حذف</button>
                        </td>
                        <td>
                            <a href="edit_مقررات.php?id=<?= $record['id'] ?>" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
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
            const search = document.getElementById('search').value;
            fetch('../backend/مقررات.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.id}</td>
                            <td>${record.name}</td>
                            <td>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                            <td>
                                <a href="edit_مقررات.php?id=${record.id}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف المقرر؟')) {
                fetch('../backend/مقررات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف المقرر بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف المقرر');
                    }
                });
            }
        }
    </script>
</body>
</html>

This code includes the following features:

1.  Session validation: The code checks if the user is authenticated before allowing access to the page.
2.  Header navigation: The code includes a header with navigation links to the index page, the current user's profile, and logout.
3.  Table: The code displays a table with a list of records, including actions to edit and delete each record.
4.  Search bar: The code includes a search bar that filters the records in real-time.
5.  AJAX requests: The code uses AJAX requests to fetch records from the backend and delete records.

Note that this code assumes that the backend API is implemented to handle GET and DELETE requests. The backend API should return JSON data in response to these requests.