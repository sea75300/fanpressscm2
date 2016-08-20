<?php
    /**
     * FanPress CM Module-Manager
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2012-2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    require_once dirname(dirname(__FILE__)).'/inc/acpcommon.php';

    if(LOGGED_IN_USER && fpSecurity::checkPermissions ("modules")) :
?>
<h1><?php fpLanguage::printLanguageConstant(LANG_SYSCFG_MODULES); ?></h1>
<div class="box box-fixed-margin" id="contentbox">
<?php
        fpModuleEventsAcp::runOnAddACPMessage();
        
        $moduleLoaded   = false;
        $moduleUpdate   = false;
        $moduleInstall  = false;
        $openInstallPHP = false;

        if(isset($_GET['ext']) && isset($_GET['act'])) {
            $moduleKey = fpSecurity::fpfilter(array('filterstring' => $_GET["ext"]));

            switch($_GET['act']) {
                case "open" :
                    if(fpModules::moduleIsActive($moduleKey)) {
                        $moduleLoaded = true;
                        $config = $moduleConfArray[$moduleKey];
                    } else {
                        fpMessages::showErrorText(LANG_MODULE_ISDISABLED);
                    }
                break; 
                case "install" :
                    $moduleInstall= true;
                break;
                case "installphp" :
                    $moduleInstall  = true;
                    $openInstallPHP = true;
                break;                
                case "update" :
                    $config = $moduleConfArray[$moduleKey];
                    $moduleUpdate = true;
                break;                
            }
        }
        
        if(isset($_POST["sbmmodulefile"]) && isset($_FILES['modulefile']) && $_FILES['modulefile']['type'] == 'application/zip' && substr($_FILES['modulefile']['name'], strlen($_FILES['modulefile']['name']) - 4) == '.zip') {
            fpModules::uploadModulePackage();
        }
        
        if(isset($_GET['modmsg'])) {
            switch ($_GET['modmsg']) {
                case 'enabled' :
                    fpMessages::showSysNotice(LANG_MODULE_ENABLED);
                break;
                case 'disabled' :
                    fpMessages::showSysNotice(LANG_MODULE_DISABLED);
                break;  
                case 'deleted' :
                    fpMessages::showSysNotice(LANG_MODULE_UNINSTALLED);
                break;
            }
        }
?>        


    <?php if($moduleLoaded && fpSecurity::checkPermissions('modules')) : ?>
    
        <h2><?php print $config["name"]; ?></h2>
        <?php call_user_func($moduleKey."_acpRun"); ?>
    
    <?php else : ?>    
        <?php if ($moduleUpdate) : ?>

        <div id="tabsGeneral">
            <ul>
                <li><a href="#tabs-modules-update"><?php print $moduleKey; ?> - <?php fpLanguage::printLanguageConstant(LANG_AUTO_UPDATE); ?></a></li>
            </ul>
            <div id="tabs-modules-update"> 

            <?php        
                $modulePackageNameArray = explode("_", fpSecurity::Filter2($_GET["loadmodule"]));
                if(version_compare($modulePackageNameArray[1], $config["version"],">")) {
                    fpModules::updateModule($modulePackageNameArray);
                } else {
                    fpMessages::showSysNotice(LANG_AUTO_UPDATE_UPTODATE_MSG);
                }
            ?>
            </div>
        </div>        
        
        <?php elseif($moduleInstall) : ?>

            <div id="tabsGeneral">
                <ul>
                    <li><a href="#tabs-modules-install"><?php print $moduleKey; ?> - <?php fpLanguage::printLanguageConstant(LANG_MODULES_INSTALLMOD); ?></a></li>
                </ul>
                <div id="tabs-modules-install">        
                <?php
                    if($fpSystem->canConnect() && !$openInstallPHP) {
                        $modulePackageNameArray = explode("_", fpSecurity::Filter2($_GET["loadmodule"]));
                        fpModules::installFPModule($modulePackageNameArray);
                    } else {
                        fpModules::installFPModule($moduleKey, false);
                    }
                ?>
                </div>
            </div>
        
        <?php else : ?>        

            <?php if($fpSystem->canConnect()) : ?>

                <?php include FPBASEDIR.'/acp/modulemgr/automatic.php'; ?>

            <?php else : ?>

                <?php include FPBASEDIR.'/acp/modulemgr/manual.php'; ?>

            <?php endif; ?> 

        <?php endif; ?>
    <?php endif; ?>
</div>    
<?php
    else :
        fpMessages::showNoAccess(LANG_ERROR_NOACCESS);
    endif;
?>
<?php include FPBASEDIR."/sysstyle/sysfooter.php"; ?>