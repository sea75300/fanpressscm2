<?php 
    /**
     * News bearbeiten
     * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
     * @copyright 2011-2014
     *
     */
    require_once dirname(dirname(__FILE__)).'/inc/acpcommon.php';

    if(LOGGED_IN_USER && fpSecurity::checkPermissions ("editnews")) :
?>
    <div class="box box-fixed-margin" id="contentbox">
        <h1><?php fpLanguage::printLanguageConstant(LANG_EDIT) ?></h1>
        
        <?php fpModuleEventsAcp::runOnAddACPMessage(); ?>
        
        <?php
            if(isset($_GET["fn"]) && $_GET["fn"] == "edit" || isset($_GET["added"])) {
                include FPBASEDIR.'/acp/newslist/loadeditor.php';
            } else {
                include FPBASEDIR.'/acp/newslist/loadlist.php';
            }
        ?>        
    </div>
<?php
    else :
        if(!fpSecurity::checkPermissions("editnews")) { fpMessages::showNoAccess(); }
    endif;
?>    
<?php include FPBASEDIR."/sysstyle/sysfooter.php"; ?>