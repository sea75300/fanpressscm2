<?php
    /**
     * FanPress CM ACP Index
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    require_once dirname(dirname(__FILE__)).'/inc/acpcommon.php';
?>

    <div class="box box-fixed-margin" id="contentbox">
<?php
    if (LOGGED_IN_USER) {
        $idxstatsCache = new fpCache('idxstatsCache');
        
        if($idxstatsCache->isExpired()) {            
            $idxstats = $fpSystem->getStats();
            $idxstatsCache->write(serialize($idxstats), FPCACHEEXPIRE);            
        } else {
            $idxstats = array_merge(unserialize($idxstatsCache->read()), $fpSystem->getStats(false));
        }
?>     
    <h1><?php fpLanguage::printLanguageConstant(LANG_DASHBOARD); ?></h1>
    
        <?php fpModuleEventsAcp::runOnAddACPMessage(); ?>

        <div class="dashboard-container-wrapper">            

            <?php
                $containerFiles = scandir(FPBASEDIR.'/inc/dashcontainer/');
                foreach ($containerFiles as $containerFile) {
                    if(strpos($containerFile, '.php') === false) { continue; }

                    print "<div class=\"fpcm-dashboard-container-box\">\n";
                    include_once FPBASEDIR.'/inc/dashcontainer/'.$containerFile;
                    print "</div>\n\n";
                }  
            ?>                

            <?php fpModuleEventsAcp::runOnAddDashboardContainer(); ?>

            <?php fpModuleEventsAcp::runOnACPIndexTable(); ?>

            <div class="clear"></div>
        </div>               
<?php
    }
    else {
        fpMessages::showNoAccess(LANG_ERROR_NOACCESS);
    }
?>         

  </div>    
<?php
  include FPBASEDIR."/sysstyle/sysfooter.php";
?>