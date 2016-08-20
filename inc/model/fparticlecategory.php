<?php
    /**
     * FanPress CM Category Model
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    class fpArticleCategory extends fpModel {
        
        protected $catname;
        
        protected $icon_path;
        
        protected $minlevel;
        
        public function __construct($id = null) {
            $this->table = 'categories';
            
            parent::__construct($id);
        }

        function getCatname() {
            return $this->catname;
        }

        function getIconPath() {
            return $this->icon_path;
        }

        function getMinlevel() {
            return (int) $this->minlevel;
        }

        function setCatname($catname) {
            $this->catname = $catname;
        }

        function setIconPath($icon_path) {
            $this->icon_path = $icon_path;
        }

        function setMinlevel($minlevel) {
            $this->minlevel = (int) $minlevel;
        }
        
        /**
         * Speichert ein Objekt in der Datenbank
         * @return bool
         */
        public function save() {
            if($this->categoryExists($this->catname)) return false;
            
            $params = $this->getPreparedSaveParams();
            $params = fpModuleEventsAcp::runOnCategorySave($params);
            
            $return = false;
            if($this->dbcon->insert($this->table, implode(',', array_keys($params)), '?, ?, ?', array_values($params))) {
                $return = true;
            }
            
            $this->cache->cleanup(); 
            
            fpMessages::writeToSysLog("add category ".$this->name);
            
            return $return;              
        }

        /**
         * Aktualisiert ein Objekt in der Datenbank
         * @return bool
         */        
        public function update() {
            $params     = $this->getPreparedSaveParams();
            $fields     = array_keys($params);
            
            $params[]   = $this->getId();
            $params     = fpModuleEventsAcp::runOnCategoryUpdate($params);

            $return = false;
            if($this->dbcon->update($this->table, $fields, array_values($params), 'id = ?')) {
                $return = true;
            }
            
            $this->cache->cleanup(); 
            
            $this->init();
            
            return $return;              
        }

        /**
         * existiert Kategorie?
         * @param string $name
         * @return boolean
         */
        private function categoryExists($name) {
            $counted = $this->dbcon->count("categories", 'id', "catname like '$name'");
            return ($counted > 0) ? true : false;
        }         
        
    }
