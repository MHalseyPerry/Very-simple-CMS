<?php

class SimpleCMS {
  public function __construct(){
    $this->conn = new mysqli("localhost", "root", "","test");
    if ($this->conn->connect_errno) {
      die("Failed to connect to MySQL: (" . $this->conn->connect_errno . ") " . $this->conn->connect_error);
    }
    $this->setup();
  }

  public function displayPublic() {
    $q = "SELECT * FROM posts ORDER BY created DESC";
    $r = $this->conn->query($q);

    if ($r && $r->num_rows > 0) {
      $html = '<table border="1">';
      while ($row = $r->fetch_assoc()) {
        $title = stripslashes($row['title']);
        $bodytext = stripslashes($row['bodytext']);

        $html .= <<<ENTRY_DISPLAY
          <tr class="post">
          	<td>
          		$title
          	</td>
      	    <td>
      	      $bodytext
      	    </td>
      	 </tr>
ENTRY_DISPLAY;
      }
      $html .= '</table>';
    } else {
      $html = <<<ENTRY_DISPLAY
        <h2> This Page Is Under Construction </h2>
        <p>
          No entries have been made on this page. 
          Please check back soon, or click the
          link below to add an entry!
        </p>
ENTRY_DISPLAY;
    }

    $html .= <<<ADMIN_OPTION
      <p class="admin_link">
        <a href="{$_SERVER['PHP_SELF']}?admin=1">Add a New Entry</a>
      </p>
ADMIN_OPTION;

    return $html;
  }

  public function displayAdmin() {
    return <<<ADMIN_FORM

    <form action="{$_SERVER['PHP_SELF']}" method="post">
    
      <label for="title">Title:</label><br />
      <input name="title" id="title" type="text" maxlength="150" />
      <div class="clear"></div>
     
      <label for="bodytext">Body Text:</label><br />
      <textarea name="bodytext" id="bodytext"></textarea>
      <div class="clear"></div>
      
      <input type="submit" value="Create This Entry!" />
    </form>
    
    <br />
    
    <a href="display.php">Back to Home</a>

ADMIN_FORM;
  }

  public function write() {   
    if ($_POST['title']) {
      $title = $this->conn->real_escape_string($_POST['title']);
    }

    if ($_POST['bodytext']) {
      $bodytext = $this->conn->real_escape_string($_POST['bodytext']);
    }

    if ($title && $bodytext) {
      $created = time();
      $sql = "INSERT INTO posts VALUES('$title','$bodytext','$created')";
      return $this->conn->query($sql);
    } else {
      return false;
    }
  }

  

  private function setup() {
    $sql = <<<OYBLIN
      CREATE TABLE IF NOT EXISTS posts (
        title		VARCHAR(150),
        bodytext	TEXT,
        created		VARCHAR(100)
      )
OYBLIN;

    return $this->conn->query($sql);
  }

}

?>