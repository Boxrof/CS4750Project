<!doctype html>


<html lang="en">
    <?php 
        require('header.php'); // to include the header and footer everytime
        require('db-connect.php');
    ?>
    <?php
        
        global $pdo;
        $receiver = $_GET['address']; 

        //echo $a->rowCount();
        if ($receiver) {
            // TODO: Figure it out how to pass the parameter to where statement with query function
            // TODO: render all meals based on the restaurant address
            $receiver .= " "; // The restaurant address has an extra space at the end
            $res = $pdo->prepare("SELECT * FROM Meals WHERE r_address=:the_address");
            $res->bindParam(":the_address",$receiver);
            $res->execute();
            

            echo "<table class='table table-condensed table-hover' style='table-layout: fixed; border-collapse:collapse;'>";
                    echo "<thead>";
                        echo "<tr>";
                            echo "<th scope='col'>Meal Number </th>";
                            echo "<th scope='col'>Name </th>";
                            echo "<th scope='col'>Price </th>";
                        echo "</tr>";
                    echo "</thead>";
                    
                    while ($row = $res->fetch()) {
                        echo "<tr>";                
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



