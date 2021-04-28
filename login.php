<!doctype html>
<html lang="en">
<body>
  <?php 
    include('header.php');
    require('db-connect.php');
  ?>

  <div class="container text-center d-flex flex-column">
      <h1 class="mb-4">Login Page</h1>
      <form action="login.php" method="POST">
        <div class="form-group">
          <input name="username" type="text" id="inputEmail" class="form-control" placeholder="Username" required autofocus>
        </div>
        <div class="form-group">
          <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
        </div>
        <button class="btn btn-lg btn-primary btn-block mt-4" type="submit">Sign in</button>
      </form>
      <a href="#" class="mt-3 mb-3">Forgot password?</a>
      <a href="./signup.php" class="mb-3">Create an account</a>
  </div>
  <?php 
    include('footer.php');
  ?>
</body>
  <?php 
    global $pdo;

    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {   	  
      try {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = "SELECT * FROM user_accounts WHERE username=:username";
      
        $statement = $pdo->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        
        $result = $statement->fetch();

        $statement->closeCursor();

        if($result['password'] != $password)
        {
          echo "<div class='alert alert-danger' role='alert'>" . "Unable to login" . "</div>";
        }
        else
        {
          $_SESSION['user'] = $username;
          echo("<script>location.href = 'index.php';</script>");
        }

      } catch (PDOException $e) {
        echo $e;
      }
    }	
  ?>
</html>

    