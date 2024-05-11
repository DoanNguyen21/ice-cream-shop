<?php 

    include('components/connect.php');

    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
    }

    if(isset($_POST['submit']))
    {
        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);

        $password = sha1($_POST['password']);
        $password = filter_var($password, FILTER_SANITIZE_STRING);

        

        $select_user = $conn->prepare("SELECT * FROM `tbl_users` WHERE email = ? AND password = ? ");
        $select_user->execute([$email, $password]);
        $row = $select_user->fetch(PDO::FETCH_ASSOC);

       if($select_user->rowCount() > 0){
          setcookie('user_id',$row['id'],time() + 60*60*24*30, '/' );
          header('location:home.php');
       } else{
         $warning_msg[] = 'incorrect email or password';
       }
   }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IceCream-GinD - user login page</title>
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
            <h1>login</h1>
            <p>An ice cream shop website has to make an excellent first impression on the target audience,<br>
                much like any other business. A friendly and informational interface attracts more customers, <br>
                which helps you grow your ice cream sales exponentially. If your ice cream...</p>
            <span><a href="home.php">home</a><i class="bx bx-right-arrow-alt"></i>login</span>
        </div>
    </div>
    <div class="form-container">
        <form action="" method="POST" enctype="multipart/form-data" class="login">
            <h3>Login Now</h3>

            <div class="input-field">
                <p>Your email<span>*</span></p>
                <input type="email" name="email" placeholder="enter your email" maxlength="50" required class="box">
            </div>

            <div class="input-field">
                <p>Your password<span>*</span></p>
                <input type="password" name="password" placeholder="enter your password" maxlength="50" required
                    class="box">
            </div>


            <p class="link">do not have an account? <a href="register.php">Register now</a></p>
            <input type="submit" name="submit" value="Login now" class="btn">
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