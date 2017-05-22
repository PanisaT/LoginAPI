<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="utf-8">
    <style type="text/css">
        .fb-button {
            width: 200px;
            height: 50px;
            background-color: #3b5998;
            border-radius: 5px;
            border: none;
            color: #fff;
        }
    </style>
</head>
<body>

</body>
</html>

<?php
 
    if(isset($accessToken)){
        if(isset($_SESSION['facebook_access_token'])){
            $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
        }else{
            // Put short-lived access token in session
            $_SESSION['facebook_access_token'] = (string) $accessToken;
            
              // OAuth 2.0 client handler helps to manage access tokens
            $oAuth2Client = $fb->getOAuth2Client();
            
            // Exchanges a short-lived access token for a long-lived one
            $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
            $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
            
            // Set default access token to be used in script
            $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
        }
        
        // Redirect the user back to the same page if url has "code" parameter in query string
        if(isset($_GET['code'])){
            header('Location: ./');
        }
        
        // Getting user facebook profile info
        try {
            $profileRequest = $fb->get('/me?fields=name,first_name,last_name,email,link,gender,locale,picture');
            $fbUserProfile = $profileRequest->getGraphNode()->asArray();
        } catch(FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            session_destroy();
            header("Location: ./");
            exit;
        } catch(FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

    // ข้อมูลมาแล้วมำตรงนี้
    
    }else{
        $fbLoginLink = $helper->getLoginUrl($fbRedirectURL, $fbPermissions);
        echo    '<a href="'.$fbLoginLink.'">
                    <button class="fb-button">
                        Login with Facebook
                    </button>
                </a>';
    }


?>
