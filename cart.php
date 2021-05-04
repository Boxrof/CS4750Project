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

        if (isset($_GET['confirm'])) {
            $_SESSION['curr_order'] = -1;
        }

        // Get the list of all restaurants to render in the front-end
        echo "<h1 class='text-center'> Your current order: </h1>";
        echo "<a class='btn btn-outline-secondary btn-sm' href='cart.php?confirm=true'> Click here to confirm your cart </a>";
        $res = $pdo->prepare("SELECT * FROM Orders WHERE customer_ID=:customer AND order_number=:curr_order");
        $res->bindParam(":customer",$current_customer);
        $res->bindParam(":curr_order",$_SESSION['curr_order']);
        $res->execute();

        echo "<table class='table table-condensed table-hover' style='table-layout: fixed; border-collapse:collapse;'>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th scope='col'> Order Number </th>";
                    echo "<th scope='col'> Order Time: </th>";
                    echo "<th scope='col'> Order Price: </th>";
                    echo "<th scope='col'> Restaurant Name: </th>";
                    echo "<th scope='col'> Driver Name: </th>";
                    echo "<th scope='col'> Meal:  </th>";
                    echo "<th scope='col'> Cancel Order:  </th>";
                echo "</tr>";
            echo "</thead>";

            // First fetch the order
            while ($row = $res->fetch()) {

                // Given the current order, search the included table to find the appropriate meals to display 
                $current_order = $row["order_number"];
                
                // Include table only contains order+number of 35
                $fetch_data = $pdo->prepare("SELECT * FROM Included WHERE order_number=:order");
                $fetch_data->bindParam(":order",$current_order);
                $fetch_data->execute();

                $res2 = $pdo->prepare("SELECT * FROM Users WHERE user_ID=:id");
                $res2->bindParam(":id",$row['driver_ID']);
                $res2->execute();
                $r = $res2->fetch();
                $driver_name = $r['first_name'] . " " . $r['last_name'];
                $res2->closeCursor();

                $res3 = $pdo->prepare("SELECT * FROM Restaurants WHERE r_ID=:id");
                $res3->bindParam(":id",$row['r_ID']);
                $res3->execute();
                $r_name = $res3->fetch();
                $name = $r_name['r_name'];
                $res3->closeCursor();

                $onlyOne = false;
                if ($fetch_data->rowCount() == 1) {
                    $onlyOne = true;
                }
                $count = 0;
                while ($row2 =$fetch_data->fetch()) {
                    echo "<tr>";
                    
                        $res4 = $pdo->prepare("SELECT * FROM Meals WHERE meal_ID=:mealid");
                        $res4->bindParam(":mealid",$row2['meal_ID']);
                        $res4->execute();
                        $res_meal = $res4->fetch();
                        
                        $meal_name = $res_meal['m_name'];
                        $res4->closeCursor();
                        if ($count == 0) {
                            echo('<td> ' .  $row["order_number"]. '</td>');
                            echo('<td> ' .  $row["order_time"]. '</td>');
                            echo('<td> ' .  $row["o_price"]. '</td>');
                            echo('<td> ' .  $name. '</td>');
                            echo('<td> ' .  $driver_name. '</td>');
                            echo('<td> ' .  $meal_name. '</td>');
                        } else {
                            echo('<td></td>');
                            echo('<td></td>');
                            echo('<td></td>');
                            echo('<td></td>');
                            echo('<td></td>');
                            echo('<td> ' .  $meal_name. '</td>');
                        }
                        if ($onlyOne) {
                            echo "<th scope='row'><a class='btn btn-outline-secondary btn-sm' href='remove.php?meal_ID=",$row2["meal_ID"],"&order_number=",$row["order_number"],"'> Click here to cancel the entire order</a></th>"; 
                        } else {
                            echo "<th scope='row'><a class='btn btn-outline-secondary btn-sm' href='remove.php?meal_ID=",$row2["meal_ID"],"&order_number=",$row["order_number"],"'> Click here to remove meal from cart</a></th>"; 
                        }
                    echo "</tr>";
                    $count++;
                }
                $fetch_data->closeCursor();
                
            }
        $res->closeCursor();

        echo "</table>";

        $res = $pdo->prepare("SELECT * FROM Orders WHERE customer_ID=:customer AND order_number!=:curr_order");
        $res->bindParam(":customer",$current_customer);
        $res->bindParam(":curr_order",$_SESSION['curr_order']);
        $res->execute();

        echo "<h1 class='text-center'> Your previous orders: </h1>";
        echo "<table class='table table-condensed table-hover' style='table-layout: fixed; border-collapse:collapse;'>";
        echo "<thead>";
            echo "<tr>";
                echo "<th scope='col'> Order Number </th>";
                echo "<th scope='col'> Order Time: </th>";
                echo "<th scope='col'> Order Price: </th>";
                echo "<th scope='col'> Restaurant Name: </th>";
                echo "<th scope='col'> Driver Name: </th>";
                echo "<th scope='col'> Meal:  </th>";
                echo "<th scope='col'>  </th>";
            echo "</tr>";
        echo "</thead>";

        // First fetch the order
        while ($row = $res->fetch()) {

            // Given the current order, search the included table to find the appropriate meals to display 
            $current_order = $row["order_number"];
            
            // Include table only contains order+number of 35
            $fetch_data = $pdo->prepare("SELECT * FROM Included WHERE order_number=:order");
            $fetch_data->bindParam(":order",$current_order);
            $fetch_data->execute();

            $res2 = $pdo->prepare("SELECT * FROM Users WHERE user_ID=:id");
            $res2->bindParam(":id",$row['driver_ID']);
            $res2->execute();
            $r = $res2->fetch();
            $driver_name = $r['first_name'] . " " . $r['last_name'];
            $res2->closeCursor();

            $res3 = $pdo->prepare("SELECT * FROM Restaurants WHERE r_ID=:id");
            $res3->bindParam(":id",$row['r_ID']);
            $res3->execute();
            $r_name = $res3->fetch();
            $name = $r_name['r_name'];
            $res3->closeCursor();

            $onlyOne = false;
            if ($fetch_data->rowCount() == 1) {
                $onlyOne = true;
            }
            $count = 0;
            while ($row2 =$fetch_data->fetch()) {
                echo "<tr>";
                
                    $res4 = $pdo->prepare("SELECT * FROM Meals WHERE meal_ID=:mealid");
                    $res4->bindParam(":mealid",$row2['meal_ID']);
                    $res4->execute();
                    $res_meal = $res4->fetch();
                    
                    $meal_name = $res_meal['m_name'];
                    $res4->closeCursor();
                    if ($count == 0) {
                        echo('<td> ' .  $row["order_number"]. '</td>');
                        echo('<td> ' .  $row["order_time"]. '</td>');
                        echo('<td> ' .  $row["o_price"]. '</td>');
                        echo('<td> ' .  $name. '</td>');
                        echo('<td> ' .  $driver_name. '</td>');
                        echo('<td> ' .  $meal_name. '</td>');
                    } else {
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td> ' .  $meal_name. '</td>');
                    }
                    echo "<th scope='row'>  </th>";
                    // if ($onlyOne) {
                    //     echo "<th scope='row'><a class='btn btn-outline-secondary btn-sm' href='remove.php?meal_ID=",$row2["meal_ID"],"&order_number=",$row["order_number"],"'> Click here to cancel the entire order</a></th>"; 
                    // } else {
                    //     echo "<th scope='row'><a class='btn btn-outline-secondary btn-sm' href='remove.php?meal_ID=",$row2["meal_ID"],"&order_number=",$row["order_number"],"'> Click here to remove meal from cart</a></th>"; 
                    // }
                echo "</tr>";
                $count++;
            }
            $fetch_data->closeCursor();
            
        }
    $res->closeCursor();

    echo "</table>";

        

    ?>

    <body> 
    

    <?php require('footer.php'); ?>
</html>



