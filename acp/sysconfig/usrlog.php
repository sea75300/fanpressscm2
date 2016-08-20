<?php
 /**
   * Benutzerlog
   * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
   * @copyright 2012
   *
   */

    if(!defined("LOGGED_IN_USER")) { die(); }

    if(LOGGED_IN_USER && fpSecurity::checkPermissions ("system")) :
        
        $syslogs = new fpLogs();
        
        if(isset($_POST['btnclearsyslog'])) { $syslogs->clearLogs(1); }
        if(isset($_POST['btnclearphplog'])) { $syslogs->clearLogs(2); }
        if(isset($_POST['btnclearusrlog'])) { $syslogs->clearLogs(3); }
        if(isset($_POST['btnclearsqllog'])) { $syslogs->clearLogs(4); }      
?>
    <h2><?php fpLanguage::printLanguageConstant(LANG_SYSCFG_SYSLOG) ?></h2>
    
    <div id="tabsGeneral">
        <form method="post" action="sysconfig.php?mod=syslog">
        <ul>
            <li><a href="#tabs-logs-userlog"><?php fpLanguage::printLanguageConstant(LANG_SYSCFG_FPUSERLOG); ?></a></li>
            <li><a href="#tabs-logs-systemlog"><?php fpLanguage::printLanguageConstant(LANG_SYSCFG_SYSTEMLOG); ?></a></li>
            <li><a href="#tabs-logs-phplog"><?php fpLanguage::printLanguageConstant(LANG_SYSCFG_PHPNOTLOG); ?></a></li>
            <li><a href="#tabs-logs-sqllog"><?php fpLanguage::printLanguageConstant(LANG_SYSCFG_SQLLOG); ?></a></li>
        </ul>
        <div id="tabs-logs-userlog">
            <div id="logcontent0"><?php $syslogs->getUserLog(); ?></div>
            <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
                <a href="reloadlog.php" reload="0" class="btnloader fp-ui-button fp-reload-btn fp-reload-btn-logs"><?php fpLanguage::printReload(); ?></a>
                <button type="submit" name="btnclearusrlog" class="btnloader fp-ui-button"><?php fpLanguage::printLanguageConstant(LANG_SYSCFG_CLEARLOG); ?></button>
            </div>              
        </div>
            <div id="tabs-logs-systemlog">
                <div id="logcontent1"><?php $syslogs->getSysLog(); ?></div>
                
                <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
                    <a href="reloadlog.php" reload="1" class="btnloader fp-ui-button fp-reload-btn fp-reload-btn-logs"><?php fpLanguage::printReload(); ?></a>
                    <button type="submit" name="btnclearsyslog" class="btnloader fp-ui-button"><?php fpLanguage::printLanguageConstant(LANG_SYSCFG_CLEARLOG); ?></button>
                </div>               
            </div>
            <div id="tabs-logs-phplog">
                <div id="logcontent2"><?php $syslogs->getPhpLog(); ?></div>
                
                <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
                    <a href="reloadlog.php" reload="2" class="btnloader fp-ui-button fp-reload-btn fp-reload-btn-logs"><?php fpLanguage::printReload(); ?></a>
                    <button type="submit" name="btnclearphplog" class="btnloader fp-ui-button"><?php fpLanguage::printLanguageConstant(LANG_SYSCFG_CLEARLOG); ?></button>
                </div>             
            </div> 
            
            <div id="tabs-logs-sqllog">
                <div id="logcontent3"><?php $syslogs->getSqlLog(); ?></div>
                
                <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
                    <a href="reloadlog.php" reload="3" class="btnloader fp-ui-button fp-reload-btn fp-reload-btn-logs"><?php fpLanguage::printReload(); ?></a>
                    <button type="submit" name="btnclearsqllog" class="btnloader fp-ui-button"><?php fpLanguage::printLanguageConstant(LANG_SYSCFG_CLEARLOG); ?></button>
                </div> 
            </div>
        </form>
    </div>    
    
    <?php if(isset($_GET['reload'])) : ?>
    <script type="text/javascript">    
        jQuery("#tabsGeneral").tabs({ active: <?php print (int) $_GET['reload']; ?> });
    </script>
    <?php endif; ?>     
<?php
    else :
        if(!fpSecurity::checkPermissions("system")) { fpMessages::showNoAccess(); }
    endif;
?>    