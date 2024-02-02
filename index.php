<?php
require_once "core/init.php";
if(isset($_SESSION["LOGIN"])) {
    header("location:" . BASE_URL . "profile.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>NEC Test | Login</title>
    <?php require_once HEAD_PATH ?>
</head>
<body>
    <?php require_once HEADER_PATH ?>
    <div class="container py-5">
        <div class="h5 text-center">Login Form</div>
        <form id="loginForm">
            <input type="hidden" name="type" value="login">
            <input type="hidden" name="csrf_token" value="<?= generate_token("loginToken") ?>">
            <div class="mb-3">
                <label for="loginUser" class="form-label">Email or Phone</label>
                <input type="text" class="form-control" id="loginUser" name="loginUser" required>
            </div>
            <div class="mb-3">
                <label for="loginPassword" class="form-label">Password</label>
                <input type="password" class="form-control" id="loginPassword" name="loginPassword" required>
            </div>
            <div class="mb-3">Don't have an account? <a href="signup.php">Signup Here</a></div>
            <div class="alert alert-danger d-flex align-items-center d-none" role="alert" id="loginError"></div>
            <button type="submit" id="loginSubmit" class="btn btn-info">Login</button>
        </form>
    </div>
    <?php require_once FOOTER_PATH ?>
    <?php require_once FOOT_PATH ?>
    <script src="<?= BASE_URL ?>js/login.js"></script>
</body>
</html>