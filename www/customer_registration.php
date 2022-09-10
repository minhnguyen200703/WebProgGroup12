<?php 
    session_start();
    ob_start();
    
    // Valide at server side again before saving user input to the text file.
    include_once('validator.php');
    $is_duplicate = false;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get user input via POST request
        $username = isset($_POST["username"]) ? $_POST['username'] : '';
        $password = isset($_POST["password"]) ? $_POST['password'] : '';
        $_SESSION['hashed_password'] = password_hash($password, PASSWORD_DEFAULT);
        $avatar = basename($_FILES['avatar']['name']);
        $real_name = isset($_POST["name"]) ? $_POST['name'] : '';
        $address = isset($_POST["address"]) ? $_POST['address'] : '';

        // Check whether username is already registered
        if (file_exists('../storage/accounts.db')) {
            $account_file = fopen('../storage/accounts.db', 'r');
            if ($account_file) {
                // Read the file line by line
                while (($account = fgets($account_file)) !== false) {
                    $account_details = explode('|', $account);
                    if ((trim($username) == trim($account_details[1]))) {
                        $is_duplicate = true;
                        break;
                    }
                }
                fclose($account_file);
            }
        }

        // Validate username at sever side
        if (empty($username)) {
            $usernameErr = "Name is required";
        } else if (!checkBetweenLength($username, 8, 15))  {
            $usernameErr = "The length of username must be between 6 and 20characters";
        } else {
            $usernameErr = "";
            $username = test_input($_POST["username"]);
        }

        // Validate password at sever side
        if (empty($password)) {
            $password_err = "Password is required";
        }  else if (!checkLowerCase($password))  {
            $password_err = "At least one lowercase character is required";
        } else if (!checkUpperCase($password))  {
            $password_err = "At least one uppercase character is required";
        } else if (!checkSymbol($password))  {
            $password_err = "At least one symbol is required";
        } else if (!checkNumber($password))  {
            $password_err = "At least one number is required";
        } else if (!checkBetweenLength($password, 6, 20))  {
            $password_err = "The length of username must be between 6 and 20 characters";
        } else {
            $password_err = "";
            $password = test_input($_POST["password"]);
        }

        // Validate real name of user at sever side
        if (empty($real_name)) {
            $real_name_err = "Password is required";
        } else if (!checkMinLength($real_name, 5)) {
            $real_name_err = "At least 5 characters is required";
        } else {
            $real_name_err = "";
            $real_name = test_input($_POST["name"]);
        }

        // Validate user address at sever side
        if (empty($address)) {
            $address = "Password is required";
        } else if (!checkMinLength($address, 5)) {
            $address = "At least 5 characters is required";
        } else {
            $address_err = "";
            $address = test_input($_POST["address"]);
        }
        
        // If all form fields satisfy the requirements, save the user data to the text file
        if ($username && $password && $real_name && $address && !$is_duplicate) {
            $user_type = "type1";  //type 1 represents for customer accounts
            include_once('save_user_to_txt.php');
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zalada</title>

    <!-- Font -->
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&family=Hachi+Maru+Pop&family=Hubballi&family=Inter:wght@300;400;500;600;700;800&family=Kalam:wght@700&family=Montserrat:wght@254&family=Open+Sans:wght@326;379&family=Permanent+Marker&family=Poppins:wght@100&family=Roboto:wght@300;400;700&family=Rubik+Glitch&display=swap');
    </style>

    <!-- CSS -->
    <link rel="stylesheet" href="./assets/css/base.css">
    <link rel="stylesheet" href="./assets/css/registration_pages.css">
</head>

<body>
    <header>
        <a href="./index.php" class="back_btn" id="customer_back_btn"><i
                class="fa-solid fa-chevron-left"></i>Back</a>
    </header>
    <main>
        <div class="registration customer">
            <div class="registration__left">
                <img src="./assets/img/customer_registration.png" alt="Registration background image">
                <div class="registration__left_content">
                    <h2>Always welcome</h2>
                    <h3>Nice to meet u!</h3>
                    <hr>
                    <p class="registration__left_content__desc">
                        Lorem Ipsum is simply dummy text of the Lorem Ipsum has been the industry's standard dummy text
                        ever since the 1500s, and scrambled it to make a type specimen book.
                    </p>

                    <ul class="registration__left__more">
                        <li>
                            <a href="./privacy_policies.html">Policies</a>
                        </li>
                        <li>
                            <a href="./about.html">About</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="registration__right">
                <h2>Customer Registration</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST"
                    enctype="multipart/form-data" class="registration__form" id="form_1">
                    <div class="form_field">
                        <label for="avatar" class="edit_btn form_field__label">
                            <img src="./assets/img/mock_avt2.png" alt="Avatar image" class="avt_block">
                        </label>
                        <input type="file" name="avatar" accept="image/*" id="avatar" onchange="loadFile(event)"
                            style="display: none">
                        <!-- <img src="data;" alt="Preview image" id="output" style="display: none;"> -->
                        <span class="form_field__message">

                        </span>
                    </div>

                    <div class="form_field">
                        <label for="username" class="form_field__label">Username</label>
                        <input type="text" class="form_field__input" id="username" placeholder="Enter username"
                            name="username">
                        <span class="form_field__message">
                            <?php 
                                echo isset($usernameErr) ? $usernameErr : '';
                                if (isset($is_duplicate) && $is_duplicate) {
                                    echo 'Username already exists';
                                } 
                            ?>
                        </span>
                    </div>

                    <div class="form_field">
                        <label for="username" class="form_field__label">Password</label>
                        <input type="text" class="form_field__input" id="password" placeholder="Enter password"
                            name="password">
                        <span class="form_field__message">
                            <?php echo isset($passwordErr) ? $passwordErr : ''  ?>
                        </span>
                    </div>

                    <div class="form_field">
                        <label for="username" class="form_field__label">Name</label>
                        <input type="text" class="form_field__input" id="name" placeholder="Enter your name"
                            name="name">
                        <span class="form_field__message">

                        </span>
                    </div>

                    <div class="form_field">
                        <label for="address" class="form_field__label">Address</label>
                        <input type="text" class="form_field__input" id="address" placeholder="Enter your address"
                            name="address">
                        <span class="form_field__message"></span>
                    </div>

                    <div class="form_field">
                        <input type="submit" class="form_field__label btn-hover color-9" id="submit_btn"
                            name="submit_btn" value="Register">
                    </div>

                    <div class="registration__right__more">
                        <p>Already have an account? <a href="./index.php" class="login_link">Login</a> now</p>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- JS CODE -->
    <!-- Upload image script -->
    <script src="./assets/js/preview.js"></script>

    <!-- Validation Client Side (JS) -->
    <script src="./assets/js/validator.js"></script>
    <script>
    var username = document.getElementById('username');
    var password = document.getElementById('password');
    var avatar = document.getElementById('avatar');
    var realName = document.getElementById('name');
    var address = document.getElementById('address');
    var submitButton = document.getElementById('submit_btn');

    function formValidation() {
        let usernameValue = username.value.trim();
        let passwordValue = password.value.trim();
        let nameValue = realName.value.trim();
        let addressValue = address.value.trim();

        let isValidUsername = false;
        let isValidPassword = false;
        let isFileUploaded = false;

        // Validate username at client side
        if (!usernameValue) {
            showError(username, 'Not be blank')
        } else if (!onlyLettersAndNumbers(usernameValue)) {
            showError(username, 'Only letters and numbers are allowed')
        } else if (!checkBetweenLength(usernameValue, 8, 15)) {
            showError(username, 'The length of username must be between 8 and 15 characters')
        } else {
            isValidUsername = true;
            showSuccess(username)
        }

        // Validate username at client side
        if (!passwordValue) {
            showError(password, 'Not be blank')
        } else if (!checkLowerCase(passwordValue)) {
            showError(password, 'At least one lowercase character is required')
        } else if (!checkUpperCase(passwordValue)) {
            showError(password, 'At least one uppercase character is required')
        } else if (!checkSymbol(passwordValue)) {
            showError(password, 'At least one symbol is required')
        } else if (!checkNumber(passwordValue)) {
            showError(password, 'At least one number is required')
        } else if (!checkBetweenLength(passwordValue, 6, 20)) {
            showError(password, 'The length of password must be between 6 and 20 characters')
        } else {
            isValidPassword = true;
            showSuccess(password)
        }

        // Validate other fields (real name of user, user address)
        let isValidName = otherFieldValidation(realName);
        let isValidAddress = otherFieldValidation(address);

        if (!checkFileUpload(avatar)) {
            showError(avatar, 'Not uploaded')
        } else {
            isFileUploaded = true;
            showSuccess(avatar)
        }

        if (isValidUsername && isValidPassword && isValidName && isValidAddress && isFileUploaded) {
            return true
        }
        return false
    }

    // When click on submit button
    submitButton.addEventListener("click", function(event) {
        let isValid = formValidation()
        if (!isValid) {
            event.preventDefault()
        }
    });
    </script>
</body>

</html>

<?php 
    ob_end_flush();
?>