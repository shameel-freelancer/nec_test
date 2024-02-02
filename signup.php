<?php
require_once "core/init.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>NEC Test | Signup</title>
    <?php require_once HEAD_PATH ?>
</head>
<body>
    <?php require_once HEADER_PATH ?>
    <div class="container py-5">
        <div class="h5 text-center">Signup Form</div>
        <form id="signupForm">
            <input type="hidden" name="type" value="self-register">
            <input type="hidden" name="csrf_token" value="<?= generate_token("signupToken") ?>">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="signupName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="signupName" name="signupName" required>
                </div>
                <div class="col-md-6">
                    <label for="signupEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="signupEmail" name="signupEmail" required pattern="<?= trim(REGEX_EMAIL, "/") ?>" title="Please Enter Valid Email">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="signupPhone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="signupPhone" name="signupPhone" required pattern="<?= trim(REGEX_PHONE, "/") ?>" title="Please Enter Valid Indian Phone Number">
                </div>
                <div class="col-md-4">
                    <label for="signupPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="signupPassword" name="signupPassword" required>
                </div>
                <div class="col-md-4">
                    <label for="signupConfirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="signupConfirmPassword" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="signupAge" class="form-label">Age</label>
                    <input type="text" class="form-control" id="signupAge" name="signupAge" required pattern="<?= trim(REGEX_AGE, "/") ?>" title="Please Enter Valid Age">
                </div>
                <div class="col-md-4">
                    <label for="signupGender" class="form-label">Gender</label>
                    <select type="text" class="form-select" id="signupGender" name="signupGender" required>
                        <option value="" selected>-Select-</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="signupQualification" class="form-label">Qualification</label>
                    <input type="text" class="form-control" id="signupQualification" name="signupQualification" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="signupAddress" class="form-label">Address</label>
                    <textarea type="text" class="form-control" id="signupAddress" name="signupAddress" required></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="signupCity" class="form-label">City</label>
                    <input type="text" class="form-control" id="signupCity" name="signupCity" required>
                </div>
                <div class="col-md-6">
                    <label for="signupState" class="form-label">State</label>
                    <input type="text" class="form-control" id="signupState" name="signupState" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="signupProfilePicture" class="form-label">Profile Picture</label>
                    <input type="file" class="form-control" id="signupProfilePicture" name="signupProfilePicture" required accept="image/jpeg">
                </div>
            </div>
            <div class="mb-3">Already have an account? <a href="<?= BASE_URL; ?>">Login Here</a></div>
            <div class="alert alert-danger d-flex align-items-center d-none" role="alert" id="signupError">
            </div>
            <button type="submit" id="signupSubmit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <?php require_once FOOTER_PATH ?>
    <?php require_once FOOT_PATH ?>
    <script src="<?= BASE_URL ?>js/signup.js"></script>
</body>
</html>