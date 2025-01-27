<?php
session_start();

require_once '../../db.php';

$perPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

$totalQuery = "SELECT COUNT(*) as total FROM events";
$totalResult = $conn->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$totalEvents = $totalRow['total'];

$totalPages = ceil($totalEvents / $perPage);

$query = "SELECT * FROM events ORDER BY created_at DESC LIMIT $offset, $perPage";
$result = $conn->query($query);

// Handle CSV download
if (isset($_GET['download']) && isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']); 

    $attendeesQuery = "SELECT a.id, a.name, a.email, a.phone
                       FROM event_attendees ea
                       JOIN attendees a ON ea.attendee_id = a.id
                       WHERE ea.event_id = ?";
    $attendeesStmt = $conn->prepare($attendeesQuery);
    if (!$attendeesStmt) {
        die("Query preparation failed: " . $conn->error);
    }

    $attendeesStmt->bind_param('i', $event_id);
    $attendeesStmt->execute();

    $attendeesResult = $attendeesStmt->get_result();
    if ($attendeesResult->num_rows == 0) {
        die("No attendees found for this event.");
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="attendees_event_' . $event_id . '.csv"');

    $output = fopen('php://output', 'w');

    fputcsv($output, ['Attendee ID', 'Name', 'Email', 'Phone'], ',', '"'); 

    while ($row = $attendeesResult->fetch_assoc()) {
        fputcsv($output, $row, ',', '"'); 
    }

    fclose($output);
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management Dashboard</title>
    <link href="../../../../assets/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        .search-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }
        .pagination {
            justify-content: center;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin_panel.php">Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="admin_panel.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="my-4">Event Management Dashboard</h2>
    <?php
        if (isset($_SESSION['message'])) {
            echo "<div class='alert alert-success'>" . $_SESSION['message'] . "</div>";
            unset($_SESSION['message']);
        }

        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']);
        }
    ?>
    <div class="search-container">
        <input type="text" id="searchInput" class="form-control" placeholder="Search Events">
    </div>

    <div class="table-responsive">
        <table class="table table-bordered" id="eventTable">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Event Name</th>
                    <th>Description</th>
                    <th>Max Capacity</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td><?= $row['max_capacity'] ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                        <a href="update_event.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm mb-1">Edit</a>
                        <a href="delete_event.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm mb-1">Delete</a>
                        <a href="?download=1&event_id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Download Attendees</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <nav>
        <ul class="pagination">
            <?php if ($page > 1): ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a></li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Next</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<!-- Bootstrap 5 bundle (includes Popper.js and Bootstrap JS) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // Cache all rows initially
        const rows = $('#eventTable tbody tr');

        $('#searchInput').on('keyup', function() {
            const searchQuery = $(this).val().toLowerCase();

            rows.each(function() {
                const rowText = $(this).text().toLowerCase();

                if (rowText.includes(searchQuery)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>
</body>
</html>

