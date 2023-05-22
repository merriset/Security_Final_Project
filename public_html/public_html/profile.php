<?php
// Start the session
session_start();

// Check if user is not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  // Redirect to the login page
  header('Location: login.html');
  exit;
}
?>

<!-- Protected page content here -->
<h1>Welcome, <?php echo $_SESSION['userName']; ?></h1>

<p>List of your art</p>

<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    include('connectionData.txt');

    $conn = mysqli_connect($server, $user, $pass, $dbname, $port)
    or die('Error connecting to MySQL server.');

    $clientID = $_SESSION['clientID'];
    $sql = "select a.artName from Art a where a.clientID = '" . $clientID . "'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

    if (!$result) {
      trigger_error('couldnt acccess art', E_USER_ERROR);
    }  
    print "<pre>";
    while($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
      print "\n";
      print "$row[artName]";
    }
    print "</pre>";
    mysqli_free_result($result);
    mysqli_close($conn);
?>


<p>upload art here(TODO)</p>
