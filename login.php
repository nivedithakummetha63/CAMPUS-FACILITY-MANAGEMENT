<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'logindatabase';
$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$errorMsg = ""; // Variable to store error messages

if (isset($_POST['btnlogin'])) {
    $roll_id = mysqli_real_escape_string($conn, $_POST["roll_id"]);
    $pwd = $_POST["pwd"];

    // Fetch user details securely
    $stmt = $conn->prepare("SELECT * FROM logindetails WHERE roll_id = ?");
    $stmt->bind_param("s", $roll_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($pwd, $row['pwd'])) {
            $_SESSION['roll_id'] = $row['roll_id'];
            $_SESSION['uname'] = $row['uname'];
            $_SESSION['email'] = $row['email'];

            // Redirect to classroom management page
            header("Location: classroom_management.php");
            exit();
        } else {
            $errorMsg = "❌ Invalid Password!";
        }
    } else {
        $errorMsg = "❌ Roll ID not found!";
    }

    $stmt->close();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url("/backgroundtoregistrationpage.jpg") no-repeat center center fixed;
            background-size: cover;
        }
        form {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        form h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
            font-size: 28px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            color: #2c3e50;
            font-size: 16px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #3498db;
            outline: none;
        }
        input[type="submit"] {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
            font-size: 18px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #2980b9;
        }
        .center {
            text-align: center;
            margin-top: 10px;
        }
        .center a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }
        .center a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            text-align: center;
            font-size: 16px;
            margin-top: 10px;
        }
	.logo-container {
    text-align: center;
    margin-bottom: 20px;
}

.logo-container img {
    width: 150px; /* Adjust the size as needed */
    height: 150px;
    border-radius: 50%; /* Make it circular */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

    </style>
</head>
<body>
        <form method="post" action="">
        <div class="logo-container">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT8es-JhbWP8iaxQ_g5pxQe-D4Cfh3qpyckAQ&s" alt="User Logo">
        </div>
        
        <h3>User Login</h3>

        <?php if (!empty($errorMsg)) { ?>
            <p class="error"><?= $errorMsg ?></p>
        <?php } ?>

        <label for="roll_id">Roll ID:</label>
        <input name="roll_id" type="text" id="roll_id" required>

        <label for="pwd">Password:</label>
        <input name="pwd" type="password" id="pwd" required>

        <input type="submit" name="btnlogin" value="Login">
        
        <p class="center">Not registered yet? <a href="regi.php">Register Here</a></p>
    </form>
</body>
</html>
