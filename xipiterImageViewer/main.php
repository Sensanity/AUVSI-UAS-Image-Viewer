<?php
  require_once "login.php";
  $db_server = mysqli_connect($db_hostname, $db_username, $db_password, $db_database);
  if(!$db_server) die("No Connection to Server: " . mysqli_connect_error());

    if( isset($_POST['name']) && isset($_POST['image_text']) ){

      $imgNum = $_POST['name'];
      $dir = 'collections';
      $fileNames = array();
      if(is_dir($dir)){
          $handle = opendir($dir);
          while(false !== ($file = readdir($handle))){
              if(is_file($dir.'/'.$file) && is_readable($dir.'/'.$file)){
                      $fileNames[] = $file;
              }
          }
          closedir($handle);

      }else {
          echo "<p>There is an directory read issue</p>";
      }
      $fName = $fileNames[$imgNum];
      $imageinfo = $_POST['image_text'];

      //Insert into Database
      $sql_insert = "INSERT INTO selected_images (image_name, image_text) VALUES ('$fName', '$imageinfo')";
      mysqli_query($db_server, $sql_insert);
      $result = mysqli_query($db_server, "SELECT * FROM selected_images ORDER BY id DESC");
      while ($row = mysqli_fetch_array($result)) {
        echo "<div id='img_div'>";
          $imgID = $row["id"];
          //echo $imgID;
        	echo "<img src='collections/".$row['image_name']."' >";
        	echo "<p id='sent_text'>".$row['image_text']."</p>";
          echo "<button type='button' name = 'delete_button' class='button' id=$imgID onclick='delImg(this)'>Delete</button>";
        echo "</div>";
      }
      exit;
    }

    if (isset($_POST['img_index'])) {
        $sql_delete = "DELETE FROM selected_images WHERE id = '".$_POST["img_index"]."'";
        mysqli_query($db_server, $sql_delete);
        $result = mysqli_query($db_server, "SELECT * FROM selected_images ORDER BY id DESC");
        while ($row = mysqli_fetch_array($result)) {
          echo "<div id='img_div'>";
            $imgID = $row["id"];
            //echo $imgID;
          	echo "<img src='collections/".$row['image_name']."' >";
          	echo "<p id='sent_text'>".$row['image_text']."</p>";
            echo "<button type='button' name = 'delete_button' class='button' id=$imgID onclick='delImg(this)'>Delete</button>";
          echo "</div>";
        }
        exit;
    }

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UAV Image Viewer</title>

    <style>
      /* Image slider orientation on page */
      body, .imgViewer, .imgContent {
        padding: 0;
        font-family: Arial, Helvetica, sans-serif;
        height: 100vh;
        overflow-x: hidden;
        margin-top: 100px;
        margin-right: 50px;
        margin-left: 50px;
      }

      .imgViewer {
        bottom: 30px;
        position: relative;
      }

      .img {
        background-size: 1200px 600px;
        background-position: center;
        background-repeat: no-repeat;
        width: auto;
        height: 600px;
        overflow-y: hidden;
      }

      /* Left and Right Arrows */
      .arrow {
        cursor: pointer;
        position: absolute;
        top: 50%;
        margin-top: -30px;
        width: 0;
        height: 0;
        border-style: solid;
        overflow-y: hidden;
      }

      #arrowLeft {
        border-width: 30px 40px 30px 0;
        border-color: transparent #101010 transparent transparent;
        left: 0;
        margin-left: 20px;
      }

      #arrowRight {
        border-width: 30px 0 30px 40px;
        border-color: transparent transparent transparent #101010;
        right: 0;
        margin-right: 20px;
      }

      /* Submit Button  */
      .buttonStyle{
        position: relative;
        margin-left: 0px;
        top: 630px;

      }

      .button {
        background: #FF0000;
        color: #fff;
        border: 1px solid #000000;
        border-radius: 20px;
        text-shadow:none;
      }

      .button:hover{
        background: #FFB2B2;
        color: #fff;
        border: 1px solid #eee;
        border-radius: 20px;
        text-shadow:none;
      }
      .button:active{
        background: #FF0000;
        color: #fff;
        border: 1px solid #eee;
        border-radius: 20px;
        text-shadow:none;
        position: relative;
      }

      /* Selected Images */
      #res{
        position: absolute;
        top: 810px;
        width: 107.5%;
       	margin: 20px auto;
        margin-left: -107.5px;
       	/* border: 1px solid #cbcbcb; */
      }
      #img_div{
      	width: 80%;
      	padding: 5px;
      	margin: 15px auto;
      	border: 1px solid #000000;
      }
      #img_div:after{
      	content: "";
      	display: block;
      	clear: both;
      }

      img{
      	float: left;
      	margin: 5px;
      	width: 300px;
      	height: 140px;
      }

      /* text */
      #textinfo{
        position: absolute;
        z-index: 1;
        margin-top: 630px;
        margin-left: 50px;
        border: 1px solid #000000;
      }
      #sent_text{
        white-space: pre-wrap;
        font-size: 14px;
        font-family: "Times New Roman";
      }

      /* title */
      #title{
        position: absolute;
        margin-top: -95px;
        margin-left: 530px;
        font-size: 4em;
        z-index: 100;
        color: #800000;
        text-shadow: .04em .04em 0 #FFFFFF;
      }

      /* page */
      #thewholepage{
        background-color: #C0C0C0;
      }
    </style>

  </head>
  <body id="thewholepage">

    <!-- Submitted Images -->
    <div class="result" id="res"></div>

    <textarea
      id="textinfo"
      cols="60"
      rows="6"
      wrap="hard"
      name="image_text"
      placeholder="Write stuff here..."></textarea>

    <div id="title">MSU Xipiter</div>

    <!-- Movement Operations -->
    <div id="arrowLeft" class="arrow"></div>
    <div id="arrowRight" class="arrow"></div>

    <!--Main Class -->
    <div class="imgViewer">

      <!-- Submit form button, with onclick function in JS below -->
      <div class="buttonStyle">
        <button type="button" class="button" id="button">Submit</button>
      </div>


    <!-- PHP loop to print imges from folder onto page -->
      <?php
        $dir = "collections";
        $images = glob($dir);
        if ($opendir = opendir($dir)){
          while(($file = readdir($opendir)) !== FALSE){
            if($file != "." && $file != ".."){
              //echo "<img src = '$dir/$file'><br>"; <- This prints all the images one on top of each other.
              echo "<div class=\"img\" style=\"background-image: url($dir/$file);\"> \n";
              //echo $file;  // STOPPED HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! Need to use ajax to use javascript variable in php.
              echo "<div class=\"imgContent\"></div> \n";
              echo "</div> \n";

              }
            }
          }

      ?>

    </div>

    <!-- Image Recognition -->
      <script>

        // Recognize images on page and allow them to be manipulated
        const realFileBtn = document.getElementById("real-file");
        const customBtn = document.getElementById("custom-button");
        const customTxt = document.getElementById("custom-text");

        customBtn.addEventListener("click", function(){
          realFileBtn.click();
        })

        realFileBtn.addEventListener("change", function() {
          if(realFileBtn.value){
            customTxt.innerHTML = realFileBtn.value.match(/[\/\\]([\w\d\s\.\-\(\)]+)$/)[1];
          } else {
            customBtn.innerHTML = "No File Chosen";
          }

        })
      </script>

    <!-- Arrow functionality -->
      <script>
        let sliderImages = document.querySelectorAll(".img"),
        aL = document.querySelector("#arrowLeft"),
        aR = document.querySelector("#arrowRight"),
        current = 0;
        setCurrentImage(current);

        // clear
        function reset() {
          for (let i = 0; i < sliderImages.length; i++) {
            sliderImages[i].style.display = "none";
          }
        }

        // start the img viewer
        function startSlide() {
          reset();
          sliderImages[0].style.display = "block";
        }

        // prv img
        function slideLeft() {
          reset();
          sliderImages[current - 1].style.display = "block";
          current--;
          setCurrentImage(current);
        }

        // next img
        function slideRight() {
          reset();
          sliderImages[current + 1].style.display = "block";
          current++;
          setCurrentImage(current);
        }

        //Determine the index number of the current image in the folder
        function setCurrentImage(num){

          var submit = document.getElementById('button');
          var del = document.getElementById('del');
          var currentFileNum = num;
          //Ajax request
          var xhttp = new XMLHttpRequest();

          submit.onclick = function(){
            xhttp.onreadystatechange = function() {
              if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("res").innerHTML = this.responseText;
              }
            };
            var textinfo = document.getElementById('textinfo')
            //Send said image index number back to main
            xhttp.open("POST", "main.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send('name='+currentFileNum + '&image_text='+textinfo.value);
            textinfo.value="";
          }
        }

        function delImg(d){
          var img_id = d.getAttribute("id");
          var delReq = new XMLHttpRequest();
          delReq.onreadystatechange = function() {
            if (delReq.readyState == 4 && delReq.status == 200) {
              document.getElementById("res").innerHTML = this.responseText;
            }
          };
          delReq.open("POST", "main.php", true);
          delReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          delReq.send('img_index='+img_id);
        }

        // left arrow functionality
        aL.addEventListener("click", function() {
          if (current === 0) {
            current = sliderImages.length;
          }
          slideLeft();
        });

        // right arrow functionality
        aR.addEventListener("click", function() {
          if (current === sliderImages.length - 1) {
            current = -1;
          }
          slideRight();
        });
        startSlide();
      </script>


  </body>
</html>
