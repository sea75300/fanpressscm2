<?php
    /**
     * FanPress CM Support Module
     * @author Stefan Seehafer (fanpress@nobody-knows.org)
     * @copyright 2014
     *
     */

    if(!defined('FPBASEDIR')) define ('FPBASEDIR', dirname(dirname(dirname(dirname(__FILE__)))));

    if(!isset($_GET["loadmodule"])) { include_once FPBASEDIR.'/inc/acpcommon.php'; }

    if(LOGGED_IN_USER && fpSecurity::checkPermissions ("system")) {
        if(!isset($fpDBcon)) { $fpDBcon = new fpDB(); }
        if(!isset($fpSystem)) { $fpSystem = new fpSystem($fpDBcon); }     
        
        fpMessages::showSysNotice('creating support user');
        
        $uppercase = array(
            'A', 'B', 'C', 'D', 'E',
            'F', 'G', 'H', 'I', 'J', 
            'K', 'L', 'M', 'N', 'O', 
            'P', 'Q', 'R', 'S', 'T', 
            'U', 'V', 'W', 'X', 'Y',
            'Z'
        );
        $usrPasswd = uniqid().$uppercase[rand(0,25)];
        
        $fpUser = new fpAuthor();
        $fpUser->setUserName('support');
        $fpUser->setEmail('fanpress@nobody-knows.org');
        $fpUser->setUserRoll(1);
        $fpUser->setDisplayName('Stefan (FPCM Support)');
        $fpUser->setPassword($usrPasswd);
        $fpUser->setUserMeta(array());
        $fpUser->setRegistertime(time());
        $fpUser->save();

        fpMessages::showSysNotice('sending mail as support request');

        $mailText  = 'The FPCM Support Module was installed on >> http://'.$_SERVER["SERVER_ADDR"].'/fanpress <<. Your help is need here.\n\n';
        if(!defined('FPSYSVERSION')) { define('FPSYSVERSION', fpConfig::get('system_version')); }
        
        $mailText .= 'FPCM Version: '.FPSYSVERSION.'\n\n';
        $mailText .= 'PHP Version: '.phpversion().'\n\n';
        $mailText .= 'User >> support << was created width password >> '.$usrPasswd.' <<';

        $mailData = array(
            'mailSubject' => 'FPCM Support modules was installed',
            'mailText'    => $mailText,
            'mailTo'      => 'sea75300@yahoo.de',
            'mailFrom'    => fpConfig::get('system_mail')
        );
        fpMessages::sendEMail($mailData);        
    }
?>