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
        $check = $pdo->prepare("SELECT * FROM Included WHERE order_number=:order");
        $check->bindParam(":order",$_GET['order_number']);
        $check->execute();
        echo $check->rowCount();
        
        if ($check->rowCount() == 1) {
            //If our cart has only one item left, remove the order
            $res = $pdo->prepare("DELETE FROM Included WHERE meal_ID=:id");
            $res->bindParam(":id",$_GET['meal_ID']);
            $res->execute();
            $res->closeCursor();

            $del = $pdo->prepare("DELETE FROM Orders WHERE order_number=:order");
            $del->bindParam(":order",$_GET['order_number']);
            $del->execute();
            $del->closeCursor();

            $_SESSION['curr_order'] = -1;
        } else {
            //Do not remove the order while there are still items in the cart
            $res = $pdo->prepare("DELETE FROM Included WHERE meal_ID=:id");
            $res->bindParam(":id",$_GET['meal_ID']);
            $res->execute();
            $res->closeCursor();
        }
        
        $check->closeCursor();

        
        
        echo("<script>location.href = 'cart.php';</script>");
        //echo "<th scope='row'><a class='btn btn-outline-secondary btn-sm' href = 'cart.php'> Click here to go back cart</a></th>"; 

        


    ?>

    <body> 
    

    <?php require('footer.php'); ?>
</html>



