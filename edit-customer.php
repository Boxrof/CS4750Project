<!doctype html>

<html lang="en">
<body>
  <?php 
    include('header.php'); 
    require('db-connect.php');
    function selectUserData($column) {
        // function to get the desired user data to autofill in the form
        global $pdo;
        $user_ID = $_SESSION['user_ID'];
        try {
          $query = "
            SELECT $column FROM Users WHERE user_ID='$user_ID';
          ";
          $statement = $pdo->prepare($query);
          $statement->execute();
          $results = $statement->fetchAll();
          $statement->closecursor();
          return $results;
  
        } catch (PDOException $e) {
          echo($e->getMessage());
        }
      }
      function selectCustomerData($column) {
        // function to get the desired user data to autofill in the form
        global $pdo;
        $user_ID = $_SESSION['user_ID'];
        try {
          $query = "
            SELECT $column FROM Customers WHERE user_ID='$user_ID';
          ";
          $statement = $pdo->prepare($query);
          $statement->execute();
          $results = $statement->fetchAll();
          $statement->closecursor();
          return $results;
  
        } catch (PDOException $e) {
          echo($e->getMessage());
        }
      }
  ?>

  <div class="container">
      <h1 class="text-center">Edit Customer Account</h1>
      <form action="edit-customer.php" method="POST">

        <div class="form-group">
          <label for="firstName">First Name</label>
          <input type="text" id="firstName" class="form-control" name="firstName" 
          value="<?php 
              echo(selectUserData('first_name')[0][0]); // to autofill the form so user can edit their data
              ?>">
        </div>

        <div class="form-group">  
          <label for="lastName">Last Name</label>
          <input type="text" id="lastName" class="form-control" name="lastName"
          value="<?php 
              echo(selectUserData('last_name')[0][0]); // to autofill the form so user can edit their data
              ?>">
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" class="form-control" name="email"
          value="<?php 
              echo(selectUserData('email')[0][0]); // to autofill the form so user can edit their data
              ?>">
        </div>

        <div class="form-group">
          <label for="password">Password (at least 8 characters)</label>
          <input type="password" id="password" class="form-control" name="password">
        </div>

        <div class="form-group">  
          <label for="phone">Phone Number</label>
          <input type="text" id="phone" class="form-control" name="phone" pattern="[0-9]{3}[0-9]{3}[0-9]{4}"
          value="<?php 
              echo(selectUserData('phone_number')[0][0]); // to autofill the form so user can edit their data
              ?>">
        </div>

        <div class="form-group">  
          <label for="street">Street Address</label>
          <input type="text" id="street" class="form-control" name="street"
          value="<?php 
              echo(selectCustomerData('street')[0][0]); // to autofill the form so user can edit their data
              ?>">
        </div>

        <div class="form-group">  
          <label for="city">City</label>
          <input type="text" id="city" class="form-control" name="city"
          value="<?php 
              echo(selectCustomerData('city')[0][0]); // to autofill the form so user can edit their data
              ?>">
        </div>

        <div class="form-group">  
          <label for="state">State</label>
          <input type="text" id="state" class="form-control" name="state"
          value="<?php 
              echo(selectCustomerData('state')[0][0]); // to autofill the form so user can edit their data
              ?>">
        </div>

        <button class="btn btn-lg btn-primary btn-block mt-4" type="submit">Edit Account</button>
      </form>
    </div>
    <?php require('footer.php'); ?>
</body>

    <style>
        .error {
            color: red;
            font-style: italic;
        }
    </style>

    <?php 
        global $pdo; // pdo is from db-connect.php

        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {   	 
          // store post data into variables
          $firstName = $_POST['firstName'];
          $lastName = $_POST['lastName'];
          $email = $_POST['email'];
          $password = $_POST['password'];
          $phone = $_POST['phone'];
          $street = $_POST['street'];
          $city = $_POST['city'];
          $state = $_POST['state'];
          $user_ID = $_SESSION['user_ID'];

          $hashed_password = password_hash($password, PASSWORD_DEFAULT);

          $num_errors = 0;

          // make sure all the data from the post is not empty
          // TODO: account for duplicate email addresses, don't want duplicates
          // TODO: more checking for username, maybe include numbers or special characters
          if(!$firstName)
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter your first name" . "</div>";
          }

          if(!$lastName)
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter your last name" . "</div>";
          }

          if(!$email)
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter an email" . "</div>";
          }

          if(strlen($password) < 8)
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Password must be at least 8 characters" . "</div>";
          }
          if (!preg_match("#[0-9]+#", $password)) 
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Password must have at least 1 number" . "</div>";
          }
          if (!preg_match("#[a-zA-Z]+#", $password)) 
          {
            echo "<div class='alert alert-danger' role='alert'>" . "Password must have at least 1 letter" . "</div>";
          } 
          if(!$phone)
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter your phone number" . "</div>";
          }

          if(!$street)
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter your street address" . "</div>";
          }

          if(!$city)
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter your city" . "</div>";
          }

          if(!$state)
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter your state" . "</div>";
          }



          if($num_errors == 0) // if all the above error checking for the form data passed
          {
                try {
                  // initialize the query to insert the new customer into the Customer database
                  // Check the database to see if the email is already existed. If yes, then we cannot allow users to register
                  
                  $query1 = "UPDATE Users SET
                  email=:email, password=:password, phone_number=:phone_number, 
                  first_name=:first_name, last_name=:last_name, user_type=:user_type
                  WHERE `user_ID`='$user_ID';";

                  $query2 = "UPDATE Customers SET
                  street=:street, city=:city, state=:state
                  WHERE `user_ID`='$user_ID';";
                  $statement = $pdo->prepare($query1);
                  // bind the form data to the sql query
                  $statement->bindValue(':first_name', $firstName);
                  $statement->bindValue(':last_name', $lastName);
                  $statement->bindValue(':email', $email);
                  $statement->bindValue(':password', $hashed_password);
                  $statement->bindValue(':phone_number', $phone);
                  $statement->bindValue(':user_type', 'customer');

                  $statement->execute();
                  $statement->closeCursor();

                  $statement = $pdo->prepare($query2);
                  $statement->bindValue(':street', $street);
                  $statement->bindValue(':city', $city);
                  $statement->bindValue(':state', $state);
                  $statement->execute();
                  $statement->closeCursor();

                  $_SESSION['firstName'] = $firstName; // set the firstName in session data to say hello <firstName> on index.php
                  $_SESSION['user_type'] = 'customer';
                  
                  // redirect to index.php after successful account creation
                  echo("<script>location.href = 'index.php';</script>");
                  // echo "<div class='alert alert-success' role='alert'>" . "Account created! <a href='login.php'>Return to login page</a>" . "</div>";
                  
                } catch (PDOException $e) {
                  echo($e->getMessage());
                  echo "<div class='alert alert-danger' role='alert'>" . "Unable to edit account" . "</div>";
                }
              }
          }
        
    ?>

</html>

    