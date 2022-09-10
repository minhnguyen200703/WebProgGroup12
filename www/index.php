<?php
    session_start();
    // Initialize the session variables "logged_in" and "user"
    $_SESSION['logged_in'] = false;
    $_SESSION['user'] = [];
    
    if (isset($_POST['submit_btn']) && $_POST['submit_btn']) {
        $username = $_POST['login_username'];
        $password = $_POST['login_password'];
        if (file_exists('../storage/accounts.db')) {
            $account_file = fopen('../storage/accounts.db', 'r');
            if ($account_file) {
                // Read the file line by line
                while (($account = fgets($account_file)) !== false) {
                    $account_details = explode('|', $account);
                    
                    if ((trim($username) == trim($account_details[1])) && (trim(password_verify($password, $account_details[2])))) {
                        $_SESSION['logged_in'] = true;
                        $_SESSION['user'] = [
                            'user_type' => $account_details[0],
                            'username'=> $account_details[1],
                            'password' => $account_details[2],
                            'avatar' => $account_details[3],
                        ];
                    
                        if (trim($account_details[0]) == "type1") {
                            $_SESSION['user']['real_name'] = $account_details[4];
                            $_SESSION['user']['address'] = $account_details[5];
                            header("Location: ./cus_main.php"); 
                            exit();
                        } else if (trim($account_details[0]) == "type2") {
                            $_SESSION['user']['business_name'] = $account_details[4];
                            $_SESSION['user']['business_address'] = $account_details[5];
                            header("Location: ./vendor_main.php"); 
                            exit();
                        } else if(trim($account_details[0]) == "type3") {
                            $_SESSION['user']['real_name'] = $account_details[4];
                            $_SESSION['user']['distribution_hub'] = $account_details[5];
                            header("Location: ./shipper_main.php"); 
                            exit();
                        }
                    }
                }
                fclose($account_file);
            }
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
    
    <!-- Font families -->
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&family=Hachi+Maru+Pop&family=Hubballi&family=Inter:wght@300;400;500;600;700;800&family=Kalam:wght@700&family=Montserrat:wght@254&family=Open+Sans:wght@326;379&family=Permanent+Marker&family=Poppins:wght@100&family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,700&family=Rubik+Glitch&display=swap');
    </style>

    <!-- CSS -->
    <link rel="stylesheet" href="./assets/css/base.css">
    <link rel="stylesheet" href="./assets/css/main.css">
</head>

<body>
    <!-- Header section -->
    <header>
        <div class="brand">
            <img src="./assets/img/logo.png" alt="" class="brand__logo">
            <p class="brand__text">Zalada</p>
        </div>
        <nav>
            <ul class="nav_pc_container">
                <li class="nav_pc_item">
                    <a href="#" class="nav_pc_item__link" id="open_login_form">Login</a>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Main content -->
    <main>
        <div class="homepage_content">
            <div class="homepage_content__intro">
                <p class="static_txt">We are <span class="dynamic_txt"></span><span class="cursor">&nbsp;</span></p>
                <p>The best e-commerce platform in Southest Asia</p>
                <p>Or register as</p>
                <div class="homepage_content__user_roles">
                    <a class="btn-hover color-9" href="customer_registration.php">Customer</a>
                    <a class="btn-hover color-2" href="vendor_registration.php">Vendor</a>
                    <a class="btn-hover color-1" href="shipper_registration.php">Shipper</a>
                </div>
            </div>
            <div class="homepage_content__img">
                <img src="./assets/img/homepage_bg.png" alt="">
            </div>

            <div class="homepage_content__overlay hide"></div>
            <div class="homepage_content__login">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" id="login_form" class="hide">
                    <img class="homepage_content__login__close" src="./assets/img/close_icon.png" alt="">
                    <div class="login_form__header">Login</div>
                    <div class="login_form_field">
                        <label for="login_username" class="login_form_field__label">Username</label>
                            <input type="text"  name="login_username" id="login_username" class="login_form_field__input" placeholder="Enter username"/>
                    </div>

                    <div class="login_form_field">
                        <label for="login_password" class="login_form_field__label">Password</label>
                        <input type="password" name="login_password" id="login_password" class="login_form_field__input" placeholder="Enter password"/>
                    </div>

                    <div class="login_form_field">
                        <span class="login_form_field__message">
                            <?php 
                                if (isset($_POST['submit_btn'])) {
                                    if (!$_SESSION['logged_in']) {
                                        echo "Wrong username or password.";
                                    }
                                }
                            ?>
                        </span>
                    </div>
                    <div class="login_form_field">
                        <input type="submit" name="submit_btn" id="submit" value = "Login"/>
                    </div>
                </form>
            </div>
            
        </div>
    </main>

   
    <!-- Footer section -->
    <?php
        include_once('./footer.php')
    ?>

    <!-- JS code -->
    <script>
        var dynamicText = document.querySelector('.dynamic_txt')
        const textArray = ['Zalada', 'Amazon', 'Shopee']
        const typingDelay = 180;
        const erasingDelay = 100;
        const newTextDelay = 2000
        let i = 0
        let j = 0

        function type() {
            if (i < textArray[j].length) {
                dynamicText.textContent += textArray[j].charAt(i)
                i++
                setTimeout(type, typingDelay)
            } else {
                setTimeout(erase, newTextDelay)
            }
        }

        function erase() {
            if (i > 0) {
                dynamicText.textContent = textArray[j].substring(0, i - 1)
                i--
                setTimeout(erase, erasingDelay)
            } else {
                j++
                if (j >= textArray.length) j = 0
                setTimeout(type, typingDelay + 1100)
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(type, newTextDelay + 250)
        })
    </script>
    <script src="./assets/js/validator.js"></script>

    <!-- Open/close login form -->
    <script>
        var loginForm = document.querySelector('#login_form');
        var closeBtn = document.querySelector('.homepage_content__login__close');
        var openBtn = document.querySelector('#open_login_form'); 
        var overlayBox = document.querySelector('.homepage_content__overlay')

        function displayForm() {
            loginForm.classList.remove('hide');
            overlayBox.classList.remove('hide');
        }

        function hideForm() {
            loginForm.classList.add('hide');
            overlayBox.classList.add('hide');
        }

        closeBtn.onclick = hideForm;
        openBtn.onclick = displayForm;
    </script>

    <?php
    if (isset($_POST['submit_btn'])) {
        if (!$_SESSION['logged_in']) {
            echo '<script> displayForm() </script>';
        }
    }
    ?>
</body>
</html>