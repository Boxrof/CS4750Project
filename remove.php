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
        $res = $pdo->prepare("DELETE FROM Orders WHERE order_number=:order");
        $res->bindParam(":order",$_GET['order_number']);
        $res->execute();
        $res->closeCursor();
        
        echo("<script>location.href = 'cart.php';</script>");
        //echo "<th scope='row'><a class='btn btn-outline-secondary btn-sm' href = 'cart.php'> Click here to go back cart</a></th>"; 

        


    ?>

    <body> 
    

    <?php require('footer.php'); ?>
</html>



