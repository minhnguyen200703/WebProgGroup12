<?php
    session_start();
    ob_start();

// Check if logged in
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == false) {
        header("Location: ./index.php"); 
        exit();
    };

    $hub_user = $_SESSION['user']['distribution_hub'];

    
    if(file_exists('../storage/hubs.db')) {
        $hubs_file = fopen('../storage/hubs.db', 'r');
        while(($hub = fgets($hubs_file)) !== false) {
            $hub_details = explode('|', $hub);
           if ($hub_user == $hub_details[1]) {
            $hub_id =  $hub_details[0];
            break;
        }
        }
    }

;?>

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
    <link rel="stylesheet" href="./assets/css/shipper.css">
</head>

<body>
    <!-- Header section -->
    <header>

        <div class="brand">
            <img src="./assets/img/logo.png" alt="Website's logo" class="brand__logo">
            <p class="brand__text">Zalada</p>
        </div>

        <!-- Nav bar -->
        <nav>
            <ul class="nav_pc_container">
                <li class="nav_pc_item">
                    <img src="<?php echo $_SESSION['user']['avatar']?>" alt="User's avatar" class="nav_pc_item__avt">
                    <ul class="account-setting-container hide">
                        <li>
                            <h3>Hi <?php echo $_SESSION['user']['real_name'] ?></h3>
                        </li>
                        <li class="account-setting-item">
                            <a href="./my_account.php">My account</a>
                        </li>
                        <li class="account-setting-item">
                            <a href="./index.php">Log out</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <h1><?php echo $_SESSION['user']['real_name'] ?></h1>
                </li>
            </ul>
        </nav>

    </header>

    <!-- Main section -->
    <main>
        <!-- Main header -->
        <div class="main_header">
            <div class="hub_info">
                <h2>
                    Hub name: <?php echo $hub_user?>
                </h2>
            </div>
        </div>

        <!-- Display all product corresspinding to the hub's registered in a table -->
        <div class="shipper_product_display">
            <table border=1px class="cus_cart_list">
                <thead>
                    <th>No</th>
                    <th>Order by</th>
                    <th>Access</th>
                </thead>

                <tbody class="cus_cart_body">
                    <!-- Render order -->
                    <?php
                                // Take the order data from order.json file
                            $order = json_decode(file_get_contents("../storage/order.json"), true);
                            $index = 0;
                            // Check if the file have any data
                            if (is_null($order)) {
                                print_r('There is no order');
                            } else {
                                // Loop the array with the $index will be the id for satisfied order, and the $key is the real index in the json file
                            foreach ($order as $key => $orderDetail) {
                                // Check if the status of order is active
                                if ($orderDetail[3] == "active") {
                                    // Check if the current user's hub equal to the order's hub
                                if ($hub_id == $orderDetail[2]) {
                                    ++$index;
                                        // Write down order as a row in table; link to the shipper detail page with url appended with real index in json file, get by $GET method
                                    echo "
                                    <tr>
                                        <td>$index</td>
                                        <td>$orderDetail[1]</td>
                                        <td>
                                            <a class=\"btn-hover color-1\" href=\"./shipper_detail.php?index=$key\">➤</a>
                                        </td>
                                    </tr>
                                    "
                                    ;
                                }
                            }}};
                        ?>
                </tbody>
            </table>
        </div>

    </main>


    <footer>
        <nav class="nav_footer">
            <ul class="nav_pc_container">
                <li class="nav_pc_item">
                    <a href="./about.html" class="nav_pc_item__link">About</a>
                </li>
                <li class="nav_pc_item">
                    <a href="./privacy_policies.html" class="nav_pc_item__link">Policies</a>
                </li>
                <li class="nav_pc_item">
                    <a href="#" class="nav_pc_item__link">Help</a>
                </li>
                <li class="nav_pc_item">
                    <a href="#" class="nav_pc_item__link">Contact</a>
                </li>
            </ul>
        </nav>
        <div class="group_name">
            <p>©2022 Group 7</p>
        </div>
    </footer>

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