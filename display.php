<!DOCTYPE html>
<html lang="en">

  <head>
    <title>SimpleCMS</title>
  </head>

  <body>
    <?php

      include_once('_class/simpleCMS.php');
      $obj = new SimpleCMS();

      if ($_POST){
        $obj->write();
      }

      $isAdmin = $_GET['admin'] ?? false;
      echo $isAdmin ? $obj->displayAdmin() : $obj->displayPublic();   
    ?>


    
  </body>

</html>