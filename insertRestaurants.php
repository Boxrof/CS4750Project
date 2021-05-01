<?php

require("./db-connect.php");
$csv = file('./Data_Generation/restaurants.csv');


$count = 0;
global $pdo;
// prepare the query
$sql = $pdo->prepare('
    INSERT INTO 
    `Restaurants`
    SET `r_address` = :r_address, `r_name` = :r_name, `r_review` = :r_review, `r_rating` = :r_rating, `r_price` = :r_price, `r_phone_number` = :r_phone_number, `opening_time` = :opening_time, `closing_time` = :closing_time, `date_opened` = :date_opened;');
foreach($csv as $row) {
    // get each row data into an array
    $restaurant_array = (str_getcsv($row));

    // index the array and save to respective columns in the table
    $r_address = $restaurant_array[0];
    $r_name = $restaurant_array[1];
    $r_review = $restaurant_array[2];
    $r_rating = $restaurant_array[3];
    $r_price = $restaurant_array[4];
    $r_phone_number = $restaurant_array[5];
    $opening_time = (new DateTime($restaurant_array[6]))->format("H:i:s");
    $closing_time = $restaurant_array[7];
    $date_opened = (new DateTime($restaurant_array[8]))->format("Y-m-d");
    
    
    try {
        $sql->execute(array(
            'r_address' => $r_address,
            'r_name' => $r_name,
            'r_review' => $r_review,
            'r_rating' => $r_rating,
            'r_price' => $r_price,
            'r_phone_number' => $r_phone_number,
            'opening_time' => $opening_time,
            'closing_time' => $closing_time,
            'date_opened' => $date_opened
        ));

        echo("Inserted restaurant #" . $count . ": " . $r_name . "<br>");
        $count++;
    } catch (Exception $e) {
        echo($e);
    }
}


?>