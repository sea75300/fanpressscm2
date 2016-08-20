<?php
    /**
      * FanPress CM Module Example 
      * @author Stefan Seehafer (fanpress@nobody-knows.org)
      * @copyright 2012
      *
      */

     fpModules::includeLanguageFile('fpmoduleexample');

    function fpmoduleexample_feAddJs() {
        return fpModules::getModuleRelPath("fpmoduleexample")."/script.js";
    }
    
    function fpmoduleexample_feAddCss() {
        return fpModules::getModuleRelPath("fpmoduleexample")."/script.css";
    }

    function fpmoduleexample_onNewsSingleLoad($paramArray) {
       print "<p> ".__FUNCTION__." - Lorem ipsum dolor sit amet</p>";
       return $paramArray;
    }

    function fpmoduleexample_onNewsParse($content) {
        return $content.'<p>'.LANG_MOD_FPCMEXAMPLE.'</p>';
    }    
    
    function fpmoduleexample_onCommentParse($content) {        
        return $content.'<p>'.LANG_MOD_FPCMEXAMPLE.'</p>';
    }     
    
    function fpmoduleexample_onCommentSave($paramArray) {
        var_dump($paramArray);
        
        return $paramArray;
    }
    
    function fpmoduleexample_onReplaceSpamPlugin() {
        return "<input type='text' name='antispam' value='Lorem ipsum.'>";
    }
    
    function fpmoduleexample_onTestSpamAnswer($answer) {
        
        fpMessages::writeToSysLog(__FILE__.': '. $answer);
        
        return true;
    }
    
    function fpmoduleexample_onParseShareButtons($content) {

        if((time() - (int) fpModules::loadModuleConfigOption("fpmoduleexample", "dummy_value")) > 60) {
            fpMessages::writeToSysLog(__FUNCTION__.": Removing share buttons for Facebook (ID 1) and E-Mail (ID 7)!");
        }
        
        unset($content['sharebuttons'][1], $content['sharebuttons'][7]);
        
        fpModules::updateModuleConfigOption("fpmoduleexample",array('confname' => "dummy_value","confvalue" => (string) time()));
        
        return $content;
        
    }    
?>