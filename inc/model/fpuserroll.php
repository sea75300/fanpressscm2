<?php
    /**
     * FanPress CM User Roll Model
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    class fpUserRoll extends fpModel {
        
        /**
         * Bezeichnung der Benutzer-Rolle
         * @var string
         */
        protected $leveltitle = '';

        public function __construct($id = null) {
            $this->table = "usrlevels";
            
            parent::__construct($id);
        }

        public function getLeveltitle() {
            return $this->leveltitle;
        }

        public function setLeveltitle($leveltitle) {
            $this->leveltitle = $leveltitle;
        }        

        /**
         * Speichert einen neuen Kommentar in der Datenbank
         * @return boolean
         */         
        public function save() {
            $newId = $this->dbcon->insert($this->table, 'leveltitle', '?', array($this->leveltitle));

            $newPermissions = array(
                'addnews'           => 0,
                'editnews'          => 0,
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
                'upload'            => 0,
                'newthumbs'         => 0,
                'deletefiles'       => 0
            );
            
            $return = false;
            if($this->dbcon->insert('permissions', 'author_level_id, permissions', '?,?', array($newId, serialize($newPermissions)))) {
                $return = true;
            }
            
            $this->cache->cleanup();
            
            fpMessages::writeToSysLog("add user roll ".$this->leveltitle);
            
            return $return;
        }
        
        /**
         * Löscht eine Benutzer-Rolle in der Datenbank
         * @return boolean
         */         
        public function delete() {
            $return = parent::delete();
            
            if($this->dbcon->delete('permissions', 'author_level_id = ?', array($this->id))) {
                $return = $return && true;
            }
            
            fpMessages::writeToSysLog("delete user roll ".$this->leveltitle);
            
            return $return;
        }
        
        /**
         * Aktualisiert eine Benutzer-Rolle in der Datenbank
         * @return boolean
         */          
        public function update() {
            $return = false;
            
            if($this->dbcon->update($this->table, array('leveltitle'), array($this->leveltitle, $this->id), 'id = ?')) {
                $return = true;
            }            
            
            $this->init();
            
            $this->cache->cleanup();
            
            fpMessages::writeToSysLog("updating user roll ".$this->leveltitle);
            
            return $return;
        }
        
    }
