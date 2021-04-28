<!doctype html>
<html lang="en">
<body>

<?php 
    include('header.php');
    require('db-connect.php');
?>

    <div class="container" style="width: 50%; margin 0 auto">
        <div class="row">
            <a href="./customer-signup.php">
                <div class="col-sm">
                Customer            
                </div>
            </a>
            <a href="./driver-signup.php">
                <div class="col-sm">
                Driver            
                </div>
            </a>
            <a href="./restaurant-owner-signup.php">
                <div class="col-sm">
                Restaurant Owner            
                </div>
            </a>
        <!-- TODO: fix all signup forms and create different tables for each type of users -->
        </div>
    </div>


<?php 
    include('footer.php');
?>


</html>
