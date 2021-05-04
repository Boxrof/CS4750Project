<!doctype html>

<html lang="en">
    <?php 
        require('header.php'); // to include the header and footer everytime
        require('db-connect.php');
    ?>

    <body> 
    <div class="container">
        <?php 
        // if user is logged in say hello <firstName>
        // firstName is set in login.php and customer-signup.php as of now
            if (!isset($_SESSION['firstName'])) {
                echo('<h4>Welcome to Food Ordering.  If you have an account, <a href="login.php">please login!</a>  If you don\'t yet, <a href="signup.php">sign up here!</a></h4>');
                echo('<br><br><br><br><br><br>');
            } else {
                echo('<h2>Hello ' . $_SESSION["firstName"]. '!</h2>');
                if ($_SESSION['user_type'] == 'driver') {
                    $res = $pdo->prepare("SELECT * FROM Orders WHERE driver_ID=:user_ID");
                    $res->bindParam(":user_ID",$_SESSION['user_ID']);
                    $res->execute();
                    
                    echo "<h1 class='text-center'> Your Delivery Orders: </h1><br>";
                    echo "<table class='table table-condensed table-hover' style='table-layout: fixed; border-collapse:collapse;'>";
                    echo "<thead>";
                        echo "<tr>";
                            echo "<th scope='col'> Order Number </th>";
                            echo "<th scope='col'> Order Time: </th>";
                            //echo "<th scope='col'> Order Price: </th>";
                            echo "<th scope='col'> Restaurant Name: </th>";
                            echo "<th scope='col'> Restaurant Address:  </th>";
                            echo "<th scope='col'> Customer Name: </th>";
                        echo "</tr>";
                    echo "</thead>";

                                // First fetch the order
                    while ($row = $res->fetch()) {

                        // Given the current order, search the included table to find the appropriate meals to display 
                        $current_order = $row["order_number"];

                        $res2 = $pdo->prepare("SELECT * FROM Users WHERE user_ID=:id");
                        $res2->bindParam(":id",$row['customer_ID']);
                        $res2->execute();
                        $r = $res2->fetch();
                        $customer_name = $r['first_name'] . " " . $r['last_name'];
                        $res2->closeCursor();

                        $res3 = $pdo->prepare("SELECT * FROM Restaurants WHERE r_ID=:id");
                        $res3->bindParam(":id",$row['r_ID']);
                        $res3->execute();
                        $r_name = $res3->fetch();
                        $name = $r_name['r_name'];
                        $addr = $r_name['r_address'];
                        $res3->closeCursor();
                        
                        echo "<tr>";
                        echo('<td> ' .  $row["order_number"]. '</td>');
                        echo('<td> ' .  $row["order_time"]. '</td>');
                        //echo('<td> ' .  $row["o_price"]. '</td>');
                        echo('<td> ' .  $name. '</td>');
                        echo('<td> ' .  $addr. '</td>');
                        echo('<td> ' .  $customer_name. '</td>');
                        echo "</tr>";
                            
                    }
                    $res->closeCursor();

                    echo "</table>";

                } else if ($_SESSION['user_type'] == 'owner') {
                    $res = $pdo->prepare("SELECT * FROM Orders NATURAL JOIN Owners WHERE user_ID=:user_ID");
                    $res->bindParam(":user_ID",$_SESSION['user_ID']);
                    $res->execute();
                    
                    echo "<h1 class='text-center'> Your Restaurant's Orders: </h1><br>";
                    echo "<table class='table table-condensed table-hover' style='table-layout: fixed; border-collapse:collapse;'>";
                    echo "<thead>";
                        echo "<tr>";
                            echo "<th scope='col'> Order Number </th>";
                            echo "<th scope='col'> Order Time: </th>";
                            //echo "<th scope='col'> Order Price: </th>";
                            // echo "<th scope='col'> Restaurant Name: </th>";
                            // echo "<th scope='col'> Restaurant Address:  </th>";
                            echo "<th scope='col'> Driver Name: </th>";
                            echo "<th scope='col'> Customer Name: </th>";
                        echo "</tr>";
                    echo "</thead>";

                                // First fetch the order
                    while ($row = $res->fetch()) {

                        // Given the current order, search the included table to find the appropriate meals to display 
                        $current_order = $row["order_number"];

                        $res2 = $pdo->prepare("SELECT * FROM Users WHERE user_ID=:id");
                        $res2->bindParam(":id",$row['customer_ID']);
                        $res2->execute();
                        $r = $res2->fetch();
                        $customer_name = $r['first_name'] . " " . $r['last_name'];
                        $res2->closeCursor();

                        $res4 = $pdo->prepare("SELECT * FROM Users WHERE user_ID=:id");
                        $res4->bindParam(":id",$row['driver_ID']);
                        $res4->execute();
                        $r = $res4->fetch();
                        $driver_name = $r['first_name'] . " " . $r['last_name'];
                        $res4->closeCursor();

                        // $res3 = $pdo->prepare("SELECT * FROM Restaurants WHERE r_ID=:id");
                        // $res3->bindParam(":id",$row['r_ID']);
                        // $res3->execute();
                        // $r_name = $res3->fetch();
                        // $name = $r_name['r_name'];
                        // $addr = $r_name['r_address'];
                        // $res3->closeCursor();
                        
                        echo "<tr>";
                        echo('<td> ' .  $row["order_number"]. '</td>');
                        echo('<td> ' .  $row["order_time"]. '</td>');
                        //echo('<td> ' .  $row["o_price"]. '</td>');
                        echo('<td> ' .  $driver_name. '</td>');
                        echo('<td> ' .  $customer_name. '</td>');
                        echo "</tr>";
                            
                    }
                    $res->closeCursor();

                    echo "</table>";
                }
                else {
                    echo $_SESSION['user_type'];
                }
            }
        ?>
    </div>

    </body>


    <?php require('footer.php'); ?>
</html>
    