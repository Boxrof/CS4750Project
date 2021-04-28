<!doctype html>

<html lang="en">
<body>
  <?php 
    include('header.php');
    require('db-connect.php');
  ?>

  <div class="container">
      <h1 class="text-center">Create an account</h1>
      <form action="signup.php" method="POST">
        <div class="form-group">
          <label for="firstName">First Name</label>
          <input type="text" id="firstName" class="form-control" name="firstName">
          <span class="error" id="firstName-note"></span>
        </div>

        <div class="form-group">  
          <label for="lastName">Last Name</label>
          <input type="text" id="lastName" class="form-control" name="lastName">
          <span class="error" id="lastName-note"></span>
        </div>

        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" class="form-control" name="username">
          <span class="error" id="username-note"></span>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" class="form-control" name="password">
          <span class="error" id="password-note"></span>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" class="form-control" name="email">
          <span class="error" id="email-note"></span>
        </div>

        <div class="form-group">  
          <label for="phone">Phone Number</label>
          <input type="text" id="phone" class="form-control" name="phone" pattern="[0-9]{3}[0-9]{3}[0-9]{4}">
          <span class="error" id="phone-note"></span>
        </div>

        <button class="btn btn-lg btn-primary btn-block mt-4" type="submit">Create Account</button>
        <a href="./login.php" class="d-inline-block text-center mt-3 mb-3">Already have an account? Sign in</a>
      </form>
    </div>
    <?php include('footer.php'); ?>
</body>

    <style>
        .error {
            color: red;
            font-style: italic;
        }
    </style>

    <?php 
        global $pdo;

        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {   	 
          $firstName = $_POST['firstName'];
          $lastName = $_POST['lastName'];
          $username = $_POST['username'];
          $password = $_POST['password'];
          $email = $_POST['email'];
          $phone = $_POST['phone'];

          $num_errors = 0;

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

          if(!$password)
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter a passowrd" . "</div>";
          }

          if(strlen($password) < 8)
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Password must be at least 8 characters" . "</div>";
          }

          if(!$email)
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter your email address" . "</div>";
          }

          if(!$phone)
          {
            $num_errors++;
            echo "<div class='alert alert-danger' role='alert'>" . "Please enter your phone number" . "</div>";
          }

          if($num_errors == 0)
          {
            try {
              $query = "INSERT INTO user_accounts (username, first_name, last_name, phone_number, email, password) 
                VALUES (:username, :first, :last, :phone, :email, :pass)";
            
              $statement = $pdo->prepare($query);
              $statement->bindValue(':username', $username);
              $statement->bindValue(':first', $firstName);
              $statement->bindValue(':last', $lastName);
              $statement->bindValue(':pass', $password);
              $statement->bindValue(':email', $email);
              $statement->bindValue(':phone', $phone);
              $statement->execute();
              
              $statement->closeCursor();
  
              echo "<div class='alert alert-success' role='alert'>" . "Account created! <a href='login.php'>Return to login page</a>" . "</div>";
  
            } catch (PDOException $e) {
              echo "<div class='alert alert-danger' role='alert'>" . "Unable to create account" . "</div>";
            }
          }
        }	
    ?>

</html>

    