<!doctype html>


<html lang="en">
    <!-- For ajax -->
    <head>
    <script src="./js/jquery-1.6.2.min.js" type="text/javascript"></script> 
	<script src="./js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>

    <script>
    $(document).ready(function() {
        $( "#cuisineInput" ).change(function() {

            $.ajax({
                url: 'searchCuisines.php',
                //data: {searchCuisine: $( "#cuisineInput" ).val()},
                success: function(data) {
                    $('#cuisineResult').html(data);

                }
            });
        });
    });
    </script>
    <script>
        function runJax(str) { // https://www.w3schools.com/php/php_ajax_php.asp
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("cuisineResult").innerHTML = this.responseText;
            }
            };
            if (str.length == 0) {
                xmlhttp.open("GET", "searchCuisines.php", true);
            } else {
                xmlhttp.open("GET", "searchCuisines.php?searchCuisine=" + str, true);
            }
            xmlhttp.send();
        }
    </script>
    </head>

    <?php 
        require('header.php'); // to include the header and footer everytime
        require("./db-connect.php");


    ?>

    <body onload="runJax('')"> 
        <label for="cuisine">Cuisine</label>
        <input type="search" id="cuisineInput" class="form-control" name="cuisineInput" onkeyup="runJax(this.value)">
        <div id="cuisineResult">Search Result</div>
    </body>
    

    <?php 
        require('footer.php'); 
    ?>
</html>



