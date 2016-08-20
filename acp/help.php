<?php
 /**
   * FanPress CM Hilfe
   * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
   * @copyright 2011-2014
   *
   */
    require_once dirname(dirname(__FILE__)).'/inc/acpcommon.php';
?>     

<?php
    $helpFileContent = fpLanguage::returnLanguageConstant(file_get_contents(FPBASEDIR.'/lang/'.$fpLanguage->getCurrentLanguage().'/help.php'));    
    $helpFileLines   = explode('##########', $helpFileContent);    
    unset($helpFileLines[0]);

?>

<?php if(LOGGED_IN_USER) : ?>

    <div class="box box-fixed-margin" id="contentbox"> 
        <h1><?php fpLanguage::printLanguageConstant(LANG_HELP); ?></h1>            
        <?php fpModuleEventsAcp::runOnAddACPMessage(); ?>
        
        <div id="accordionHelp" class="options">
        <?php foreach ($helpFileLines as $helpFileLine) : ?>
            <?php $helpLine = explode('----------', $helpFileLine); ?>
            <h2><?php print $helpLine[0]; ?></h2>
            <div><?php print $helpLine[1]; ?></div>
        <?php endforeach; ?>       

            <?php fpModuleEventsAcp::runOnCreateHelpEntry(); ?>
        </div>
<?php
    else :
        fpMessages::showNoAccess(LANG_ERROR_NOACCESS);
    endif;
?>         

    </div>    
<?php
  include FPBASEDIR."/sysstyle/sysfooter.php";
?>