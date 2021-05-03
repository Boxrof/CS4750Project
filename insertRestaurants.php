<?php

require("./db-connect.php");
$csv = file('./Data_Generation/restaurants.csv');


$count = 0;
global $pdo;
// prepare the query
$sql = $pdo->prepare('
    INSERT INTO 
    `Restaurants`
    SET `r_address` = :r_address, `r_name` = :r_name, `r_rating` = :r_rating, `r_price` = :r_price, 
    `r_phone_number` = :r_phone_number, `opening_time` = :opening_time, `closing_time` = :closing_time, 
    `date_opened` = :date_opened, `r_payment` = :r_payment, `r_cuisine` = :r_cuisine;');
$sql2 = $pdo->prepare('
    INSERT INTO 
    `Cuisines`
    SET `r_ID` = :r_ID, `cuisine` = :cuisine;');
foreach($csv as $row) {
    // if ($count == 0) {
    //     $count += 1;
    //     continue;
    // }
    // get each row data into an array
    $restaurant_array = (str_getcsv($row));

    // index the array and save to respective columns in the table
    $r_address = $restaurant_array[0];
    $r_name = $restaurant_array[1];
    $r_review = $restaurant_array[2];
    $r_rating = $restaurant_array[3];
    $r_price = $restaurant_array[4];
    $r_phone_number = $restaurant_array[5];
    //$opening_time = (new DateTime($restaurant_array[6]))->format("H:i:s");
    $opening_time = $restaurant_array[6];
    $closing_time = $restaurant_array[7];
    //$date_opened = (new DateTime($restaurant_array[8]))->format("Y-m-d");
    $date_opened = $restaurant_array[8];
    $r_payment = $restaurant_array[9];
    $r_cuisine = $restaurant_array[10];
    $cuisine_list = explode(' ', $r_cuisine);
    
    try {
        $sql->execute(array(
            'r_address' => $r_address,
            'r_name' => $r_name,
            'r_rating' => $r_rating,
            'r_price' => $r_price,
            'r_phone_number' => $r_phone_number,
            'opening_time' => $opening_time,
            'closing_time' => $closing_time,
            'date_opened' => $date_opened,
            'r_payment' => $r_payment,
            'r_cuisine' => $r_cuisine
        ));

        $query2 = 'SELECT * FROM Restaurants WHERE r_address=:r_address AND r_name=:r_name AND r_phone_number=:r_phone_number';
        $statement = $pdo->prepare($query2);
        $statement->bindValue(':r_address',$r_address);
        $statement->bindValue(':r_name',$r_name);
        $statement->bindValue(':r_phone_number',$r_phone_number);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        $r_ID = $result['r_ID'];

        foreach ($cuisine_list as $cuis) {
            $sql2->execute(array(
                'r_ID' => $r_ID,
                'cuisine' => $cuis
            ));
        }

        echo("Inserted restaurant #" . $count . ": " . $r_name . "<br>");
        $count++;
    } catch (Exception $e) {
        echo($e);
        echo('<br></br>');
    }
}


?>