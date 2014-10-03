<?php

 
// Facebook PHP SDK v4.0.8
 

require_once( 'Facebook/HttpClients/FacebookHttpable.php' );
require_once( 'Facebook/HttpClients/FacebookCurl.php' );
require_once( 'Facebook/HttpClients/FacebookCurlHttpClient.php' );
 
require_once( 'Facebook/Entities/AccessToken.php' );
require_once( 'Facebook/Entities/SignedRequest.php' );
 

require_once( 'Facebook/FacebookSession.php' );
require_once( 'Facebook/FacebookRedirectLoginHelper.php' );
require_once( 'Facebook/FacebookRequest.php' );
require_once( 'Facebook/FacebookResponse.php' );
require_once( 'Facebook/FacebookSDKException.php' );
require_once( 'Facebook/FacebookRequestException.php' );
require_once( 'Facebook/FacebookOtherException.php' );
require_once( 'Facebook/FacebookAuthorizationException.php' );
require_once( 'Facebook/GraphObject.php' );
require_once( 'Facebook/GraphSessionInfo.php' );
 
// path of these files have changes
use Facebook\HttpClients\FacebookHttpable;
use Facebook\HttpClients\FacebookCurl;
use Facebook\HttpClients\FacebookCurlHttpClient;
 
use Facebook\Entities\AccessToken;
use Facebook\Entities\SignedRequest;
 
// other files remain the same
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookOtherException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphSessionInfo;

//FacebookSession::setDefaultApplication('759419547432664','6bbece5ca29a88e95d27ac841568ce2d');



// start session
session_start();
 
// init app with app id and secret
FacebookSession::setDefaultApplication( '759419547432664','6bbece5ca29a88e95d27ac841568ce2d' );
 
// login helper with redirect_uri
$helper = new FacebookRedirectLoginHelper( 'http://localhost/fblogin/fb_login.php');
 
// see if a existing session exists
if ( isset( $_SESSION ) && isset( $_SESSION['fb_token'] ) ) {
  // create new session from saved access_token
  $session = new FacebookSession( $_SESSION['fb_token'] );
  
  // validate the access_token to make sure it's still valid
  try {
    if ( !$session->validate() ) {
      $session = null;
    }
  } catch ( Exception $e ) {
    // catch any exceptions
    $session = null;
  }
}  
 
if ( !isset( $session ) || $session === null ) {
  // no session exists
  
  try {
    $session = $helper->getSessionFromRedirect();
  } catch( FacebookRequestException $ex ) {
    // When Facebook returns an error
    // handle this better in production code
    print_r( $ex );
  } catch( Exception $ex ) {
    // When validation fails or other local issues
    // handle this better in production code
    print_r( $ex );
  }
  
}
 
// see if we have a session
if ( isset( $session ) ) {
  
  // save the session
  $_SESSION['fb_token'] = $session->getToken();
  // create a session using saved token or the new one we generated at login
  $session = new FacebookSession( $session->getToken() );
  
  // into this:
$me = (new FacebookRequest( $session, 'GET', '/me' ))->execute()->getGraphObject()->asArray();
  
 

  // print profile data
 echo '<pre>' . print_r( $me, 1 ) . '</pre>';
//print_r($me);


 //getting some information and store it in a session variable

$_SESSION['name'] = $me['name'];
$_SESSION['email'] = $me['email'];
$_SESSION['firstname'] = $me['first_name'];
$_SESSION['lastname'] = $me['last_name'];

//and so on


  // print logout url using session and redirect_uri (logout.php page should destroy the session)
  echo '<a href="' . $helper->getLogoutUrl( $session, 'http://localhost/fblogin/logout.php' ) . '">Logout</a>';
  
} else {
  // show login url
  echo '<a target="_blank" href="' . $helper->getLoginUrl( array( 'email', 'user_friends' ) ) . '">Login with Facebook</a>';
}



?>


</body>
</html>