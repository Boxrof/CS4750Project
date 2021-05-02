<!doctype html>

<html lang="en">
<body>
  <?php 
    include('header.php'); 
    require('db-connect.php');
  ?>

  <div class="container">
      <h1 class="text-center">Create a Restaurant Owner Account</h1>
      <form action="restaurant-owner-signup.php" method="POST">

        <div class="form-group">
          <label for="firstName">First Name</label>
          <input type="text" id="firstName" class="form-control" name="firstName">
        </div>

        <div class="form-group">  
          <label for="lastName">Last Name</label>
          <input type="text" id="lastName" class="form-control" name="lastName">
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" class="form-control" name="email">
        </div>

        <div class="form-group">
          <label for="password">Password (at least 8 characters)</label>
          <input type="password" id="password" class="form-control" name="password">
        </div>

        <div class="form-group">  
          <label for="phone">Phone Number</label>
          <input type="text" id="phone" class="form-control" name="phone" pattern="[0-9]{3}[0-9]{3}[0-9]{4}">
        </div>

        <div class="form-group">  
          <label for="r_address">Restaurant Address</label>
          <input type="text" id="r_address" class="form-control" name="r_address">
        </div>

        <div class="form-group">  
          <label for="r_name">Restaurant Name</label>
          <input type="text" id="r_name" class="form-control" name="r_name">
        </div>

        <div class="form-group">  
          <label for="r_rating">Restaurant Rating (1 -> 5)</label>
          <input type="text" id="r_rating" class="form-control" name="r_rating">
        </div>

        <div class="form-group">  
          <label for="r_price">Restaurant Price ($ -> $$$$)</label>
          <input type="text" id="r_price" class="form-control" name="r_price">
        </div>

        <div class="form-group">  
          <label for="r_phone_number">Restaurant Phone Number</label>
          <input type="text" id="r_phone_number" class="form-control" name="r_phone_number">
        </div>

        <div class="form-group">  
          <label for="opening_time">Restaurant Opening Time (24 Hour Time Format)</label>
          <input type="text" id="opening_time" class="form-control" name="opening_time">
        </div>

        <div class="form-group">  
          <label for="closing_time">Restaurant Closing Time (24 Hour Time Format)</label>
          <input type="text" id="closing_time" class="form-control" name="closing_time">
        </div>

        <div class="form-group">  
          <label for="date_opened">Restaurant Open Date (YYYY-MM-DD)</label>
          <input type="text" id="date_opened" class="form-control" name="date_opened">
        </div>


        <button class="btn btn-lg btn-primary btn-block mt-4" type="submit">Create Account</button>
        <a href="./login.php" class="d-inline-block text-center mt-3 mb-3">Already have an account? Sign in</a>
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




          if($num_errors == 0) // if all the above error checking for the form data passed
          {

              $res = $pdo->prepare("SELECT * FROM Users WHERE email=:email");
              $res->bindParam(":email", $email); // adding email variable to the where clause in SQL statement
              $res->execute();
              
              if ($res->rowCount() > 0) {
                // Already exist
                echo "<div class='alert alert-danger' role='alert'>" . "Email already exists" . "</div>";
              } else {
                try {
                  // initialize the query to insert the new customer into the Customer database
                  // Check the database to see if the email is already existed. If yes, then we cannot allow users to register
                  
                  $query1 = "INSERT INTO Users (email, password, phone_number, first_name, last_name, user_type) 
                    VALUES (:email, :password, :phone_number, :first_name, :last_name, :user_type)";

                  $query2 = "INSERT INTO Owners (user_ID, r_address) 
                    VALUES (:user_ID, :r_address)";

                  $query3 = "INSERT INTO Restaurants (r_address, r_name, r_rating, r_price, r_phone_number, closing_time, opening_time, date_opened)
                    VALUES (:r_address, :r_name, :r_rating, :r_price, :r_phone_number, :closing_time, :opening_time, :date_opened)";
    
                  $statement = $pdo->prepare($query1); // insert new user
                  // bind the form data to the sql query
                  $statement->bindValue(':first_name', $firstName);
                  $statement->bindValue(':last_name', $lastName);
                  $statement->bindValue(':email', $email);
                  $statement->bindValue(':password', $hashed_password);
                  $statement->bindValue(':phone_number', $phone);
                  $statement->bindValue(':user_type', 'owner');
                  // $statement->bindValue(':street', $street);
                  // $statement->bindValue(':city', $city);
                  // $statement->bindValue(':state', $state);
                  $statement->execute();
                  $statement->closeCursor();

                  $res = $pdo->prepare("SELECT * FROM Users WHERE email=:email");
                  $res->bindParam(":email", $email); // adding email variable to the where clause in SQL statement
                  $res->execute();
                  $result = $res->fetch();
                  $res->closeCursor();
                  $user_ID = $result['user_ID'];
                  
                  
                  $statement = $pdo->prepare($query3); // insert new restaurant before inserting new user to avoid foreign key error
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

                  $statement = $pdo->prepare($query2); // insert new restaurant owner user
                  $statement->bindValue(':user_ID', $user_ID);
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
                  echo "<div class='alert alert-danger' role='alert'>" . "Unable to create account" . "</div>";
                }

              }
              
          }
        }	
    ?>

</html>

    