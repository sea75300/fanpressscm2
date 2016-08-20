<?php
    /**
     * FanPress CM Model
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    abstract class fpModel {

        /**
         *
         * @var \fpDB
         */
        protected $dbcon    = null;
        
        /**
         *
         * @var int
         */
        protected $id       = null;
        
        /**
         *
         * @var string
         */
        protected $table    = '';
        
        /**
         *
         * @var array
         */
        protected $data     = array();
        
        /**
         *
         * @var \fpCache
         */
        protected $cache    = null;
        
        /**
         *
         * @var \fpSystem
         */
        protected $system   = null;

        /**
         * 
         * @global \fpDB $fpDBcon
         * @param int $id
         */
        public function __construct($id = null) {
            global $fpDBcon;

            $this->dbcon    = $fpDBcon;
            $this->cache    = new fpCache();
            $this->system   = new fpSystem($fpDBcon);
            
            if(!is_null($id)) {
                $this->id = (int) $id;
                $this->init();
            }
        }
        
        /**
         * 
         * @param string $name
         * @return mixed
         */
        public function __get($name) {
            return isset($this->data[$name]) ? $this->data[$name] : false;
        }
        
        /**
         * 
         * @param mixed $name
         * @param mixed $value
         */
        public function __set($name, $value) {
            $this->data[$name] = $value;
        }
        
        /**
         * 
         * @return string
         */
        public function __toString() {
            return json_encode($this->getPreparedSaveParams());
        }
        
        /**
         * Inittiert Objekt mit Daten aus der Datenbank, sofern ID vergeben wurde
         */
        protected function init() {            
            $data = $this->dbcon->fetch($this->dbcon->select($this->table, '*', "id = ?", array($this->id)));
            foreach ($data as $key => $value) { $this->$key = $value; }
        }        
        
        /**
         * Gibt Object-ID zurück
         * @return int
         */
        public function getId(){
            return $this->id;
        }
        
        /**
         * Speichert ein Objekt in der Datenbank
         * @return bool
         * @abstract
         */
        abstract public function save();

        /**
         * Aktualisiert ein Objekt in der Datenbank
         * @return bool
         * @abstract
         */        
        abstract public function update();
        
        /**
         * Löscht ein Objekt in der Datenbank
         * @return bool
         */        
        public function delete() {
            $this->dbcon->delete($this->table, 'id = ?', array($this->id));            
            $this->cache->cleanup();
            
            return true;
        }
        
        /**
         * Füllt Objekt mit Daten aus Datenbank-Result
         * @param object $newsObject
         * @return boolean
         * @since FPCM 2.5
         */
        public function createFromDbObject($newsObject) {
            
            if(!is_object($newsObject)) return false;
            
            foreach ($this->getPreparedSaveParams() as $key => $value) {
                if(!isset($newsObject->$key)) continue;
                $this->$key = $newsObject->$key;
            }
            
            return true;
        }        
        
        /**
         * Bereitet Eigenschaften des Objects zum Speichern ind er Datenbank vor und entfernt nicht speicherbare Eigenschaften
         * @return array
         */
        protected function getPreparedSaveParams() {
            $params = get_object_vars($this);
            unset($params['id'], $params['table'], $params['data'], $params['dbcon'], $params['cache'], $params['system']);
            return $params;
        }
        
    }
