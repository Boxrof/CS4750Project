<!doctype html>

<html lang="en">
    <?php 
        require('header.php'); 
        session_start();
    ?>

    <body> <!--Put body here-->
    <div class="container">
        <?php 
            if (isset($_SESSION['user'])) {
                echo('<h2>Hello ' . $_SESSION["user"]. '!</h2>');
            } else {
                echo('<a href="login.php">Please login!</a>');
            }
        ?>
    </div>

    </body>


    <?php require('footer.php'); ?>
</html>
    