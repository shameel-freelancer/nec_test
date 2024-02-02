<?php
require_once "core/init.php";
if(!isset($_SESSION["LOGIN"])) {
    header("location:" . BASE_URL);
}
$db = new MYSQLDatabase();
$user = $db->select("users", "*", "id = $_SESSION[LOGIN]", "id", 1)[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>NEC Test | Profile</title>
    <?php require_once HEAD_PATH ?>
</head>
<body>
    <?php require_once HEADER_PATH ?>
    <div class="container py-5">
        <button class="btn btn-danger float-end" id="logoutButton">Logout</button>
        <div class="h5 text-center">Profile Form</div>
        <form id="profileForm">
            <input type="hidden" name="type" value="profile-update">
            <input type="hidden" name="csrf_token" value="<?= generate_token("profileToken") ?>"> 
            <input type="hidden" name="old_profile" value="<?= $user["file"] ?>"> 
            <div class="row mb-3">
                <div class="col-sm-12">
                    <img src="<?= BASE_URL . PROFILE_PATH . $user["file"]; ?>" class="profile-picture"/>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="profileName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="profileName" name="profileName" value="<?= $user["name"] ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="profileEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="profileEmail" name="profileEmail" value="<?= $user["email"] ?>" required pattern="<?= trim(REGEX_EMAIL, "/") ?>" title="Please Enter Valid Email">
                </div>
                <div class="col-md-4">
                    <label for="profilePhone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="profilePhone" name="profilePhone" value="<?= $user["phone"] ?>" required pattern="<?= trim(REGEX_PHONE, "/") ?>" title="Please Enter Valid Indian Phone Number">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="profileAge" class="form-label">Age</label>
                    <input type="text" class="form-control" id="profileAge" name="profileAge" value="<?= $user["age"] ?>" required pattern="<?= trim(REGEX_AGE, "/") ?>" title="Please Enter Valid Age">
                </div>
                <div class="col-md-4">
                    <label for="profileGender" class="form-label">Gender</label>
                    <select type="text" class="form-select" id="profileGender" name="profileGender" required>
                        <option value="" selected>-Select-</option>
                        <option <?= $user["gender"] == "Male" ? "selected" : "" ?> value="Male">Male</option>
                        <option <?= $user["gender"] == "Female" ? "selected" : "" ?> value="Female">Female</option>
                        <option <?= $user["gender"] == "Other" ? "selected" : "" ?> value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="profileQualification" class="form-label">Qualification</label>
                    <input type="text" class="form-control" id="profileQualification" name="profileQualification" value="<?= $user["qualification"] ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="profileAddress" class="form-label">Address</label>
                    <textarea type="text" class="form-control" id="profileAddress" name="profileAddress" required><?= $user["address"] ?></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="profileCity" class="form-label">City</label>
                    <input type="text" class="form-control" id="profileCity" name="profileCity" value="<?= $user["city"] ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="profileState" class="form-label">State</label>
                    <input type="text" class="form-control" id="profileState" name="profileState" value="<?= $user["state"] ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="profilePicture" class="form-label">Profile Picture</label>
                    <input type="file" class="form-control" id="profilePicture" name="profilePicture" accept="image/jpeg">
                </div>
            </div>
            <div class="alert alert-danger d-flex align-items-center d-none" role="alert" id="profileError">
            </div>
            <button type="submit" id="profileSubmit" class="btn btn-success">Update</button>
        </form>
    </div>
    <?php require_once FOOTER_PATH ?>
    <?php require_once FOOT_PATH ?>
    <script src="<?= BASE_URL ?>js/profile.js"></script>
</body>
</html>