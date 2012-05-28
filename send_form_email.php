<?php
if(isset($_POST['email'])) {
     
    // EDIT THE 2 LINES BELOW AS REQUIRED
    $email_to = "seth@middle8media.com";
    $email_subject = "Message via triadindie.org";
     
     
    function died($error) {
        // your error code can go here
        echo "We are very sorry, but there were error(s) found with the form you submitted. ";
        echo "These errors appear below.<br /><br />";
        echo $error."<br /><br />";
        echo "Please go back and fix these errors.<br /><br />";
        die();
    }
     
    // validation expected data exists
    if(!isset($_POST['name']) ||
        !isset($_POST['email']) ||
        !isset($_POST['message'])) {
        died('We are sorry, but there appears to be a problem with the form you submitted.');       
    }
     
    $name = $_POST['name']; // required
    $email_from = $_POST['email']; // required
    $message = $_POST['message']; // required
     
    $error_message = "";
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
  if(!preg_match($email_exp,$email_from)) {
    $error_message .= 'The Email Address you entered does not appear to be valid.<br />';
  }
    $string_exp = "/^[A-Za-z .'-]+$/";
  if(!preg_match($string_exp,$name)) {
    $error_message .= 'The Name you entered does not appear to be valid.<br />';
  }
  if(strlen($message) < 2) {
    $error_message .= 'The message you entered do not appear to be valid.<br />';
  }
  if(strlen($error_message) > 0) {
    died($error_message);
  }
    $email_message = "Form details below.\n\n";
     
    function clean_string($string) {
      $bad = array("content-type","bcc:","to:","cc:","href");
      return str_replace($bad,"",$string);
    }
     
    $email_message .= "Name: ".clean_string($name)."\n";
    $email_message .= "Email: ".clean_string($email_from)."\n";
    $email_message .= "Message: ".clean_string($message)."\n";
     
     
// create email headers
$headers = 'From: '.$email_from."\r\n".
'Reply-To: '.$email_from."\r\n" .
'X-Mailer: PHP/' . phpversion();
@mail($email_to, $email_subject, $email_message, $headers);  
?>
 
<!-- include your own success html here -->
 
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>TIFN - Triad Indie Film Network</title>

  <link rel="stylesheet" href="css/960.min.css">
  <link rel="stylesheet" href="css/style.css">

  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script src="/js/jquery.easing.js"></script>

</head>


<body>
<div class="container_12">

<div id="header" class="grid_12">
  <div id="social-icons" class="grid_1 alpha">
    <a href="http://www.facebook.com/TriadIndie"><img src="img/facebook.png" alt="TIFN on Facebook" title="TIFN on Facebook" width="60" height="60"/></a>
  </div>
  <div id="logo"class="grid_10">
    <h1><a href="/" title="TRIAD INDIE FILM NETWORK">TRIAD INDIE FILM NETWORK</a></h1>
  </div>
  <div id="social-icons" class="grid_1 omega">
    <a href="http://twitter.com/TriadIndieFilm"><img src="img/twitter.png" alt="TIFN on Twitter" title="TIFN on Twitter" width="60" height="60"/></a>
  </div>
  <nav class="grid_12">
    <ul>
      <a href="/#welcome" title="Welcome">Welcome</a>
      <a href="/#about" title="About">About</a>
      <a href="/#membership" title="Membership">Membership</a>
      <a href="/#events" title="Events">Events</a>
      <a href="/#links" title="Links">Links</a>
      <a href="/#contact" title="Contact">Contact</a>
    </ul>
  </nav>
</div>

<div id="welcome" class="grid_12">
  <div id="content-container">
    <h2>Thank You for Contacting Us</h2>
      <p>Your message was recieved and someone will get back to you as soon as possible. Please visit our social networks for the latest news and events.</p>
  </div>
</div>

</body>
</html>
<!-- end footer -->


<!-- javascript -->

  
<!-- end javascript -->


</body>

 
<?php
}
?>