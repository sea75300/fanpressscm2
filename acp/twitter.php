<?php
 /**
   * Twitetr Verbindung einrichten
   * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
   * @copyright 2011-2014
   *
   */
    define('NO_HEADER','');
    require_once dirname(dirname(__FILE__)).'/inc/acpcommon.php';

    if(LOGGED_IN_USER && fpSecurity::checkPermissions ("system")) {

        $tmhOAuth = new tmhOAuth(array(
            'consumer_key'    => fpConfig::get('twitter_consumer_key'),
            'consumer_secret' => fpConfig::get('twitter_consumer_secret')
        ));
        
        $fpTwitter = new fpTwitter();
        
        session_start();
        
        if (isset($_GET['start'])) {
                $fpTwitter->request_token($tmhOAuth);
        } elseif (isset($_REQUEST['oauth_verifier'])) {
                $fpTwitter->access_token($tmhOAuth);
                $fpTwitter->verify_credentials($tmhOAuth);
                
                fpMessages::showSysNotice(LANG_WRITE_TWITTER_AUT_MSG);
        } elseif (isset($_REQUEST['disconnect'])) {
                $fpTwitter->disconnect();
                fpMessages::showSysNotice(LANG_WRITE_TWITTER_DISC_MSG);
        }
        
    } else {
            if(!fpSecurity::checkPermissions("system")) { fpMessages::showNoAccess(); }
            if(!LOGGED_IN_USER) { fpMessages::showNoAccess(LANG_ERROR_NOACCESS); }
    }  
?>