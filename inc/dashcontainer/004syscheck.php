<?php if(fpSecurity::checkPermissions ("system")) : ?>  
<div class="fpcm-dashboard-conatiner">
    <div class="fpcm-dashboard-conatiner-inner fpcm-dashboard-conatiner-inner-boxes ui-widget-content ui-corner-all ui-state-normal">
        <h3 class="ui-corner-top  ui-corner-all"><span class="fa fa-medkit"></span> <?php fpLanguage::printLanguageConstant(LANG_CHECK_HEADLINE); ?></h3>
        <div>
            <ul>
                <li><b><?php fpLanguage::printLanguageConstant(LANG_CHECK_WRITE_DATA); ?></b>: <?php (is_writable(FPDATAFOLDER) ? fpLanguage::printLanguageConstant(LANG_CHECK_WRITE_OK) : print "<span class=\"important-notice-text\">".fpLanguage::returnLanguageConstant(LANG_CHECK_WRITE_FAILURE)."</span>"); ?></li>
                <li><b><?php fpLanguage::printLanguageConstant(LANG_CHECK_WRITE_UPLOAD); ?></b>: <?php (is_writable(FPUPLOADFOLDER) ? fpLanguage::printLanguageConstant(LANG_CHECK_WRITE_OK) : print "<span class=\"important-notice-text\">".fpLanguage::returnLanguageConstant(LANG_CHECK_WRITE_FAILURE)."</span>"); ?></li>                        
                <li><b><?php fpLanguage::printLanguageConstant(LANG_CHECK_WRITE_FILMGRTHUMBS); ?></b>: <?php (is_writable(FPFMGRTHUMBS) ? fpLanguage::printLanguageConstant(LANG_CHECK_WRITE_OK) : print "<span class=\"important-notice-text\">".fpLanguage::returnLanguageConstant(LANG_CHECK_WRITE_FAILURE)."</span>"); ?></li>
                <li><b><?php fpLanguage::printLanguageConstant(LANG_CHECK_WRITE_CACHE); ?></b>: <?php (is_writable(FPCACHEFOLDER) ? fpLanguage::printLanguageConstant(LANG_CHECK_WRITE_OK) : print "<span class=\"important-notice-text\">".fpLanguage::returnLanguageConstant(LANG_CHECK_WRITE_FAILURE)."</span>"); ?></li>
                <li><b><?php fpLanguage::printLanguageConstant(LANG_CHECK_WRITE_REVISIONS); ?></b>: <?php (is_writable(FPREVISIONFOLDER) ? fpLanguage::printLanguageConstant(LANG_CHECK_WRITE_OK) : print "<span class=\"important-notice-text\">".fpLanguage::returnLanguageConstant(LANG_CHECK_WRITE_FAILURE)."</span>"); ?></li>
                <li><b><?php fpLanguage::printLanguageConstant(LANG_CHECK_WRITE_UPGRADES); ?></b>: <?php (is_writable(FPBASEDIR."/upgrade/") ? fpLanguage::printLanguageConstant(LANG_CHECK_WRITE_OK) : print "<span class=\"important-notice-text\">".fpLanguage::returnLanguageConstant(LANG_CHECK_WRITE_FAILURE)."</span>"); ?></li>
                <li><b><?php fpLanguage::printLanguageConstant(LANG_CHECK_WRITE_MODULES); ?></b>: <?php (is_writable(FPBASEDIR."/inc/modules") ? fpLanguage::printLanguageConstant(LANG_CHECK_WRITE_OK) : print "<span class=\"important-notice-text\">".fpLanguage::returnLanguageConstant(LANG_CHECK_WRITE_FAILURE)."</span>"); ?></li>
                <li><b><?php fpLanguage::printLanguageConstant(LANG_CHECK_WRITE_STYLES); ?></b>: <?php (is_writable(FPBASEDIR."/styles") ? fpLanguage::printLanguageConstant(LANG_CHECK_WRITE_OK) : print "<span class=\"important-notice-text\">".fpLanguage::returnLanguageConstant(LANG_CHECK_WRITE_FAILURE)."</span>"); ?></li>
                <li><b><?php fpLanguage::printLanguageConstant(LANG_CHECK_WRITE_CONNECTTOSRV); ?></b>: <?php ($fpSystem->canConnect()) ? fpLanguage::printLanguageConstant(LANG_GLOBAL_YES) : fpLanguage::printNo(); ?></li>
                <?php if(ini_get("magic_quotes_gpc")) : ?>
                <li class="important-notice-text"><?php fpLanguage::printLanguageConstant(LANG_CHECK_MAGIC_QUOTES);  ?></li>
                <?php endif; ?>
                <?php if(ini_get("register_globals")) : ?>
                <li class="important-notice-text"><?php fpLanguage::printLanguageConstant(LANG_CHECK_REGISTER_GLOBALS);  ?></li>
                <?php endif; ?>                        
            </ul>             
        </div>
    </div>
</div>
<?php endif; ?>