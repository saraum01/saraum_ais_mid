<?php
session_start();

// Include the database connection
require_once "config.php"; 

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php"); // Redirect to login page if not logged in
    exit;
}

// Initialize variables
$otp = $otp_err = "";
$generated_otp = ""; // Store the generated OTP

// Function to generate a random 6-digit OTP
function generateOtp() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT); // Generates a 6-digit OTP
}

// If the user clicks the 'Send OTP' button
if (isset($_POST['send_otp'])) {
    // Generate the OTP
    $generated_otp = generateOtp();
    // Set expiry time for OTP (10 minutes from now)
    $expiry_time = date("Y-m-d H:i:s", strtotime("+10 minutes"));
    
    // Update OTP and expiry in the database
    $sql = "UPDATE users SET otp = :otp, otp_expiry = :otp_expiry WHERE id = :id";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":otp", $generated_otp, PDO::PARAM_STR);
        $stmt->bindParam(":otp_expiry", $expiry_time, PDO::PARAM_STR);
        $stmt->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);

        // Execute the query to update the OTP in the database
        if ($stmt->execute()) {
            $_SESSION["otp_required"] = true; // Set the session flag to indicate OTP is required
        } else {
            echo "Error updating OTP.";
        }
        unset($stmt);
    }
    unset($pdo);
}

// OTP submission handling (when the user enters the OTP)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["otp"])) {
    if (empty(trim($_POST["otp"]))) {
        $otp_err = "Please enter the OTP.";
    } else {
        $otp = trim($_POST["otp"]);
    }

    if (empty($otp_err)) {
        // Fetch OTP and OTP expiry from the database
        $sql = "SELECT otp, otp_expiry FROM users WHERE id = :id";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                if ($row = $stmt->fetch()) {
                    $stored_otp = $row["otp"];
                    $otp_expiry = $row["otp_expiry"];

                    // Check if OTP matches and is not expired
                    if ($otp == $stored_otp && strtotime($otp_expiry) > time()) {
                        // OTP is valid, proceed to the dashboard
                        $_SESSION["otp_required"] = false; // OTP verified
                        header("location: dashboard.php");
                        exit;
                    } else {
                        $otp_err = "Invalid OTP or OTP expired.";
                    }
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            unset($stmt);
        }
    }
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
       body {
    background-color: #0d47a1; /* Deep blue background */
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

/* Wrapper for the login/OTP form */
.wrapper {
    width: 100%;
    max-width: 450px;
    padding: 40px;
    background-color: #1a237e; /* Darker blue for the form background */
    border-radius: 12px;
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.7); /* Soft shadow for depth */
    text-align: center;
}

/* Heading for the page */
.wrapper h2 {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #ffccbc; /* Soft light orange */
}

/* Input fields */
.form-control {
    background-color: #121212; /* Darker black background */
    color: #e0e0e0; /* Light grey text */
    border: 1px solid #0d47a1; /* Blue border */
    padding: 12px;
    font-size: 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-control:focus {
    background-color: #121212;
    border-color: #1976D2; /* Brighter blue on focus */
    box-shadow: 0 0 8px rgba(33, 150, 243, 0.6); /* Glow effect on focus */
}

/* Submit button */
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
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.btn-primary:hover {
    background-color: #1976D2;
    transform: scale(1.05); /* Slight scaling on hover for a dynamic effect */
}

/* Error message for OTP or login failures */
.alert-danger {
    background-color: #f44336;
    color: white;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
    font-size: 14px;
}

/* Modal Style */
.modal-content {
    background-color: #1a237e;
    color: white;
    border-radius: 10px;
}

.modal-header {
    background-color: #0d47a1;
    border-bottom: 1px solid #1976D2;
    padding: 15px;
    text-align: center;
    border-radius: 10px 10px 0 0;
}

.modal-body {
    padding: 20px;
    text-align: center;
}

.modal-body p {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 20px;
}

#otpText {
    font-size: 24px;
    font-weight: bold;
    color: #2196F3; /* Blue color for OTP */
}

/* Copy OTP button */
#copyOtpBtn {
    background-color: #2196F3;
    color: white;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
    margin-top: 10px;
}

#copyOtpBtn:hover {
    background-color: #0d47a1;
    transform: scale(1.05); /* Slight scaling effect on hover */
}

/* Responsive Styling */
@media (max-width: 768px) {
    .wrapper {
        padding: 30px;
    }

    .modal-body p {
        font-size: 16px;
    }

    .btn-primary {
        padding: 12px 24px;
    }

    #copyOtpBtn {
        font-size: 14px;
        padding: 8px 16px;
    }
}


    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Enter OTP</h2>
        <p>Please enter the OTP sent to you.</p>

        <?php 
        if (!empty($otp_err)) {
            echo '<div class="alert alert-danger">' . $otp_err . '</div>';
        }
        ?>

        <!-- OTP Input Form -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>OTP</label>
                <input type="text" name="otp" class="form-control" value="<?php echo $otp; ?>" maxlength="6">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit OTP">
            </div>
        </form>

        <!-- Button to send OTP -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="submit" name="send_otp" class="btn btn-primary" value="Send OTP">
            </div>
        </form>
    </div>

    <!-- Modal to display OTP -->
    <div class="modal" id="otpModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Your OTP</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Your OTP is: <span id="otpText"></span></p>
                    <button class="btn btn-success" id="copyOtpBtn">Copy OTP</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap and JS for Modal -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // JavaScript to show OTP modal after 3 seconds
        <?php if ($generated_otp): ?>
            setTimeout(function() {
                // Display OTP in the modal after 3 seconds
                document.getElementById("otpText").textContent = "<?php echo $generated_otp; ?>";
                $('#otpModal').modal('show');
            }, 3000);
        <?php endif; ?>

        // Function to copy OTP to clipboard
        document.getElementById('copyOtpBtn').addEventListener('click', function() {
            var otp = document.getElementById('otpText').textContent;
            navigator.clipboard.writeText(otp).then(function() {
                alert('OTP copied to clipboard!');
            });
        });
    </script>
</body>
</html>
