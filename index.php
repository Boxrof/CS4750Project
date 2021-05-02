<!doctype html>

<html lang="en">
    <?php 
        require('header.php'); // to include the header and footer everytime
    ?>

    <body> 
    <div class="container">
        <?php 
        // if user is logged in say hello <firstName>
        // firstName is set in login.php and customer-signup.php as of now
            if (isset($_SESSION['firstName'])) {
                echo('<h2>Hello ' . $_SESSION["firstName"]. '!</h2>');
            } else {
                echo('<h4>Welcome to Food Ordering.  If you have an account, <a href="login.php">please login!</a>  If you don\'t yet, <a href="signup.php">sign up here!</a></h4>');
            }
        ?>
    </div>

    </body>


    <?php require('footer.php'); ?>
</html>
    