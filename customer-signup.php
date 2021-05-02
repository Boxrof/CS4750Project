<!doctype html>

<html lang="en">
<body>
  <?php 
    include('header.php'); 
    require('db-connect.php');
  ?>

  <div class="container">
      <h1 class="text-center">Create a Customer Account</h1>
      <form action="customer-signup.php" method="POST">

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
          <label for="street">Street Address</label>
          <input type="text" id="street" class="form-control" name="street">
        </div>

        <div class="form-group">  
          <label for="city">City</label>
          <input type="text" id="city" class="form-control" name="city">
        </div>

        <div class="form-group">  
          <label for="state">State</label>
          <input type="text" id="state" class="form-control" name="state">
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
          $street = $_POST['street'];
          $city = $_POST['city'];
          $state = $_POST['state'];

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

                  $query2 = "INSERT INTO Customers (user_ID, street, city, state) 
                    VALUES (:user_ID, :street, :city, :state)";
    
                  $statement = $pdo->prepare($query1);
                  // bind the form data to the sql query
                  $statement->bindValue(':first_name', $firstName);
                  $statement->bindValue(':last_name', $lastName);
                  $statement->bindValue(':email', $email);
                  $statement->bindValue(':password', $hashed_password);
                  $statement->bindValue(':phone_number', $phone);
                  $statement->bindValue(':user_type', 'customer');
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
                  
                  $statement = $pdo->prepare($query2);
                  $statement->bindValue(':user_ID', $user_ID);
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
                  echo "<div class='alert alert-danger' role='alert'>" . "Unable to create account" . "</div>";
                }

              }
              
          }
        }	
    ?>

</html>

    