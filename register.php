<?php 

    include('components/connect.php');

    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
    }

    if(isset($_POST['submit']))
    {
        $id = unique_id();
        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);

        $password = sha1($_POST['password']);
        $password = filter_var($password, FILTER_SANITIZE_STRING);

        $confirm_password = sha1($_POST['confirm_password']);
        $confirm_password = filter_var($confirm_password, FILTER_SANITIZE_STRING);

        $image = $_FILES['image']['name'];
        $image  = filter_var($image, FILTER_SANITIZE_STRING);
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = unique_id().'.'.$ext;
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'uploaded_files/'.$rename;

        $select_user = $conn->prepare("SELECT * FROM `tbl_users` WHERE email = ?");
        $select_user->execute([$email]);

        if($select_user->rowCount() > 0) {
            $warning_msg[] = 'email already exists';
        
    } else {
        if($password != $confirm_password){
            $warning_msg[] = 'confirm password not matched';
        }else {
            $insert_user = $conn->prepare("INSERT INTO `tbl_users`(id,name, email,password, image) VALUES (?,?,?,?,?)");
            $insert_user->execute([$id, $name, $email, $confirm_password, $rename]);
            move_uploaded_file($image_tmp_name, $image_folder);
            $success_msg[] = 'new user registered! please loging now';
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
    <title>IceCream-GinD - user registration page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css">
    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- box icon cdn link -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>

<body>
    <?php include('components/user_header.php'); ?>
    <div class="banner">
        <div class="detail">
            <h1>register</h1>
            <p>An ice cream shop website has to make an excellent first impression on the target audience,<br>
                much like any other business. A friendly and informational interface attracts more customers, <br>
                which helps you grow your ice cream sales exponentially. If your ice cream...</p>
            <span><a href="home.php">home</a><i class="bx bx-right-arrow-alt"></i>register</span>
        </div>
    </div>
    <div class="form-container">
        <form action="" method="POST" enctype="multipart/form-data" class="register">
            <h3>Register Now</h3>
            <div class="flex">
                <div class="col">
                    <div class="input-field">
                        <p>Your name<span>*</span></p>
                        <input type="text" name="name" placeholder="enter your name" maxlength="50" required
                            class="box">
                    </div>

                    <div class="input-field">
                        <p>Your email<span>*</span></p>
                        <input type="email" name="email" placeholder="enter your email" maxlength="50" required
                            class="box">
                    </div>
                </div>

                <div class="col">
                    <div class="input-field">
                        <p>Your password<span>*</span></p>
                        <input type="password" name="password" placeholder="enter your password" maxlength="50" required
                            class="box">
                    </div>

                    <div class="input-field">
                        <p>Confirm password<span>*</span></p>
                        <input type="password" name="confirm_password" placeholder="confirm your password"
                            maxlength="50" required class="box">
                    </div>
                </div>

            </div>
            <div class="input-field">
                <p>Your profile<span>*</span></p>
                <input type="file" name="image" accept="image/*" required class="box">
            </div>
            <p class="link">already have an account? <a href="login.php">Login now</a></p>
            <input type="submit" name="submit" value="Register now" class="btn">
        </form>
    </div>





    <!-- sweetalert cdn link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- custom js link -->
    <script src="js/user_script.js"></script>

    <?php include('components/alert.php'); ?>

    <?php include('components/footer.php'); ?>

</body>

</html>