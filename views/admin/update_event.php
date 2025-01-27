<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /views/login.php");
    exit;
}

require_once '../../db.php';

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    $query = "SELECT * FROM events WHERE id = $event_id";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $event = mysqli_fetch_assoc($result);
    } else {
        $_SESSION['error'] = "Event not found!";
        header('Location: index.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $event_name = $_POST['name'];
        $event_description = $_POST['description'];
        $event_date = $_POST['date'];
        $max_capacity = $_POST['max_capacity'];

        $update_query = "UPDATE events SET name = '$event_name', description = '$event_description', date = '$event_date', max_capacity = '$max_capacity' WHERE id = $event_id";

        if (mysqli_query($conn, $update_query)) {
            $_SESSION['message'] = "Event updated successfully!";
            header('Location: admin_panel.php?id=' . $event_id);
            exit;
        } else {
            $_SESSION['error'] = "Error updating event: " . mysqli_error($conn);
        }
    }
} else {
    $_SESSION['error'] = "No event ID specified!";
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .card {
            border: 1px solid #ddd;
            padding: 20px;
            background-color: #fff;
        }
        .form-control {
            border-radius: 0.375rem;
        }
        .btn {
            width: 100%;
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
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Edit Event</h3>
                </div>
                <div class="card-body">

                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-success">
                            <?php echo $_SESSION['message']; ?>
                        </div>
                        <?php unset($_SESSION['message']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $_SESSION['error']; ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <form method="POST" action="update_event.php?id=<?php echo $event_id; ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Event Name</label>
                            <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($event['name']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Event Description</label>
                            <textarea class="form-control" name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="event_date" class="form-label">Event Date & Time</label>
                            <input type="date" class="form-control" name="date" value="<?php echo htmlspecialchars($event['date']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="max_capacity" class="form-label">Max Capacity</label>
                            <input type="number" class="form-control" name="max_capacity" value="<?php echo htmlspecialchars($event['max_capacity']); ?>" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
