<?php
    /**
     * FanPress CM User, rolls and permissions
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    class fpUser {   
        private $table = 'authors';

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
         * Ist Neuer Benutzer-Form leer
         * @param string $name angezeigter Name
         * @param string $email E-Mailo-Adresse
         * @param string $sysusr Benutzername
         * @param string $passwd Passwort
         * @param string $usrlevel user-Level
         * @return boolean
         */
        public function isFormEmpty($name,$email,$sysusr,$passwd,$usrlevel) {
            if (empty($name))     { return true; }
            if (empty($email))    { return true; }
            if (empty($sysusr))   { return true; }
            if (empty($passwd))   { return true; }
            if (!isset($usrlevel)) { return true; }  

            return false;  
        }

        /**
         * alle Benutzer auslesen
         * @return object mit allen Benutzern
         */
        public function getAllUsers() {
            $tables = array($this->table.' aut', 'usrlevels ulv');
            $fields = 'aut.id, aut.email, aut.registertime, aut.sysusr, ulv.leveltitle, aut.usrmeta';
            $where  = 'aut.usrlevel = ulv.id';             
            
            $result = $this->fpDBcon->select($tables, $fields, $where, null, true);	  
            if($result === false) { $this->fpDBcon->getError();return false; }
            return $this->fpDBcon->fetch($result, true);
        }    
        
        /**
         * alle deaktivierten Benutzer auslesen
         * @return array
         */
        public function getDisabledUser() {
            $result = $this->fpDBcon->select($this->table, '*', 'usrlevel = 0');
            if($result === false) { $this->fpDBcon->getError();return false; }
            return $this->fpDBcon->fetch($result, true);            
        }

        /**
         * Daten eines bestimmten Benutzers auslesen, Suche per Username
         * 
         * @param string $sysusr
         * @return object
         */  
        public function getUserByUsrName($sysusr) {           
            $tables = array($this->table.' aut', 'usrlevels ulv');
            $fields = 'aut.email, aut.registertime, aut.sysusr, aut.name, aut.usrmeta, aut.usrlevel';
            $where  = "aut.usrlevel = ulv.id AND aut.sysusr like ?";            
            
            $result = $this->fpDBcon->select($tables, $fields, $where, array($sysusr));	  
            if($result === false) { $this->fpDBcon->getError();return false; }
                        
            return $this->fpDBcon->fetch($result);
        }

        /**
        * alle Userlevel + deren Namen aus Datenbank holen
        * @return object User-Level
        */    
        public function getUsrLevels() {
            $result = $this->fpDBcon->select('usrlevels');
            if($result === false) { $this->fpDBcon->getError();return false; }
            return $this->fpDBcon->fetch($result, true);
        }      

        /**
        * User-Level des Benuters ermitteln
        * @param string $sysusr Benutzername
        */      
        public function getUsrLevel($sysusr) {
            $result = $this->fpDBcon->select($this->table, 'usrlevel', 'id LIKE ?', array($sysusr));
            if($result === false) { $this->fpDBcon->getError();return false; }
            return $result;
        }

        /**
         * Anzahl an News ermitteln, die Benutzer geschrieben hat    
         * @param int $usrid Benutzer-ID
         * @return int Anzahl an geschriebenen News-Posts
         */     
        public function countWrittenNews($usrid) {
            return $this->fpDBcon->count("newsposts", "*", "author = '".$usrid."'");
        }
        
        /**
         * zählt deaktivierte Benutzer
         * @return int
         */
        public function countDisabledUsers() {
            return $this->fpDBcon->count($this->table, "*", "usrlevel = 0");
        }

       /**
        * Benutzerrechte aktualisieren
        * @param int $group
        * @param string $permissionConfRow
        * @return boolean
        */     
        public function updatePermissions($group,$permissionConfRow) {
            $result = $this->fpDBcon->update('permissions', array('permissions'), array(serialize($permissionConfRow), $group), 'author_level_id = ?');
            if($result === false) { $fpDBcon->getError();return false; }
            return $result;
        }

        /**
         * Password vergessen Funktion
         * @param string $username
         */
        public function resetPassword($username) {
            $user = $this->getUserByUsrName($username);
            
            if($user && $user->usrlevel > 0) {
                $newpasswd = str_shuffle(uniqid('pass', true));
                $newpasswdhas = fpSecurity::createPasswdhash($newpasswd);

                $result = $this->fpDBcon->update($this->table, array('passwd'), array($newpasswdhas, $username), "sysusr LIKE ?");
                if($result === false) { $this->fpDBcon->getError(); }

                mail($user->email,LANG_NEWSPASSWD_MAILSUB,LANG_NEWSPASSWD_MAILSUB.": ".$newpasswd, "From: FanPress");                   
            }
        }
    }
?>