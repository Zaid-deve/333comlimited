<?php

include "../php/config.php";
session_start();
if(isset($_SESSION['user_id'])){
    header("Location:account.php");
    die();
}

$error = '';

$email = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $pass = htmlspecialchars($_POST['password']);

    if (empty($email) || empty($pass)) {
        $error = "Email and Password are required.";
    } else {
        require "../php/conn.php";
        $encoded_email = base64_encode($email);
        $qry = mysqli_query($conn, "SELECT uid,upass FROM users WHERE uemail = '$encoded_email'");
        
        if ($qry && mysqli_num_rows($qry) > 0) {
            $data = mysqli_fetch_assoc($qry);
            if (base64_encode($pass) == $data['upass']) {
                $_SESSION['user_id'] = $data['uid'];
                echo "<script>location.replace('account.php')</script>";
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "No account found with that email.";
        }
    }
}

include "../includes/head.php";

?>
<link rel="stylesheet" href="../styles/form.css">
</head>

<body>

    <div class="container-fluid vh-100 d-flex">
        <div class="m-auto rounded-4 p-4 form-container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" onsubmit="return validateForm()">
                <h1 class="text-center fw-bolder">Login</h1>
                <hr>
                <?php
                if (!empty($error)) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
                ?>
                <div class="mb-3">
                    <label for="__email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="__email" name="email" placeholder="example@gmail.com" value="<?php echo $email ?>">
                    <div class="form-text text-danger" id="email-error"></div>
                </div>
                <div class="mb-3">
                    <label for="__pass" class="form-label">Password</label>
                    <input type="password" class="form-control" id="__pass" name="password" placeholder="Enter Your Password" value="<?php echo $password ?>" required>
                    <div class="form-text text-danger" id="password-error"></div>
                </div>
                <button type="submit" id="submit-btn" class="btn btn-dark d-block w-100 fw-bold">Submit</button>
                <div class="text-center pt-3">
                    <a href="signup.php">Create my account</a>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-qFOQ9YFAeGj1gDOuUD61g3D+tLDv3u1ECYWqT82WQoaWrOhAY+5mRMTTVsQdWutbA5FORCnkEPEgU0OF8IzGvA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../js/login.js"></script>
 
</body>

</html>
