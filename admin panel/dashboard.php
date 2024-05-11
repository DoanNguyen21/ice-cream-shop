<?php
    include('../components/connect.php');

    if(isset($_COOKIE['seller_id'])) {
        $seller_id = $_COOKIE['seller_id'];
    } else {
        $seller_id = '';
        header('location:login.php');
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IceCream-GinD - seller Dashboard page</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- box icon cdn link -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>

<body>

    <div class="main-container">
        <?php include('../components/admin_header.php'); ?>
        <section class="dashboard">
            <div class="heading">
                <h1>dashboard</h1>
                <img src="../image/separator-img.png" alt="">
            </div>
            <div class="box-container">
                <div class="box">
                    <h3>Welcome !</h3>
                    <p><?= $fetch_profile['name']; ?></p>
                    <a href="update.php" class="btn">Update Profile</a>
                </div>
                <div class="box">
                    <?php 
                      $select_message = $conn->prepare("SELECT * FROM `tbl_message`");
                      $select_message->execute();
                      $number_of_msg = $select_message->rowCount();
                    ?>
                    <h3><?= $number_of_msg; ?></h3>
                    <p>unread message</p>
                    <a href="admin_message.php" class="btn">See Message</a>
                </div>
                <div class="box">
                    <?php 
                      $select_products = $conn->prepare("SELECT * FROM `tbl_products` WHERE 
                      seller_id = ?");
                      $select_products->execute([$seller_id]);
                      $number_of_products = $select_products->rowCount();
                    ?>
                    <h3><?= $number_of_products; ?></h3>
                    <p>products added</p>
                    <a href="add_product.php" class="btn">Add Product</a>
                </div>
                <div class="box">
                    <?php 
                      $select_active_products = $conn->prepare("SELECT * FROM `tbl_products` WHERE 
                      seller_id = ? AND status = ?");
                      $select_active_products->execute([$seller_id,'active']);
                      $number_of_active_products = $select_active_products->rowCount();
                    ?>
                    <h3><?= $number_of_active_products; ?></h3>
                    <p>total active products</p>
                    <a href="view_product.php" class="btn">Active Product</a>
                </div>
                <div class="box">
                    <?php 
                      $select_deactive_products = $conn->prepare("SELECT * FROM `tbl_products` WHERE 
                      seller_id = ? AND status = ?");
                      $select_deactive_products->execute([$seller_id,'deactive']);
                      $number_of_deactive_products = $select_deactive_products->rowCount();
                    ?>
                    <h3><?= $number_of_deactive_products; ?></h3>
                    <p>total deactive products</p>
                    <a href="view_product.php" class="btn">Deactive Product</a>
                </div>
                <div class="box">
                    <?php 
                      $select_users = $conn->prepare("SELECT * FROM `tbl_users`");
                      $select_users->execute();
                      $number_of_users = $select_users->rowCount();
                    ?>
                    <h3><?= $number_of_users; ?></h3>
                    <p>users account</p>
                    <a href="user_accounts.php" class="btn">See Users</a>
                </div>
                <div class="box">
                    <?php 
                      $select_sellers = $conn->prepare("SELECT * FROM `tbl_sellers`");
                      $select_sellers->execute();
                      $number_of_sellers = $select_sellers->rowCount();
                    ?>
                    <h3><?= $number_of_sellers; ?></h3>
                    <p>sellers account</p>
                    <a href="user_accounts.php" class="btn">See Sellers</a>
                </div>
                <div class="box">
                    <?php 
                      $select_orders = $conn->prepare("SELECT * FROM `tbl_orders` WHERE 
                      seller_id = ?");
                      $select_orders->execute([$seller_id]);
                      $number_of_orders = $select_orders->rowCount();
                    ?>
                    <h3><?= $number_of_orders; ?></h3>
                    <p>total orders placed</p>
                    <a href="admin_order.php" class="btn">Total Orders</a>
                </div>
                <div class="box">
                    <?php 
                      $select_confirm_orders = $conn->prepare("SELECT * FROM `tbl_orders` WHERE 
                      seller_id = ? AND status =?");
                      $select_confirm_orders->execute([$seller_id,'in progress']);
                      $number_of_confirm_orders = $select_confirm_orders->rowCount();
                    ?>
                    <h3><?= $number_of_confirm_orders; ?></h3>
                    <p>total confirm orders</p>
                    <a href="admin_order.php" class="btn">Confirm Orders</a>
                </div>
                <div class="box">
                    <?php 
                      $select_canceled_orders = $conn->prepare("SELECT * FROM `tbl_orders` WHERE 
                      seller_id = ? AND status =?");
                      $select_canceled_orders->execute([$seller_id,'canceled']);
                      $number_of_canceled_orders = $select_canceled_orders->rowCount();
                    ?>
                    <h3><?= $number_of_canceled_orders; ?></h3>
                    <p>total canceled orders</p>
                    <a href="admin_order.php" class="btn">Canceled Orders</a>
                </div>
            </div>
        </section>
    </div>




    <!-- sweetalert cdn link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- custom js link -->
    <script src="../js/admin_script.js"></script>

    <?php include('../components/alert.php'); ?>

</body>

</html>