<?php
    /**
      * FanPress CM Module Example 
      * @author Stefan Seehafer (fanpress@nobody-knows.org)
      * @copyright 2012
      *
      */

    function fpinegrationassitant_acpRun() {
        fpModules::includeModuleTemplate("fpinegrationassitant", "assistantForm", array('systemurl' => fpConfig::get('system_url')));        
    }

    function fpinegrationassitant_addToNavigationMain() {   
        return array(
            array(
                'icon'  => 'ui-icon ui-icon-circle-check',
                'descr' => fpLanguage::returnLanguageConstant(LANG_MOD_INTEGRATEASSITANT_NAVIGATION),
                'link'  => fpModules::getModuleStdLink("fpinegrationassitant", '', true)
            )
        );                
    }    
    
?>