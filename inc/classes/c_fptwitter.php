<?php
    /**
     * FanPress CM twitter connection wrapper
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2012-2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    class fpTwitter {
        
	/**
         * Step 1: Request a temporary token
         * @param object $tmhOAuth
         */
	public function request_token($tmhOAuth) {
            $url = fpSecurity::Filter5('http://' . $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']);
            $code = $tmhOAuth->request(
                'POST',
                $tmhOAuth->url('oauth/request_token', ''),
                array(
                        'oauth_callback' => $url
                )
            );

            if ($code == 200) {
                $_SESSION['oauth'] = $tmhOAuth->extract_params($tmhOAuth->response['response']);
                $this->authorize($tmhOAuth);
            } else {
                fpMessages::showErrorText('FPTWITTER: Error Code '.$code);
                die();
            }
	}


	/**
         * Step 2: Direct the user to the authorize web page
         * @param object $tmhOAuth
         */
	public function authorize($tmhOAuth) {
            $authurl = $tmhOAuth->url("oauth/authorize", '') .  "?oauth_token={$_SESSION['oauth']['oauth_token']}";
            header("Location: {$authurl}");
            // in case the redirect doesn't fire
            echo '<p><a href="'. $authurl . '">Click here to finish connection.</a></p>';
	}


	/**
         * Step 3: This is the code that runs when Twitter redirects the user to the callback. Exchange the temporary token for a permanent access token
         * @param object $tmhOAuth
         */
	public function access_token($tmhOAuth) {
            $tmhOAuth->config['user_token']  = $_SESSION['oauth']['oauth_token'];
            $tmhOAuth->config['user_secret'] = $_SESSION['oauth']['oauth_token_secret'];

            $code = $tmhOAuth->request(
                    'POST',
                    $tmhOAuth->url('oauth/access_token', ''),
                    array(
                            'oauth_verifier' => $_REQUEST['oauth_verifier']
                    )
            );

            if ($code == 200) {
                    $url = fpSecurity::Filter5('http://' . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
                    
                    $_SESSION['access_token'] = $tmhOAuth->extract_params($tmhOAuth->response['response']);
                    unset($_SESSION['oauth']);
                    header('Location: ' . $url);
            } else {
                fpMessages::showErrorText('FPTWITTER: Error Code '.$code);
                die();
            }
        }
	
        /**
         * Step 4: Now the user has authenticated, do something with the permanent token and secret we received
         * @param object $tmhOAuth
         */
	public function verify_credentials($tmhOAuth) {
            $fpDB = new fpDB();
            
            $tmhOAuth->config['user_token']  = $_SESSION['access_token']['oauth_token'];
            $tmhOAuth->config['user_secret'] = $_SESSION['access_token']['oauth_token_secret'];

            $code = $tmhOAuth->request(
                    'GET',
                    $tmhOAuth->url('1.1/account/verify_credentials')
            );

            if ($code == 200) {
                $resp = json_decode($tmhOAuth->response['response']);

                // Access Token
                $sql = "UPDATE ".FP_PREFIX."_config
                        SET config_value = '".$_SESSION['access_token']['oauth_token']."'
                        WHERE config_name LIKE 'twitter_access_token';";
                $fpDB->exec($sql);

                // Access Token Secret
                $sql = "UPDATE ".FP_PREFIX."_config
                        SET config_value = '".$_SESSION['access_token']['oauth_token_secret']."'
                        WHERE config_name LIKE 'twitter_access_token_secret';";
                $fpDB->exec($sql);
            } else {
                fpMessages::showErrorText('FPTWITTER: Error Code '.$code);
                die();
            }
            
            fpMessages::writeToSysLog("Twitter connection enabled");
	}    
        
        public function disconnect() {
            $fpDB = new fpDB();
            
            // Access Token
            $sql = "UPDATE ".FP_PREFIX."_config
                    SET config_value = ''
                    WHERE config_name LIKE 'twitter_access_token';";
            $fpDB->exec($sql);

            // Access Token Secret
            $sql = "UPDATE ".FP_PREFIX."_config
                    SET config_value = ''
                    WHERE config_name LIKE 'twitter_access_token_secret';";
            $fpDB->exec($sql); 
            
            fpMessages::writeToSysLog("Twitter connection disabled");
        }
    }
?>
