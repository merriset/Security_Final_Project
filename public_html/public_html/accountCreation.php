<html>
<head>
       <title>Art Broker</title>
</head>

<body bgcolor="white">
<style>
  form {
    display: flex;
    flex-direction: column;
    max-width: 300px; /* Adjust the maximum width as needed */
    margin-right: auto;
  }

  label {
    width: 120px; /* Adjust the width as needed */
    margin-right: 10px; /* Adjust the margin as needed */
  }

  input[type="text"] {
    flex: 1;
  }

  input[type="submit"],
  input[type="reset"] {
    margin-top: 5px; /* Adjust the margin as needed */
  }

</style>

<h3>Connecting to ArtBroker database using MySQL/PHP</h3>
<hr>

<p>
website that uses differen't encryption techniques to buy and sell art
<p>


<!-- Menu for adding clients-->

<h1> Create Account</h1>
<form action="" method="POST">
  <label for="fname">First Name:</label>
  <input type="text" name="item1[fname]" id="fname"><br>
  <label for="lname">Last Name:</label>
  <input type="text" name="item1[lname]" id="lname"><br>
  <label for="uname">User Name:</label>
  <input type="text" name="item1[uname]" id="uname"><br>
  <label for="pkey">Public Key:</label>
  <input type="text" name="item1[pkey]" id="pkey"><br>
  <label for="pass">Password:</label>
  <input type="text" name="item1[pass]" id="pass"><br>
  <input type="submit" value="Submit">
  <input type="reset" value="Reset">
</form>

<a href="signIn.php" >Need to sign in? click here</a>


</body>
</html>



<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('connectionData.txt');

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');

if (isset($_POST['item1'])) {
  // For adding clients 
  if (isset($_POST['item1']['fname']) && isset($_POST['item1']['lname']) && isset($_POST['item1']['uname']) && isset($_POST['item1']['pkey']) && isset($_POST['item1']['pass']) && $_POST['item1']['fname'] !== '' && $_POST['item1']['lname'] !== '' && $_POST['item1']['uname'] !== '' && $_POST['item1']['pkey'] !== '' && $_POST['item1']['pass'] !== '') {
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
  $sql = "select username from Clients where username = '" . $uname . "'";
  $result = $conn->query($sql);

  if ($result->num_rows !== 0) {
    echo "Username already exists";
    return;
  }
  $result->close();
  $sql = "SELECT COALESCE(MIN(clientID) + 1, 1) AS new_key FROM (SELECT 0 AS clientID UNION ALL SELECT clientID FROM Clients) t1 WHERE NOT EXISTS (SELECT 1 FROM Clients t2 WHERE t2.clientID = t1.clientID + 1)";
  $result = $conn->query($sql);

  if (!$result) {
    echo "Error: " . $sql . "<br>" . $conn->error;
  } else {
    $row = $result->fetch_assoc();
    $new_key = $row['new_key'];
  }

  $sql = "INSERT INTO Clients (clientID, fname, lname, userName, pass, publickey) VALUES ('$new_key', '$fname', '$lname', '$uname', '$pass', '$pkey')";
  $result = $conn->query($sql);

  if (!$result) {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
  $result->close();

  session_start();

  // Set session variables
  $_SESSION['loggedin'] = true;
  $_SESSION['clientID'] = $new_key;
  $_SESSION['userName'] = $uname;


  // Redirect to the protected page
  header('Location: profile.php');
  
  
}

mysqli_close($conn);
?>
