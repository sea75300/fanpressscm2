<?php
    /**
     * FanPress CM comments options
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    final class fpComments { 
        
        private $table = 'comments';


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
         * Kommentare aus Datenbank holen
         * @param int $showprivate private Kommentare anzeigen
         * @param int $nid Kommentar-ID
         * @return array
         */    
        public function getAllComments($showprivate,$nid) {
            
            if($nid == -1) {
                $where  = " 1=1 ORDER BY comment_time DESC";
                $tables = array("comments");
                $fields = "id, newsid, author_name, author_email, author_url, comment_text, comment_time, status";                
                
                $params = array();
            } else {
                $where  = !$showprivate
                        ? "cmt.newsid = ? AND np.id = ? AND cmt.status = 0 ORDER BY cmt.comment_time ASC "
                        : "cmt.newsid = ? AND np.id = ? ORDER BY cmt.comment_time ASC";
             
                $tables = array("newsposts np", "comments cmt");
                $fields = "cmt.id, cmt.newsid, cmt.author_name, cmt.author_email, cmt.author_url, cmt.comment_text, cmt.comment_time, cmt.status";                
                
                $params = array($nid, $nid);
            }
            
            $result = $this->fpDBcon->select($tables, $fields, $where, $params);
            if($result === false) { $this->fpDBcon->getError();return false; }
            return $this->fpDBcon->fetch($result, true);
        }

        /**
          * prüfen, ob IP gesperrt ist
          * @param string $ipadress Ip-Adresse
          * @return true, wenn IP oder IP-Bereich gesperrt ist    
          */     
        public function checkForBannedIP ($ipadress) {                        
            /* ganze IP testen */
            $counted = $this->fpDBcon->count("bannedips", "*", "ip LIKE '%".$ipadress."%'");            
            if($counted > 0) { return true; }

            $addressRange = explode('.', $ipadress);       
            $addressRange[3] = '*';

            /* IP ohne letzten Block */
            $ipadress = implode('.', $addressRange);
            $counted = $this->fpDBcon->count("bannedips", "*", "ip LIKE '%".$ipadress."%'");            
            if($counted > 0) { return true; }            

            /* IP ohne letzten 2 Blocks */
            $addressRange[2] = '*';
            $ipadress = implode('.', $addressRange);        
            $counted = $this->fpDBcon->count("bannedips", "*", "ip LIKE '%".$ipadress."%'");            
            if($counted > 0) { return true; }
            
            /* IP ohne letzten 3 Blocks */
            $addressRange[1] = '*';
            $ipadress = implode('.', $addressRange);        
            $counted = $this->fpDBcon->count("bannedips", "*", "ip LIKE '%".$ipadress."%'");            
            if($counted > 0) { return true; }

            return false;  
        }

        /**
         * Hat aktuelle IP Zeitsperre schon überschritten
         * @param int $ipid aktualle IP
         * @return boolean
         */
        public function checkForCommentFlooding ($ipid) {
            $result = $this->fpDBcon->select($this->table, 'MAX(comment_time) AS lasttime', "ip LIKE ?", array($ipid));
            if($result === false) { $this->fpDBcon->getError();return false;}
            $row = $this->fpDBcon->fetch($result);
            
            if($row != null) {
                $testime = time() - (int) $row->lasttime;
                if ($testime > (int) fpConfig::get('comment_flood')) { return true; } else { return false; }   
            }
            
            return true;
        }      

        /**
         * Zählt die Anzahl an Kommentaren einer News
         * 
         * @param int $newsid ID der News
         * @param int $countprivates Sollen private Kommentare gezählt werden
         *      0 oder leer : private/ nicht freigegebene Kommentarnicht zählen
         *      1 : alle Kommentare zählen
         *      2 : nur private Kommentare zählen
         * @return int Anzahl der Kommentare
         */
        public function countCommentsOfNews($newsid,$countprivates = 0) {    
            switch ($countprivates) {
                case 1 :
                    return $this->fpDBcon->count("comments", "*", "newsid = '".(int) $newsid."'");
                break;
                case 2 :
                    return $this->fpDBcon->count("comments", "*", "newsid = '".(int) $newsid."' AND status <> 0");
                break;
                default :
                    return $this->fpDBcon->count("comments", "*", "newsid = '".(int) $newsid."' AND status = 0");
                break;            
            }
        }
        
        /**
         * Gibt es zur News private oder nicht freigegebene Kommentare
         * @param int $newsid ID der News
         * @return boolean
         */
        public function hasPrivateOrNotConfirmedComments($newsid) {
            if($this->countCommentsOfNews($newsid, 2) > 0) return true;
            
            return false;
        }

        /**
         * Ist Neuer Kommentar-Form leer
         * @param string $comauthor
         * @param string $comemail
         * @param string $comtext
         * @return boolean
         */   
        public function isFormEmpty($comauthor,$comemail,$comtext) {  
            if(empty($comauthor)) return true;
            if(empty($comemail) && fpConfig::get('sysemailoptional') == 1) return true;
            if(empty($comtext)) return true;

            return false;
        }   
        
        /**
         * Prüft, ob Antwort auf Anti Spam Frage dem entspricht, was in Datenbank steht
         * @param string $answerText Text der Anti-Spam-Antwort
         * @return boolean
         */
        public function isAntiSpamCorrect($answerText) {
            $moduleReturn = fpModuleEventsFE::runOnTestSpamAnswer($answerText);
            if($moduleReturn) {
                return $moduleReturn;
            } else {
                if($answerText == fpConfig::get('anti_spam_answer')) { return true; }
                return false;                                
            }
        }

        /**
          * Kommentar parsen
          * @param array $row Datenbank-Abfrage
          * @param string $cmttemplate Template
          * @param string $comi Datumsmaske
          * @return string geparster Kommentar   
          */          
        public function parseComment($row,$cmttemplate,$comi) {
            $fpSystem = new fpSystem($this->fpDBcon);
            
            $cmttemplate = "\n<span id=\"comment_".$comi."-".$row->newsid."\"></span>\n".$cmttemplate;
            
            $comment_txt = $row->comment_text;
            if(strpos($comment_txt, '@#') !== false && preg_match_all("/[0-9]+/", $comment_txt, $matchIds) >= 1){
                foreach ($matchIds[0] as $matchId) {
                    $comment_txt = preg_replace("/@#[0-9]+/", "<a class=\"fp-comment-mention-link\" href=\"#comment_".$matchId."-".$row->newsid." \">@".$matchId."</a>", $comment_txt, 1);
                }
            }
            $comment_txt = nl2br(htmlspecialchars_decode($comment_txt));
            $comment_txt = $this->parseLinks($comment_txt);
            $comment_txt = str_replace ("<a ", "<a rel=\"nofollow\" ",$comment_txt);
            
            $markerReplaceArray = array(
                "%comment_text%" => $comment_txt,
                "%author%" => htmlspecialchars_decode($row->author_name),
                "%email%" => htmlspecialchars_decode($row->author_email),
                "%date%" => date(fpConfig::get('timedate_mask'), $row->comment_time),
                "%comment_num%" => $comi,
                "[mention]" => "<a id=\"$comi\" class=\"commentMentionLink\" href=\"\" title=\"".LANG_TEMPLATE_COMMENTION."\">",
                "[/mention]" => "</a>"
            );

            if (empty($row->author_url)) {
                $markerReplaceArray["%url%"] = "";
            } else {
                $markerReplaceArray["%url%"] = "(<a href=\"".htmlspecialchars_decode($row->author_url)."\">".htmlspecialchars_decode($row->author_url)."</a>)";
            }

            $cmtparsed = fpTemplates::replaceMarker($markerReplaceArray, $cmttemplate);
            
            // Smilies parsen
            $cmtparsed    = $this->parseSmiliesInPost($cmtparsed);  
            $moduleReturn = fpModuleEventsFE::runOnCommentParse($cmtparsed);                
            $cmtparsed = !empty($moduleReturn) ? $moduleReturn : $cmtparsed;

            return $cmtparsed;
        }      

        /**
         * Smiley in Kommentar parsen
         * 
         * @param string $cmtparsed
         * @return string
         */
        private function parseSmiliesInPost($cmtparsed) {           
            $fpFileSystem = new fpFileSystem($this->fpDBcon);

            $rows = $fpFileSystem->getSmilies();

            foreach($rows AS $row) {
                $smilieimg = "<img src=\"".FP_ROOT_DIR."img/smilies/".$row->sml_filename."\" alt=\"".$row->sml_code."\">";
                $cmtparsed = str_replace ($row->sml_code, $smilieimg,$cmtparsed);
            }                
            return $cmtparsed;            
        }
        
        /**
         * Links in Kommentar parsen
         * @param string $cmtparsed
         * @param array $attributes
         * @return string
         */
        private function parseLinks($cmtparsed, $attributes=array()) {
            $attrs = '';
            foreach ($attributes as $attribute => $value) {
                $attrs .= " {$attribute}=\"{$value}\"";
            }
            $cmtparsed = ' ' . $cmtparsed;
            $cmtparsed = preg_replace(
                '`([^"=\'>])(((http|https)://|www.)[^\s<]+[^\s<\.)])`i',
                '$1<a href="$2"'.$attrs.'>$2</a>',
                $cmtparsed
            );
            $cmtparsed = substr($cmtparsed, 1);
            $cmtparsed = preg_replace('`href=\"www`','href="http://www',$cmtparsed);
            return $cmtparsed;
        }
    }
?>