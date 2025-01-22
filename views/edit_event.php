<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /views/login.php");
    exit;
}

require_once '../db.php';

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
    $createdBy = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ? AND created_by = ?");
    $stmt->bind_param("ii", $event_id, $createdBy);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $_SESSION['error'] = "Event not found or you don't have permission to edit it.";
        header("Location: dashboard.php");
        exit;
    }

    $event = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $event_date = $_POST['date'];
    $max_capacity = $_POST['max_capacity'];

    $stmt = $conn->prepare("UPDATE events SET name = ?, description = ?, date = ?, max_capacity = ? WHERE id = ? AND created_by = ?");
    $stmt->bind_param("sssiii", $name, $description, $event_date, $max_capacity, $event_id, $createdBy);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Event updated successfully!";
        header("Location: dashboard.php");
    } else {
        $_SESSION['error'] = "Failed to update event.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<!-- Navbar -->
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Edit Event</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="name" class="form-label">Event Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($event['name']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($event['description']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="event_date" class="form-label">Event Date & Time</label>
                            <input type="datetime-local" class="form-control" id="event_date" name="date" value="<?php echo date('Y-m-d\TH:i', strtotime($event['date'])); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="max_capacity" class="form-label">Max Capacity</label>
                            <input type="number" class="form-control" id="max_capacity" name="max_capacity" value="<?php echo htmlspecialchars($event['max_capacity']); ?>" required>
                        </div>

                        <button type="submit" class="btn btn-warning w-100">Update Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
