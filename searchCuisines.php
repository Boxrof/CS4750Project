<?php 
        require("./db-connect.php");
        session_start();
        global $pdo;
        if (isset($_SESSION['firstName'])) {
            echo('<h2>Welcome ' . $_SESSION["firstName"]. '!</h2>');
        }

        // function console_log($output, $with_script_tags = true) {
        //     $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
        //     if ($with_script_tags) {
        //         $js_code = '<script>' . $js_code . '</script>';
        //     }
        //     echo $js_code;
        // }
        // console_log('sandwich');
        if (isset($_GET['searchCuisine'])) {
            $cuis = $_GET['searchCuisine'];
            if ($cuis != '') {
                $cuisine_list = explode(' ', $cuis);
                $numItems = sizeof($cuisine_list);
                if ($numItems == 1) {
                    $res = $pdo->prepare("SELECT * FROM Restaurants NATURAL JOIN Cuisines WHERE cuisine LIKE '{$cuis}%';");
                    $res->bindParam(":cuisine",$_GET['searchCuisine']);
                    $res->execute();
                } else {
                    $queryPrefix = 'SELECT * FROM Restaurants NATURAL JOIN ';
                    $queryMain = '';
                    for ($i = 0; $i < $numItems; $i++) {
                        //$queryMain = "(" . "SELECT r_ID FROM `Cuisines` WHERE cuisine = ':c{$i}'" . $queryMain . ")";
                        $val = $cuisine_list[$i];
                        //$val = ':c' . $i;
                        //echo $val . "<br>";
                        $queryMain = "(" . "SELECT DISTINCT(r_ID) FROM `Cuisines` WHERE cuisine LIKE '" . $val . "%' " . $queryMain . ")";
                        if (($i + 1) != $numItems) {
                            $queryMain = ' AND r_ID IN ' . $queryMain;
                        }
                        //echo "The number is: $x <br>";
                    } 
                    $queryMain .= " AS T";
                    //echo $queryPrefix . $queryMain;
                    $res = $pdo->prepare($queryPrefix . $queryMain . ";");

                    // $my_array = array_fill(0, $numItems, '');

                    // for ($j = 0; $j < $numItems; $j++) {
                        //echo '<br></br>';
                        
                        // $my_array[$j] = ':c' . $j;
                        // $val = $cuisine_list[$j];
                        //echo $tempStr . ' ';
                        // $res->bindParam($my_array[$j], $cuisine_list[$j]);
                        //echo $cuisine_list[$j];
                    // }
                    //$res->bindParam(":cuisine",$_GET['searchCuisine']);
                    //echo '<br>';
                    // $res->debugDumpParams();
                    $res->execute();
                }
            } else {
                $res = $pdo->prepare("SELECT * FROM Restaurants;");
                $res->execute();
            }
        } else {
            $res = $pdo->prepare("SELECT * FROM Restaurants;");
            $res->execute();
        }
        // SELECT * FROM Restaurants NATURAL JOIN Cuisines WHERE cuisine LIKE :cuisine 
        // Get the list of all restaurants to render in the front-end
        echo "<h1 class='text-center'> List of Restaurants: </h1>";
        echo "<br>";
        echo "<table class='table table-condensed table-hover' style='table-layout: fixed; border-collapse:collapse;'>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th scope='col'> Detail </th>";
                    echo "<th scope='col'>Favorite </th>";
                    echo "<th scope='col'>Address </th>";
                    echo "<th scope='col'>Restaurant Name </th>";
                    echo "<th scope='col'>Rating </th>";
                    echo "<th scope='col'>Price </th>";
                    echo "<th scope='col'>Cuisine </th>";
                echo "</tr>";
            echo "</thead>";


            $user_ID = $_SESSION['user_ID'];
            $queryS = "SELECT DISTINCT(r_ID) FROM Saved WHERE user_ID='" . $user_ID . "';";
            // echo $queryS . "<br>";
            $temp_res = $pdo->query($queryS);
            // while ($row = $res->fetch()) {
            //     echo $row . "<br>";
            // }
            $r_ID_array = $temp_res->fetchAll();
            if ($r_ID_array == false) {
                $r_ID_array = [];
            }

            for ($i = 0; $i < sizeof($r_ID_array); $i++) {
                $r_ID_array[$i] = $r_ID_array[$i][0];
            }

            foreach ($r_ID_array as $x) {
                // echo $x . "<br>";
                // foreach ($x as $y) {
                //     echo $y . "<br>";
                // }
            }
            //echo $r_ID_array;
    
            while ($row = $res->fetch()) {
                $r_id = $row["r_ID"];
                $address = $row["r_address"];
                echo "<tr>";
                    echo "<th scope='row'><a class='btn btn-outline-secondary btn-sm' href='menu.php?r_id=",$r_id,"&address=",$address,"'> Click here to view menu </a></th>";
                    if (isset($_SESSION['user_ID'])) {
                        //$user_ID = $_SESSION['user_ID'];
                        //$temp_res = $pdo->query("SELECT * FROM Saved WHERE user_ID='" . $user_ID . "' AND r_ID='" . $r_id . "'");
                        //$temp_res->execute();
                        //if ($temp_res->rowCount() > 0) {
                        if (in_array($r_id, $r_ID_array)) {
                            echo "<td><a href='saveFavorite.php?user_ID=",$_SESSION['user_ID'],"&r_ID=",$r_id,"'><img src='star_full.png' style='width:25px; height:24.0625px'></td></a>";
                        } else {
                            echo "<td><a href='saveFavorite.php?user_ID=",$_SESSION['user_ID'],"&r_ID=",$r_id,"'><img src='star_empty.png' style='width:25px; height:24.0625px'></td></a>";
                        }
                    } else {
                        echo ('<td>Login</td>');
                    }
                    echo('<td> ' .  $row["r_address"]. '</td>');
                    echo('<td> ' .  $row["r_name"]. '</td>');
                    echo('<td> ' .  $row["r_rating"]. '</td>');
                    echo('<td> ' .  $row["r_price"]. '</td>');
                    echo('<td> ' .  $row["r_cuisine"]. '</td>');
                echo "</tr>";    
            }
    
        echo "</table>";

?>