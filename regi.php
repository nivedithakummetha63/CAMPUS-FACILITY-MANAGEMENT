<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (isset($_POST['btnreg'])) {
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'logindatabase';
    $conn = new mysqli($host, $user, $pass, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $roll_id = mysqli_real_escape_string($conn, trim($_POST["roll_id"]));
    $uname = mysqli_real_escape_string($conn, trim($_POST["uname"]));
    $email = mysqli_real_escape_string($conn, trim($_POST["email"]));
    $pwd = mysqli_real_escape_string($conn, $_POST["pwd"]);

    // **Validate Email Format**
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@srit\.ac\.in$/', $email)) {
        echo "<script>alert('Only email addresses ending with srit.ac.in are allowed.'); window.history.back();</script>";
        exit();
    }

    // **Validate Roll ID Format**
    if (!preg_match('/^224g1a05(0[0-9]|[1-6][0-9]|70)$/i', $roll_id)) {
        echo "<script>alert('Invalid Roll ID. Registration not allowed for this ID.'); window.history.back();</script>";
        exit();
    }

    // **Check if Roll ID or Email Already Exists**
    $checkUser = $conn->prepare("SELECT roll_id, email FROM logindetails WHERE roll_id = ? OR email = ?");
    $checkUser->bind_param("ss", $roll_id, $email);
    $checkUser->execute();
    $checkUser->store_result();
    
    if ($checkUser->num_rows > 0) {
        echo "<script>alert('Roll ID or Email already registered. Try another.'); window.history.back();</script>";
        exit();
    }
    $checkUser->close();

    // **Hash Password for Security**
    $hashed_pwd = password_hash($pwd, PASSWORD_DEFAULT);

    // **Insert User Data**
    $stmt = $conn->prepare("INSERT INTO logindetails (roll_id, uname, email, pwd) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $roll_id, $uname, $email, $hashed_pwd);

    if ($stmt->execute()) {
        $_SESSION['user'] = $roll_id; // Store Roll ID in session
        echo "<script>alert('Registration Successful! Redirecting to login page.'); window.location.href = 'login.php';</script>";
        exit();
    } else {
        echo "<script>alert('Registration failed. Please try again.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: url("/backgroundtoregistrationpage.jpg") no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        form {
            background: rgba(255, 255, 255, 0.85);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        h3 {
            text-align: center;
            color: #333;
            font-size: 24px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        input:focus {
            border-color: #2575fc;
            outline: none;
        }
        .error {
            color: red;
            font-size: 14px;
            display: none;
        }
        input[type="submit"] {
            background-color: #2575fc;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            font-size: 18px;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #6a11cb;
        }
        .center {
            text-align: center;
            margin-top: 10px;
        }
        .center a {
            color: #2575fc;
            text-decoration: none;
            font-weight: bold;
        }
        .center a:hover {
            text-decoration: underline;
        }
.logo-container {
    text-align: center;
    margin-bottom: 20px;
}

.logo-container img {
    width: 150px; /* Increased size */
    height: 150px; /* Keeps it proportional */
    border-radius: 50%; /* Circular shape */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}


    </style>
</head>
<body>
   <body>
    <form method="post" action="regi.php">
        <div class="logo-container">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT8es-JhbWP8iaxQ_g5pxQe-D4Cfh3qpyckAQ&s" alt="User Logo">
        </div>
        <h3>User Registration</h3>
        <label>Roll ID:</label>
        <input name="roll_id" type="text" required>
        
        <label>Username:</label>
        <input name="uname" type="text" required>

        <label>Email:</label>
        <input name="email" type="email" required>

        <label>Password:</label>
        <input name="pwd" type="password" required>

        <input type="submit" name="btnreg" value="Register">
        <p class="center">Already a member? <a href="login.php">Login Here</a></p>
    </form>
</body>

</body>
</html>
