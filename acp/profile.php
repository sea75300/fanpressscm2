<?php
    /**
     * FanPress CM Benutzer-Profil
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    require_once dirname(dirname(__FILE__)).'/inc/acpcommon.php';

    if(LOGGED_IN_USER) :
?>
<h1><?php fpLanguage::printLanguageConstant(LANG_SYSCFG_PROFILE); ?></h1>
<div class="box box-fixed-margin" id="contentbox">
<?php
    $fpAuthor   = new fpAuthor(fpConfig::currentUser('id'));
    
    $inprofile  = true;
    $iseditmode = true;

    if(isset($_POST["sbmeusr"])) {       
        $fpAuthor->setDisplayName(fpSecurity::fpfilter(array('filterstring' => $_POST["name"]), array(1,4,2)));
        $fpAuthor->setEmail(fpSecurity::fpfilter(array('filterstring' => $_POST["email"]), array(1,4,2)));

        $newpass         = fpSecurity::fpfilter(array('filterstring' => $_POST["password"]), array(1,4,2));
        $newpass_confirm = fpSecurity::fpfilter(array('filterstring' => $_POST["password_confirm"]), array(1,4,2));        
        
        if($newpass && $newpass_confirm) {
            if(md5($newpass) == md5($newpass_confirm)) {
                $fpAuthor->setPassword($newpass);
            } else {
                $fpAuthor->internal = true;
                fpMessages::showErrorText(LANG_USR_PASS_CONFIRM_FAILED);
            }                            
        } else {
            $fpAuthor->internal = true;
        }
        $userMeta = array_map('fpSecurity::Filter2', $_POST["usrmeta"]);
        $fpAuthor->setUserMeta($userMeta);

        $success = $fpAuthor->update();

        switch ($success) {
            case true :
                fpMessages::showSysNotice(LANG_PROFILE_EDITMSG);
            break;                    
            case -2 :
                fpMessages::showErrorText(LANG_USR_PASSSEC);
            break;
        }       
    }
    
    include "userrollmgr/usreditor.php";  
?>
</div>    
<?php
    else :
        fpMessages::showNoAccess(LANG_ERROR_NOACCESS);
    endif;
?>
<?php include FPBASEDIR."/sysstyle/sysfooter.php"; ?>