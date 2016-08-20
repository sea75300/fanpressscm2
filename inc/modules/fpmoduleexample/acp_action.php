<?php
    /**
     * FanPress CM Module Example
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2012-2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    function fpmoduleexample_acpRun() {
        fpLanguage::printLanguageConstant(LANG_MOD_FPCMEXAMPLE);
        print "<p>Last update of dummyvalue on execution was on ".date('d.m.Y H:i:s', fpModules::loadModuleConfigOption("fpmoduleexample", "dummy_value"))."</p>";
        
        fpModules::updateModuleConfigOption("fpmoduleexample",array('confname' => "dummy_value","confvalue" => (string) time()));
        
    }
    
    function fpmoduleexample_onNewsSave($data) {
        fpMessages::writeToSysLog(print_r($data, true));
        
        return $data;
    }
    
    function fpmoduleexample_onNewsUpdate($data) {
        fpMessages::writeToSysLog(print_r($data, true));
        
        return $data;
    } 
    
    function fpmoduleexample_onCategorySave($data) {
        fpMessages::writeToSysLog(print_r($data, true));
        
        return $data;
    }
    
    function fpmoduleexample_onCategoryUpdate($data) {
        fpMessages::writeToSysLog(print_r($data, true));
        
        return $data;
    }    
    
    function fpmoduleexample_onAddDashboardContainer() {
        return array(
            'containerHeadline' => fpLanguage::returnLanguageConstant(LANG_MOD_FPCMEXAMPLE),
            'containerBody'     => 'This is a test-message!'
        ); 
    }        
    
    function fpmoduleexample_addToNavigation() {   
        return array(
            'descr' => LANG_MOD_FPCMEXAMPLE,
            'link'  => fpModules::getModuleStdLink("fpmoduleexample", '', true)
        );                
    }
    
    function fpmoduleexample_addToNavigationMain() {   
        return array(
            array(
                'group' => 'editnews',
                'icon'  => '',
                'descr' => fpLanguage::returnLanguageConstant(LANG_MOD_FPCMEXAMPLE),
                'link'  => fpModules::getModuleStdLink("fpmoduleexample", '', true)                
            ),
            array(
                'icon'  => '',
                'descr' => fpLanguage::returnLanguageConstant(LANG_MOD_FPCMEXAMPLE).' 2',
                'link'  => fpModules::getModuleStdLink("fpmoduleexample", '', true)                
            )
        );                
    }
        
    function fpmoduleexample_addToHeaderJs() {
        if(file_exists(fpModules::getModuleRelPath("fpmoduleexample")."/script.js")) {
            return fpModules::getModuleRelPath("fpmoduleexample")."/script.js";
        }
    }    
    
    function fpmoduleexample_addToHeaderCss() {
        if(file_exists(fpModules::getModuleRelPath("fpmoduleexample")."/script.css")) {
            return fpModules::getModuleRelPath("fpmoduleexample")."/script.css";
        }
    }
    
    function fpmoduleexample_onCreateShortLink($paramArray) {
        return array(
            "replaceIsGd" => true,
            "linkText" => "This is an example text to show, e. g. how to use the onCreateShortLink event for an other shortener service."
        );
    }    
    
    function fpmoduleexample_onCreateHelpEntry() {
        return array(
            "helpHeadline" => fpLanguage::returnLanguageConstant(LANG_MOD_FPCMEXAMPLE),
            "helpText" => "This is an example text to show, e. g. how to use the onCreateHelpEntry event for a new entry on help page."
        );
    }     
    
    function fpmoduleexample_onACPLogin($paramArray) {
        fpMessages::writeToSysLog(fpLanguage::returnLanguageConstant(LANG_MOD_FPCMEXAMPLE));
    }   
    
    function fpmoduleexample_onAddACPMessage() {
        return fpLanguage::returnLanguageConstant(LANG_MOD_FPCMEXAMPLE);
    }

    function fpmoduleexample_addEditorButton() {
        return array(
            'btnTitle'          => fpLanguage::returnLanguageConstant(LANG_MOD_FPCMEXAMPLE),
            'btnCssId'          => "fpmoduleexample",
            'btnDescription'    => "<span class=\"fa fa-spinner\"></span>",
            'btnTag'            => "span"
        );
    }  
    
    function fpmoduleexample_addEditorParagraph() {
        return array(
            'optionDescription' => fpLanguage::returnLanguageConstant(LANG_MOD_FPCMEXAMPLE),
            'optionTag'         => "div"
        );
    }   
    
    function fpmoduleexample_addEditorFontSize() {
        return array(
            array(
                'optionDescription' => '18pt',
                'optionSize'        => '18'
            ),
            array(
                'optionDescription' => '20pt',
                'optionSize'        => '20'
            ),
            array(
                'optionDescription' => '22pt',
                'optionSize'        => '22'
            )            
        );
    }     
    
    function fpmoduleexample_addEditorExtendedField() {
        $newFields      = array();
        $newFields[]    = array(
            'fieldType'         => "text",
            'fieldName'         => "fpmoduleexample",
            'fieldDescription'  => fpLanguage::returnLanguageConstant(LANG_MOD_FPCMEXAMPLE),
            'fieldDefaultValue' => fpLanguage::returnLanguageConstant(LANG_MOD_FPCMEXAMPLE)
        );
        $newFields[]    = array(
            'fieldType'         => "select",
            'fieldName'         => "fpmoduleexample2",
            'fieldDescription'  => fpLanguage::returnLanguageConstant(LANG_MOD_FPCMEXAMPLE).' 2',
            'fieldDefaultValue' => 2,
            'fieldOptions'      => array('Option 1' => 1, 'Option 2' => 2, 'Option 3' => 3)
        );        
        
        return $newFields;
    }
    
    function fpmoduleexample_onLinkAutocomplete() {
        return array(
            array('label' => 'Google', 'value' => 'https://google.de'),
            array('label' => 'Yahoo', 'value' => 'https://yahoo.de'),
            array('label' => 'Bing', 'value' => 'https://bing.de')
            
        );
    }
    
?>