<?php
session_start();
require_once("twitteroauth/twitteroauth/twitteroauth.php"); //Path to twitteroauth library
 
$twitteruser = "doctorchemistrycom";
$notweets = 30;
$consumerkey = "CZN84F4WCpGkFDruIo65eAUtl";
$consumersecret = "Bf1vbZvkqFkHNTAxfOjDV4TM9Pcba3WbnxD24XJFgc4Yftscpc";
$accesstoken = "2772228284-366uvU2Vb1d1mI7qwdWxePYiOcACYsfwemRNZ6z";
$accesstokensecret = "gbQ3Pp46Z6lwAqnalYTfQZl8gKi2cIPlN9vASDnQ3cRho";
 
function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
  return $connection;
}
  
$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
 
$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$twitteruser."&count=".$notweets);
 
echo json_encode($tweets);
?>
