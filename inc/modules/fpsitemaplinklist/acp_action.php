<?php
    function loadActiveList() {
        $data = array();
        
        if(file_exists(__DIR__.'/activelist.php')) {
            $data = json_decode(file_get_contents(__DIR__.'/activelist.php',false,null,15));
        }
        
        return $data;
    }

    function fpsitemaplinklist_onLinkAutocomplete() {
        $linkList   = array();
        
        foreach(loadActiveList() as $item) {
            $linkList[] = array('label' => $item,'value' => $item);
        };

        return $linkList;
    }

    function fpsitemaplinklist_addToNavigationMain() {
         return array(
             array(
                 'icon'  => 'ui-icon ui-icon-extlink',
                 'descr' => fpLanguage::returnLanguageConstant(MOD_SITEMAPLIST_HEADLINE),
                 'link'  => fpModules::getModuleStdLink(MOD_SITEMAPLIST_KEY, '', true)                
             )            
         );       
    }

    function fpsitemaplinklist_acpRun() {

        $xmlfilpath = fpModules::loadModuleConfigOption(MOD_SITEMAPLIST_KEY, 'xmlfilepath');

        if(isset($_POST['btncfesave']) && isset($_POST['xmlfilepath']) && isset($_POST['activelinks'])) {

            $xmlfilpathNew = realpath(fpSecurity::fpfilter(array('filterstring' => $_POST['xmlfilepath'])));

            if($xmlfilpathNew) {
                fpModules::updateModuleConfigOption(
                     MOD_SITEMAPLIST_KEY,
                     array(
                         'confname' => 'xmlfilepath',
                         "confvalue" => $xmlfilpathNew
                     )
                 );
                
                $xmlfilpath = $xmlfilpathNew;
            }
            
            $linklist = fpSecurity::fpfilter(array('filterstring' => $_POST['activelinks']));
            $contant  = "<?php die(); ?>".PHP_EOL.json_encode($linklist);
                    
            file_put_contents(__DIR__.'/activelist.php',$contant);

        }

        if(!file_exists($xmlfilpath)) {
            fpMessages::showErrorText(MOD_SITEMAPLIST_XMLPATH_NOTFOUND);
        } else {
            fpMessages::showSysNotice(MOD_SITEMAPLIST_XMLPATH_FOUND);
        }
        
        $linkList = array();
        if(file_exists($xmlfilpath)) {
            $xmlObject = new SimpleXMLElement(file_get_contents($xmlfilpath));
            foreach($xmlObject->children() as $child) {
                $linkList[md5($child->loc->__toString())] = $child->loc->__toString();
            };            
        }
        
        $params = array(
            'tabsHead'      => fpLanguage::returnLanguageConstant(MOD_SITEMAPLIST_XMLPATH),
            'tabsListHead'  => fpLanguage::returnLanguageConstant(MOD_SITEMAPLIST_ENABLEDLINKS),
            'tabsListCheck' => fpLanguage::returnLanguageConstant(MOD_SITEMAPLIST_ENABLEDLINKS_CHECK),
            'defaultPath'   => $xmlfilpath,
            'links'         => $linkList,
            'active'        => array_map('md5', loadActiveList())
        );

        fpModules::includeModuleTemplate(MOD_SITEMAPLIST_KEY, 'admin', $params);

    }

?>