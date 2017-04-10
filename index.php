<!DOCTYPE html>

<html>
  <head>
  <!-- Lidt JS til at trylle send knappen ud og ind -->
  <script type="text/javascript">
  function validateInput(){
    var name = document.getElementById("name");
    var com = document.getElementById("comment");
    var pass = true;
    if(name.value == '' || com.value == '')
        pass = false;
    document.getElementById("send").style.display = pass?"inline":"none";
    return pass;
  };
  </script>
  
  <!-- Metadata -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale = 1">
	<title>Tankestrøm!</title>
  
  <!-- En smule style så det hele ikke ser herrens ud-->
  <style type="text/css">
    body {
    font-family: sans-serif;
    font-size: 70%;
    display: block;
    margin: 0;
    padding: 0;
    text-align: center;
    }
    span {
      color: rgb(100, 100, 100);
    }
    .form {
      height: 30vh;
      min-height: 250px;
    }
    .comments {
      background-color: rgb(174, 198, 207);
      margin: 0 auto;
      max-height: 60vh;
      max-width: 600px;
      overflow-y: scroll;
      width: 100%;
      word-wrap: break-word;
    }
    .comment {
      border-bottom: 1px solid rgb(91, 91, 91);;
    }
    #send {
      margin-top: 20px;
    }
  </style>

  </head>
  
  <!-- Start dokument -->
  <body onload="validateInput();" onmousedown="validateInput();" onkeyup="validateInput();">
    
    <!-- Ny kommentar -->  
    <?php
      if($_POST) { 
        $con = new mysqli('localhost', 'root', '', 'recruit');
        if(!$con)
          die('Kunne ikke forbinde til serveren: ' . mysqli_error($con));

        $insert = "INSERT INTO comments (Comment, Name, Udvikler) 
        VALUES ('$_POST[comment]', 
                '$_POST[name]', 
                'mtn.dtu@gmail.com')";

        $trueins = mysqli_query($con, $insert);
        if(!$trueins)
          die('Kunne ikke indsætte kommentar: ' . mysqli_error($con));

        //PRG efter skriv til server
        header("Location: " . $_SERVER['REQUEST_URI']);         

        // Luk forbindelse
        mysqli_close($con);
      }
    ?>

    <!-- Kommentarformen -->
    <div class="form">
      <form method='post' id='commentform' onsubmit="return validateInput()">
        <h2>Udfyld formen og den magiske knap dukker op!</h2>
        <p>Navn:</p>
        <input type='text' name='name' id='name' maxlength='50'><br/>

        <p>Kommentar:</p>
        <input type='text' name='comment' id='comment' maxlength='1000'><br/>
        <button type='submit' name='send' id='send' value='Send'>Send kommentar</button>
      </form>
    </div>

    <!-- Listen over andre kommentarer opdateres løbende -->
    <div class="comments">
      <h2>Tankestrømmen!</h2>
      <?php
        $con = new mysqli('localhost', 'root', '', 'recruit');
      
        if(!$con)
          die('Kunne ikke forbinde til serveren: ' . mysqli_error($con));

        $con->set_charset("utf-8");

        $output = "SELECT * FROM `comments`";
        $commenting = mysqli_query($con, $output);

        if(!$commenting)
          die('Kunne ikke vise kommentarer: ' . mysqli_error($con));

        while($row = mysqli_fetch_array($commenting)) {
          $commentout = $row['Comment'];
          $commentout = htmlspecialchars($row['Comment'],ENT_QUOTES);
          $nameout = $row['Name'];
          $nameout = htmlspecialchars($row['Name'],ENT_QUOTES);

          echo "<div class='comment'>
                <p>$commentout
                <span> af $nameout</span></p>
                </div>";
        }

        mysqli_close($con);
      ?>
    </div>
  </body>
</html>