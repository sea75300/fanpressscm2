<?php
    /**
     * Converter von Cutenews nach FanPress CM
     * @author Stefan Seehafer (fanpress@nobody-knows.org)
     * @copyright 2011-2014
     *
     */

    function showStartForm() {
        $fpSystem = new fpSystem(fpModules::getDBConnection());    

        $idxstats = $fpSystem->getStats();
        if($idxstats['newscount'] > 0) {
            fpMessages::showErrorText(LANG_MOD_FPCMCONV_FOUNDDB_NEWS);
        }

        fpModules::includeModuleTemplate("fpcutenewscoverter", "converterStart");
    }
    
    function startConversion() {
        $fpSystem = new fpSystem(fpModules::getDBConnection());
        $fpConverterObj = new fpModule_fpCutenewsCoverter_Converter(fpModules::getDBConnection());                
        
        $cnpath = dirname(FPBASEDIR).fpSecurity::Filter5($_POST["pathtonewstxt"]);
        print "<p>$cnpath</p>";
        
        switch($_POST['cnversion']) {
            case "1.5.x" :
            case "2.0.x" :
                $test_newstxt  = $cnpath."cdata/news.txt";
                $test_userdb   = $cnpath."cdata/users.db.php";
                $test_catdb    = $cnpath."cdata/category.db.php";
                $test_archives = $cnpath."cdata/archives/";                   
            break;
            default :
                $test_newstxt  = $cnpath."data/news.txt";
                $test_userdb   = $cnpath."data/users.db.php";
                $test_catdb    = $cnpath."data/category.db.php";
                $test_archives = $cnpath."data/archives/";
            break;
        }

        /* Benutzer konvertieren */    
        if(file_exists($test_userdb)) {
            fpMessages::showSysNotice(LANG_MOD_FPCMCONV_FOUNDDB_USER);     
            $usrhandel = @fopen($test_userdb,"r");

            $setlinetwo = false;
            while (!feof($usrhandel)) {
                $usrline = "";

                $usrline = $usrline.fgets($usrhandel);
                if ($setlinetwo) {          
                    $cnarrayline_usr = explode("|",$usrline);
                    if(isset($cnarrayline_usr[2])) {
                        $fpConverterObj->copyUsers($cnarrayline_usr[4],$cnarrayline_usr[5],$cnarrayline_usr[2]);
                    }
                } else {
                    $setlinetwo = true;
                }
            }

            fclose($usrhandel);
            fpMessages::showSysNotice(LANG_MOD_FPCMCONV_FINISHED);
        } else {
            fpMessages::showErrorText(LANG_MOD_FPCMCONV_FOUNDDB_NOUSER."<br>".$test_userdb);
        }    

        /* Kategorien konvertieren */
        if(file_exists($test_catdb)) {
            fpMessages::showSysNotice(LANG_MOD_FPCMCONV_FOUNDDB_CATEGORY);

            $cathandel = @fopen($test_catdb,"r");

            $setlinetwo = false;
            while (!feof($cathandel)) {
                $catline = "";

                $catline = $usrline.fgets ($cathandel);
                $cnarrayline_cat = explode("|",$catline);

                if(isset($cnarrayline_cat[1])) {
                    $fpConverterObj->copyCategories($cnarrayline_cat[1],$cnarrayline_cat[2],$cnarrayline_cat[3]);
                }
            }

            fclose($cathandel);
            fpMessages::showSysNotice(LANG_MOD_FPCMCONV_FINISHED);
        } else {
            fpMessages::showErrorText(LANG_MOD_FPCMCONV_FOUNDDB_NOCATEGORY."<br>".$test_catdb);
        }    

        /* News konvertieren - Archive */
        if(file_exists($test_archives)) {
            if (count(scandir($test_archives)) > 2) {
                fpMessages::showSysNotice(LANG_MOD_FPCMCONV_FOUNDNEWS_ARCHIVE);

                $import_sum = 0;

                $handle = opendir ($test_archives);
                while ($datei = readdir ($handle)) {
                    $dateiinfo = pathinfo($datei);               
                    if($datei != "." && $datei != ".." && $datei != "index.htm") {
                        if(strpos($dateiinfo['filename'],"comments") === false) {
                            print "<p>".$dateiinfo['filename']." &bull; ";   

                            $arch_newshandel = fopen($test_archives.$datei,"r");
                            $newsline = array();

                            $nl = 0;

                            while (!feof($arch_newshandel)) {
                                $templine = "";
                                $templine = $templine.fgets ($arch_newshandel);    
                                if($templine != "") {
                                    $newsline[$nl] = $templine;
                                    $nl++;
                                }
                            }

                            $newsline = array_reverse($newsline);

                            for($i=0;$i<count($newsline);$i++) {
                                $cnarrayline = explode("|",$newsline[$i]); 							
                                if(isset($cnarrayline[2])) {
                                    $fpConverterObj->copyCNpostToFPpost($cnarrayline[0],$cnarrayline[6],$cnarrayline[2],$cnarrayline[1],$cnarrayline[3],0,1);
                                }
                            }

                            fclose($arch_newshandel);
                            print str_replace('%records%', count($newsline), LANG_MOD_FPCMCONV_FINISHED_COUNT);

                            $import_sum = $import_sum + count($newsline);
                        }
                    }
                }
                fpMessages::showSysNotice(str_replace('%records%', count($import_sum), LANG_MOD_FPCMCONV_FINISHED_COUNT));                    
                closedir($handle);       				
            } else {
                fpMessages::showSysNotice(LANG_MOD_FPCMCONV_FOUNDNEWS_NOARCHIVE);
            }
        } else {
            fpMessages::showErrorText(LANG_MOD_FPCMCONV_FOUNDNEWS_NOARCHIVE."<br>".$test_archives);
        }

        /* News konvertieren - aktiv */
        if(file_exists($test_newstxt)) {
            fpMessages::showSysNotice(LANG_MOD_FPCMCONV_FOUNDNEWS_ACTIVE);

            $newshandel = fopen($test_newstxt,"r");

            $newsline = array();

            $nl = 0;

            while (!feof($newshandel)) {
                $templine = "";
                $templine = $templine.fgets ($newshandel);    
                if($templine != "") {
                    $newsline[$nl] = $templine;
                    $nl++;        
                }
            }

            $newsline = array_reverse($newsline);

            for($i=0;$i<count($newsline);$i++) {
                $cnarrayline = explode("|",$newsline[$i]);         
                if(isset($cnarrayline[2])) {
                    $fpConverterObj->copyCNpostToFPpost($cnarrayline[0],$cnarrayline[6],$cnarrayline[2],$cnarrayline[1],$cnarrayline[3],0);                     
                }
            }

            fclose($newshandel);
            print str_replace('%records%', count($newsline), LANG_MOD_FPCMCONV_FINISHED_COUNT);
        } else {
            fpMessages::showErrorText(LANG_MOD_FPCMCONV_FOUNDNEWS_NOACTIVE."<br>".$test_newstxt);
        }
    }

    function fpcutenewscoverter_acpRun() {
        if(isset($_POST["sbtn_startconvertion"])) {           
            startConversion();
        } else {
            showStartForm();
        }          
    }
    
    function fpcutenewscoverter_addToNavigation() {
        return array(
            'descr' => 'Cutenews Converter',
            'link'  => fpModules::getModuleStdLink("fpcutenewscoverter", '', true)
        );
    }    
?>