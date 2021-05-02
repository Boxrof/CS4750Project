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
        // 
        $customer = $_GET['current_customer']; // 3
        $restaurant_address = $_GET['chosen_restaurant']; // address 
        $meal = $_GET['meal_number']; // 1
        $price = $_GET['price'];
        $array_random = [];
        //$order_time = rtrim(strval(date("h:i:sa")),'PM');
        $order_time = date("h:i:sa");

        // Pick a random driver to deliver the food
        $res = $pdo->query("SELECT * FROM Drivers");
        while ($row = $res->fetch()) {
            array_push($array_random,$row['user_ID']);
        }
        
        $chosen_driver_id = $array_random[array_rand($array_random)];
        $truncated_time = substr($order_time,0,-2);
        
        echo $chosen_driver_id;
        echo $customer;
        echo $restaurant_address;
        echo $meal;
        echo $price;
        //$order_number = 1;
        $query = "INSERT INTO Orders (order_time,o_price,driver_ID,customer_ID,r_address) VALUES (:order_time,:o_price,:driver_ID,:customer_ID,:r_address)";
        $statement = $pdo->prepare($query);
        //$statement->bindValue(':order_number',$order_number);
        $statement->bindValue(':order_time',$truncated_time);
        $statement->bindValue(':o_price',$price);
        $statement->bindValue(':driver_ID',$chosen_driver_id);
        $statement->bindValue(':customer_ID',$customer);
        $statement->bindValue(':r_address',$restaurant_address);
        $statement->execute();
        $statement->closeCursor();

        echo("<script>location.href = 'cart.php';</script>");

    ?>

    <body> 
    

    <?php require('footer.php'); ?>
</html>



