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
          <label for="phone">Street Address</label>
          <input type="text" id="phone" class="form-control" name="street">
        </div>

        <div class="form-group">  
          <label for="city">City Address</label>
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
              $query = "INSERT INTO Customers (c_firstName, c_lastName, c_email, c_password, c_phone_number, c_street, c_city, c_state) 
                VALUES (:firstName, :lastName, :email, :password, :phone, :street, :city, :state)";
            
              $statement = $pdo->prepare($query);
              // bind the form data to the sql query
              $statement->bindValue(':firstName', $firstName);
              $statement->bindValue(':lastName', $lastName);
              $statement->bindValue(':email', $email);
              $statement->bindValue(':password', $password);
              $statement->bindValue(':phone', $phone);
              $statement->bindValue(':street', $street);
              $statement->bindValue(':city', $city);
              $statement->bindValue(':state', $state);
              $statement->execute();
              
              $statement->closeCursor();
              $_SESSION['firstName'] = $firstName; // set the firstName in session data to say hello <firstName> on index.php
              // redirect to index.php after successful account creation
              echo("<script>location.href = 'index.php';</script>");
              // echo "<div class='alert alert-success' role='alert'>" . "Account created! <a href='login.php'>Return to login page</a>" . "</div>";
              
            } catch (PDOException $e) {
              echo($e->getMessage());
              echo "<div class='alert alert-danger' role='alert'>" . "Unable to create account" . "</div>";
            }
          }
        }	
    ?>

</html>

    