<?php
    /**
     * FanPress CM categories class
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    final class fpCategory { 
        
        private $table = 'categories';

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
        * ist "Kategorie hinzufügen" Form leer
        * @param string $name Name
        * @param string $minulvl Mindest-User-Level
        * @return bool true, wenn Formular leer ist
        */  
        public function isFormEmpty($name,$minulvl) {
            if (empty($name)) { return true; }
            if (empty($minulvl)) { return true; }  

            return false;
        }

        /**
         * Kategorie löschen
         * @param int $catid ID der Kategorie
         * @return void
         */
        public function deleteCategory($catid) {
            
            $catid = implode(', ', !is_array($catid) ? array($catid) : $catid);
            
            $result = $this->fpDBcon->delete($this->table, "id IN ($catid)");
            if($result === false) { $this->fpDBcon->getError();return false; }   

            $fpCache = new fpCache();
            $fpCache->cleanup();                
        }        

        /**
         * Kategorien aus DB holen
         * @param mixed $catid
         *      "all" => alle Kategorien holen
         *      ID    => nur Kategorie mit ID holen
         * @return boolean
         */   
        public function getCategories($catid = "all", $fetchAll = false) {            
            $result = ($catid == "all")
                    ? $this->fpDBcon->select(array($this->table.' cat', 'usrlevels ulv'), 'cat.id, cat.catname, cat.icon_path, cat.minlevel, ulv.leveltitle', 'cat.minlevel = ulv.id ORDER BY cat.catname')
                    : $this->fpDBcon->select(array($this->table.' cat', 'usrlevels ulv'), 'cat.id, cat.catname, cat.icon_path, cat.minlevel, ulv.leveltitle', 'cat.minlevel = ulv.id AND cat.id = ?', array($catid));

            if($result === false) { $this->fpDBcon->getError();return false; }
            return $this->fpDBcon->fetch($result, $fetchAll);
        }
    }
?>