<?php
class Ajax
{
    private $_db;
    public function __construct()
    {
        if(strtolower($_SERVER["REQUEST_METHOD"] ?? "") != "post") {
            http_response_code(404);
            exit;
        }
        $this->_db = new MYSQLDatabase();
    }

    public function loginCheck()
    {
        $this->_isAjax();
        $this->_verifyToken("loginToken", $_POST["csrf_token"] ?? FALSE);
        $this->_validateSubmitLogin();
        $emailOrPhone = $this->_db->escape($_POST["loginUser"]);
        $password = enrcypt_password($_POST["loginPassword"]);
        $where = "password = '$password' AND (email = '$emailOrPhone' OR phone = '$emailOrPhone')";
        $res = $this->_db->select("users", "id", $where, "id", 1)[0]['id'] ?? NULL;
        $this->_expireToken("loginToken");
        if(!$res) {
            exit(json_encode([
                "status"    =>  false,
                "message"   =>  "Invalid Credentials, Please try again",
                "redirect"  =>  BASE_URL
            ]));
        }
        $_SESSION["LOGIN"] = $res;
        exit(json_encode([
            "status"    =>  true,
            "message"   =>  "Login Success, you will be redirected soon...",
            "redirect"  =>  BASE_URL . "profile.php"
        ]));
    }

    public function register()
    {
        $this->_isAjax();
        $this->_verifyToken("signupToken", $_POST["csrf_token"] ?? FALSE);
        $this->_validateSubmitRegister();
        $this->_insertUser();
    }

    public function updateProfile()
    {
        if(!isset($_SESSION["LOGIN"])) {
            http_response_code(403);
            exit("Access Denied");
        }
        $this->_isAjax();
        $this->_verifyToken("profileToken", $_POST["csrf_token"] ?? FALSE);
        $this->_validateSubmitProfile();
        $this->_updateUser();
    }

    public function logoutSession()
    {
        $this->_isAjax();
        if(isset($_SESSION["LOGIN"])) {
            unset($_SESSION["LOGIN"]);
            exit(json_encode(["status" => true, "message" => "Logout Success", "redirect" => BASE_URL]));
        }
        http_response_code(401);
    }

    private function _validateSubmitRegister()
    {
        $validationErrors = [];
        $errorFields = [];
        $postLabels = [
            "type"                  =>  "Type",
            "csrf_token"            =>  "CSRF token",
            "signupName"            =>  "Name",
            "signupEmail"           =>  "Email",
            "signupPhone"           =>  "Phone",
            "signupPassword"        =>  "Password",
            "signupAge"             =>  "Age",
            "signupGender"          =>  "Gender",
            "signupQualification"   =>  "Qualification",
            "signupAddress"         =>  "Address",
            "signupCity"            =>  "City",
            "signupState"           =>  "State",
        ];

        if(array_keys($_POST) != array_keys($postLabels) || !empty($_FILES["signupProfilePicture"]["error"])) {
            http_response_code(400);
            exit("Bad Request");
        }

        foreach($postLabels as $postKey => $postKeyLabel) {
            if(in_array($postKey, ["type", "csrf_token"])) {
                continue;
            }
            if(empty($_POST[$postKey])) {
                $validationErrors[] =   "$postKeyLabel is Required";
                $errorFields[] = "#$postKey";
            }
        }
        $regexArray = [
            "signupEmail"   =>  REGEX_EMAIL,
            "signupPhone"   =>  REGEX_PHONE,
            "signupAge"     =>  REGEX_AGE
        ];

        foreach($regexArray as $postKey => $regex) {
            if(!preg_match($regex, $_POST[$postKey])) {
                $validationErrors[] = "Invalid " . $postLabels[$postKey];
                $errorFields[] = "#$postKey";
            }
        }

        $this->_validateProfilePicture("signupProfilePicture", $validationErrors, $errorFields);
        
        if(count($validationErrors) > 0) {
            exit(json_encode([
                "status"        =>  false,
                "message"       =>  implode("<br>", $validationErrors),
                "errorFields"   =>  implode(", ", $errorFields)
            ]));
        }
    }

    private function _validateSubmitLogin()
    {
        $validationErrors = [];
        $errorFields = [];
        $postLabels = [
            "type"                  =>  "Type",
            "csrf_token"            =>  "CSRF token",
            "loginUser"             =>  "Email or Phone",
            "loginPassword"         =>  "Password"
        ];

        if(array_keys($_POST) != array_keys($postLabels)) {
            http_response_code(400);
            exit("Bad Request");
        }

        foreach($postLabels as $postKey => $postKeyLabel) {
            if(in_array($postKey, ["type", "csrf_token"])) {
                continue;
            }
            if(empty($_POST[$postKey])) {
                $validationErrors[] =   "$postKeyLabel is Required";
                $errorFields[] = "#$postKey";
            }
        }

        if(count($validationErrors) > 0) {
            exit(json_encode([
                "status"        =>  false,
                "message"       =>  implode("<br>", $validationErrors),
                "errorFields"   =>  implode(", ", $errorFields)
            ]));
        }
    }

    private function _validateSubmitProfile()
    {
        $validationErrors = [];
        $errorFields = [];
        $postLabels = [
            "type"                  =>  "Type",
            "csrf_token"            =>  "CSRF token",
            "old_profile"           =>  "Old Profile Picture",
            "profileName"           =>  "Name",
            "profileEmail"          =>  "Email",
            "profilePhone"          =>  "Phone",
            "profileAge"            =>  "Age",
            "profileGender"         =>  "Gender",
            "profileQualification"  =>  "Qualification",
            "profileAddress"        =>  "Address",
            "profileCity"           =>  "City",
            "profileState"          =>  "State",
        ];

        if(array_keys($_POST) != array_keys($postLabels) || !empty($_FILES["signupProfilePicture"]["error"])) {
            http_response_code(400);
            exit("Bad Request");
        }

        foreach($postLabels as $postKey => $postKeyLabel) {
            if(in_array($postKey, ["type", "csrf_token", "old_profile"])) {
                continue;
            }
            if(empty($_POST[$postKey])) {
                $validationErrors[] =   "$postKeyLabel is Required";
                $errorFields[] = "#$postKey";
            }
        }

        $regexArray = [
            "profileEmail"   =>  REGEX_EMAIL,
            "profilePhone"   =>  REGEX_PHONE,
            "profileAge"     =>  REGEX_AGE
        ];

        foreach($regexArray as $postKey => $regex) {
            if(!preg_match($regex, $_POST[$postKey])) {
                $validationErrors[] = "Invalid " . $postLabels[$postKey];
                $errorFields[] = "#$postKey";
            }
        }

        if(!empty($_FILES["profilePicture"]["tmp_name"])) {
            $this->_validateProfilePicture("profilePicture", $validationErrors, $errorFields);
        }
        
        if(count($validationErrors) > 0) {
            exit(json_encode([
                "status"        =>  false,
                "message"       =>  implode("<br>", $validationErrors),
                "errorFields"   =>  implode(", ", $errorFields)
            ]));
        }
    }

    private function _validateProfilePicture($index, &$validationErrors, &$errorFields)
    {
        if(strtolower($_FILES[$index]["type"] ?? "") != "image/jpeg") {
            $validationErrors[] =   "Profile Picture is Invalid";
            $errorFields[]      =   "#$index";
        } else if(($_FILES[$index]["size"] ?? 0) > 1048576 ) {
            $validationErrors[] =   "Profile Picture is Greater than 1 Mb";
            $errorFields[]      =   "#$index";
        } else {
            $currentExtension   =   pathinfo($_FILES[$index]["name"], PATHINFO_EXTENSION);
            if(!in_array($currentExtension, ["jfif", "pjp", "jpg", "pjpeg", "jpeg"])) {
                $validationErrors[] =   "Profile Picture unknown extension";
                $errorFields[]      =   "#$index";
            }
        }
    }

    private function _insertUser()
    {
        $file = $this->_uploadFile("signupProfilePicture");
        $res = $this->_db->insert("users", [
            "name"          =>  htmlentities($_POST["signupName"]),
            "email"         =>  $_POST["signupEmail"],
            "phone"         =>  $_POST["signupPhone"],
            "age"           =>  $_POST["signupAge"],
            "gender"        =>  htmlentities($_POST["signupGender"]),
            "qualification" =>  htmlentities($_POST["signupQualification"]),
            "address"       =>  htmlentities($_POST["signupAddress"]),
            "city"          =>  htmlentities($_POST["signupCity"]),
            "state"         =>  htmlentities($_POST["signupState"]),
            "password"      =>  enrcypt_password($_POST["signupPassword"]),
            "file"          =>  $file,
            "created_at"    =>  date("Y-m-d H:i:s")
        ]);
        if($res) {
            $this->_expireToken("signupToken");
            exit(json_encode([
                "status"    =>  true,
                "message"   =>  "Successfully Registered, You can now login",
                "redirect"  =>  BASE_URL
            ]));
        }
        @unlink(ROOT_PATH . PROFILE_PATH . $file); // removing the uploaded file
        exit(json_encode([
            "status"    =>  false,
            "message"   =>  "Something went wrong while inserting data, Please try again later"
        ]));
    }

    private function _updateUser()
    {
        $profilePictureSet  =   !empty($_FILES["profilePicture"]["tmp_name"]);
        $data               =   [
            "name"          =>  htmlentities($_POST["profileName"]),
            "email"         =>  $_POST["profileEmail"],
            "phone"         =>  $_POST["profilePhone"],
            "age"           =>  $_POST["profileAge"],
            "gender"        =>  htmlentities($_POST["profileGender"]),
            "qualification" =>  htmlentities($_POST["profileQualification"]),
            "address"       =>  htmlentities($_POST["profileAddress"]),
            "city"          =>  htmlentities($_POST["profileCity"]),
            "state"         =>  htmlentities($_POST["profileState"]),
            "updated_at"    =>  date("Y-m-d H:i:s")
        ];
        if($profilePictureSet) {
            $file           =   $this->_uploadFile("profilePicture");
            $data["file"]   =   $file;
        }
        $res = $this->_db->update("users", $data, "id = $_SESSION[LOGIN]");
        if($res) {
            if($profilePictureSet) {
                @unlink(ROOT_PATH . PROFILE_PATH . $_POST["old_profile"]); // removing the old profile picture from storage
            }
            $this->_expireToken("signupToken");
            exit(json_encode([
                "status"    =>  true,
                "message"   =>  "Successfully Updated Profile, Please wait...",
                "redirect"  =>  BASE_URL . "profile.php"
            ]));
        }
        @unlink(ROOT_PATH . PROFILE_PATH . $file); // removing the uploaded file
        exit(json_encode([
            "status"    =>  false,
            "message"   =>  "No changes made, please recheck and try again"
        ]));
    }

    private function _isAjax()
    {
        if(strtolower($_SERVER["HTTP_X_REQUESTED_WITH"] ?? '') != "xmlhttprequest") {
            http_response_code(403);
            exit("Access Denied");
        }
    }

    private function _verifyToken($index, $input)
    {
        if(get_token($index) !== $input) {
            http_response_code(401);
            exit("Authentication Error");
        }
    }

    private function _expireToken($index)
    {
        expire_token($index);
    }
    
    private function _uploadFile($index)
    {
        $path       =   ROOT_PATH . PROFILE_PATH;
        if(!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $fileName   =   md5(microtime(true) . rand(100,999)) . "." . pathinfo($_FILES[$index]["name"], PATHINFO_EXTENSION);
        $res = copy($_FILES[$index]["tmp_name"], ROOT_PATH . PROFILE_PATH . $fileName);
        if(!$res) {
            $res = move_uploaded_file($_FILES[$index]["tmp_name"], ROOT_PATH . PROFILE_PATH . $fileName);
        }
        return  $res ? $fileName : FALSE;
    }
}