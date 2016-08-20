<?php if(fpSecurity::checkPermissions ("system")) : ?>
<div class="fpcm-dashboard-conatiner">
    <div class="fpcm-dashboard-conatiner-inner fpcm-dashboard-conatiner-inner-boxes ui-widget-content ui-corner-all ui-state-normal">
        <h3 class="ui-corner-top  ui-corner-all"><span class="fa fa-refresh"></span> <?php fpLanguage::printLanguageConstant(LANG_SYS_UPDATESTATUS); ?></h3>
        <div class="update-box" id="update-box">
            <?php if (version_compare(PHP_VERSION, '5.4.0', '>=')) : ?>
            <div class="update-box-grid">
                <div class="version_dev">
                    <?php fpLanguage::printLanguageConstant(LANG_SYS_UPGRADE_FPCM3X); ?><br>
                    <small>
                        <a target="_blank" href="http://nobody-knows.org/download/fanpress-cm/#FanPress_CM_3.x">Download</a> &bull;
                        <a target="_blank" href="http://nobody-knows.org/download/fanpress-cm/schnelleinstieg/#Upgrade_von_FanPress_CM_2.x_auf_3.x">Upgrade</a>
                    </small>
                </div>
            </div>
            <?php endif; ?>
            <div class="update-box-grid">
                <?php $fpSystem->checkForUpdates(); ?>
            </div>
            <div class="update-box-grid">
                <?php $fpSystem->checkForModuleUpdates(true); ?>
            </div>
            <?php if(file_exists(FPBASEDIR."/update/update.php")) : ?>
            <div class="update-box-grid">
                <?php if(file_exists(FPBASEDIR."/update/update.php"))  { print "<div class=\"version_old\">".fpLanguage::returnLanguageConstant(LANG_AUTO_UPDATE_PHP_FILE)."</div>"; } ?>
            </div>                
            <?php endif; ?>
            <div class="clear"></div>
        </div>                        
    </div>
</div>
<?php endif; ?> 