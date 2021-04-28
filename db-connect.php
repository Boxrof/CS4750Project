<!doctype html>
<html lang="en">
<?php 
    $loginUser = "jm9hx"; 
    $loginPass = "Praphamontripong1234!!";
    $host = "usersrv01.cs.virginia.edu"; // DB Host
    $schema = "jm9hx"; // DB name
    
    // create PDO statements
    $pdo = new PDO('mysql:host='.$host.';dbname='.$schema, $loginUser, $loginPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo($host);

?>

</html>