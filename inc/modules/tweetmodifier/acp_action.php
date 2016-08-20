<?php
    function tweetmodifier_addToNavigation() {
        return array(
            'descr' => LANG_MOD_TWEETMOD_NAME,
            'link'  => fpModules::getModuleStdLink(MOD_TWEETMOD_MODKEY, '', true),
        );
    }
    
    function tweetmodifier_addToNavigationMain() {
        return array(
            array(
                'icon'  => 'tweetmod-fa-icon-left fa fa-twitter',
                'descr' => LANG_MOD_TWEETMOD_NAME,
                'link'  => fpModules::getModuleStdLink(MOD_TWEETMOD_MODKEY, '', true)
            )            
        );
    }          
    
    function tweetmodifier_addToHeaderCss() {
        return fpModules::getModuleRelPath(MOD_TWEETMOD_MODKEY).'/tweetmodifier.css';
    }    
    
    function tweetmodifier_onCreateHelpEntry() {
        return array(
            "helpHeadline" => fpLanguage::returnLanguageConstant(LANG_MOD_TWEETMOD_NAME),
            "helpText" => fpLanguage::returnLanguageConstant(LANG_MOD_TWEETMOD_TPL_HELP)
        );
    }

    function tweetmodifier_onCreateTweet($params) {
        $modifier   = new fpModule_TweetModifier_TweetModifier();
        $terms      = $modifier->getTerms();

        foreach ($terms as $key => $value) {
            $params['tweetText'] = preg_replace(array("/($key)/is"),  $value, $params['tweetText']);
        }

        return $params;
    }

    function tweetmodifier_acpRun() {
        if(fpConfig::currentUser('usrlevel') > 1) {
            fpMessages::showNoAccess();
        }
        
        $modifier = new fpModule_TweetModifier_TweetModifier();
        
        if(isset($_POST['addterm']) && !empty($_POST['termorg']) && !empty($_POST['termrpl'])) {
            $modifier->addTerm(array(
                'term'      => fpSecurity::Filter5($_POST['termorg']),
                'replace'   => fpSecurity::Filter5($_POST['termrpl'])
            ));
        }

        if(isset($_POST['delterm']) && isset($_POST['termdel']) && count($_POST['termdel'])) {
            $deletedTerms = $_POST['termdel'];            
            foreach ($deletedTerms as $deletedTerm) { $modifier->deleteTerm($deletedTerm); }
        }        
        
        $vars = array('terms' => $modifier->getTerms());
        
        fpModules::includeModuleTemplate(MOD_TWEETMOD_MODKEY, 'replacelist', $vars);
    }

?>