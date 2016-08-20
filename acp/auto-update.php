<?php
    /**
     * FanPress CM Auto-Updater
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
	
    require_once dirname(dirname(__FILE__)).'/inc/acpcommon.php';
    
    if(!$fpSystem->canConnect()) { header("Location: index.php"); }

    if(LOGGED_IN_USER && fpSecurity::checkPermissions ("system")) {
        
        $versions = json_decode(base64_decode(fpSecurity::fpfilter(array('filterstring' => $_GET["newver"]), array(7))), true);

        if(isset($_GET['versionfile']) && defined("FPCM_SKIPVERSIONS")) {
            $updateFileRemote = fpSecurity::fpfilter(array('filterstring' => $_GET['versionfile']), array(1,4,7));
        } else {
            $updateFileRemote = isset($versions['versionfile']) ? $versions['versionfile'] : '';
        }
?>
  <div class="box box-fixed-margin" id="contentbox">
  <h1><?php fpLanguage::printLanguageConstant(LANG_AUTO_UPDATE); ?></h1>

<?php fpModuleEventsAcp::runOnAddACPMessage(); ?>
    <div id="tabsGeneral">
        <ul>
            <li><a href="#tabs-update"><?php fpLanguage::printLanguageConstant(LANG_AUTO_UPDATE); ?></a></li>
        </ul>
        
        <div id="tabs-update">         
            <?php if(isset($_GET["newver"])): ?>
            
            <div id="fp-progressbar" style="margin-bottom: 15px;"></div>            
            
            <div id="updater-info0"></div>
            <div id="updater-info1"></div>
            <div id="updater-info1_msg"></div>
            <div id="updater-info2"></div>
            <div id="updater-info2_msg"></div>
            <div id="updater-info3"></div>
            <div id="updater-info3_msg"></div>
            <div id="updater-info4"></div>
            <div id="updater-info4_msg"></div>
            <div id="updater-info5"></div>
            <div id="updater-info_timer"></div>
            
            <script type="text/javascript">            
                var updaterUrl = jsFProotPath + "acp/ajax/updater.php?newver=<?php print $_GET["newver"]; ?>&step=";
                var updaterInitError = '<?php print str_replace(PHP_EOL,'',fpMessages::showErrorText(LANG_AUTO_UPDATE_UPDATEDATA_ERR_MSG, true)); ?>';
                var updaterStartDownload = '<?php print str_replace(PHP_EOL,'',fpMessages::showSysNotice(LANG_AUTO_UPDATE_START.$updateFileRemote, true)); ?>';
                var updaterUpgradeFolderError = '<?php print str_replace(PHP_EOL,'',fpMessages::showErrorText(LANG_AUTO_UPDATE_PERMFAILURE_MSG, true)); ?>';
                var updaterDownloadError = '<?php print str_replace(PHP_EOL,'',fpMessages::showErrorText(LANG_AUTO_UPDATE_DLNOSUCCESS_MSG, true)); ?>';
                var updaterExtractError = '<?php print str_replace(PHP_EOL,'',fpMessages::showErrorText(LANG_AUTO_UPDATE_EXTRACT_ERROR, true)); ?>';
                var updaterExtractOk = '<?php print str_replace(PHP_EOL,'',fpMessages::showSysNotice(LANG_AUTO_UPDATE_EXTRACT_OK, true)); ?>';
                var updaterCopyError = '<?php print str_replace(PHP_EOL,'',fpMessages::showErrorText(LANG_AUTO_UPDATE_COPYFAILED_MSG, true)); ?>';
                var updaterCleanupMsg = '<?php print str_replace(PHP_EOL,'',fpMessages::showSysNotice(LANG_AUTO_UPDATE_REMOVETEMPFILES, true)); ?>';
                var updaterTimerMsg = '<?php fpLanguage::printLanguageConstant(LANG_AUTO_UPDATE_TIMEFORDL); ?>';
                var updaterStart    = '<?php print "<p><a class=\"btnloader fp-ui-button fp-ui-button-updater\" href=\"\" onclick=\"location.reload();\">".fpLanguage::returnLanguageConstant(LANG_SYS_UPDATESTATUS_START)."</a></p>"; ?>';

                var updateConfirmed = false;

                jQuery(document).ready(function() {
                    <?php if(isset($_GET['force'])) : ?>
                    updateConfirmed = true;
                    <?php else : ?>
                    updateConfirmed = confirm('<?php fpLanguage::printLanguageConstant(LANG_EDIT_ACTION_CONFIRM_MSG); ?>');
                    <?php endif; ?>                    
                    
                    runUpdater();
                });
            </script>            
            
            <?php else : ?>
            
            <?php fpMessages::showSysNotice(LANG_AUTO_UPDATE_UPTODATE_MSG); ?>
            
            <?php endif; ?>
        </div>        
    </div>
</div>
<?php
    } else {
        if(!fpSecurity::checkPermissions("system")) { fpMessages::showNoAccess(); }

    }  
    include FPBASEDIR."/sysstyle/sysfooter.php";
?>