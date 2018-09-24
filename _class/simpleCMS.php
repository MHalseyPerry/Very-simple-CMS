<?php

class SimpleCMS {
  public function __construct(){
    $this->conn = new mysqli("localhost", "root", "","test"); // create conn variable to be used whenever connecting to database
    if ($this->conn->connect_errno) {
      die("Failed to connect to MySQL: (" . $this->conn->connect_errno . ") " . $this->conn->connect_error); // error handling (database connection errror)
    }
    $this->setup(); // call setup function
  }

  public function displayPublic() {
    $q = "SELECT * FROM posts ORDER BY created DESC"; // $q holds the query to select all columns in database and order descending
    $r = $this->conn->query($q); // $r holds the results of query $q 

    if ($r && $r->num_rows > 0) { // if table has contents
      $html = '<table border="1">';
      while ($row = $r->fetch_assoc()) { // $row stores data from table as an assoc array
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

  

  private function setup() {  //setup function sets up sql query to create table in database
    $sql = <<<SQL_QUERY
      CREATE TABLE IF NOT EXISTS posts ( 
        title		VARCHAR(150),
        bodytext	TEXT,
        created		VARCHAR(100)
      )
SQL_QUERY;

    return $this->conn->query($sql); //connect to database and pass query
  }

}

?>