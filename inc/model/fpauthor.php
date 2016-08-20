<?php
    /**
     * FanPress CM Author/ User Model
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    class fpAuthor extends fpModel {
        
        /**
         * Anzeigeter Name
         * @var string
         */
        protected $name;
        
        /**
         *E-Mail-Adresse
         * @var string
         */
        protected $email;
        
        /**
         * Zeit, an dem der Benutzer angelegt wurde
         * @var int
         */
        protected $registertime;
        
        /**
         * Benutzername
         * @var string
         */
        protected $sysusr;
        
        /**
         * sha256-Hash des Passwortes
         * @var string
         */
        protected $passwd;
        
        /**
         * Benutezrrolle
         * @var int
         */
        protected $usrlevel;
        
        /**
         * Meta-Daten für persönliche Einstellungen
         * @var array
         */
        protected $usrmeta;
        
        public function __construct($id = null) {
            $this->table = "authors";
            
            parent::__construct($id);
        }        
        
        public function getDisplayName() {
            return $this->name;
        }

        public function getEmail() {
            return $this->email;
        }

        public function getRegistertime() {
            return $this->registertime;
        }

        public function getUserName() {
            return $this->sysusr;
        }

        public function getPassword() {
            return $this->passwd;
        }

        public function getUserRoll() {
            return $this->usrlevel;
        }

        public function getUserMeta($valueName = null) {
            $userMeta = json_decode($this->usrmeta, true);
            
            if(is_null($valueName))          { return $userMeta; }
            if(isset($userMeta[$valueName])) { return $userMeta[$valueName]; }
            
            switch ($valueName) {
                case 'lang' :
                    return fpConfig::get('system_lang');
                break;
                case 'timemask' :
                    return 'd.m.Y H:i:s T';
                break;   
                case 'timezone' :
                    return date_default_timezone_get();
                break;             
            }
        }

        public function setDisplayName($name) {
            $this->name = $name;
        }

        public function setEmail($email) {
            $this->email = $email;
        }

        public function setRegistertime($registertime) {
            $this->registertime = $registertime;
        }

        public function setUserName($sysusr) {
            $this->sysusr = $sysusr;
        }

        public function setPassword($passwd) {
            $this->passwd = $passwd;
        }

        public function setUserRoll($userroll) {
            $this->usrlevel = (int) $userroll;
        }

        public function setUserMeta($usrmeta) {
            $usrmeta  = (is_array($usrmeta)) ? $usrmeta : array($usrmeta);
            $this->usrmeta = json_encode($usrmeta);
        }        
        
        /**
         * Speichert einen neuen Benutzer in der Datenbank
         * @return boolean
         */
        public function save() {
            if($this->authorExists()) return -1;
            if(!$this->checkPasswordSecure()) return -2;
            
            $this->passwd = fpSecurity::createPasswdhash($this->passwd);
            
            $params = $this->getPreparedSaveParams();

            $return = false;
            if($this->dbcon->insert($this->table, implode(',', array_keys($params)), '?, ?, ?, ?, ?, ?, ?', array_values($params))) {
                $return = true;
            }
            
            $this->cache->cleanup(); 
            
            fpMessages::writeToSysLog("add user ".$this->sysusr);
            
            return $return;            
        }

        /**
         * Aktualisiert einen Benutzer in der Datenbank
         * @return boolean
         */
        public function update() {
            if(!$this->authorExists()) return -1;
            if(!empty($this->passwd) && !$this->internal) {
                if(!$this->checkPasswordSecure()) return -2;
                
                $this->passwd = fpSecurity::createPasswdhash($this->passwd);
            }
            
            $params     = $this->getPreparedSaveParams();
            if(empty($this->passwd)) { unset($params['passwd']); }
            
            $fields     = array_keys($params);
            
            $params[]   = $this->getId();

            $return = false;
            if($this->dbcon->update($this->table, $fields, array_values($params), 'id = ?')) {
                $return = true;
            }            
            
            $this->cache->cleanup();
            $this->init();
            
            fpMessages::writeToSysLog("updating user ".$this->sysusr);
            
            return $return;
        }
        
        /**
         * Löscht einen Benutzer in der Datenbank
         * @return bool
         */
        public function delete() {
            fpMessages::writeToSysLog("remove user ".$this->sysusr);
            
            return parent::delete();
        }
        
        /**
         * Deaktiviert einen Benutzer
         * @return bool
         */
        public function disable() {
            $this->usrlevel = 0;
            $this->internal = true;
            
            fpMessages::writeToSysLog("disable user ".$this->sysusr);
            
            return $this->update();
        }
        
        /**
         * Aktiviert einen Benutzer
         * @return bool
         */        
        public function enable() {
            $this->usrlevel = 3;
            $this->internal = true;
            
            fpMessages::writeToSysLog("enable user ".$this->sysusr);
            
            return $this->update();
        }
        
        /**
         * Passwort-Check ein Anlegen/Aktualisieren deaktivieren
         */
        public function disablePasswordSecCheck() {
            $this->nopasscheck = true;
        }

        /**
         * Prüft, ob Neutzer existiert
         * @return bool
         */
        private function authorExists() {
            $result = $this->dbcon->count("authors","id", "sysusr like '{$this->sysusr}'");
            return ($result > 0) ? true : false;
        }
        
        /**
         * Prüft, ob Passwort den minimalen Anforderungen entspricht
         * @return boolean
         */
        private function checkPasswordSecure() {
            if($this->nopasscheck) return true;
            return (preg_match(FPUSRPASSWORDREGEX, $this->passwd)) ? true : false;
        }
        
    }
