<?php
    session_start();
    ob_start();

    // Check if logged in
    include_once('check_logged.php');
    
    // Take the order value base on $_GET method
    if (isset($_GET['index'])) {
        $index = $_GET['index'];
    }
    
    // Read data and take all the order relevant information 
    $file = json_decode(file_get_contents("../storage/order.json"), true);
    $order = json_decode($file[$index][0],true);
    $address = $file[$index][4];
    $status = $file[$index][3];
    $user = $file[$index][1];
    
    // Check if user update status
    if (isset($_GET['update_status'])) {
        $new_status = $_GET['update_status'];
        $file[$index][3] = $new_status;
        file_put_contents("../storage/order.json", json_encode($file));
        echo "<script>alert('Order status has been changed')</script>";
        header('Location: '."./shipper_main.php");
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
                    <ul class="account_setting_container hide">
                        <li class="account_setting_item">
                            <a href="./my_account.php">My account</a>
                        </li>
                        <li class="account_setting_item">
                            <a href="./index.php">Log out</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <h2><?php echo $_SESSION['user']['real_name'] ?></h2>
                </li>
            </ul>
        </nav>

    </header>

    <!-- Main section -->
    <main>
        <!-- Main header -->
        <div class="main_header">
            <!-- Back to cus_main -->
            <div class="shipper_back_to_main">
                <a href="./shipper_main.php" class="shipper_back_to_main__btn back_btn" id="shipper_back_btn">Back</a>
            </div>
        </div>
        <div class="hub_info">
            <h1>Shipping Details</h1>
        </div>

        <!-- Buyer personal inforamtion -->
        <div class="buyer_detail_container">
            Orderer: <span><?php echo $user ?></span> <br>
            Address: <span><?php echo $address ?> </span> <br>
        </div>

        <!-- Order detail -->
        <div class="order_detail_container">
            <table class="shipper_cart_list">
                <thead class="order_head">
                    <tr>
                        <th>No</th>
                        <th>Product Image</th>
                        <th>Product Name</th>
                        <th>Product Price</th>
                        <th>Quantity</th>
                    </tr>
                </thead>

                <tbody class="order_body shipper_cart_body">
                    <?php
                        $total_price = 0;
                        foreach ($order as $key => $product) {
                            $no = $key + 1;
                            $product_detail = $product['product'];
                            $name = $product_detail['name'];
                            $image = $product_detail["image"];
                            $price = $product_detail["price"];
                            $quantity = $product['quantity'];
                            $total_price += (int)substr($price, 1) * (int)$quantity;
                            echo "
                                <tr class=\"shipper_item_list\">
                                    <td>$no</td>
                                    <td>
                                        <img src=\"./assets/product_img/$image\" alt=\"Product's image\">
                                    </td>
                                    <td>$name</td>
                                    <td>$price</td>
                                    <td>$quantity</td>
                                </tr>
                                "
                                ;
                            };
                        ?>
                </tbody>
            </table>
        </div>

        <div class="order_information_container">
            Total price: <span><?php echo $total_price ?></span><br>
            Current Status: <span><?php echo $status ?></span>
        </div>

        <div class="order_status_btn">
            <button class="delivered btn-hover color-1">
                Deliver
            </button>

            <button class="canceled btn-hover color-1">
                Cancel
            </button>
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
    var accountSetting = document.querySelector('.account_setting_container');

    avatarElement.onclick = function() {
        if (accountSetting.classList.contains('hide')) {
            accountSetting.classList.remove('hide');
        } else {
            accountSetting.classList.add('hide');
        }
    }
    </script>

    <script>
    // Get current url function
    function getCurrentURL() {
        return window.location.href
    }

    // Change status function
    function changeStatus(status) {
        window.location.href = url.concat("&", "update_status=", status)
    }

    var deliver = document.querySelector('.delivered')
    var cancel = document.querySelector('.canceled')
    const url = getCurrentURL()

    // Run the change status function as click on delivered button
    deliver.addEventListener('click', function() {
        changeStatus('delivered')
    })

    // Run the change status function as click on canceled button
    cancel.addEventListener('click', function() {
        changeStatus('canceled')
    })
    </script>

</body>

</html>