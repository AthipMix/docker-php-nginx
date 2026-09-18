<?php
// Database Connection
$servername = "db";
$username = "admin";
$password = "1234";
$dbname = "sample_db";

try {
    $conn = new PDO(
        "mysql:host=$servername;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    // ให้ PDO แสดง Exception เมื่อเกิด Error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}


// Pagination Setup
$items_per_page = 20; // จำนวนรายการต่อหน้า

$current_page = isset($_GET['page'])
    ? (int) $_GET['page']
    : 1;

// ป้องกัน page น้อยกว่า 1
if ($current_page < 1) {
    $current_page = 1;
}

$offset = ($current_page - 1) * $items_per_page;


// Fetch Total Records
$total_sql = "SELECT COUNT(*) AS total FROM titanic";

$total_stmt = $conn->prepare($total_sql);
$total_stmt->execute();

$total_row = $total_stmt->fetch(PDO::FETCH_ASSOC);

$total_records = $total_row['total'];

$total_pages = ceil($total_records / $items_per_page);


// ถ้า page เกินจำนวนหน้าที่มี
if ($total_pages > 0 && $current_page > $total_pages) {
    $current_page = $total_pages;
    $offset = ($current_page - 1) * $items_per_page;
}


// Fetch Paginated Data
$sql = "SELECT *
        FROM titanic
        LIMIT :limit OFFSET :offset";

$stmt = $conn->prepare($sql);

$stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Titanic Data</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">

        <h2 class="text-center mb-4">
            Titanic Passenger Data
        </h2>


        <?php if (count($result) > 0): ?>

            <table class="table table-striped table-bordered">

                <thead class="table-dark">

                    <tr>
                        <th>Index</th>
                        <th>Passenger ID</th>
                        <th>Survived</th>
                        <th>Pclass</th>
                        <th>Name</th>
                        <th>Sex</th>
                        <th>Age</th>
                        <th>SibSp</th>
                        <th>Parch</th>
                        <th>Ticket</th>
                        <th>Fare</th>
                        <th>Cabin</th>
                        <th>Embarked</th>
                    </tr>

                </thead>


                <tbody>

                    <?php foreach ($result as $row): ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars($row['index']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['PassengerId']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['Survived']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['Pclass']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['Name']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['Sex']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['Age']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['SibSp']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['Parch']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['Ticket']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['Fare']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['Cabin']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['Embarked']) ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>


            <!-- Pagination Links -->

            <nav>

                <ul class="pagination justify-content-center">


                    <!-- Previous Page -->

                    <li class="page-item
                    <?= $current_page <= 1 ? 'disabled' : '' ?>">

                        <a class="page-link" href="?page=<?= $current_page - 1 ?>">

                            Previous

                        </a>

                    </li>


                    <!-- Page Number Links -->

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>

                        <li class="page-item
                        <?= $i == $current_page ? 'active' : '' ?>">

                            <a class="page-link" href="?page=<?= $i ?>">

                                <?= $i ?>

                            </a>

                        </li>

                    <?php endfor; ?>


                    <!-- Next Page -->

                    <li class="page-item
                    <?= $current_page >= $total_pages ? 'disabled' : '' ?>">

                        <a class="page-link" href="?page=<?= $current_page + 1 ?>">

                            Next

                        </a>

                    </li>

                </ul>

            </nav>


        <?php else: ?>

            <p class="text-center">
                No records found in the Titanic table.
            </p>

        <?php endif; ?>

    </div>


    <!-- Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>

</body>

</html>