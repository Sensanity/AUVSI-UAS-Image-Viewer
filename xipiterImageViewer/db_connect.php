<!DOCTYPE html>
<html>
<body>
  <div class="db_messages"></div>

  <?php

    //Create DATABASE in mySQL
    require_once "login.php";
    $db_server = mysqli_connect($db_hostname, $db_username, $db_password);
    if(!$db_server) die("No Connection to Server: " . mysqli_connect_error());

    $db_selected = mysqli_select_db($db_server, 'xipiter');
    if (!$db_selected) {
      
    // If no database exists, create the database
      $db_create = 'CREATE DATABASE xipiter';
      if (mysqli_query($db_server, $db_create)) {
          echo "<div>Database, xipiter, created successfully.</div>";
      } else {
          echo "Error creating database: " . mysql_error() . "</div>";
        }
    } else {
      echo "<div>Database, xipiter, is already present.</div>";
    }


    $db_server = mysqli_connect($db_hostname, $db_username, $db_password, $db_database);
    if(!$db_server) die("No Connection to Server: " . mysqli_connect_error());

    //Create table
    $tbl_selected_images = "CREATE TABLE IF NOT EXISTS selected_images (
                id          INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  			        image_name  VARCHAR(100),
  			        image_text  TEXT
               )";

    if (mysqli_query($db_server, $tbl_selected_images)) {
       echo "<div>Table, selected_images, created successfully.</div>";
    } else {
       echo "<div>Error creating table: " . mysql_error() . "</div>";
    }
   ?>


</body>
</html>
