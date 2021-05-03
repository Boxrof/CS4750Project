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
        
        // Get the list of all restaurants to render in the front-end
        echo "<h1 class='text-center'> List of Restaurants: </h1>";
        $res = $pdo->query("SELECT * FROM Restaurants");
        echo "<table class='table table-condensed table-hover' style='table-layout: fixed; border-collapse:collapse;'>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th scope='col'> Detail </th>";
                    echo "<th scope='col'>Address: </th>";
                    echo "<th scope='col'>Restaurant Name: </th>";
                    echo "<th scope='col'>Rating: </th>";
                echo "</tr>";
            echo "</thead>";

            while ($row = $res->fetch()) {
                $r_id = $row["r_ID"];
                $address = $row["r_address"];
                echo "<tr>";
                    echo "<th scope='row'><a class='btn btn-outline-secondary btn-sm' href='menu.php?r_id=",$r_id,"&address=",$address,"'> Click here to view menu </a></th>";
                    echo('<td> ' .  $row["r_address"]. '</td>');
                    echo('<td> ' .  $row["r_name"]. '</td>');
                    echo('<td> ' .  $row["r_rating"]. '</td>');
                echo "</tr>";    
            }

        echo "</table>";

        

    ?>

    <body> 
    

    <?php require('footer.php'); ?>
</html>



