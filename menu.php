<!doctype html>


<html lang="en">
    <?php 
        require('header.php'); // to include the header and footer everytime
        require('db-connect.php');
    ?>
    <?php
        
        global $pdo;
        $receiver = $_GET['r_id'];
        $restaurant_address = $_GET['address']; 
        if (isset($_SESSION['user_ID'])) {
            $current_customer = $_SESSION['user_ID']; //Get current user
        } else {
            echo "<div class='alert alert-danger' role='alert'>" . "You must sign in first to order" . "</div>";
        }
        
        $canBuy = true;
        if ($_SESSION['curr_order'] != -1) {
            $res = $pdo->prepare("SELECT * FROM Orders WHERE order_number=:curr_order");
            $res->bindParam(":curr_order",$_SESSION['curr_order']);
            $res->execute();
            $result = $res->fetch();
            if ($result['r_ID'] != $receiver) {
                $canBuy = false;
                echo $result['r_ID'];
            }
            $res->closecursor();
        }
        //echo $a->rowCount();
        if ($receiver) {
            // TODO: Figure it out how to pass the parameter to where statement with query function
            // TODO: render all meals based on the restaurant address
            $res = $pdo->prepare("SELECT * FROM Meals WHERE r_id=:id");
            $res->bindParam(":id",$receiver);
            $res->execute();
            

            echo "<table class='table table-condensed table-hover' style='table-layout: fixed; border-collapse:collapse;'>";
                    echo "<thead>";
                        echo "<tr>";
                            echo "<th scope='col'> Order  </th>";
                            echo "<th scope='col'>Meal Number </th>";
                            echo "<th scope='col'>Name </th>";
                            echo "<th scope='col'>Price </th>";
                        echo "</tr>";
                    echo "</thead>";
                    
                    while ($row = $res->fetch()) {
                        echo "<tr>";
                            $meal_ID = $row['meal_ID']; 
                            $price = $row['m_price'];
                            if ($canBuy) {
                                echo "<th scope='row'><a class='btn btn-outline-secondary btn-sm' href='order.php?current_customer=",$current_customer,"&meal_number=",$meal_ID,"&chosen_restaurant=",$receiver,"&price=",$price,"&r_address=",$restaurant_address,"'> Click here to add it to your cart </a></th>";                
                            } else {
                                //echo '1';
                                echo "<th scope='row'><div class='btn btn-outline-secondary btn-sm'> Cart currently for different restaurant </div></th>";                
                            }
                            echo('<td> ' .  $row["meal_ID"]. '</td>');
                            echo('<td> ' .  $row["m_name"]. '</td>');
                            echo('<td> ' .  $row["m_price"]. '</td>');
                        echo "</tr>";    
                    }
                    
            echo "</table>";
        }

        

    ?>

    <body> 
    

    <?php require('footer.php'); ?>
</html>



