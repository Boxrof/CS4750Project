<!doctype html>


<html lang="en">
    <?php 
        require('header.php'); // to include the header and footer everytime
        require('db-connect.php');
    ?>
    <?php
        
        global $pdo;


        $user_ID = $_GET['user_ID'];
        $r_ID = $_GET['r_ID'];
        // echo "<p>Hi</p><br>";
        // echo $user_ID . "<br>";
        // echo $r_ID . "<br>";

        $res = $pdo->prepare("SELECT * FROM Saved WHERE user_ID=:user_ID AND r_ID=:r_ID");
        $res->bindParam(":user_ID", $user_ID);
        $res->bindParam(":r_ID", $r_ID);
        $res->execute();

        // echo $res->rowCount();
        
        if ($res->rowCount() == 0) {
            $res = $pdo->prepare("INSERT INTO Saved (user_ID, r_ID) VALUES (:user_ID, :r_ID)");
            $res->bindParam(":user_ID", $user_ID);
            $res->bindParam(":r_ID", $r_ID);
            $res->execute();
        } else {
            $res = $pdo->prepare("DELETE FROM Saved WHERE user_ID=:user_ID AND r_ID=:r_ID");
            $res->bindParam(":user_ID", $user_ID);
            $res->bindParam(":r_ID", $r_ID);
            $res->execute();
        }

        //echo("<script>location.href = 'cart.php';</script>"); href='menu.php?r_id=",$r_id,"&address=",$address,"'
        // echo "<p>Hi</p>";
        echo "<script>location.href = 'main.php';</script>" ;

    ?>

    <body> 
    

    <?php require('footer.php'); ?>
</html>



