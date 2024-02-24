<?php
session_start();

require_once 'config.php';

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string(trim($_POST["username"]));
    $password = $conn->real_escape_string(trim($_POST["password"]));

    // Verificar que no haya campos vacíos
    if (empty($username) || empty($password)) {
        $errorMessage = "Both username and password are required.";
        } else {
            // Verificar si el usuario ya existe
            $checkUser = $conn->prepare("SELECT id FROM Users WHERE username = ?");
            $checkUser->bind_param("s", $username);
            $checkUser->execute();
            $result = $checkUser->get_result();
            if ($result->num_rows > 0) {
                $errorMessage = "Username already exists!";
            } else {
                // Insertar nuevo usuario
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $insert = $conn->prepare("INSERT INTO Users (username, password) VALUES (?, ?)");
                $insert->bind_param("ss", $username, $hashedPassword);

                if ($insert->execute()) {
                    $_SESSION['success_message'] = "User registered successfully!";
                    header("Location: index.php");
                    exit;
                } else {
                    $errorMessage = "Error: " . $conn->error;
                }
                $insert->close();
            }
        }
    }
    $conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login Page</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous"
        />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    </head>
    <body>
        <div class="container">
            <!-- Mostrar mensaje de error si existe -->
            <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $errorMessage; ?>
            </div>
            <?php endif; ?>

            <form class="form-signin" method="post" action="register.php">
                <h2 class="form-signin-heading text-center mb-1">REGISTER</h2>
                <p class="text-center mt-0 mb-4">Create your account</p>
                
                <div class="input-group">
                    <span class="input-group-addon"><i class="bi bi-person-fill"></i></span>
                    <input type="text" name="username" id="regInputUsername" class="form-control" placeholder="Username">
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password" id="regInputPassword" class="form-control" placeholder="Password">
                </div>
                <br>
                <div class="flex-container">
                    <button class="btn btn-lg btn-dark" type="submit">Register</button>
                </div>
            </form>
        </div>
        
        <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            document.querySelector('.form-signin').addEventListener('submit', function(event) {
                var username = document.getElementById('regInputUsername').value.trim();
                var password = document.getElementById('regInputPassword').value.trim();
                if (!username || !password) {
                    alert("Username and password are required.");
                    event.preventDefault();
                }
            });
        });
        </script>

        <!--  Enlace para iniciar sesión -->
        <a href="index.php" style="position: fixed; top: 20px; left: 50px; color: inherit;">
            <i class="bi bi-house" style="font-size: 2rem;"></i>
        </a>

    </body>
</html>