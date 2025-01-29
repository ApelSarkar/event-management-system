<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /views/login.php");
    exit;
}

require_once '../db.php';

$userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User';
$userRole = isset($_SESSION['user_role']) ? ucfirst($_SESSION['user_role']) : 'User';

$createdBy = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM events WHERE created_by = ? ORDER BY date DESC");
$stmt->bind_param("i", $createdBy);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
     <!-- Navbar -->
    <?php include('navbar.php'); ?>

    <!-- Main Dashboard Content -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h3>
                    </div>
                    <div class="card-body">
                        <h5>Your Events:</h5>
                        <?php if ($result->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th style="width: 40%;">Description</th>
                                            <th>Date</th>
                                            <th>Max Capacity</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($event = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($event['name']); ?></td>
                                                <td style="word-wrap: break-word; max-width: 40%;">
                                                    <?php echo nl2br(htmlspecialchars($event['description'])); ?>
                                                </td>
                                                <td><?php echo date('d M Y, h:i A', strtotime($event['date'])); ?></td>
                                                <td><?php echo htmlspecialchars($event['max_capacity']); ?></td>
                                                <td class="text-nowrap">
                                                    <a href="view_event_user.php?event_id=<?php echo $event['id']; ?>"
                                                    class="btn btn-info btn-sm">View</a>
                                                    <a href="edit_event.php?event_id=<?php echo $event['id']; ?>"
                                                        class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="delete_event.php?event_id=<?php echo $event['id']; ?>"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p>You have no events yet. Create a new event!</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>

</html>