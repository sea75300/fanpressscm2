<?php
    /**
     * Update: manuelles Update Script
     * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
     * @copyright 2011-2014
     *
     */

    // zentrale Konfiguration
    require_once dirname(dirname(__FILE__)).'/inc/acpcommon.php';
    
    error_reporting(1);
    
    require_once "ulang_".fpConfig::get('system_lang').".php";
    
    $systemVersion = fpConfig::get("system_version");
?>

<div class="box box-fixed-margin" id="contentbox">

    <h1>FanPress CM Update</h1> 
    
    <div id="tabsGeneral">
        <ul>
            <li><a href="#tabs-update">FanPress CM Update</a></li>
        </ul>

        <div id="tabs-update">
        <?php
            if (isset($_POST["btnstart"]) || isset($_GET["auto"])) {

                $fpUpdate = new fpUpdate($fpDBcon);
                $fpUpdate->runUpdate();                        

                if (version_compare($systemVersion,"2.3.0",">=")) {   

                    print "<p>".fpLanguage::returnLanguageConstant(L_INSTALL_FINTOLOGIN)."</p>\n";

                    $fpFileSystem = new fpFileSystem($fpDBcon);

                    if($fpFileSystem->deleteRecursive(FPBASEDIR."/update") != 0) {
                        print "<p class=\"important-notice-text\">".fpLanguage::returnLanguageConstant(L_DELETE_INSTALL_DIR)."</p>\n";
                    }
                }
            } else { 
                print "<p>".fpLanguage::returnLanguageConstant(L_UPDATE_WELCOME)."</p>";
                print "<div style=\"text-align:center;\"><form action=\"update.php\" method=\"post\">".
                      "<button type=\"Submit\" class=\"fp-ui-button btnloader fp-update-btn-start\" name=\"btnstart\">START</button>".
                      "</form></div>";

            }
        ?> 
        </div>
    </div>
</div>
<?php include FPBASEDIR."/sysstyle/sysfooter.php"; ?>