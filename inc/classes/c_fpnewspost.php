<?php
    /**
     * FanPress CM posts class
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    final class fpNewsPost {
          
        private $table = 'newsposts';

        private $fpDBcon;

        /**
         * Der Konstruktor
         * @return void
         */
        public function __construct($fpDBcon) {
            $this->fpDBcon = $fpDBcon;
        }

        /**
         * Der Destruktor
         */
        public function __destruct() {
            $this->fpDBcon = null;
        }

        /**
         * News aus Datenbank laden
         * 
         * @param string $mode Modus um News auszulesen
         * mögliche Modi
         *      'editmode'    - alle News in Edit-Liste laden
         *      'editactive'  - alle aktiven News in Edit-Liste laden
         *      'editarchive' - alle archivierten News in Edit-Liste laden
         *      'all'         - alle News laden
         *      'rss'         - alle News für RSS-Feed laden
         *      'archive'     - alle News für Archiv laden
         *      'pinnedonly'  - nur gepinnte News laden
         * 
         * @param array $dataArray Array mit Infos, um die Ausgabe einzustellen
         * Struktur:
         *      'previews'       => Status ob Entwurf
         *      'startfrom'      => ID einer News, von der aus gestartet werden soll (Seiten-Navigation)
         *      'showlimit'      => Limit an angezeiten News pro Seite
         *      'newsid'         => ID einer News,
         *      'deleted         => geöschte News laden ja/nein
         * @return boolean
         */
        public function getNews($mode,$dataArray) {
            
            $threeTabModes = array("editmode", "editactive", "editarchive");

            if(in_array($mode, $threeTabModes)) {
                $fields = "np.id, np.titel, np.content, np.writtentime, np.editedtime, np.category, np.is_archived, np.ispreview, np.author, np.is_pinned, np.comments_active, np.is_deleted, a.name";
                $tables  = array($this->table.' np', 'authors a', 'categories c');
            } elseif($mode == 'rss') {
                $fields = "np.id, np.titel, np.content, np.writtentime, np.category, a.name";
                $tables = array($this->table.' np', 'authors a');                
            } else {
                $fields = "np.id, np.titel, np.content, np.writtentime, np.editedtime, np.category, np.author, np.is_pinned, np.comments_active, a.name";
                $tables = array($this->table.' np', 'authors a');
            }

            $where = '';
            
            switch($mode) {
                case "editmode" :
                    $where .= "np.author = a.id ";			
                break;
                case "editactive" :
                case "list" :
                    $where .= "np.author = a.id AND np.is_archived = 0 ";
                break;
                case "editarchive" :
                    $where .= "np.author = a.id AND np.is_archived = 1 ";
                break;                
                case "all" :
                case "rss" :
                    $where .= "np.author = a.id AND np.ispreview = '".$dataArray['previews']."' AND np.is_archived = 0 ";
                break;
                case "pinnedonly" :
                    $where .= "np.author = a.id AND np.ispreview = '".$dataArray['previews']."' AND np.is_archived = 0 AND np.is_pinned = 1 ";
                break;                
                case "archive" :
                    $where .= "np.author = a.id AND np.ispreview = '".$dataArray['previews']."' AND np.is_archived = 1 ";
                break; 
                default :
                    $where .= "np.author = a.id AND np.id = '".$dataArray['newsid']."' ";		
                break;
            }

            $dataArray['deleted'] = isset($dataArray['deleted']) ? $dataArray['deleted'] : 0;
            $where .= " AND is_deleted = {$dataArray['deleted']} ";
            
            $limitModes = array('list', 'all', 'pinnedonly', 'rss', 'archive');

            if(in_array($mode, $limitModes)) {
                $where .= "ORDER BY np.".fpConfig::get('sort_news')." ".fpConfig::get('sort_news_order');
            } elseif(!is_int($mode)) {
                $where .= "ORDER BY np.writtentime DESC";
            }

            if(in_array($mode, $limitModes)) {
                $where .= " LIMIT ".$dataArray['startfrom'].", ".$dataArray['showlimit'];             
            }
            
            $result  = $this->fpDBcon->select($tables, $fields, $where, null, true);
            if($result === false) { $this->fpDBcon->getError();return false; }
            
            return ($mode == "single") ? $this->fpDBcon->fetch($result) : $this->fpDBcon->fetch($result, true);
        }

        /**
         * Sucht in Titel und/oder Newstext nach Zeichenfolge
         * @param string $textToSearch Text, nachdem gesucht werden soll
         * @param string $mode Such-Modus
         * @return mixed
         * @since FPCM2.1
         */
        public function searchOrFilterNews($textToSearch, $authorToSearch, $categoryToSearch, $mode, $searchFn, $dateTimeFrom, $dateTimeTo) {
            $where = '';
            
            $where .= ($authorToSearch > 0)
                    ? "a.id = ".(int) $authorToSearch." AND np.author = ".(int) $authorToSearch
                    : "np.author = a.id";

            $where .= ($categoryToSearch > 0)
                    ? " AND np.category LIKE '%".$categoryToSearch."%' AND c.id = ".$categoryToSearch
                    : " AND np.category = c.id";

            if($textToSearch != "") {
                $textToSearch = $this->fpDBcon->parseSingleQuotes($textToSearch);
                
                $where .= ($mode == 0)
                        ? " AND np.titel LIKE '%".utf8_decode($textToSearch)."%'"
                        : " AND np.content LIKE '%".utf8_decode($textToSearch)."%'";
            }
            
            if($dateTimeFrom != "" && strpos($dateTimeFrom, '.') !== false) {
                $dateTimeFrom = explode('.', $dateTimeFrom);
                $dateTimeFrom = mktime(0, 0, 0, (int) $dateTimeFrom[1], (int) $dateTimeFrom[0], (int) $dateTimeFrom[2]);
                $where .= " AND np.writtentime >= ".$dateTimeFrom;   
            }
            if($dateTimeTo != "" && strpos($dateTimeTo, '.') !== false) {
                $dateTimeTo = explode('.', $dateTimeTo);
                $dateTimeTo = mktime(0, 0, 0, (int) $dateTimeTo[1], (int) $dateTimeTo[0], (int) $dateTimeTo[2]);
                $where .= " AND np.writtentime <= ".$dateTimeTo;   
            }                

            switch ($searchFn) {
                case 1 :
                    $where .= " AND np.is_archived = 0";
                break;
                case 2 :
                    $where .= " AND np.is_archived = 1";
                break;
            }

            $where  .= " AND is_deleted = 0 ORDER BY np.writtentime DESC;";
            $fields  = 'np.id, np.titel, np.content, np.writtentime, np.editedtime, np.category, np.is_archived, np.ispreview, np.author, np.is_pinned, np.comments_active, a.name';            
            $tables  = array($this->table.' np', 'authors a', 'categories c');
            
            $result  = $this->fpDBcon->select($tables, $fields, $where);            
            
            if($result === false) { $this->fpDBcon->getError();return false; }
            return $this->fpDBcon->fetch($result, true);
        }

        /**
         * Kategorien laden
         * @param bool $fetchAll
         * @param int $id
         * @return type
         */
        public function getCategories($fetchAll = false, $id = null) {
            $fpCategory = new fpCategory($this->fpDBcon);
            if(is_null($id)) {
                return $fpCategory->getCategories("all", $fetchAll);
            } else {
                return $fpCategory->getCategories($id, $fetchAll);
            }
        }

        /**
         * Prüft, ob Kommentare für diese News aktiv sind oder nicht
         * @param int $newsid ID der News
         * @return boolean
         * @since FPCM2.2
         */
        public function commentsEnabled($newsid) {
            $article = new fpArticle($newsid);

            if ($article->getCommentsActive() == 1 || $article->getCommentsActive() == 2) return true;
            
            return false;
        }        

        /**
         * Anzahl an Newsposts in Datenbank ermitteln
         * kann eingeschrönkt werden auf alle und nur Archiv
         * 
         * @param string $status Status der News, 'active' (Standard) oder 'archive' möglich
         * @return int
         */
        public function countNewsPosts($status = "active") {                
            if($status == "archive") {                    
                return $this->fpDBcon->count('newsposts', '*', 'is_archived = 1');                    
            } else {
                return $this->fpDBcon->count('newsposts', '*', 'is_archived = 0');
            }
        }

        /**
         * verschiebt News in Archiv
         * 
         * @param int $newsid ID der News
         * @return mixed
         */
        public function setNewsToArchive($newsid){           
            
            $article = new fpArticle($newsid);
            
            if($article->isPinned()) $article->setPinned(0);

            $article->setArchived(1);
            return $article->update();                 
        }

        /**
         * News pinnen oder loslösen
         * 
         * @param type $newsid
         * @return boolean
         */
        public function pinNews($newsid){            
            $article = new fpArticle($newsid);            
            if(!$article->isArchived() && !$article->isPreview()) {                
                $pinnedStatus = $article->isPinned() ? 0 : 1;
                
                $article->setPinned($pinnedStatus);
                $article->update();
                
                return $pinnedStatus;
            }
            
            return -1;
        }

        /**
         * Kommentare für News aktivieren/deaktivieren
         * 
         * @param type $newsid
         * @return boolean
         */
        public function enableDisableCommentsForNews($newsid){
            $article = new fpArticle($newsid);
            if(!$article->isArchived() && !$article->isPreview()) {                
                $status = $article->getCommentsActive() ? 0 : 1;
                
                $article->setCommentsActive($status);
                $article->update();
                
                return $status;
            }

            return -1;
        }            

        /**
         * News löschen
         * 
         * @param int $newsid ID der News
         * @return mixed
         */
        public function deleteNews($newsid) {
            $article = new fpArticle($newsid);
            $article->delete();              
        }

        /**
         * Papierkorb leeren
         * 
         * @param int $newsids News-IDs
         * @since FPCM2.5
         */
        public function clearTrash() {
            
            $newslist = $this->fpDBcon->fetch($this->fpDBcon->select($this->table, 'id', 'is_deleted = 1'));
            
            if(is_array($newslist) && count($newslist)) {
                foreach ($newslist as $news) {
                    $article = new fpArticle($news->id);                
                    $revisionIds = array_keys($article->getRevisions());                
                    $article->deleteRevisions($revisionIds);
                }                
            }
            
            $result = $this->fpDBcon->delete($this->table, 'is_deleted = 1');
            if($result === false) { $this->fpDBcon->getError();return false; }

            unset($result);           
        }
        

        /**
         * Inhalt aus Papierkorb wiederherstellen
         * 
         * @param int $newsids News-IDs
         * @since FPCM2.5
         */
        public function restoreFromTrash($newsids) {
            $result = $this->fpDBcon->update($this->table, array('is_deleted'), array(0), 'id IN ('.  implode(',', $newsids).') AND is_deleted = 1');
            
            if($result === false) { $this->fpDBcon->getError();return false; }

            unset($result);           
        }         

        /**
         * alle zum "jetzt freischalten" vorgemerkten News freischalten
         * @param int $newsid
         * @return mixed
         */
        public function openPostponedNews($newsids) {
            $result = $this->fpDBcon->update($this->table, array('ispreview'), array(0), 'id IN ('.  implode(',', $newsids).')');
            if($result === false) { $this->fpDBcon->getError();return false; }

            unset($result);
        }

        /**
         * Prüft, ob news zum automatischen freischalten vorhanden sind
         * @return mixed
         */
        public function checkPostponedNews() {
            $result = $this->fpDBcon->select($this->table, 'id, titel', 'ispreview = 2 AND writtentime <= ?', array(time()));
            if($result === false) { $this->fpDBcon->getError();return false; }
            $rows    = $this->fpDBcon->fetch($result,true);

            if(count($rows)) {
                $openIds = array();
                foreach ($rows AS $row) {
                    $openIds[] = $row->id;
                    $this->createTweet($row->titel,$this->createShortLink(fpConfig::get("system_url")."?fn=cmt&nid=".$row->id));
                }
                
                $this->openPostponedNews($openIds);
                $fpCache = new fpCache();
                $fpCache->cleanup();                
            }

            unset($fpCache, $result, $rows, $row);
        }	

        /**
         * Erzeugt neuen Tweet beim veröffentlichen einer News
         * 
         * @param string $ntitle
         * @param string $link
         */
        public function createTweet($ntitle,$link) {
            $twitterConf = fpConfig::get();

            if($twitterConf["twitter_access_token"] != "" && $twitterConf["twitter_access_token_secret"] != "") {
                $ntitle_tweet = html_entity_decode(htmlspecialchars_decode($ntitle), ENT_QUOTES, "UTF-8"); 

                $moduleReturn = fpModuleEventsAcp::runOnCreateTweet(array('tweetText' => $ntitle_tweet, 'linkText' => $link));
                if(!empty($moduleReturn)) {
                    if(isset($moduleReturn["tweetText"])) { $ntitle_tweet = $moduleReturn["tweetText"]; }
                    if(isset($moduleReturn["linkText"])) { $link = $moduleReturn["linkText"]; }
                }                    

                $tweet = new tmhOAuth(array(
                    'consumer_key'    => $twitterConf["twitter_consumer_key"],
                    'consumer_secret' => $twitterConf["twitter_consumer_secret"],
                    'user_token'      => $twitterConf["twitter_access_token"],
                    'user_secret'     => $twitterConf["twitter_access_token_secret"]
                ));

                $title_len = strlen($ntitle_tweet);
                $link_len  = strlen($link);

                if($title_len >= 140)  {
                    $ntitle_tweet = substr($ntitle_tweet,0,140);

                    $new_len = 140 - ($link_len + 6);
                    $ntitle_tweet = substr($ntitle_tweet,0,$new_len);
                    $ntitle_tweet .= "...";
                }
                $code = $tweet->request('POST', $tweet->url('1.1/statuses/update'), array(
                        'status' => $ntitle_tweet.': '.$link
                ));

                if ($code != 200) {
                    $responseData = json_decode($tweet->response['response'], true);
                    
                    $twitterErrors = array();
                    if (isset($responseData['errors'])) {
                        foreach ($responseData['errors'] as $value) {
                            $twitterErrors[] = $value['message'].' (ERROR CODE: '.$value['code'].')'.'<br>';
                        }
                    }
                    
                    
                    if (!count($twitterErrors)) {
                        $twitterErrors[] = 'Unknown error...';
                    }
                    $twitterErrors = implode('<br>', $twitterErrors);
                    
                    fpMessages::showErrorText(LANG_WRITE_TWITTER_ERR."<br>\n".$twitterErrors);
                    fpMessages::writeToSysLog($twitterErrors);
                }
            }	

            unset($title_len, $link_len, $link, $ntitle_tweet, $code);
        }

        /**
         * Erzeugt neuen Kurzlink
         * 
         * @param string $alink Link zur News
         * @return string
         */
        public function createShortLink($alink) {
            $moduleReturn = fpModuleEventsAcp::runOnCreateShortLink($alink);                
            if(!empty($moduleReturn)) {
                if(isset($moduleReturn["replaceIsGd"])) {
                    return (string) $moduleReturn["linkText"];
                }
            } else {
                try {
                    $tlfile = @fopen("http://is.gd/create.php?format=simple&url=".urlencode($alink), "rb");	
                    
                    if(!is_resource($tlfile))  return $alink;
                    
                    $tlfile_content = @fgets($tlfile);

                    if(empty($tlfile_content)) return $alink;

                    return strip_tags($tlfile_content);	
                } catch (Exeption $e) {
                    fpMessages::showErrorText($e);
                }                    
            }
        }

        /**
         * ist Eingabeformular leer
         * @return boolean true, wenn Formularteil leer ist
         */
        public function isFormEmpty() {

            if(empty($_POST["titel"]) || empty($_POST["cat"]) || empty($_POST["newstext"])) {
                return true;
            }

            return false;
        }

        /**
         * Prüft, ob eine News vorhanden ist
         * 
         * @param int $newsid ID einer News
         * @return boolean true, wenn news vorhanden
         */		
        public function existNews($newsid) {                   
            $counted = $this->fpDBcon->count("newsposts", "id", "id = '".$newsid."'");            
            return $counted == 1 ? true : false;
        }

        /**
         * Räumt HTML-Code einer News auf
         * Sicherheitsvorkehrung nach Einführung von TinyMCE ab FPCM 1.7.0
         * 
         * @param string $newstext
         * @return string
         */
        public function cleanPost($newstext) {
            $replaces_br  = array("<br />","<br>");
            $replaces_tag = array(
                "<p>","</p>","<li>","</li>","<ul>","<ol>","</ul>","</ol>","<blockquote>","</blockquote>",
                "<div>","</div>","<tr>","</tr>","<td>","</td>","<th>","</th>","<table>","</table>",
                "<tbody>","</tbody>","<h1>","</h1>","<h2>","</h2>","<h3>","</h3>","<h4>","</h4>",
                "<h5>","</h5>","<h6>","</h6>"                    
            );

            foreach($replaces_br AS $br) {
                foreach ($replaces_tag as $tag) {
                    $newstext = str_replace($tag.$br,$tag,$newstext);
                }
            }

            unset($replaces_br, $replaces_tag, $br, $tag);

            return $newstext;
        }	

        /**
         * Link zum direkten Bearbeiten eienr News anzeigen
         * @param int $newsid
         * @param fpSystem $fpSystem
         * @return string
         */
        public function showNewsEditLink($newsid) {
            if(fpConfig::currentUser() && fpSecurity::checkPermissions ("editnews")) {
                $acpPath = "http://".fpSecurity::Filter2($_SERVER["HTTP_HOST"]).FP_ROOT_DIR."acp/";
                return "<div class=\"fp-news-toolbar\">\n<a href=\"".$acpPath."editnews.php?fn=edit&nid=".$newsid."\" target=\"_blank\">".fpLanguage::returnLanguageConstant(LANG_EDIT)."</a>\n</div>";                
            }
        }

        /**
         * parst News in Template
         * 
         * @param object $row News-Objekt aus Datenbank-Abfrage
         * @param string $nptemplate Template-Code
         * @param int $cmtcount Anzahl an News
         * @return string
         */
        public function parseNews($row,$nptemplate,$cmtcount,$showEditLink=true,$showPinnedMsg=false) {
            $editorConf = fpConfig::get('system_editor');

            // News-Überschrift parsen
            if($editorConf == "classic" || $row->writtentime <= FPSPECIALCHARTIMEFIX) {
                $row->titel = htmlspecialchars_decode(htmlentities($row->titel, ENT_QUOTES, FPSPECIALCHARSET));
            }

            if(version_compare(phpversion(), '5.5.0', '>') && $row->writtentime <= FPSPECIALCHARTIMEFIX) {
                $npparsed = str_replace ("%news_headline%", $row->titel,$nptemplate);
            } else {
                $npparsed = str_replace ("%news_headline%", htmlspecialchars_decode($row->titel),$nptemplate);
            }

            // News-Text parsen
            $newstext = $row->content;
            $newstext = str_replace(FP_ROOT_DIR.'upld/', FPUPLOADFOLDERURL, $newstext);
            $newstext = str_replace ("<p><readmore>", "<readmore>",$newstext);
            $newstext = str_replace ("</readmore></p>", "</readmore>",$newstext);
            $newstext = str_replace ("<img", "<img class=\"fp-news-img\"",$newstext);

            // readmore-Block parsen
            $readmoretxt_b = "<div class=\"fp-readmore-block\"><a href=\"#\" class=\"fp-readmore-block-link\">".fpLanguage::returnLanguageConstant(LANG_LINK_READMORE)."</a><p class=\"fp-readmore-block-text\">";                
            $newstext = str_replace ("<readmore>", $readmoretxt_b,$newstext);
            $newstext = str_replace ("</readmore>", "</p></div>",$newstext);                

            if (strpos($newstext,"<br>") === false || strpos($newstext,"<br />") === false) {	  
                $newstext = nl2br($newstext);
                $newstext = $this->cleanPost($newstext);
            }  

            if($editorConf == "classic" || (defined('FPSPECIALCHARTIMEFIX') && $row->writtentime <= FPSPECIALCHARTIMEFIX)) {
                $newstext = htmlspecialchars_decode(htmlentities($newstext, ENT_COMPAT | ENT_HTML401, FPSPECIALCHARSET));
            }               

            // Kategory-Icon und -Name parsen
            $cat_array = explode(";",$row->category);
            $catcount  = count($cat_array);

            $iconpattern = "";
            $catpattern  = "";

            $fpCategoryObj = new fpCategory($this->fpDBcon);

            for($i=0;$i<$catcount;$i++) {	  
                $catinfo = $fpCategoryObj->getCategories($cat_array[$i]);
                if(isset($catinfo->icon_path)) {
                    $iconpattern  = $iconpattern."<img class=\"fp-category-icon\" src=\"".htmlspecialchars_decode($catinfo->icon_path)."\" alt=\"".htmlspecialchars_decode($catinfo->catname)."\"> ";                    
                }

                if (!isset($catinfo->catname)) continue;
                
                $catpattern .= "<span>".htmlspecialchars_decode($catinfo->catname);                    
                if($i < $catcount-1) { $catpattern .= ", "; }
                $catpattern .= "</span>";                    

            }

            $markerReplaceArray = array(
                "%news_text%" => $newstext,
                "%caticon%" => $iconpattern,
                "%category%" => $catpattern,
                "%author%" => $row->name,
                "%date%" => date(fpConfig::get('timedate_mask'), $row->writtentime),
                "[newslink]" => "<a href=\"".fpConfig::get("system_url")."?fn=cmt&nid=".$row->id."\">",
                "[/newslink]" => "</a>",

            );     

            // Edit-Datum parsen
            if($row->editedtime != 0) {
                $markerReplaceArray["%edited%"] = fpLanguage::returnLanguageConstant(date(fpConfig::get('timedate_mask'), $row->editedtime));
            } else {
                $markerReplaceArray["%edited%"] = "";
            }

            // Kommentar-Link & Anzahl parsen
            if($row->comments_active == 1) {
                $markerReplaceArray["%commemts%"] = "<a href=\"".isset($SELF_PHP)."?fn=cmt&nid=".$row->id."#commentformbox\">".fpConfig::get('comlinkdescr')."</a>";
                $markerReplaceArray["%comment_count%"] = $cmtcount;
            } else {
                $markerReplaceArray["%commemts%"] = fpLanguage::returnLanguageConstant(LANG_FE_NOCOMMENTS);
                $markerReplaceArray["%comment_count%"] = "";
            }

            $markerReplaceArray['%status_pinned%'] = ($row->is_pinned && $showPinnedMsg) ? "<span class=\"fp-news-pinned-status\">".fpLanguage::returnLanguageConstant(LANG_NEWS_STATUSPINNED)."</span>" : '';

            $npparsed = fpTemplates::replaceMarker($markerReplaceArray, $npparsed);

            if(fpConfig::get('showshare') == 1) {
                $npparsed = $this->parseShareButtons($npparsed, $row->id,$row->titel);
            } else {
                $npparsed = str_replace ("%sharebuttons%", "",$npparsed);
            }

            // Smilies parsen
            $npparsed = $this->parseSmilyesInPost($npparsed);

            $npparsed = $this->parsePlayerInPost($npparsed);

            $npparsed = fpModuleEventsFE::runOnNewsParse($npparsed);                

            $npparsed = ($showEditLink) ? $this->showNewsEditLink($row->id).$npparsed : $npparsed;

            unset($row, $readmoretxt_b, $cmtcount, $fpCategoryObj);

            return $npparsed;
        }

        /**
         * Parst Smilies in News-Post
         * 
         * @param string $npparsed bereits gepaster Code
         * @return string
         */
        private function parseSmilyesInPost($npparsed) {
            $fpFileSystem = new fpFileSystem($this->fpDBcon);

            $rows = $fpFileSystem->getSmilies();

            foreach($rows AS $row) {
                $smilieimg = "<img src=\"".FP_ROOT_DIR."img/smilies/".$row->sml_filename."\" alt=\"".$row->sml_code."\">";
                $npparsed = str_replace ($row->sml_code, $smilieimg,$npparsed);
            }   

            unset($fpFileSystem, $rows, $row, $smilieimg);
            return $npparsed;
        }

       /**
        * Parst Audio-Player in News-Post
        * 
        * @param string $npparsed bereits gepaster Code
        * @return string
        */
        private function parsePlayerInPost($npparsed) {
            $playerbcode = "<object type=\"application/x-shockwave-flash\" data=\"".FP_ROOT_DIR."player.swf\" id=\"audioplayer1\" height=\"20\" width=\"290\">\n<param name=\"movie\" value=\"player.swf\">\n<param name=\"FlashVars\" value=\"playerID=audioplayer1&soundFile=";
            $npparsed = str_replace ("<player>", $playerbcode,$npparsed);
            $playerecode = "\"><param name=\"quality\" value=\"high\"><param name=\"menu\" value=\"false\"><param name=\"wmode\" value=\"transparent\"></object>\n";
            return str_replace ("</player>", $playerecode,$npparsed);
        }   

        /**
         * parst Share-Buttons in Temnplate
         * @param string $npparsed bereits gepaster Code
         * @param int $npid ID der News
         * @param string $nptitle Titel der News
         * @return string
         */
        private function parseShareButtons($npparsed,$npid,$nptitle) {
            $fpSystem = new fpSystem($this->fpDBcon);

            $shareulr = urlencode(fpConfig::get("system_url")."?fn=cmt&amp;nid=".$npid);

            $sharecode  = array();

            $sharecode[] = "<ul class=\"fp-share-buttons\" style=\"list-style:none;\">";
            $sharecode[] = "<li><a href=\"https://www.facebook.com/sharer/sharer.php?u=$shareulr&t=$nptitle\" target=\"_blank\"><img src=\"".FP_ROOT_DIR."img/shareicons/default/facebook.png\"></a></li>";
            $sharecode[] = "<li><a href=\"https://twitter.com/intent/tweet?source=$shareulr&text=$nptitle\" target=\"_blank\" title=\"Tweet\"><img src=\"".FP_ROOT_DIR."img/shareicons/default/twitter.png\"></a></li>";
            $sharecode[] = "<li><a href=\"https://plus.google.com/share?url=$shareulr\" target=\"_blank\" title=\"Share on Google+\"><img src=\"".FP_ROOT_DIR."img/shareicons/default/googleplus.png\"></a></li>";
            $sharecode[] = "<li><a href=\"http://www.tumblr.com/share?v=3&u=$shareulr&t=$nptitle&s=\" target=\"_blank\" title=\"Post to Tumblr\"><img src=\"".FP_ROOT_DIR."img/shareicons/default/tumblr.png\"></a></li>";
            $sharecode[] = "<li><a href=\"http://pinterest.com/pin/create/button/?url=$shareulr&description=$nptitle\" target=\"_blank\" title=\"Pin it\"><img src=\"".FP_ROOT_DIR."img/shareicons/default/pinterest.png\"></a></li>";
            $sharecode[] = "<li><a href=\"http://www.reddit.com/submit?url=$shareulr&title=$nptitle\" target=\"_blank\" title=\"Submit to Reddit\"><img src=\"".FP_ROOT_DIR."img/shareicons/default/reddit.png\"></a></li>";
            $sharecode[] = "<li><a href=\"mailto:?subject=$nptitle&body=$shareulr\" target=\"_blank\" title=\"Email\"><img src=\"".FP_ROOT_DIR."img/shareicons/default/email.png\"></a></li>";
            $sharecode[] = "<!-- Default button icon set powered by http://simplesharingbuttons.com/ -->";
                        
            $moduleParams   = array(
                'sharebuttons'  => $sharecode,
                'description'   => $nptitle,
                'shareurl'      => $shareulr
            );
            $moduleReturn   = fpModuleEventsFE::runOnParseShareButtons($moduleParams);
            
            $sharecode      = $moduleReturn['sharebuttons'];
            
            $sharecode[]    = "</ul>";

            
            
            return str_replace ("%sharebuttons%", implode(PHP_EOL, $sharecode),$npparsed);
        }
    }
?>