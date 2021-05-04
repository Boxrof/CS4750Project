
<header>

    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>

    <!-- Bootstrap (CSS only) -->
    <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'
        integrity='sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z' crossorigin='anonymous'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
    <style type="text/css">
        <?php include './css/style.css'; ?>
    </style>
    
    <title>CS 4750 Project</title>

    <!-- For ajax -->
    <script src="./js/jquery-1.6.2.min.js" type="text/javascript"></script> 
	<script src="./js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>

    <script>
    $(document).ready(function() {
        $( "#cuisineInput" ).change(function() {
            $.ajax({
                url: 'searchCuisines.php',
                data: {searchCuisine: $( "#cuisineInput" ).val()},
                success: function(data) {
                    $('#cuisineResult').html(data);
                }
            });
        });
    });
    </script>


    <nav class="navbar navbar-light bg-light">
        <div class="brand-title">
            <a href="./index.php">
                <h1>Food Ordering</h1>
            </a>
        </div>

        <ul class='nav'>
                <?php 
                session_start();
                    if (isset($_SESSION['firstName'])) {
                        if ($_SESSION['user_type'] == 'customer') { // customer account
                            echo("
                            <li class='nav-item'>
                                <a class='nav-link' href='edit-customer.php'> Edit Profile </a>
                            </li>
                            <li class='nav-item'>
                                <a class='nav-link' href='main.php'> Restaurant List </a>
                            </li>
                            <li class='nav-item'>
                                <a class='nav-link' href='cart.php'> Cart </a>
                            </li>
                            <li class='nav-item'>
                                <a class='nav-link' href='logout.php'>Logout</a>
                            </li>
                            "); 
                        }

                        if ($_SESSION['user_type'] == 'driver') { // driver account
                            echo("
                            <li class='nav-item'>
                                <a class='nav-link' href='edit-driver.php'> Edit Profile </a>
                            </li>
                            <li class='nav-item'>
                                <a class='nav-link' href='logout.php'>Logout</a>
                            </li>
                            "); 
                        }

                        if ($_SESSION['user_type'] == 'owner') { // owner account
                            echo("
                            <li class='nav-item'>
                                <a class='nav-link' href='edit-restaurant.php'> Edit Restaurant </a>
                            </li>
                            <li class='nav-item'>
                                <a class='nav-link' href='logout.php'>Logout</a>
                            </li>
                            "); 
                        }
                       
                    } else {
                        echo("
                        <li class='nav-item active'>
                            <a class='nav-link' href='signup.php'>Signup</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link' href='login.php'>Login</a>
                        </li>
                        ");
                    }
                ?>


            </ul>
    </nav>
</header>