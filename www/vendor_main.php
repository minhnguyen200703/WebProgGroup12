<?php
    session_start();
    ob_start();

    // Check if logged in
    include_once('check_logged.php');
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
    <link rel="stylesheet" href="./assets/css/vendor.css">
</head>

<body>
    <!-- Header section -->
    <header>

        <!-- Logo -->
        <div class="brand">
            <img src="./assets/img/logo.png" alt="Website's logo" class="brand__logo">
            <p class="brand__text">Zalada</p>
        </div>

        <!-- Navigation bar -->
        <nav>
            <ul class="nav_pc_container">

                <!-- User -->
                <li class="nav_pc_item">
                    <img src="<?php echo $_SESSION['user']['avatar']?>" alt="User's avatar" class="nav_pc_item__avt">
                    <ul class="account-setting-container hide">
                        <li class="account-setting-item">
                            <a href="./my_account.php">My account</a>
                        </li>
                        <li class="account-setting-item">
                            <a href="./index.php">Log out</a>
                        </li>
                    </ul>
                </li>
                <li class="nav_pc_item">
                    <h2><?php echo $_SESSION['user']['business_name'] ?></h2>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Main section -->

    <main>

        <!-- Choosing section -->
        <div class="vendor_main__heading">
            <h1> Pick a Section: </h1>
        </div>
        <div class="vendor_main_container">
            <a href="vendor_add_product.php">
                <div class="vendor_add_product__btn btn-hover  color-2">
                    Add product
                </div>
            </a>
            <h2> Or </h2>
            <a href="vendor_product.php">
                <div class="vendor_view_product__btn  btn-hover  color-2">
                     View product
                </div>
            </a>
        </div>
    </main>

    <!-- Footer section -->
    <?php 
        include_once('./footer.php')
    ?>

    <!-- Drop-down account setting -->
    <script src="./assets/js/account_setting.js"></script>

    <script>
    // Open the Accouunt setting subnav bar

    var avatarElement = document.querySelector('.nav_pc_item__avt');
    var accountSetting = document.querySelector('.account-setting-container');

    avatarElement.onclick = function() {
        if (accountSetting.classList.contains('hide')) {
            accountSetting.classList.remove('hide');
        } else {
            accountSetting.classList.add('hide');
        }
    }
    </script>

</body>

</html>