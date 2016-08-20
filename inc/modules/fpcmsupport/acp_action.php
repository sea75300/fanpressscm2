<?php

    function postgresIndexReset($tableName, $newTableIndex) {
        $dbCon = fpModules::getDBConnection();
        $dbCon->exec("SELECT pg_catalog.setval('".FP_PREFIX."_".$tableName."_id_seq', ".$newTableIndex.", true);");
    }

    function fpcmsupport_addToNavigation() {        
        return array(
            'descr' => 'FanPress CM Support Module',
            'link'  => fpModules::getModuleStdLink(MOD_FPCM_SUPPORT_MODKEY, '', true)
        );         
    }

    function fpcmsupport_onAddDashboardContainer() {
        return array(
            'containerHeadline' => fpLanguage::returnLanguageConstant('FanPress CM Support Module'),
            'containerBody'     => '<p>FanPress CM Support Module is installed and active! This includes the "support" user'
            . 'with permissions to all area of FanPress CM.</p>'
        );         
    }

    function fpcmsupport_acpRun() {
        print "<div id=\"tabsGeneral\"><ul><li><a href=\"#tabs-mod-support1\">".fpLanguage::returnLanguageConstant(LANG_SYS_GENERAL)."</a></li></ul><div id=\"tabs-mod-support1\">";
        print '<h3>Database data</h3>';
        print '<ul>';
        print '<li><b>DB user:</b> '.DBUSR.'</li>';
        print '<li><b>DB password:</b> '.DBPASSWD.'</li>';
        print '<li><b>DB name:</b> '.DBNAME.'</li>';
        print '<li><b>DB dbtype:</b> '.DBTYPE.'</li>';
        print '<li><b>DB prefix:</b> '.FP_PREFIX.'</li>';        
        print '</ul>';

        print '<h3>Tools</h3>';
        print '<ul>';
        if(file_exists(FPBASEDIR.'/update/update.php')) {
            print '<li><a href="'.FP_ROOT_DIR.'/update/update.php'.'" target="_blank">Updater</a></li>';
        }
        if(file_exists(FPBASEDIR.'/inc/modules/'.MOD_FPCM_SUPPORT_MODKEY.'/adminer.php')) {
            print '<li><a href="'.FP_ROOT_DIR.'/inc/modules/'.MOD_FPCM_SUPPORT_MODKEY.'/adminer.php'.'" target="_blank">Adminer</a></li>';
        }
        if(file_exists(FPBASEDIR.'/inc/modules/'.MOD_FPCM_SUPPORT_MODKEY.'/filemgr.php')) {
            print '<li><a href="'.FP_ROOT_DIR.'/inc/modules/'.MOD_FPCM_SUPPORT_MODKEY.'/filemgr.php'.'" target="_blank">File Manager</a></li>';
        }  
        print '<ul>';

        if(DBTYPE == 'pgsql') {
            print '<h3>Postgres Table Index Reset</h3>';
            print '<p><a href="&modact=resettab&tabname=XYZ&newtabidx=XYZ" target="_blank">Reset Table Index</a></p>';
            if(isset($_GET['modact'])) {
                postgresIndexReset(fpSecurity::Filter2($_GET['tabname']), fpSecurity::Filter2($_GET['newtabidx']));
            }
        }
        
        print "</div></div>";
    }

?>