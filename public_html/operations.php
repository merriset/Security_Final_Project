<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('connectionData.txt');

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');



if (isset($_POST['item1'])) {

// For adding clients 
  if(   isset($_POST['item1']['fname']) 
    &&  isset($_POST['item1']['lname']) 
    &&  isset($_POST['item1']['uname']) 
    &&  isset($_POST['item1']['pkey']) 
    &&  isset($_POST['item1']['pass']) 
    && $_POST['item1']['fname'] !== '' 
    && $_POST['item1']['lname'] !== ''
    && $_POST['item1']['uname'] !== ''
    && $_POST['item1']['pkey'] !== ''
    && $_POST['item1']['pass'] !== ''
    ) {
    // Both first name and last name are set and not empty
    $fname = $_POST['item1']['fname'];
    $lname = $_POST['item1']['lname'];
    $uname = $_POST['item1']['uname'];
    $pkey = $_POST['item1']['pkey'];
    $pass = $_POST['item1']['pass'];

  } else {
    // Either first name or last name is not set or empty
    trigger_error('All entries must be filled', E_USER_ERROR);
  }
 $sql = "SELECT COALESCE(MIN(clientID) + 1, 1) AS new_key
  FROM (
      SELECT 0 AS clientID
      UNION ALL
      SELECT clientID FROM Clients
  ) t1
  WHERE NOT EXISTS (
      SELECT 1 FROM Clients t2 WHERE t2.clientID = t1.clientID + 1
  )";
    $result = $conn->query($sql);
  if (!$result) {
    echo "Error: " . $sql . "<br>" . $conn->error;
  } else {
    $row = $result->fetch_assoc();
    $new_key = $row['new_key'];
  }
  $sql = "INSERT INTO Clients (clientID, fname, lname, userName, pass, publickey)
  VALUES ('$new_key', '$fname', '$lname', '$uname', '$pass', '$pkey')";
  $result = $conn->query($sql);
  if (!$result) {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
  print "<pre>";
  print("\n$fname $lname successfully joined!");
  print "</pre>";


// For removing Clients
} else if (isset($_POST['item2'])) {
  $ID = $_POST['item2'];

  $sql = "delete from Ownership where clientID = $ID";
  $result = $conn->query($sql);
  if (!$result) {
      trigger_error('ERROR deleting from Clients', E_USER_ERROR);
  }

  $sql = "delete from Art where clientID = $ID";
  $result = $conn->query($sql);
  if (!$result) {
      trigger_error('ERROR deleting from Clients', E_USER_ERROR);
  }

  $sql = "delete from Clients where clientID = $ID";
  $result = $conn->query($sql);
  if (!$result) {
      trigger_error('ERROR deleting from Clients', E_USER_ERROR);
  }

  print("client removed");


} else if (isset($_POST['item3'])) {

  if (isset($_POST['item3']['hashArtID']) 
  && isset($_POST['item3']['clientID']) 
  && isset($_POST['item3']['artName']) 
  && isset($_POST['item3']['wmKey']) 
  && isset($_POST['item3']['restoreKey']) 
  && $_POST['item3']['hashArtID'] !== '' 
  && $_POST['item3']['clientID'] !== ''
  && $_POST['item3']['artName'] !== ''
  && $_POST['item3']['wmKey'] !== '' 
  && $_POST['item3']['restoreKey'] !== '') 
  {
    $hashArtID = $_POST['item3']['hashArtID'];
    $clientID = $_POST['item3']['clientID'];
    $artName = $_POST['item3']['artName'];
    $wmKey = $_POST['item3']['wmKey'];
    $restoreKey = $_POST['item3']['restoreKey'];
  } else {
    echo "All fields must be filled in.";
  }

    $sql = "INSERT INTO Art (hashArtID, clientID, artName, wmKey, restoreKey)
    VALUES ('$hashArtID', '$clientID', '$artName', '$wmKey', '$restoreKey')";
    $result = $conn->query($sql);  
    if (!$result) {
        trigger_error('could not insert into Art', E_USER_ERROR);
    }
    $sql = "INSERT INTO Ownership (hashArtID, clientID)
    VALUES ('$hashArtID', '$clientID')";
    $result = $conn->query($sql); 
    if (!$result) {
      trigger_error('could not insert into Ownership', E_USER_ERROR);
    } 
    print("Art Added");
  }


  else if (isset($_POST['item4'])) {
    if 
      (isset($_POST['item4']['hashArtID']) 
    && isset($_POST['item4']['clientID']) 
    && $_POST['item4']['hashArtID'] !== '' 
    && $_POST['item4']['clientID'] !== '') 
    {
      $hashArtID = $_POST['item4']['hashArtID'];
      $clientID = $_POST['item4']['clientID'];
    }
    else {
      echo "All fields must be filled in.";
      return;
    }
    $sql = "UPDATE Ownership
              SET clientID = '$clientID'
              WHERE hashArtID = '$hashArtID'";
    $result = $conn->query($sql); 
    if (!$result) {
      trigger_error('could not insert into Ownership', E_USER_ERROR);
      return;
    }
    print("ownership changed");
  }

  else if (isset($_POST['item5'])) {
    print("hello");

    if (isset($_POST['item5']['clientID'])
    &&  $_POST['item5'] !== "")
    {
      $clientID = $_POST['item5']['clientID'];
    }
    else {
      echo "All fields must be filled in.";
      return;
    }
    $sql = "select a.artName from Art a where a.clientID = '" . $clientID . "'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

    if (!$result) {
      trigger_error('couldnt acccess art', E_USER_ERROR);
    }  
    print "<pre>";
    print("\n list of art");
    while($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
      print "\n";
      print "$row[artName]";
    }
    print "</pre>";
    mysqli_free_result($result);

  }






mysqli_close($conn);
?>

<p>
<hr>

<p>
<a href="operations.txt" >Contents</a>
of the PHP program that created this page. 	 
 
</body>
</html>
	  
