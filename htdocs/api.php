<?php
 session_start();
 $con = new mysqli("localhost", "root", "root", "chat"); 
 
 function require_login() {
  if(!isset($_SESSION['udata'])) {
   $redirect = "<div class='alert alert-danger' role='alert'>You are not logged in</div><script>function a() { window.location.replace('/login'); } setInterval(a, 2000);</script>";
   die($redirect); 
  }	  
 }
  
 function register($con) {
  $redirect = "<script>function a() { window.location.replace('/'); } setInterval(a, 2000);</script>";
  if(!isset($_POST['username']) || !isset($_POST['password'])) die("<div class='alert alert-danger' role='alert'>invalid register request</div>"); 
  $username = $con->real_escape_string($_POST['username']);
  $password = md5($_POST['password']);   
  $pattern = '/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/';
  if(preg_match($pattern, $_POST['username'])) die("<div class='alert alert-danger' role='alert'>Username cannot have special characters</div>");
  if(strlen($password) < 6) die("Password is too short"); 
  $usercheck = $con->query("select * from users where username = '$username'"); 
  if(isset($usercheck) && ($usercheck->num_rows > 0)) die("<div class='alert alert-danger' role='alert'>user already exists</div>");
  $con->query("insert into users(username,password) values('$username','$password')") or die("<div class='alert alert-danger' role='alert'>unable to register account</div>");
  die("<div class='alert alert-success' role='alert'>Registered successfully</div>".$redirect); 
 }
 
 function login($con) {
  $redirect = "<script>function a() { window.location.replace('/'); } setInterval(a, 2000);</script>";
  if(!isset($_POST['username']) || !isset($_POST['password'])) die("<div class='alert alert-danger' role='alert'>invalid login request</div>"); 
  $username = $con->real_escape_string($_POST['username']);
  $password = md5($_POST['password']);   
  $usercheck = $con->query("select * from users where username = '$username' and password = '$password'"); 
  if(isset($usercheck) && ($usercheck->num_rows > 0)) {
   $_SESSION['udata'] = $usercheck->fetch_object();    
  }
  die("<div class='alert alert-success' role='alert'>Logged in successfully</div>".$redirect); 
 }
 
 function retreive($con) {
  require_login();
  $messages = $con->query("select * from messages order by id limit 50"); 
  $message_full = '<ul class="list-group">';
  while($msg = $messages->fetch_object()) {
   $message_full .= "<li class='list-group-item'>".$msg->username.": ".htmlentities($msg->content)."</li>"; 
  } 	  
  $message_full .= "</div>";
  print($message_full);
 }
 
 function message($con) {
  require_login();
  $message = $con->real_escape_string($_POST['message']); 
  if(strlen($message) < 500) {
   $con->query("insert into messages(username,content) values('{$_SESSION['udata']->username}', '$message')") or die("<div class='alert alert-danger' role='alert'>Error sending message</div>");   
  } else {
   die("<div class='alert alert-danger' role='alert'>Message too long, max 500 characters</div>");
  }
 }
 
 
 if(isset($_POST['submit'])) {
  switch($_POST['submit']) {
    case 'register':
      register($con);
      break;	   
    case 'login':
	  login($con);
	  break;
	case 'message':
	  message($con);
	  break;
	case 'retreive':
	  retreive($con);
	  break;
  }
 }
 
 
?>