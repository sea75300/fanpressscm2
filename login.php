<?php
    /**
     * FanPress CM Login
     * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
     * @copyright 2011-2014
     *
     */
    session_start();
    
    $loginAttempts = 0;
    $timeOut       = 0;
    
    if(isset($_SESSION["loginAttempts"])) { $loginAttempts = (int) $_SESSION["loginAttempts"]; }
    if($loginAttempts >= 5) {
        if(isset($_SESSION["loginTime"])) { 
            $timeOut = time() - $_SESSION["loginTime"];
            if($timeOut >= session_cache_expire()) {                
                $_SESSION["loginAttempts"] = $loginAttempts = 0;
                unset($_SESSION["loginTime"]);
            }
        }        
    }

    define('NO_HEADER','');
    require_once dirname(__FILE__).'/inc/acpcommon.php';

    if(isset($_POST["sbmpasswdreset"])){
         if($_POST["loginusrname"] != "") {                 
               $fpUser->resetPassword(fpSecurity::fpfilter(array('filterstring' => $_POST["loginusrname"]), array(1,2,4,7)));
         }
    }

    $loginfalse = false;    
    
    if (!LOGGED_IN_USER) {        
        if (isset($_POST["sbmlogin"])) {            
            $dataArray = array(
                "loginusrname" => fpSecurity::fpfilter(array('filterstring' => $_POST["loginusrname"]), array(1,2,4,7)),
                "loginpasswd" => fpSecurity::fpfilter(array('filterstring' => $_POST["loginpasswd"]), array(1,2,4,7))
            );

            if((int) $_POST['b64'] == 1) {
                $dataArray['loginpasswd'] = explode('($$)', base64_decode($dataArray['loginpasswd']));
                $dataArray['loginpasswd'] = base64_decode($dataArray['loginpasswd'][1]);
            }
            
            fpModuleEventsAcp::runOnACPLogin($dataArray);

            if ($fpSystem->UsrLogin($dataArray)) {
                session_destroy();
                header("location: acp/index.php");
            } else {
                if($loginAttempts < 5) {
                    $loginAttempts++;
                    $_SESSION["loginAttempts"] = $loginAttempts;
                    $_SESSION["loginTime"] = time();                       
                    $loginfalse = true;
                }
            }
        }
    } else {
        session_destroy();
        header("location: acp/index.php");
    }

    include FPBASEDIR.'/sysstyle/styleinit.php';
?>
    <body id="body"> 
        
        <div class="fp-login-form">
            <div class="editorbtn ui-widget-content ui-corner-all ui-state-normal">
                <div class="fp-login-logo">                    
                    <div class="fp-logo-img"><img class="fp-logo" src="<?php print FP_ROOT_DIR ?>sysstyle/syslogo.png" alt="FanPress CM"></div>
                    <div class="fp-logo-text"><span>FanPress CM</span> <span>News System</span></div>
                    <div class="clear"></div>                                        
                </div>
            
            <?php
                if($loginfalse) { fpMessages::showErrorText(LANG_ERROR_LOGIN_DATA_FALSE); }
                if(!LOGGED_IN_USER && isset($_GET['nologin'])) { fpMessages::showNoAccess(LANG_ERROR_NOACCESS); }
                if($loginAttempts >= 5) {
                   
                    if($loginAttempts == 5 && isset($_POST["loginusrname"])) {
                        $loginAttempts++;
                        $LockedMailText = fpLanguage::replaceTextPlaceholder(
                                LANG_LOGIN_LOCKED_TXT,
                                array(
                                    "%lockeduser%" => fpSecurity::Filter2($_POST["loginusrname"]),
                                    "%lockedtime%" => date(fpConfig::get('timedate_mask'), time()),
                                    "%lockedip%"   => $_SERVER["REMOTE_ADDR"]
                                )
                        );
                        fpMessages::sendEMail(array(
                                    "mailSubject" => LANG_LOGIN_LOCKED_SUBJECT,
                                    "mailText"    => $LockedMailText
                        ));   
                        fpMessages::writeToSysLog("max login attemps for user ".fpSecurity::Filter2($_POST["loginusrname"])." from ip ".$_SERVER["REMOTE_ADDR"]);
                    }
                    
                    fpMessages::showErrorText(
                        fpLanguage::replaceTextPlaceholder(LANG_ERROR_LOGIN_ATTEMPTS, array("%timeout%" => (session_cache_expire() / 60)), false)
                    );                
                }
            ?>            
            
            <?php if($loginAttempts < 5) : ?>
            
            <div>
                <form method="post" action="login.php">
                    <table class="fp-login-form-table">
                    <?php if(isset($_GET["sbmnewpasswd"])) : ?>
                        <tr>
                            <td class="login-label"><label><?php fpLanguage::printLanguageConstant(LANG_USR_SYSUSR); ?>:</label></td>
                            <td class="login-input"><input type="text" name="loginusrname" size="50" maxlength="255" value=""></td>
                        </tr>
                        <tr>
                            <td class="fp-login-buttons" colspan="2">
                                <button type="submit" class="fp-ui-button btnloader" name="sbmpasswdreset"><?php fpLanguage::printOk(); ?></button>
                                <a href="login.php" class="fp-ui-button btnloader"><?php fpLanguage::printBack(); ?></a>
                            </td>
                        </tr>                    
                <?php else : ?>                    
                        <tr>
                            <td class="login-label"><label><?php print fpLanguage::printLanguageConstant(LANG_USR_SYSUSR); ?>:</label></td>
                            <td class="login-input"><input type="text" name="loginusrname" size="50" maxlength="255"></td>
                        </tr>
                        <tr>
                            <td class="login-label"><label><?php print fpLanguage::printLanguageConstant(LANG_USR_PASSWD); ?>:</label></td>
                            <td class="login-input"><input type="password" id="loginpasswd" name="loginpasswd" size="50" maxlength="255"></td>
                        </tr>
                        <tr>
                            <td class="fp-login-buttons" colspan="2">
                                <button type="submit" class="fp-ui-button btnloader" id="sbmlogin" name="sbmlogin"><?php print fpLanguage::printLanguageConstant(LANG_LOGIN); ?></button>
                                <a href="?sbmnewpasswd" class="fp-ui-button btnloader"><?php print fpLanguage::printLanguageConstant(LANG_NEWSPASSWD); ?></a>
                            </td>
                        </tr>
                    <?php endif; ?>                    
                    </table>
                                        
                    <input type="hidden" id="b64" name="b64" value="0">
                </form> 
            </div>

            <?php endif; ?>
        </div>                
            
<?php include FPBASEDIR."/sysstyle/sysfooter.php"; ?>