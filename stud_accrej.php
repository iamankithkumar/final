<!-- 3002 -->
<?php

require_once "config.php";

$id = $_GET['id'];
$status = $_GET['status'];

if ($status == 'ACCEPT') {
  $rollno = $_GET['rollno'];
  $sector = $_GET['sector'];
  $title = $_GET['title'];
  $location = $_GET['location'];
  $salary = $_GET['salary'];
  $name = $_GET['name'];
  // Update sidestudenttable with sector and title
  $sql = "UPDATE sidestudenttable SET sector = '$sector', recent_job = '$title',placement_status='$name', recent_salary = '$salary', location = '$location' WHERE rollno = '$rollno'";
    echo $sql;
  $result = mysqli_query($conn, $sql);

  if ($result) {
    echo "Entry updated successfully.";
  } else {
    echo "Error updating entry: " . mysqli_error($conn);
  }

  $sql = "SELECT * FROM studentjobs WHERE sjroll = '$rollno'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
      // Rollno already exists, update studentjobs table
      $sql = "UPDATE studentjobs SET sjcompname = '$name', sjsector = '$sector', sjtitle = '$title', sjsalary = '$salary', sjloc = '$location' WHERE sjroll = '$rollno'";
      mysqli_query($conn, $sql);
  } else {
      // Rollno does not exist, insert into studentjobs table
      $sql = "SELECT * FROM mainstudenttable WHERE rollno = '$rollno'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) > 0) {
          $row = mysqli_fetch_assoc($result);
          $stuname = $row['name'];
          $branch = $row['branch'];
          $yop = $row['yop'];

          $sql = "INSERT INTO studentjobs (sjcompname, sjsector, sjtitle, sjsalary, sjloc, sjstuname, sjbranch, sjroll, yop) VALUES ('$name', '$sector', '$title', '$salary', '$location', '$stuname', '$branch', '$rollno', '$yop')";
          mysqli_query($conn, $sql);
          echo $sql;
      }
  }


   $sql = "DELETE FROM studentapply WHERE id = '$id' and rollno='$rollno' and title='$title' and sector = '$sector'";
 $result = mysqli_query($conn, $sql);

$_SESSION['jobaccstu']='f';

   header("Location: http://localhost:3000/");
} elseif ($status == 'REJECT') {
  // Delete entry from studentapply table
  $sql = "DELETE FROM studentapply WHERE id = '$id' and rollno='$rollno' and title='$title' and sector = '$sector'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    echo "Entry deleted successfully.";
  } else {
    echo "Error deleting entry: " . mysqli_error($conn);
  }
}

mysqli_close($conn);

?>
