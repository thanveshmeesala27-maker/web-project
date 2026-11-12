<?php
session_start();
$con=mysqli_connect("localhost","root","","myhmsdb");
require_once __DIR__ . '/auth_utils.php';
if(isset($_POST['adsub'])){
    $username=hms_escape($_POST['username1'] ?? '');
    $password=$_POST['password2'] ?? '';
    $query="SELECT * FROM admintb WHERE username='{$username}' LIMIT 1;";
    $result=mysqli_query($con,$query);
    if($result && mysqli_num_rows($result)==1)
    {
        $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
        if(hms_verify_password($password, $row['password'])){
          $_SESSION['username']=$username;
          header("Location:admin-panel1.php");
          exit;
        }
    }
    echo("<script>alert('Invalid Username or Password. Try Again!');
          window.location.href = 'index.php';</script>");
}
if(isset($_POST['update_data']))
{
	$contact=$_POST['contact'];
	$status=$_POST['status'];
	$query="update appointmenttb set payment='$status' where contact='$contact';";
	$result=mysqli_query($con,$query);
	if($result)
		header("Location:updated.php");
}




function display_docs()
{
	global $con;
	$query="select * from doctb";
	$result=mysqli_query($con,$query);
	while($row=mysqli_fetch_array($result))
	{
		$name=$row['username'];
		# echo'<option value="" disabled selected>Select Doctor</option>';
		echo '<option value="'.$name.'">'.$name.'</option>';
	}
}

if(isset($_POST['doc_sub']))
{
	$name=$_POST['name'];
	$query="insert into doctb(name)values('$name')";
	$result=mysqli_query($con,$query);
	if($result)
		header("Location:adddoc.php");
}
