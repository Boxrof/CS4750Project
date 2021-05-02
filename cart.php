<!doctype html>


<html lang="en">
    <?php 
        require('header.php'); // to include the header and footer everytime
        require('db-connect.php');
    ?>
    <?php
        
        global $pdo;
        if (isset($_SESSION['firstName'])) {
            echo('<h2>Welcome ' . $_SESSION["firstName"]. '!</h2>');
        }
        
        if (isset($_SESSION['user_ID'])) {
            $current_customer = $_SESSION['user_ID']; //Get current user
        } 

        // Get the list of all restaurants to render in the front-end
        echo "<h1 class='text-center'> Your order: </h1>";
        $res = $pdo->prepare("SELECT * FROM Orders WHERE customer_ID=:customer");
        $res->bindParam(":customer",$current_customer);
        $res->execute();

        echo "<table class='table table-condensed table-hover' style='table-layout: fixed; border-collapse:collapse;'>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th scope='col'> Order Number </th>";
                    echo "<th scope='col'> Order Time: </th>";
                    echo "<th scope='col'> Restaurant Name: </th>";
                    echo "<th scope='col'> Driver Name: </th>";
                    echo "<th scope='col'> Cancel Order:  </th>";
                echo "</tr>";
            echo "</thead>";

            while ($row = $res->fetch()) {
                echo "<tr>";
                    //echo $row['driver_ID'];
                    $res2 = $pdo->prepare("SELECT * FROM Users WHERE user_ID=:id");
                    $res2->bindParam(":id",$row['driver_ID']);
                    $res2->execute();
                    $r = $res2->fetch();
                    $driver_name = $r['first_name'] . " " . $r['last_name'];
                    $res2->closeCursor();

                    $res3 = $pdo->prepare("SELECT * FROM Restaurants WHERE r_address=:r_address");
                    $res3->bindParam(":r_address",$row['r_address']);
                    $res3->execute();
                    $r_name = $res3->fetch();
                    $name = $r_name['r_name'];
                    $res3->closeCursor();

                    echo('<td> ' .  $row["order_number"]. '</td>');
                    echo('<td> ' .  $row["order_time"]. '</td>');
                    echo('<td> ' .  $name. '</td>');
                    echo('<td> ' .  $driver_name. '</td>');
                    echo "<th scope='row'><a class='btn btn-outline-secondary btn-sm' href='remove.php?order_number=",$row["order_number"],"'> Click here to remove meal from cart</a></th>"; 
                echo "</tr>";    
            }
        $res->closeCursor();

        echo "</table>";

        

    ?>

    <body> 
    

    <?php require('footer.php'); ?>
</html>



