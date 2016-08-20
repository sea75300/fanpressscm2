<?php
 /**
   * Benutzer bearbeiten
   * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
   * @copyright 2011-2014
   *
   */

    if(!defined("LOGGED_IN_USER")) { die(); }

    if(LOGGED_IN_USER && fpSecurity::checkPermissions ("user")) {
        $idxstats = $fpSystem->getStats();
        
        $iseditmode = 0;
        
        if(isset($_GET["fn"])) {
            $method = fpSecurity::fpfilter(array('filterstring' => $_GET["fn"]), array(7));
            
            switch ($method) {
                case 'edit' :
                    $fpAuthor = new fpAuthor(fpSecurity::Filter5($_GET["userid"]));

                    if(isset($_POST["sbmeusr"])) {  
                        
                        $fpAuthor->setUserName(fpSecurity::Filter5($_POST["usrname"]));
                        $fpAuthor->setEmail(fpSecurity::Filter5($_POST["email"]));
                        $fpAuthor->setDisplayName(fpSecurity::Filter5($_POST["name"]));
                        $fpAuthor->setUserRoll(fpSecurity::Filter5($_POST["ulvl"]));
                        $fpAuthor->setUserMeta($_POST["usrmeta"]);
                        
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
                        
                        if($fpAuthor->update()) {
                            fpMessages::showSysNotice(LANG_USR_USREDITEDMSG);
                        }                        
                    }
                    print "<h2>".fpLanguage::returnLanguageConstant(LANG_USR)."</h2>\n";
                    
                    $iseditmode = 1;
                    
                    include "userrollmgr/usreditor.php";
                    print "</div>";                    
                break;
                
                case 'editroll' :
                    $userRoll   = new fpUserRoll(fpSecurity::fpfilter(array('filterstring' => $_GET['id']), array(1,4,7)));
                    
                    if(isset($_POST["sbmeditroll"])) {
                        $rollName = fpSecurity::fpfilter(array('filterstring' => $_POST['newrollname']), array(1,4,2,7));            
                        $userRoll->setLeveltitle($rollName);
                        $userRoll->update();
                    }                    
                    
                    $rollName   = $userRoll->getLeveltitle();
                    
                    $edit       = true;
                    
                    print "<h2>".fpLanguage::returnLanguageConstant(LANG_USR_ROLLS)."</h2>\n";
                    include "userrollmgr/rolleditor.php";
                    print "</div>";                     
                break;
            }
        }
        
        if(isset($_POST['sbmnewroll'])) {            
            $rollName = fpSecurity::fpfilter(array('filterstring' => $_POST['newrollname']), array(1,4,2,7));            
            $userroll = new fpUserRoll();
            $userroll->setLeveltitle($rollName);
            $userroll->save();
            
            fpMessages::showSysNotice(fpLanguage::returnLanguageConstant(LANG_USR_ROLL_CREATEDMSG));
        }
        
        if(isset($_POST['sbmdelroll'])) {
            $rollId = fpSecurity::fpfilter(array('filterstring' => $_POST['delroll']), array(1,2,7));            
            $userroll = new fpUserRoll($rollId);
            $userroll->delete();       
            
            fpMessages::showSysNotice(fpLanguage::returnLanguageConstant(LANG_USR_ROLL_DELETEDMSG));
        }
        
        if (isset($_POST["sbmnusr"])) {                  
            if($fpUser->isFormEmpty($_POST["name"],$_POST["email"],$_POST["usrname"],$_POST["password"],isset($_POST["ulvl"]))) {                
                $frmisempty = true;    
            } else {                
                $newpass         = fpSecurity::fpfilter(array('filterstring' => $_POST["password"]), array(1,4,2));
                $newpass_confirm = fpSecurity::fpfilter(array('filterstring' => $_POST["password_confirm"]), array(1,4,2));        

                $fpAuthor = new fpAuthor();
                $fpAuthor->setDisplayName(fpSecurity::Filter2($_POST["name"]));
                $fpAuthor->setEmail(fpSecurity::Filter2($_POST["email"]));
                $fpAuthor->setUserName(fpSecurity::Filter2($_POST["usrname"]));
                $fpAuthor->setUserRoll($_POST["ulvl"]);
                $fpAuthor->setRegistertime(time());
                $fpAuthor->setUserMeta(array());                
                
                if(md5($newpass) == md5($newpass_confirm)) {
                    $fpAuthor->setPassword($newpass);
                    $success = $fpAuthor->save();
                    
                    switch ($success) {
                        case true :
                            $usradded = true;
                        break;                    
                        case -1 :
                            $usrexist = false;
                        break;
                        case -2 :
                            $passsec = true;
                        break;
                    }                    
                } else {
                    $passsmatch = true;
                }
            }
        }

        if (isset($_POST["delusr"])) {
            $userId     = (int) $_POST["delusr"];
            
            $fpAuthor = new fpAuthor($userId);
            
            if (isset($_POST["sbmenausr"])) {
                $fpAuthor->enable();
                $usrenabled = true;
            } else {
                if($idxstats['authorscount'] < 2)   {
                    $lastusr = true;                
                } elseif($userId == fpConfig::currentUser('id')) {
                    $ownaccountdel = true;                
                }

                if(!isset($lastusr) && !isset($ownaccountdel)) {
                    if(isset($_POST["sbmdelusr"])) {
                        $fpAuthor->delete();
                        $usrdeleted = true;
                    }

                    if (isset($_POST["sbmdisusr"])) {
                        $fpAuthor->disable();
                        $usrdisabled = true;
                    }              
                }                
            }            
            
        }               
?>
<?php if(!isset($_GET["fn"]) && !isset($_GET["susr"]) && !isset($_GET["id"])) : ?>
    <h2><?php fpLanguage::printLanguageConstant(LANG_USR); ?></h2>
    <?php
        if (isset($usradded))   { fpMessages::showSysNotice(LANG_USR_USRADDEDMSG); } 
        if (isset($usrexist))   { fpMessages::showErrorText(LANG_USR_USREXISTMSG); } 
        if (isset($usrdeleted)) { fpMessages::showSysNotice(LANG_USR_USRDELETEDMSG); }
        if (isset($usrdisabled)) { fpMessages::showSysNotice(LANG_USR_USRDISABLEDMSG); } 
        if (isset($usrenabled)) { fpMessages::showSysNotice(LANG_USR_USRENABLEDMSG); } 
        if (isset($ownaccountdel)) { fpMessages::showErrorText(LANG_USR_NOTWONDELETE); }
        if (isset($frmisempty)) { fpMessages::showErrorText(LANG_USR_EMPTY); }       
        if (isset($lastusr)) { fpMessages::showErrorText(LANG_USR_LASTUSR); }
        if (isset($passsec)) { fpMessages::showErrorText(LANG_USR_PASSSEC); }
        if (isset($passsmatch)) { fpMessages::showErrorText(LANG_USR_PASS_CONFIRM_FAILED); }
    ?>
    <div id="tabsGeneral">
        <ul>
            <li><a href="#tabs-user-existing"><?php fpLanguage::printLanguageConstant(LANG_USR_EXIST); ?></a></li>
            <?php if($fpUser->countDisabledUsers() > 0) : ?>
            <li><a href="#tabs-user-disabled"><?php fpLanguage::printLanguageConstant(LANG_USR_EXIST_DISABLED); ?></a></li>
            <?php endif; ?>
            <li><a href="#tabs-user-create"><?php fpLanguage::printLanguageConstant(LANG_USR_ADDUSRTITL); ?></a></li>
            <li><a href="#tabs-rolls-manage"><?php fpLanguage::printLanguageConstant(LANG_USR_ROLLS_MANAGE); ?></a></li>
            <li><a href="#tabs-rolls-new"><?php fpLanguage::printLanguageConstant(LANG_USR_ROLLS_NEW); ?></a></li>
        </ul>
        <div id="tabs-user-existing">
            <?php include "userrollmgr/userlist.php"; ?>
        </div>
        <?php if($fpUser->countDisabledUsers() > 0) : ?>
        <div id="tabs-user-disabled">
            <?php include "userrollmgr/userlist_disabled.php"; ?>
        </div>
        <?php endif; ?>
        <div id="tabs-user-create">
        <?php include "userrollmgr/usreditor.php"; ?>
        </div>     
        <div id="tabs-rolls-manage">
            <?php include "userrollmgr/userrolloverview.php"; ?>
        </div>
        <div id="tabs-rolls-new">
            <?php include "userrollmgr/rolleditor.php"; ?>
        </div>      
    </div>
    <?php endif; ?>
<?php    
    } else {
            if(!fpSecurity::checkPermissions("system")) { fpMessages::showNoAccess(); }
            
    }
?>