<?php

    final class fpModule_fpRssImporter_RssImporter {
        private $fpDBcon = null;
        private $rssFeedPath = null;
        private $monthNumer = array(
                "Jan" => 1,
                "Feb" => 2,
                "Mar" => 3,
                "Apr" => 4,
                "May" => 5,
                "Jun" => 6,
                "Jul" => 7,
                "Aug" => 8,
                "Sep" => 9,
                "Oct" => 10,
                "Nov" => 11,
                "Dec" => 12                  
        );

        private static $moduleKey = "fprssimporter";


        /**
         * Der Konstruktor
         * @param string $rssFeedPath
         */
        public function __construct($rssFeedPath, $fpDBcon) {
            $this->fpDBcon = $fpDBcon;
            $this->rssFeedPath = $rssFeedPath;
        }

        public static function getModulKey() {
            return self::$moduleKey;
        }

        /**
         * Import starten
         */
        public function startImport() {
            $xmlData = file_get_contents($this->rssFeedPath, "r");
            $xml = new SimpleXMLElement($xmlData);         
            $xmlItems = $xml->channel->item;
            
            $counter = 0;
            
            if(!defined('FPSYSVERSION')) { define('FPSYSVERSION', fpConfig::get('system_version')); }

            $importerCatObj = new fpArticleCategory();
            
            foreach($xmlItems AS $xmlItem) {
                $categories = $xmlItem->xpath('category');
                $newsCategories = array();
                
                foreach ($categories as $category) {
                    if(strtolower(mb_detect_encoding($category)) == 'utf-8') { $category = utf8_decode($category); }
                    
                    $importerCatObj->setCatname($category);
                    $importerCatObj->setIconPath('');
                    $importerCatObj->setMinlevel(3);
                    
                    if($importerCatObj->save()) fpMessages::writeToSysLog("added cateory $category from RSS feed");

                    $dbData = $this->getCategoryByName($category);
                    $newsCategories[] = $dbData->id;
                }

                $data = get_object_vars($xmlItem);

                $text = (string) $xmlItem->description;
                if(strtolower(mb_detect_encoding($text)) == 'utf-8') { $text = utf8_decode($text); }                
                if(strtolower(mb_detect_encoding($data['title'])) == 'utf-8') { $data['title'] = utf8_decode($data['title']); }

                if((isset($xmlItem->author))) {
                    $authorRss = $xmlItem->author;
                } else {
                    $creator = $xmlItem->children("http://purl.org/dc/elements/1.1/");
                    if(isset($creator->creator)) {
                        $authorRss = $creator->creator;
                    }
                }

                if(isset($authorRss) && !$this->userExist($authorRss)) {
                    $author = str_replace('@', '_', $authorRss);
                    $email = (strpos($authorRss, '@') !== false) ? $authorRss : '';

                    if(strtolower(mb_detect_encoding($author)) == 'utf-8') { $author = utf8_decode($author); }

                    $fpUser = new fpAuthor();
                    $fpUser->setUserName($author);
                    $fpUser->setEmail($email);
                    $fpUser->setUserRoll(2);
                    $fpUser->setDisplayName($author);
                    $fpUser->setPassword($author);
                    $fpUser->setUserMeta(array());
                    $fpUser->setRegistertime(time());

                    $fpUser->disablePasswordSecCheck();
                    
                    if($fpUser->save()) {
                        fpMessages::writeToSysLog("added user $author from RSS feed");
                    }
                }

                if(isset($authorRss) && $this->userExist($authorRss)) {
                    $user = $this->getUserByUsrName($authorRss); 
                    $userId = $user->id;
                } else {
                    $userId = fpConfig::currentUser('id');
                }
                
                $fpNewsPost = new fpArticle();
                $fpNewsPost->setTitel($data['title']);
                $fpNewsPost->setContent($text);
                $fpNewsPost->setCategory((!empty($newsCategories)) ? implode(';', $newsCategories) : '1');
                $fpNewsPost->setArchived(0);
                $fpNewsPost->setCommentsActive(1);
                $fpNewsPost->setPreview(0);
                $fpNewsPost->setPinned(0);
                $fpNewsPost->setAuthor($userId);
                $fpNewsPost->setWrittentime($this->createTimeStamp($data['pubDate']));
                $fpNewsPost->save();
                       
                $counter++;
            }

            fpMessages::showSysNotice(str_replace("%records%", "$counter", LANG_MOD_RSSIMPORTER_COUNT));
        }

        private function userExist($username) {
            $result = $this->fpDBcon->count(
                    "authors",
                    "id",
                    "sysusr like '".$username."'"
            );
            if($result > 0) { return true; } else { return false; }            
        }

        /**
         * Abkürzung des Monats
         * @param string $monthString Abkürzung Monat, bspw. Jan/Feb/Mar/etc.
         */
        private function getMonthNumber($monthString) {
            return $this->monthNumer[$monthString];
        }

        /**
         * 
         * @param string $rssDateTime pubDate aus RSS-Feed
         * @return int
         */
        private function createTimeStamp($rssDateTime) {
            $dateArr = explode(" ", $rssDateTime);
            $timeArr = explode(":", $dateArr[4]);            
            return mktime($timeArr[0], $timeArr[1], $timeArr[2], $this->getMonthNumber($dateArr[2]), $dateArr[1],$dateArr[3]);            
        }
        
        /**
         * 
         * @param string $name
         * @return array
         */
        private function getCategoryByName($name) {
            $sql = "SELECT id FROM ".FP_PREFIX."_categories cat WHERE catname like '".$name."';";

            $result = $this->fpDBcon->query($sql);	  
            return $this->fpDBcon->fetch($result);            
        }

        /**
         * Daten eines bestimmten Benutzers auslesen, Suche per Username
         * 
         * @param string $sysusr
         * @return object
         */  
        public function getUserByUsrName($sysusr) {           
            $sql = "
                SELECT aut.id, aut.email, aut.registertime, aut.sysusr, aut.name, aut.usrlevel, aut.usrmeta
                FROM ".FP_PREFIX."_authors aut, ".FP_PREFIX."_usrlevels ulv
                WHERE aut.usrlevel = ulv.id AND aut.sysusr like '".$sysusr."'
            "; 
            $result = $this->fpDBcon->query($sql);	  
            if($result === false) { $this->fpDBcon->getError();return false; }
                        
            return $this->fpDBcon->fetch($result);
        }         
    }
?>