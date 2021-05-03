<!doctype html>


<html lang="en">
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

    <?php 
        require('header.php'); // to include the header and footer everytime
        require("./db-connect.php");


    ?>

    <body> 
        <label for="cuisine">Cuisine</label>
        <input type="search" id="cuisineInput" class="form-control" name="cuisineInput">
        <div id="cuisineResult">Search Result</div>
    </body>
    

    <?php 
        
    
    
        require('footer.php'); 
    ?>
</html>



