<?php
    /**
     * FanPress CM Comment Model
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    class fpComment extends fpModel {
        
        /**
         * News-ID
         * @var int
         */
        protected $newsid = 0;

        /**
         * Kommentare-Author-Name
         * @var string
         */
        protected $author_name = '';
        
        /**
         * E-Mail-Adresse
         * @var string
         */
        protected $author_email = '';
        
        /**
         * Webseite des Authors
         * @var string
         */
        protected $author_url = '';
        
        /**
         * Kommentare an sich
         * @var string
         */
        protected $comment_text = '';
        
        /**
         * Status
         * @var int
         */
        protected $status = 0;
        
        /**
         * Veröffentlichungszeitpunkt
         * @var int
         */
        protected $comment_time = 0;
        
        /**
         * IP des AUthors
         * @var string
         */
        protected $ip = '';

        public function __construct($id = null) {
            $this->table = 'comments';
            
            parent::__construct($id);
        }
        
        public function getNewsid() {
            return $this->newsid;
        }

        public function getAuthorName() {
            return $this->author_name;
        }

        public function getAuthorEmail() {
            return $this->author_email;
        }

        public function getAuthorUrl() {
            return $this->author_url;
        }

        public function getCommentText() {
            return $this->comment_text;
        }

        public function getStatus() {
            return $this->status;
        }

        public function getCommentTime() {
            return $this->comment_time;
        }

        public function getIp() {
            return $this->ip;
        }

        public function setNewsid($newsid) {
            $this->newsid = $newsid;
        }

        public function setAuthorName($author_name) {
            $this->author_name = $author_name;
        }

        public function setAuthorEmail($author_email) {
            $this->author_email = $author_email;
        }

        public function setAuthorUrl($author_url) {
            $this->author_url = $author_url;
        }

        public function setCommentText($comment_text) {
            $this->comment_text = $comment_text;
        }

        public function setStatus($status) {
            $this->status = $status;
        }

        public function setCommentTime($comment_time) {
            $this->comment_time = $comment_time;
        }

        public function setIp($ip) {
            $this->ip = $ip;
        }        
        
        /**
         * Speichert einen neuen Kommentar in der Datenbank
         * @return boolean
         */        
        public function save() {
            $params = $this->getPreparedSaveParams();            
            $params = fpModuleEventsFE::runOnCommentSave($params);

            $return = false;
            if($this->dbcon->insert($this->table, implode(',', array_keys($params)), '?, ?, ?, ?, ?, ?, ?, ?', array_values($params))) {
                $return = true;
            }
            
            $this->cache->cleanup();
            
            return $return;
        }
        
        /**
         * Aktualisiert einen Kommentar in der Datenbank
         * @return boolean
         */        
        public function update() {
            $this->comment_time = time();
            
            $params = $this->getPreparedSaveParams();
            $params = fpModuleEventsAcp::runOnCommentUpdate($params);
            
            $fields     = array_keys($params);
            
            $params[]   = $this->getId();
            $params     = fpModuleEventsFE::runOnCommentSave($params);

            $return = false;
            if($this->dbcon->update($this->table, $fields, array_values($params), 'id = ?')) {
                $return = true;
            }
            
            $this->cache->cleanup(); 
            
            $this->init();
            
            return $return;
        }
        
        /**
         * Löscht einen Kommentar in der Datenbank
         * @return boolean
         */        
        public function delete() {
            $return = false;
            
            if($this->deleteIdOnly) {
                if($this->dbcon->delete($this->table, 'id = ?', array($this->id))) {
                    $return = true;
                }                
            } else {
                if($this->dbcon->delete($this->table, 'id = ? AND newsid = ?', array($this->id, $this->newsid))) {
                    $return = true;
                }                
            }
            
            $this->cache->cleanup();
            
            fpMessages::writeToSysLog("delete comment id=".$this->id);
            
            return $return;
        }
        
    }
