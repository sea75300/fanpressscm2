<?php
    function customfileeditor_addToNavigation() {        
        return array(
            'descr' => LANG_MOD_CFE_NAME,
            'link'  => fpModules::getModuleStdLink(MOD_CFE_MODKEY, '', true)
        );        
    }

    function customfileeditor_onCreateHelpEntry() {
         return array(
             "helpHeadline" => fpLanguage::returnLanguageConstant(LANG_MOD_CFE_NAME),
             "helpText" => fpLanguage::returnLanguageConstant(LANG_MOD_CFE_HELP)
         );
    }

    function customfileeditor_acpRun() {       
        $fileList = array(
            FPBASEDIR.'/inc/central.custom.php',
            FPBASEDIR.'/inc/tiny_mce.custom.css'
        );         

        $writeFailed = array();
        if(isset($_POST['btncfesave'])) {
            foreach ($fileList as $file) {                
                $content = trim($_POST['files'][str_replace('.', '_', basename($file))]);                
                if($content != "your code here")
                    if((file_exists($file) && !is_writable($file)) || file_put_contents($file, $content) === FALSE) $writeFailed[] = basename($file);
            }
            
            if(count($writeFailed) > 0)
                fpMessages::showErrorText(LANG_MOD_CFE_EDITEDFAILED.PHP_EOL.  implode(',', $writeFailed));
            else
                fpMessages::showSysNotice(LANG_MOD_CFE_EDITED);
        }

        $tplData = array();       
        foreach ($fileList as $file) {         
            if(file_exists($file)) {
                if(!is_writable($file)) fpMessages::showErrorText($file.': '.LANG_CHECK_WRITE_FAILURE);   
                $tplData[basename($file)] = file_get_contents($file);
            } else {
                $tplData[basename($file)] = "your code here";
            }
        }

        fpModules::includeModuleTemplate(MOD_CFE_MODKEY, 'editor', array('customeFiles' => $tplData));
    }
?>