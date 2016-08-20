<?php
    /**
     * FanPress CM Installer class
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-*2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
     
    final class fpInstallation {
        protected $newfpversion;
        protected $fpDBcon      = null;
        protected $dbprefix     = null;
        protected $queryStrings = array();

        public function __construct($queryStrings) {
            
            include dirname(dirname(__FILE__)).'/version.php';            
            $this->newfpversion = $fpcmVersion;
            
            if(file_exists(dirname(dirname(__FILE__))."/inc/config.php")) {
                $this->fpDBcon = new fpDB();
                $this->dbprefix = FP_PREFIX;
                $this->queryStrings = $queryStrings;
            }
        }

        public function getFpDBcon() {
            return $this->fpDBcon;
        }
        
        public function createFPConfigFile($confData) {
              $tplfile = fopen (dirname(dirname(__FILE__))."/inc/config.php", "w");  

              $txt = "<?php\n";
              $txt .= "  define(\"DBSRV\",\"".$confData['dbserver']."\");\n";
              $txt .= "  define(\"DBNAME\",\"".$confData['dbname']."\");\n";
              $txt .= "  define(\"DBUSR\",\"".$confData['dbuser']."\");\n";
              $txt .= "  define(\"DBPASSWD\",\"".$confData['dbpasswd']."\");\n";
              $txt .= "  define(\"DBTYPE\",\"".$confData['dbtype']."\");\n";              
              $txt .= "  define(\"FP_PREFIX\",\"".$confData['dbprefix']."\");\n";
              $txt .= "  define(\"FP_ROOT_DIR\",\"".$confData['fproot']."\");\n";
              $txt .= "?>";
              fwrite($tplfile, $txt);   
              fclose ($tplfile);   
              
              $this->dbprefix = $confData['dbprefix'];
        }  

        public function createTables(){   
            print "<p>create author table...</p>";
            $this->createAuthorTab();

            print "<p>create category table...</p>";
            $this->createCategoryTab();

            print "<p>create comment table...</p>";
            $this->createCommenTab();

            print "<p>create config table...</p>";
            $this->createConfigTab();
            $this->insertDefaultToConfig();

            print "<p>create news post table...</p>";
            $this->createNewspostTab();

            print "<p>create permission table...</p>";
            $this->createPermissionTab();  
            $this->insertDefaultToPermissions();

            print "<p>create smilie table...</p>";
            $this->createSmilieTab();      
            $this->insertDefaultToSmilies();

            print "<p>create upload store table...</p>";
            $this->createUploadTab();

            print "<p>create userlevel table...</p>";
            $this->createUserlevelTab();
            $this->insertDefaultToUserlevel();

            print "<p>create userlog table...</p>";
            $this->createLogTab();

            print "<p>create ip locking table...</p>";
            $this->createBannedIPTab();
        }

        /**
         * Author-Tabelle erstellen
         * @acces private
         *
         */ 
        private function createAuthorTab(){     
            $result = $this->fpDBcon->exec($this->getSQLQueryString("authors"));
            if($result === false) { $this->fpDBcon->getError(); }
        }

        /**
         * Author-Tabelle erstellen
         * @acces private
         *
         */      
        private function createCategoryTab(){  
            $result = $this->fpDBcon->exec($this->getSQLQueryString("categories"));
            if($result === false) { $this->fpDBcon->getError(); } 
            
            $sql = " INSERT INTO ".$this->dbprefix."_categories (id, catname, icon_path, minlevel) VALUES (1, 'Allgemein', '', 1);";
            $result = $this->fpDBcon->exec($sql);
            if($result === false) { $this->fpDBcon->getError(); }              
        }    

        private function createCommenTab(){  
            $result = $this->fpDBcon->exec($this->getSQLQueryString("comments"));
            if($result === false) { $this->fpDBcon->getError(); }
        } 

        private function createConfigTab(){   
                $result = $this->fpDBcon->exec($this->getSQLQueryString("config"));
                if($result === false) { $this->fpDBcon->getError(); } 
        } 


        private function createNewspostTab(){ 
            $result = $this->fpDBcon->exec($this->getSQLQueryString("newsposts"));
            if($result === false) { $this->fpDBcon->getError(); }
        } 

        private function createPermissionTab(){   
            $result = $this->fpDBcon->exec($this->getSQLQueryString("permissions"));
            if($result === false) { $this->fpDBcon->getError(); }
        }   

        private function createSmilieTab(){ 
            $result = $this->fpDBcon->exec($this->getSQLQueryString("smilies"));
            if($result === false) { $this->fpDBcon->getError(); } 
        }  

        private function createUploadTab(){   
            $result = $this->fpDBcon->exec($this->getSQLQueryString("uploads"));
            if($result === false) { $this->fpDBcon->getError(); } 
        }  

        private function createUserlevelTab(){
            $result = $this->fpDBcon->exec($this->getSQLQueryString("usrlevels"));
            if($result === false) { $this->fpDBcon->getError(); } 
        }   

        private function createLogTab(){
            $result = $this->fpDBcon->exec($this->getSQLQueryString("usrlogs"));
            if($result === false) { $this->fpDBcon->getError(); }   
        }     

        private function createBannedIPTab(){
            $result = $this->fpDBcon->exec($this->getSQLQueryString("bannesips"));
            if($result === false) { $this->fpDBcon->getError(); }   
        }      	

        private function insertDefaultToConfig(){
            $twitter_consumer_key    = "U1VxajVRcnFRbW1nYXNtSEFSMWhB";
            $twitter_consumer_secret = "Q3pyRk9qaFNvaWRkTkZ3bnVyVDY4MzA4bTdsQ2twMEFZNUp4TjVYTEpr"; 
            
            $sql = "
                INSERT INTO ".$this->dbprefix."_config (id, config_name, config_value) VALUES
                (1, 'system_version', '".$this->newfpversion."'),
                (2, 'system_mail', ''),
                (3, 'system_url', ''),
                (4, 'system_lang', ''),
                (5, 'timedate_mask', 'd.m.Y, H:i'),
                (6, 'news_show_limit', '5'),
                (7, 'session_length', '3600'),
                (8, 'anti_spam_question', ''),
                (9, 'anti_spam_answer', ''),
                (10, 'active_news_template', 'news'),
                (11, 'active_comment_template', 'comments'),
                (12, 'comment_flood', '30'),
                (13, 'usemode', 'phpinc'),
                (14, 'useiframecss', ''),
                (15, 'showshare', '1'),
                (16, 'comlinkdescr', 'Comment(s)'),
                (17, 'sysemailoptional', '1'),
                (18, 'time_zone', 'Europe/London'),
                (19, 'twitter_consumer_key', '".base64_decode($twitter_consumer_key)."'),
                (20, 'twitter_consumer_secret', '".base64_decode($twitter_consumer_secret)."'),
                (21, 'twitter_access_token', ''),
                (22, 'twitter_access_token_secret', ''),
                (23, 'system_editor', 'standard'),
                (24, 'archive_link', '1'),
                (25, 'confirm_comments', '1'),
                (26, 'sys_jquery', '0'),
                (27, 'cache_timeout', '21600'),
                (28, 'max_img_size_x', '500'),
                (29, 'max_img_size_y', '500'),
                (30, 'max_img_thumb_size_x', '175'),
                (31, 'max_img_thumb_size_y', '175'),
                (32, 'new_file_uploader', '1'),
                (33, 'revision', '1'),
                (34, 'sort_news', 'writtentime'),
                (35, 'sort_news_order', 'DESC'),
                (36, 'comments_enabled_global', '1'),
                (37, 'use_trash', '1')
            ;";
            $result = $this->fpDBcon->exec($sql);
            if($result === false) { $this->fpDBcon->getError(); }      
        }    

        private function insertDefaultToPermissions(){
            $permissions1 = array (
                'addnews'           => 1,
                'editnews'          => 1,
                'deletenews'        => 1,
                'editnewsarchive'   => 1,
                'editallnews'       => 1,
                'editcomments'      => 1,
                'deletecomments'    => 1,
                'user'              => 1,
                'system'            => 1,
                'category'          => 1,
                'permissions'       => 1,
                'templates'         => 1,
                'smilies'           => 1,
                'modules'           => 1,
                'moduleinstall'     => 1,
                'moduleuninstall'   => 1,
                'moduleendisable'   => 1,
                'upload'            => 1,
                'newthumbs'         => 1,
                'deletefiles'       => 1
            );
            $permissions2 = array (
                'addnews'           => 1,
                'editnews'          => 1,
                'deletenews'        => 1,
                'editnewsarchive'   => 1,
                'editallnews'       => 1,
                'editcomments'      => 1,
                'deletecomments'    => 1,
                'user'              => 0,
                'system'            => 1,
                'category'          => 0,
                'permissions'       => 0,
                'templates'         => 0,
                'smilies'           => 0,
                'modules'           => 0,
                'moduleinstall'     => 0,
                'moduleuninstall'   => 0,
                'moduleendisable'   => 0,
                'upload'            => 1,
                'newthumbs'         => 1,
                'deletefiles'       => 1
            );           
            $permissions3 = array (
                'addnews'           => 1,
                'editnews'          => 1,
                'deletenews'        => 0,
                'editnewsarchive'   => 0,
                'editallnews'       => 0,
                'editcomments'      => 0,
                'deletecomments'    => 0,
                'user'              => 0,
                'system'            => 0,
                'category'          => 0,
                'permissions'       => 0,
                'templates'         => 0,
                'smilies'           => 0,
                'modules'           => 0,
                'moduleinstall'     => 0,
                'moduleuninstall'   => 0,
                'moduleendisable'   => 0,
                'upload'            => 1,
                'newthumbs'         => 0,
                'deletefiles'       => 0
            );

            $sql = "INSERT INTO ".$this->dbprefix."_permissions (id, author_level_id, permissions) VALUES (1, 1, ?),(2, 2, ?),(3, 3, ?);";
            $result = $this->fpDBcon->exec($sql, array(serialize($permissions1), serialize($permissions2), serialize($permissions3)));
            if($result === false) { $this->fpDBcon->getError(); }     
        }    

        private function insertDefaultToSmilies(){
            $sql = "
                INSERT INTO ".$this->dbprefix."_smilies (id, sml_code, sml_filename) VALUES
                (2, ':)', 'smile.gif'),
                (3, ':(', 'sad.gif'),
                (4, ':D', 'biggrin.gif'),
                (5, ':P', 'tongue.gif'),
                (6, ':shocked:', 'shocked.gif'),
                (7, ':lol:', 'laugh.gif'),
                (8, ':blah:', 'blah.gif'),
                (9, ':blush:', 'blush.gif'),
                (10, ':bored:', 'bored.gif'),
                (11, ':confused:', 'confused.gif'),
                (12, ':cool:', 'cool.gif'),
                (13, ':cry:', 'cry.gif'),
                (14, ':cute:', 'cute.gif'),
                (15, ':evil:', 'evil.gif'),
                (16, ':frustrated:', 'frustrated.gif'),
                (17, ':annoyed:', 'annoyed.gif'),
                (18, ':|', 'blank.gif'),
                (19, ':bounce:', 'bounce.gif'),
                (20, ':grins:', 'grin.gif'),
                (21, ':gross:', 'gross.gif'),
                (22, ':grr:', 'grr.gif'),
                (23, ':huh:', 'huh.gif'),
                (24, ':kiss:', 'kissy.gif'),
                (25, ':love:', 'love.gif'),
                (26, ':mad:', 'mad.gif'),
                (27, ':nono:', 'no.gif'),
                (28, ':ouch:', 'ouch.gif'),
                (29, ':secret:', 'secret.gif'),
                (30, ':sleepy:', 'sleepy.gif'),
                (31, ':spin:', 'spin.gif'),
                (32, ':sweat:', 'sweatdrop.gif'),
                (33, ':think:', 'thinking.gif'),
                (34, ':whoa:', 'whoa.gif'),
                (35, ';)', 'wink.gif'),
                (36, ':whoohoo:', 'woohoo.gif'),
                (37, ':yeah:', 'yes.gif'),
                (38, ':yummy:', 'yum.gif');
            ";
            $result = $this->fpDBcon->exec($sql);
            if($result === false) { $this->fpDBcon->getError(); }    
        }      

        private function insertDefaultToUserlevel(){
            $sql = "
              INSERT INTO ".$this->dbprefix."_usrlevels (id, leveltitle) VALUES
              (1, 'Admin'),
              (2, 'Editor'),
              (3, 'Autor'); 
            ";
            $result = $this->fpDBcon->exec($sql);
            if($result === false) { $this->fpDBcon->getError(); }     
        }         
        
        private function getSQLQueryString($queryStringName) {
            return str_replace("%dbprefix%", FP_PREFIX, $this->queryStrings[$queryStringName]);
        }
    }
?>