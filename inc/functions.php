<?php
    /**
     * FanPress CM 2 Class Autoloader
     * @param string $class
     * @return boolean
     */
    function fpcmautoloader($class) {
        // Affiliat*r Klassen nicht laden, wenn beide Systeme kombiniert werden
        $afltrClasses = array('database', 'language', 'messages', 'viewHelper', 'base_config', 'tools');
        if(strpos($class, '\\') || in_array($class, $afltrClasses)) { return true; }
        
        if($class == "fpUpdate") {
            require_once FPBASEDIR."/update/c_fpupdate.php";
            return true;
        }

        if($class == "fpInstallation") return true;

        if(strpos($class, "fpModule") !== false && !in_array($class, array('fpModules', 'fpModuleEventsAcp', 'fpModuleEventsFE'))) {
            $modClassTest = explode("_", $class);            
            $classNameForms = array(FPBASEDIR.'/inc/modules/'.strtolower($modClassTest[1]).'/c_'.strtolower($modClassTest[2]).'.php');            
        } else {
            $classNameForms = array(
                FPBASEDIR.'/inc/'.strtolower($class).'.php',
                FPBASEDIR.'/inc/classes/c_'.strtolower($class).'.php',
                FPBASEDIR.'/inc/classes/'.strtolower($class).'/c_'.strtolower($class).'.php',
                FPBASEDIR.'/inc/model/'.strtolower($class).'.php',
                FPBASEDIR.'/inc/lib/'.  strtolower($class).'/'.$class.'.php'
            );            
        }
        
        if(defined('FPCM_DEBUG') && FPCM_DEBUG > 1) fpMessages::writeToSysLog(implode('<br>'.PHP_EOL, $classNameForms));

        foreach ($classNameForms as $index => $classNameForm) {            
            if(!file_exists($classNameForm)) {
                unset($classNameForms[$index]);
                continue;
            }

            require_once $classNameForm;
        }
        
        if(!count($classNameForms)) { fpMessages::writeToSysLog("FATAL ERROR: Loading class ".$class." failed."); }      
    }
    
    /**
     * FanPress CM 2 Error Handler (FPCM 2.1)
     * @param string $ecode Error-Code
     * @param string $etext Error-Text
     * @param string $efile Error-File
     * @param string $eline Error-File-Line
     */
    function fpcmerrorhandler($ecode, $etext, $efile, $eline) {        
        if(strpos($efile, FPBASEDIR) === false) return;
        
        $errorLog = FPLOGSFOLDER."/fpcm_phplog.txt";
          
        $LogLine = json_encode(array('time' => time(),'text' => $ecode."|".$etext."|".$efile."|".$eline));
        file_put_contents($errorLog, $LogLine.PHP_EOL, FILE_APPEND);
        
        if($ecode != 2 && $ecode != 8 && $ecode != 2048 && $ecode != 8192) {
            if(defined("FPCM_DEBUG") && FPCM_DEBUG) {
                print $etext.'<br> FILE: '.$efile.'<br> LINE: '.$eline.'<br> CODE: '.$ecode;
            } else {
                print "An error occured. See ".$errorLog." for more informations.";
            }
        }
        
        return false;
    }  
    
    function fpDebugOutput() {
        if(defined("FPCM_DEBUG") && FPCM_DEBUG) {
            $html   = array();
            $html[] = 'Memory usage: '.round(memory_get_usage(true) / 1024 / 1024,2).'MB<br>';
            $html[] = 'Memorypeak: '.round(memory_get_peak_usage(true) / 1024 / 1024,2).'MB<br>';
            $html[] = 'BASEDIR: '.FPBASEDIR.'<br>';
            $html[] = 'PHPVERSION: '.PHP_VERSION;
            print '<p style="text-align:center;font-size:0.9em;margin-bottom:35px;">'.implode("\n", $html).'</p>';
        }
    }

    /**
     * FanPress CM 2 Dump Function (FPCM 2.1)
     * @param mixed $data
     */
    function fpDump($data) {
        print "<pre>";
        var_dump($data);
        print "</pre>";
    }
?>
