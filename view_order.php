<?php 

    include('components/connect.php');

    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
    }

   if (isset($_GET['get_id'])) {
      $get_id = $_GET['get_id'];
   }else{
    $get_id = '';
    header('location:order.php');
   }

   if (isset($_POST['cancle'])) {
    
    $update_order = $conn->prepare("UPDATE `tbl_orders` SET status =? WHERE id =?");
    $update_order->execute(['cancled', $get_id]);

    header('location:order.php');
   }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IceCream-GinD - order detail page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css">
    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- box icon cdn link -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

    <!-- Định dạng CSS của Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rI2Hwfz4y/u80vHGJCCXpjRYWVBsZOoB24pH/aXW2snjXUB0geOp1w5HZHRN9zSA" crossorigin="anonymous">
    <!-- Thư viện biểu tượng Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>
    <?php include('components/user_header.php'); ?>
    <div class="banner">
        <div class="detail">
            <h1>order detail</h1>
            <p>An ice cream shop website has to make an excellent first impression on the target audience,<br>
                much like any other business. A friendly and informational interface attracts more customers, <br>
                which helps you grow your ice cream sales exponentially. If your ice cream...</p>
            <span><a href="home.php">home</a><i class="bx bx-right-arrow-alt"></i>order detail</span>
        </div>
    </div>

    <div class="order-detail">
        <div class="heading">
            <h1>my order detail</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto dolorum deserunt
                minus veniam tenetur</p>
            <img src="image/separator-img.png">
        </div>
        <div class="box-container">
            <?php
                $grand_total = 0;
                $select_order = $conn->prepare("SELECT * FROM `tbl_orders` WHERE id = ? LIMIT 1");
                $select_order->execute([$get_id]);

                if ($select_order->rowCount() > 0) {
                    while($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)) {
                        $select_product = $conn->prepare("SELECT * FROM `tbl_products` WHERE id =? LIMIT 1");
                        $select_product->execute([$fetch_order['product_id']]);
                        if ($select_product->rowCount() > 0) {
                            while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)) {
                                $sub_total =($fetch_order['price']* $fetch_order['qty']);
                                $grand_total += $sub_total;
                           
            ?>
            <div class="box">
                <div class="col">
                    <p class="title"><i class="bx bxs-calender-alt"></i><?= $fetch_order['date'] ?></p>
                    <img src="uploaded_files/<?= $fetch_product['image']; ?>" class="image">
                    <p class="price">$<?= $fetch_product['price']; ?>/-</p>
                    <h3 class="name"><?= $fetch_product['name']; ?></h3>
                    <p class="grand-total">total amout payable : <span>$<?= $grand_total; ?>/-</span></p>
                </div>
                <div class="col">
                    <p class="title">billing address</p>
                    <p class="user"><i class="bi bi-person-bounding-box"></i><?= $fetch_order['name']; ?></p>
                    <p class="user"><i class="bi bi-phone"></i><?= $fetch_order['number']; ?></p>
                    <p class="user"><i class="bi bi-envelope"></i><?= $fetch_order['email']; ?></p>
                    <p class="user"><i class="bi bi-pin-map-fill"></i><?= $fetch_order['address']; ?></p>
                    <p class="status" style="color:<?php if($fetch_order['status'] == 'delivered
                        '){echo "green";}elseif($fetch_order['status'] == 'cancled'){echo "red"
                        ;}else{echo "orange";} ?>"><?= $fetch_order['status']; ?></p>
                    <?php if($fetch_order['status'] == 'cancled'){ ?>
                    <a href="checkout.php?get_id=<?=  $fetch_product['id'];?>" class="btn" style="line-height: 3;">order
                        again</a>
                    <?php }else{ ?>
                    <form action="" method="POST">
                        <button type="submit" name="cancle" class="btn"
                            onclick="return confirm('do you want to cancle this product');">cancle</button>
                    </form>
                    <?php } ?>
                </div>
            </div>
            <?php
                        }
                    }
                }
            }else{
                echo '<p class="empty">no order take placed yet!</p>';
            }
            ?>
        </div>
    </div>







    <!-- sweetalert cdn link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- custom js link -->
    <script src="js/user_script.js"></script>

    <?php include('components/alert.php'); ?>

    <?php include('components/footer.php'); ?>

</body>

</html>