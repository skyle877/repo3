<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';

$errorMessage = "";

// Verificar si el usuario ya está logueado
if (isset($_SESSION['user_id'])) {
    // Redirigir a home.php
    header('Location: home.php');
    exit;
}

// Inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string(trim($_POST["username"]));
    $password = $conn->real_escape_string(trim($_POST["password"]));
    if (empty($username) || empty($password)) {
        $errorMessage = "Both fields are required.";
    } else {
        $sql = "SELECT * FROM Users WHERE username = '$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: home.php");
                exit;
            } else {
                $errorMessage = "Invalid username or password.";
            }
        } else {
            $errorMessage = "Invalid username or password.";
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

        <!-- Formulario de Login -->
        <div class="container">

            <!-- Mostrar mensaje de login correcto si existe -->
            <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success" role="alert">
                <?php
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
                ?>
            </div>
            <?php endif; ?>

            <form class="form-signin" method="post" action="index.php">
                <h2 class="form-signin-heading text-center mb-1">LOGIN</h2>
                <p class="text-center mt-0 mb-4">Please enter your Username and Password</p>

                <?php if (!empty($errorMessage)) { ?>
                    <div class="alert alert-danger" role="alert"><?php echo $errorMessage; ?></div>
                <?php } ?>

                <input type="hidden" name="action" value="login">
                
                <div class="input-group">
                    <span class="input-group-addon"><i class="bi bi-person-fill"></i></span>
                    <input type="text" name="username" id="inputUsername" class="form-control" placeholder="Username">
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password">
                </div>
                <br>
        
                <div class="flex-container">
                    <button class="btn btn-lg btn-dark" type="submit">Sign in</button>
                </div>
            </form>

            <!-- Enlace al formulario de registro -->
            <div class="text-center mt-4">
                <a href="register.php">Don't have an account? Register here.</a>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            document.querySelector('.form-signin').addEventListener('submit', validateForm);
        });

        function validateForm(event) {
            var username = document.getElementById('inputUsername').value.trim();
            var password = document.getElementById('inputPassword').value.trim();
            if (username === "" || password === "") {
                alert("Username and password are required.");
                event.preventDefault();
                return false;
            }
            return true;
        }
        </script>
    </body>
</html>