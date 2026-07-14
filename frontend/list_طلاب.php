**list_طلاب.php**

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
    <title>طلاب</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f2937;
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
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
        }
        .search-bar input {
            width: 100%;
            height: 2rem;
            padding: 0.5rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input:focus {
            outline: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الصفحة الرئيسية</a>
        <span class="text-indigo-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-indigo-500">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h1 class="text-3xl text-slate-900">طلاب</h1>
            <a href="create_طلاب.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة جديد</a>
        </div>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم الطالب</th>
                    <th>البريد الإلكتروني</th>
                    <th>الجنسية</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = json_decode(file_get_contents('../backend/طلاب.php'), true);
                foreach ($records as $record) {
                    echo '<tr>';
                    echo '<td>' . $record['name'] . '</td>';
                    echo '<td>' . $record['email'] . '</td>';
                    echo '<td>' . $record['nationality'] . '</td>';
                    echo '<td>';
                    echo '<a href="edit_طلاب.php?id=' . $record['id'] . '" class="text-indigo-500">تعديل</a>';
                    echo '<button class="text-red-500" onclick="deleteRecord(' . $record['id'] . ')">حذف</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Search bar filtering
        const searchInput = document.getElementById('search');
        const records = document.getElementById('records');
        searchInput.addEventListener('input', () => {
            const searchQuery = searchInput.value.toLowerCase();
            const recordsHtml = Array.from(records.children).filter((record) => {
                const recordText = Array.from(record.children).map((cell) => cell.textContent.toLowerCase()).join('');
                return recordText.includes(searchQuery);
            }).map((record) => record.outerHTML);
            records.innerHTML = recordsHtml.join('');
        });

        // Delete record using AJAX
        function deleteRecord(id) {
            fetch('../backend/طلاب.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch((error) => console.error(error));
        }
    </script>
</body>
</html>

**backend/طلاب.php**

<?php
// Fetch records from database
$records = array();
// Replace with your database connection and query
$records = array(
    array('id' => 1, 'name' => 'محمد', 'email' => 'mohamed@example.com', 'nationality' => 'مصرى'),
    array('id' => 2, 'name' => 'عمر', 'email' => 'omar@example.com', 'nationality' => 'مصرى'),
    array('id' => 3, 'name' => 'أحمد', 'email' => 'ahmed@example.com', 'nationality' => 'مصرى')
);
header('Content-Type: application/json');
echo json_encode($records);