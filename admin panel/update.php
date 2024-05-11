<?php
    include('../components/connect.php');

    if(isset($_COOKIE['seller_id'])) {
        $seller_id = $_COOKIE['seller_id'];
    } else {
        $seller_id = '';
        header('location:login.php');
    }

    if (isset($_POST['submit'])) {
        
        $select_seller = $conn->prepare("SELECT * FROM `tbl_sellers` WHERE id =? LIMIT 1");
        $select_seller->execute([$seller_id]);
        $fetch_seller = $select_seller->fetch(PDO::FETCH_ASSOC);

        $prev_pass = $fetch_seller['password'];
        $prev_image = $fetch_seller['image'];

        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);


        //update name
        if(!empty($name)){
            $update_name = $conn->prepare("UPDATE `tbl_sellers` SET name = ? WHERE id = ?");
            $update_name->execute([$name,$seller_id]);
            $success_msg[] = 'username updated successfully';
        }
       
        //update email
        if (!empty($email)) {
            $select_email = $conn->prepare("SELECT * FROM `tbl_sellers` WHERE id = ? AND email =?");
            $select_email->execute([$seller_id, $email]);

            if ($select_email->rowCount() > 0) {
                $warning_msg[] = 'email already exist';
            }else{
                $update_email = $conn->prepare("UPDATE `tbl_sellers` SET email = ? WHERE id = ?");
                $update_email->execute([$email,$seller_id]);
                $success_msg[] = 'email updated successfully';
            }
        }

        //update image 

        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = unique_id().'.'.$ext;
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../uploaded_files/'.$rename;

        if (!empty($image)) {
            if ($image_size > 2000000) {
                $warning_msg[] = 'image size is too large';
            }else{
                $update_image = $conn->prepare("UPDATE `tbl_sellers` SET `image` = ? WHERE id =?");
                $update_image->execute([$rename,$seller_id]);
                move_uploaded_file($image_tmp_name,$image_folder);

                if ($prev_image != '' AND $prev_image != $rename) {
                    unlink('../uploaded_files/'.$prev_image);
                }
                $success_msg[] = 'iamge updated successfully';
            }
        }

        //update password

        $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';

        $old_pass = sha1($_POST['old_pass']);
        $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);

        $new_pass = sha1($_POST['new_pass']);
        $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);

        $confirm_pass = sha1($_POST['confirm_pass']);
        $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

        if ($old_pass != $empty_pass) {
            if ($old_pass != $prev_pass) {
                $warning_msg[] = 'old password not matched';
            }elseif($new_pass != $confirm_pass){
                $warning_msg[] = 'password not matched';
            }else{
                if ($new_pass != $empty_pass) {
                    $update_pass = $conn->prepare("UPDATE `tbl_sellers` SET password =? WHERE id =?");
                    $update_pass->execute([$confirm_pass,$seller_id]);
                    $success_msg[] = 'password updated successfully';
                }else{
                    $warning_msg[] = 'please enter a new password';
                }
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
    <title>IceCream-GinD - update profile page</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- box icon cdn link -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>

<body>

    <div class="main-container">
        <?php include('../components/admin_header.php'); ?>
        <section class="form-container">
            <div class="heading">
                <h1>update profile details</h1>
                <img src="../image/separator-img.png" alt="">
            </div>
            <form action="" method="POST" enctype="multipart/form-data" class="register">
                <div class="img-box">
                    <img src="../uploaded_files/<?= $fetch_profile['image']; ?>">
                </div>

                <div class="flex">
                    <div class="col">
                        <div class="input-field">
                            <p>your name <span>*</span></p>
                            <input type="text" name="name" placeholder="<?= $fetch_profile['name']; ?>" class="box">
                        </div>
                        <div class="input-field">
                            <p>your email <span>*</span></p>
                            <input type="email" name="email" placeholder="<?= $fetch_profile['email']; ?>" class="box">
                        </div>
                        <div class="input-field">
                            <p>select pic <span>*</span></p>
                            <input type="file" name="image" accept="image/*" class="box">
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-field">
                            <p>old password <span>*</span></p>
                            <input type="password" name="old_pass" placeholder="enter your old password" class="box">
                        </div>
                        <div class="input-field">
                            <p>new password <span>*</span></p>
                            <input type="password" name="new_pass" placeholder="enter your news password" class="box">
                        </div>
                        <div class="input-field">
                            <p>confirm password <span>*</span></p>
                            <input type="password" name="confirm_pass" placeholder="confirm your password" class="box">
                        </div>
                    </div>
                </div>
                <input type="submit" name="submit" value="update profile" class="btn">
            </form>
        </section>
    </div>




    <!-- sweetalert cdn link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- custom js link -->
    <script src="../js/admin_script.js"></script>

    <?php include('../components/alert.php'); ?>

</body>

</html>