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
    <link rel="stylesheet" href="./assets/css/cus.css">
</head>

<body>
    <!-- Header -->
    <header>
        <div class="brand">
            <img src="./assets/img/logo.png" alt="Website's logo" class="brand__logo">
            <p class="brand__text">Zalada</p>
        </div>
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

        <div class="main_header">
            <!-- Back to cus_main -->
            <div class="cus_back_to_main">
                <a href="./cus_main.php" class="cus_back_to_main__btn">Back</a>
            </div>
        </div>

        <div class="cus_cart_container">
            <h1>Shopping cart</h1>
            <table id="cus_cart_list">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Product Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Description</th>
                        <th class="remove_section">Remove</th>
                    </tr>
                </thead>

                <tbody class="cus_cart_body">

                </tbody>
            </table>

            <div class="cus_cart_btn_container">
                <button class="btn-hover color-9 checkout_btn">
                    Check out
                </button>

                <button class="btn-hover color-9 reset_card">
                    Reset
                </button>
            </div>
        </div>
    </main>

    <!-- Footer section -->
    <?php 
        include_once('./footer.php')
    ?>

    <!-- Drop-down account setting -->
    <script src="./assets/js/account_setting.js"></script>

    <script>
    // The function to show cart data from LOCAL STORAGE
    showCart()

    function showCart() {
        // Take the cartbody append to a variable, or the tbody of the table
        let cardBody = document.querySelector(".cus_cart_body")
        cardBody.innerHTML = ""

        // Get the data in variable cart in local storage
        let storage = localStorage.getItem('cart')
        if (storage) {
            cart = JSON.parse(storage)

        }
        // If there is no product, annouce
        else {
            cardBody.innerHTML += `<span>There is no product here</span>`
        }

        // Loop the cart to take all product
        cart.forEach((product, index) => {
            let productId = index
            index++

            // Append the product to the cardBody, or the tbody, as the forEach run
            cardBody.innerHTML += `
            <tr class=\"cus_item_list\">
                <td>${productId + 1}</td>
                <td><img src="./assets/product_img/${product.product.image}" alt="Product's image"></td>
                <td>${product.product.name}</td>
                <td>${product.product.price}</td>
                <td>${product.quantity}</td>
                <td>${product.product.detail}</td>
                <td>
                    <a href="#" onclick="removeItem(${productId})" class= 'remove_btn btn-hover color-9' >Remove</a>
                </td>
            </tr>
        `
        })

    };

    // Get random value function
    function random(maxValue) {
        return Math.floor(Math.random() * maxValue)
    }

    // Send order function
    function orderSend() {
        // Take the data from LOCAL STORAGE
        let storage = (localStorage.getItem('cart'))
        // Randomly take 1 value from the three value
        let distributionHub = random(3)
        // Redirect the url with all the information of the order: products, customer's username, customer's address, hub
        window.location.href =
            `./cus_main.php?cart=${storage}&user=<?php echo $_SESSION['user']['username'] ?>&hub=${distributionHub}&address=<?php echo $_SESSION['user']['address'] ?>`
        // Reset cart in LOCAL STORAGE
        localStorage.removeItem('cart')
        // Alert
        alert('Order placed. Thank you for your order!')
    }

    // Remove selected item
    function removeItem(id) {
        // Take the data from LOCAL STORAGE
        let storage = localStorage.getItem('cart')
        if (storage) {
            cart = JSON.parse(storage)
        }

        // Remove the product with the given ID, aka index, in the order table
        cart.splice(id, 1)
        // Write the new cart into LOCAL STORAGE
        localStorage.setItem('cart', JSON.stringify(cart))
        // Run the show cart data to update
        showCart()
    }


    // As the reset cart button clicked, remove all data in cart in LOCAL STORAGE
    document.querySelector(".reset_card").addEventListener("click", function() {
        localStorage.removeItem('cart')
        location.reload()
    })
    </script>
    <script>
    // If the checkout is clicked, run orderSend function
    document.querySelector(".checkout_btn").addEventListener("click", orderSend)
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