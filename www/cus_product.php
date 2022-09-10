<?php
    session_start();
    ob_start();

    // Check if logged in
    include_once('check_logged.php');
    
    // Function to check if name value equal to value from GET from url
    function name_check($arr) { 
        return $arr["name"] == $_GET['name']; 
    };

    // Take the value out from json file
    $products = json_decode(file_get_contents("../storage/product.json"), true);
    // Slice 1 value from product if the name equal to data in url
    $t_product = array_slice(array_filter($products, "name_check"), 0, 1);
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
    <link rel="stylesheet" href="./assets/css/cus.css">
</head>

<body>
    <!-- Header section -->
    <header>

        <div class="brand">
            <img src="assets/img/logo.png" alt="Website's logo" class="brand__logo">
            <p class="brand__text">Zalada</p>
        </div>

        <!-- Nav bar -->
        <nav>
            <ul class="nav_pc_container">
                <li class="nav_pc_item">
                    <img src="<?php echo $_SESSION['user']['avatar']?>" alt="User's avatar" class="nav_pc_item__avt">
                    <ul class="account_setting_container hide">
                        <li class="account_setting_item">
                            <a href="my_account.php">My account</a>
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

        <div class="main_header">
            <!-- Back to cus_main -->
            <div class="cus_back_to_main">
                <a href="./cus_main.php" class="cus_back_to_main__btn">Back</a>
            </div>
            <!-- Shopping cart -->
            <div class="shopping_cart">
                <a id="shopping_cart_link" href="./cus_cart.php">
                    To cart
                </a>
            </div>

        </div>
        <?php
            $image = $t_product[0]['image'];
            $name = $t_product[0]['name'];
            $price = $t_product[0]['price'];
            $desc = $t_product[0]['desc'];
            echo "<div class=\"detail_product_list__item\">";
            echo "<div class=\"detail_product_list__item_price\">";
            echo    "<h2>$$price</h2>";
            echo "</div>";
            echo "<div class=\"detail_product_list__item_img\">";
            echo    "<img src=\"./assets/product_img/$image\" alt=\"$image\">";
            echo "</div>";
            echo "<div class=\"detail_product_list__item_name\">";
            echo     "<h2>$name</h2>";
            echo "</div>";
            echo "<div class=\"detail_product_list__item_desc\">";
            echo    "<p>$desc</p>";
            echo "</div>";
            echo "<div class=\"detail_product_list__item_add\">";
            echo     "<button class=\"add_to_cart\" onclick=\"addProduct()\">Add to cart </button>";
            echo "</div>";
            echo  "</div>";
        ?>
    </main>

    <!-- Footer section -->
    <?php 
        include_once('./footer.php')
    ?>

    <!-- Drop-down account setting -->
    <script src="./assets/js/account_setting.js"></script>

    <script>
    // Add product to cart in LOCAL STORAGE
    var cart = []

    function addProduct() {
        // Take the data in cart in LOCAL STORAGE
        let storage = localStorage.getItem('cart')
        if (storage) {
            cart = JSON.parse(storage)
        }

        // Take all data of the product by query selector and innerHTML
        var productName = document.querySelector('.detail_product_list__item_name h2').innerHTML;
        var productPrice = document.querySelector('.detail_product_list__item_price h2').innerHTML;
        var productDetail = document.querySelector('.detail_product_list__item_desc p').innerHTML;
        var image = document.querySelector(".detail_product_list__item_img img").alt;
        // Append to product object
        var product = {
            name: productName,
            price: productPrice,
            detail: productDetail,
            image: image,
        }

        // Check if the product already exists in the cart or not
        let item = cart.find(c => c.product.name == productName)
        if (item) {
            // If yes, increase the quantity instead
            item.quantity += 1
            // If not, push the product with quantity equal to 1
        } else {
            cart.push({
                product,
                quantity: 1
            })
        }

        // Add the new cart array into LOCAL STORAGE
        localStorage.setItem('cart', JSON.stringify(cart))

        // Alert Added
        alert('Added to cart')
    }
    </script>

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
</body>

</html>