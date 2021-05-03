<?php
require("./db-connect.php");
$csv = file('./Data_Generation/meals.csv');

$count = 0;
global $pdo;

foreach($csv as $row) {
    // get each row data into an array
    $restaurant_array = (str_getcsv($row));

    // index the array and save to respective columns in the table
    $r_address = $restaurant_array[1];
    $m_name = $restaurant_array[2];
    $m_price = $restaurant_array[3];
    $m_price = str_replace('$', '', $m_price);

    

    try {
        $query = "SELECT * FROM Restaurants WHERE r_address LIKE '%$r_address%'";
        $statement = $pdo->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closecursor();
        $r_ID = $results[0][0];

        // prepare the query
        $sql = $pdo->prepare(
            '
            INSERT INTO 
            `Meals`
            SET `r_ID`=:r_ID, `r_address`=:r_address, `m_name`=:m_name, `m_price`=:m_price;'
        );
        $sql->execute(array(
            'r_ID' => $r_ID,
            'r_address' => $r_address,
            'm_name' => $m_name,
            'm_price' => $m_price
        ));

        echo("Inserted meal #" . $count . ": " . $m_name . "<br>");
        $count++;
    } catch (Exception $e) {
        echo($e);
    }
}
?>