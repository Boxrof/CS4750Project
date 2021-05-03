<!doctype html>


<html lang="en">
    <?php 
        require('header.php'); // to include the header and footer everytime
        require('db-connect.php');
    ?>
    <?php
        
        global $pdo;

        /* 
            We need three things to complete the order: 
            1. driver_ID (Who will deliver?)
            2. customer_ID (who ordered it?)
            3. r_address (idenfify the restaurant)
            4. order_number (what customer ordered?)
        */
        $meal = $_GET['meal_number']; // 1
        $customer = $_GET['current_customer']; // 3
        $address = $_GET['r_address'];
        $price = $_GET['price'];
        $order_time = date("h:i:sa");
        $restaurant_id = $_GET['chosen_restaurant']; // address 
        if ($_SESSION['curr_order'] == -1) {            
            
            
            $array_random = [];
            //$order_time = rtrim(strval(date("h:i:sa")),'PM');
            

            // Pick a random driver to deliver the food
            $res = $pdo->query("SELECT * FROM Drivers");
            while ($row = $res->fetch()) {
                array_push($array_random,$row['user_ID']);
            }
            
            $chosen_driver_id = $array_random[array_rand($array_random)];
            $truncated_time = substr($order_time,0,-2);
            
            echo $chosen_driver_id;
            echo $customer;
            echo $restaurant_id;
            echo $meal;
            echo $price;
            $query = "INSERT INTO Orders (order_time,o_price,driver_ID,customer_ID,r_ID,r_address) VALUES (:order_time,:o_price,:driver_ID,:customer_ID,:r_id,:r_address)";
            $statement = $pdo->prepare($query);
            //$statement->bindValue(':order_number',$order_number);
            $statement->bindValue(':order_time',$truncated_time);
            $statement->bindValue(':o_price',$price);
            $statement->bindValue(':driver_ID',$chosen_driver_id);
            $statement->bindValue(':customer_ID',$customer);
            $statement->bindValue(':r_id',$restaurant_id);
            $statement->bindValue(':r_address',$address);
            $statement->execute();
            $statement->closeCursor();
            
            $query2 = "SELECT * FROM Orders WHERE order_time=:order_time AND customer_ID=:customer_ID AND r_ID=:r_ID AND driver_ID=:driver_ID";
            // $query2 = "SELECT * FROM Orders WHERE customer_ID=:customer_ID AND r_ID=:r_ID";
            $statement = $pdo->prepare($query2);
            $statement->bindValue(':order_time',$truncated_time);
            $statement->bindValue(':driver_ID',$chosen_driver_id);
            $statement->bindValue(':customer_ID',$customer);
            $statement->bindValue(':r_ID',$restaurant_id);
            // $statement->bindValue(':o_price',$price);
            $statement->execute();
            $result = $statement->fetch();
            $statement->closeCursor();
            $_SESSION['curr_order'] = $result['order_number'];
        }

        $query3 = "INSERT INTO Included (order_number,meal_ID) VALUES (:order_number,:meal_ID)";
        $statement = $pdo->prepare($query3);
        //$statement->bindValue(':order_number',$order_number);
        $statement->bindValue(':order_number',$_SESSION['curr_order']);
        $statement->bindValue(':meal_ID',$meal);
        $statement->execute();
        $statement->closeCursor();

        //echo("<script>location.href = 'cart.php';</script>"); href='menu.php?r_id=",$r_id,"&address=",$address,"'
        echo "<script>location.href = 'menu.php?r_id=",$restaurant_id,"&address=",$address,"';</script>" ;

    ?>

    <body> 
    

    <?php require('footer.php'); ?>
</html>



