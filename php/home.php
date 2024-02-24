<?php
session_start();

require_once 'config.php';

// Si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Obtener todos los usuarios
$sql = "SELECT id, username FROM Users";
$result = $conn->query($sql);

$conn->close();
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
        <style>
            body {
                background-color: #f3faff;
            }
            .row {
                margin-top: 20px;
            }
            .col-md-4 {
                margin-bottom: 30px;
                padding-left: 15px;
                padding-right: 15px;
            }
        </style>
    </head>
    <body>
        <div class="container mt-3">
        <h2 class="form-dashboard-heading text-center mb-1">Home</h2>
            <div class="row">
                <?php if ($result->num_rows > 0): ?>

                    <?php  // Devuelve una fila de la tabla Users como array asociativo
                        while($user = $result->fetch_assoc()): ?>
                        <div class="col-md-4">
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header text-center">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                                <div class="card-body">
                                    <strong>Id:</strong> <?php echo $user['id']; ?><br>
                                    <strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?><br>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No users found.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!--  Enlace para cerrar sesión -->
        <div class="logout-container" style="position: fixed; top: 20px; right: 20px;">
            <a href="logout.php?logout=1" class="logout-link">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </body>
</html>
