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
      function selectRestaurantData($column) {
        // function to get the desired user data to autofill in the form
        global $pdo;
        $user_ID = $_SESSION['user_ID'];
        // get r_address from Owner to get data from Restaurants table
     
        try {
          $query = "
            SELECT $column 
            FROM Restaurants NATURAL JOIN Owners 
            WHERE `user_ID`='$user_ID';
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
      <h1 class="text-center">Edit Restaurant Details</h1>
      <form action="edit-restaurant.php" method="POST">

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
          <label for="r_address">Restaurant Address</label>
          <input type="text" id="r_address" class="form-control" name="r_address"
          value="<?php 
              echo(selectRestaurantData('r_address')[0][0]); // to autofill the form so user can edit their data
              ?>">
        </div>


        <div class="form-group">  
          <label for="r_name">Restaurant Name</label>
          <input type="text" id="r_name" class="form-control" name="r_name"
          value="<?php 
              echo(selectRestaurantData('r_name')[0][0]); // to autofill the form so user can edit their data
              ?>">
        </div>

        <div class="form-group">  
          <label for="r_rating">Restaurant Rating (1 -> 5)</label>
          <input type="text" id="r_rating" class="form-control" name="r_rating"
          value="<?php 
              echo(selectRestaurantData('r_rating')[0][0]); // to autofill the form so user can edit their data
              ?>">
        </div>

        <div class="form-group">  
          <label for="r_price">Restaurant Price ($ -> $$$$)</label>
          <input type="text" id="r_price" class="form-control" name="r_price"
          value="<?php 
              echo(selectRestaurantData('r_price')[0][0]); // to autofill the form so user can edit their data
              ?>">
        </div>

        <div class="form-group">  
          <label for="r_phone_number">Restaurant Phone Number</label>
          <input type="text" id="r_phone_number" class="form-control" name="r_phone_number"
          value="<?php 
              echo(selectRestaurantData('r_phone_number')[0][0]); // to autofill the form so user can edit their data
              ?>">
        </div>

        <div class="form-group">  
          <label for="opening_time">Restaurant Opening Time (24 Hour Time Format)</label>
          <input type="text" id="opening_time" class="form-control" name="opening_time"
          value="<?php 
              echo(selectRestaurantData('opening_time')[0][0]); // to autofill the form so user can edit their data
              ?>">
        </div>

        <div class="form-group">  
          <label for="closing_time">Restaurant Closing Time (24 Hour Time Format)</label>
          <input type="text" id="closing_time" class="form-control" name="closing_time"
          value="<?php 
              echo(selectRestaurantData('closing_time')[0][0]); // to autofill the form so user can edit their data
              ?>">
        </div>

        <div class="form-group">  
          <label for="date_opened">Restaurant Open Date (YYYY-MM-DD)</label>
          <input type="text" id="date_opened" class="form-control" name="date_opened"
          value="<?php 
              echo(selectRestaurantData('date_opened')[0][0]); // to autofill the form so user can edit their data
              ?>">
        </div>


        <button class="btn btn-lg btn-primary btn-block mt-4" type="submit">Edit Restaurant</button>
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
          $r_address = $_POST['r_address'];
          $r_name = $_POST['r_name'];
          $r_rating = $_POST['r_rating'];
          $r_price = $_POST['r_price'];
          $r_phone_number = $_POST['r_phone_number'];
          $opening_time = $_POST['opening_time'];
          $closing_time = $_POST['closing_time'];
          $date_opened = $_POST['date_opened'];
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
          if 
          (!preg_match("#[a-zA-Z]+#", $password)) 
          {
            echo "<div class='alert alert-danger' role='alert'>" . "Password must have at least 1 letter" . "</div>";
          } 
          if(!$phone)
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter your phone number" . "</div>";
          }
          if(!$r_address)
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter your restaurant's address" . "</div>";
          }
          if (!$r_name) 
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter your restaurant's name" . "</div>";
          }
          if (!$r_rating) 
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter your restaurant's rating" . "</div>";
          }
          if (!$r_price) 
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter your restaurant's price" . "</div>";
          }
          if (!$r_phone_number) 
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter your restaurant's phone number" . "</div>";
          }
          if (!$opening_time) 
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter your restaurant's opening time" . "</div>";
          }
          if (!$closing_time) 
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter your restaurant's closing time" . "</div>";
          }
          if (!$date_opened) 
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter your restaurant's date of opening" . "</div>";
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

                  $query2 = "UPDATE Owners SET
                  r_address=:r_address
                  WHERE `user_ID`='$user_ID';";
            
                  $query3 = "UPDATE Restaurants SET
                  r_address=:r_address, r_name=:r_name, r_rating=:r_rating, 
                  r_price=:r_price, r_phone_number=:r_phone_number, closing_time=:closing_time, 
                  opening_time=:opening_time, date_opened=:date_opened
                  WHERE `r_address`=" . "'$r_address'" . " OR `r_name`=" . "'$r_name'" . " OR `r_phone_number`=" . "'$r_phone_number';";

                  $statement = $pdo->prepare($query3); // edit restaurant 
                  $statement->bindValue(':r_address', $r_address);
                  $statement->bindValue(':r_name', $r_name);
                  $statement->bindValue(':r_rating', $r_rating);
                  $statement->bindValue(':r_price', $r_price);
                  $statement->bindValue(':r_phone_number', $r_phone_number);
                  $statement->bindValue(':closing_time', $closing_time);
                  $statement->bindValue(':opening_time', $opening_time);
                  $statement->bindValue(':date_opened', $date_opened);
                  $statement->execute();
                  $statement->closeCursor();

                  $statement = $pdo->prepare($query1); // edit user
                  // bind the form data to the sql query
                  $statement->bindValue(':first_name', $firstName);
                  $statement->bindValue(':last_name', $lastName);
                  $statement->bindValue(':email', $email);
                  $statement->bindValue(':password', $hashed_password);
                  $statement->bindValue(':phone_number', $phone);
                  $statement->bindValue(':user_type', 'owner');
                  $statement->execute();
                  $statement->closeCursor();

                  $statement = $pdo->prepare($query2); // edit restaurant owner user
                  $statement->bindValue(':r_address', $r_address);
                  $statement->execute();
                  $statement->closeCursor();

                  $_SESSION['firstName'] = $firstName; // set the firstName in session data to say hello <firstName> on index.php
                  $_SESSION['user_type'] = 'owner';

                  // redirect to index.php after successful account creation
                  echo("<script>location.href = 'index.php';</script>");
                  // echo "<div class='alert alert-success' role='alert'>" . "Account created! <a href='login.php'>Return to login page</a>" . "</div>";
                  
                } catch (PDOException $e) {
                  echo($e->getMessage());
                  echo "<div class='alert alert-danger' role='alert'>" . "Unable to edit restaurant" . "</div>";
                }

              }
              
          }
        
    ?>

</html>

    