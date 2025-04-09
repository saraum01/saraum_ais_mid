<?php
session_start();

if (!isset($_SESSION["captcha_correct_count"])) {
    $_SESSION["captcha_correct_count"] = 0;
}

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: otp.php");
    exit;
}

require_once "config.php";

$username = $password = "";
$username_err = $password_err = $login_err = "";

$colors = ["Red", "Green", "Blue"];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CAPTCHA Check
    if (!isset($_POST["captcha"]) || $_POST["captcha"] !== $_SESSION["captcha_color"]) {
        $login_err = "CAPTCHA failed. Please try again.";
    } else {
        $_SESSION["captcha_correct_count"] += 1;

        if (empty(trim($_POST["username"]))) {
            $username_err = "Please enter username.";
        } else {
            $username = trim($_POST["username"]);
        }

        if (empty(trim($_POST["password"]))) {
            $password_err = "Please enter your password.";
        } else {
            $password = trim($_POST["password"]);
        }

        if (empty($username_err) && empty($password_err)) {
            $sql = "SELECT id, username, password FROM users WHERE username = :username";

            if ($stmt = $pdo->prepare($sql)) {
                $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
                $param_username = $username;

                if ($stmt->execute()) {
                    if ($stmt->rowCount() == 1) {
                        if ($row = $stmt->fetch()) {
                            $id = $row["id"];
                            $username = $row["username"];
                            $hashed_password = $row["password"];

                            if (password_verify($password, $hashed_password)) {
                                $otp = rand(100000, 999999);
                                $otp_expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

                                $sql = "UPDATE users SET otp = :otp, otp_expiry = :otp_expiry WHERE id = :id";
                                if ($stmt = $pdo->prepare($sql)) {
                                    $stmt->bindParam(":otp", $otp, PDO::PARAM_STR);
                                    $stmt->bindParam(":otp_expiry", $otp_expiry, PDO::PARAM_STR);
                                    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

                                    if ($stmt->execute()) {
                                        $_SESSION["loggedin"] = true;
                                        $_SESSION["id"] = $id;
                                        $_SESSION["username"] = $username;
                                        $_SESSION["otp_required"] = true;

                                        unset($_SESSION["captcha_color"]);
                                        header("location: otp.php");
                                        exit;
                                    }
                                }
                            } else {
                                $login_err = "Invalid username or password.";
                            }
                        }
                    } else {
                        $login_err = "Invalid username or password.";
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
                unset($stmt);
            }
        }
    }
    unset($pdo);
}

// ✅ Only regenerate CAPTCHA if not POST or after it
if ($_SERVER["REQUEST_METHOD"] != "POST" || !empty($login_err)) {
    $random_color = $colors[array_rand($colors)];
    $_SESSION["captcha_color"] = $random_color;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login with CAPTCHA</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font: 14px sans-serif;
            background-color: #0d47a1;
            color: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .wrapper {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            background-color: #1a237e;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }

        .form-control {
            background-color: #121212;
            color: #e0e0e0;
            border: 1px solid #0d47a1;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .form-control:focus {
            border-color: #1976D2;
            box-shadow: 0 0 8px rgba(33, 150, 243, 0.6);
        }

        .btn-primary {
            background-color: #0d47a1;
            border: none;
            color: white;
            padding: 14px 28px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            width: 100%;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #1976D2;
            transform: scale(1.05);
        }

        .alert-danger {
            background-color: #f44336;
            color: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 14px;
        }

        .captcha-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .captcha-buttons button {
            flex: 1;
            border: none;
            padding: 12px 0;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
        }

        .captcha-label {
            margin-bottom: 10px;
            font-weight: bold;
        }

        .captcha-counter {
            text-align: center;
            margin-bottom: 10px;
            color: #a5d6a7;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>

        

        <?php 
        if (!empty($login_err)) {
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username"
                    class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>"
                    value="<?php echo htmlspecialchars($username); ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password"
                    class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>

            <!-- CAPTCHA -->
            <div class="form-group">
                <div class="captcha-label">Click the color: <u><?php echo $_SESSION["captcha_color"]; ?></u></div>
                <div class="captcha-buttons">
                    <?php foreach ($colors as $color): ?>
                        <button type="submit" name="captcha" value="<?php echo $color; ?>"
                            style="background-color: <?php echo strtolower($color); ?>;">
                            <?php echo $color; ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <p>Don't have an account? <a href="register.php">Register now</a>.</p>
        </form>
    </div>
</body>
</html>
