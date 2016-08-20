<?php
    /**
     * FanPress CM Article Model
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    class fpArticle extends fpModel {
        
        /**
         * Kategorien
         * @var int
         */
        protected $category         = '';
        
        /**
         * Author-ID
         * @var int
         */
        protected $author           = 0;
        
        /**
         * News-Titel
         * @var string
         */
        protected $titel            = '';
        
        /**
         * News-Text
         * @var string
         */
        protected $content          = '';
        
        /**
         * Vorschau-Status
         * @var int
         */
        protected $ispreview        = 0;
        
        /**
         * Archiv-Status
         * @var int
         */
        protected $is_archived      = 0;
        
        /**
         * Pinned-Status
         * @var int
         */
        protected $is_pinned        = 0;
        
        /**
         * Veröffentlichungszeit
         * @var int
         */
        protected $writtentime      = 0;
        
        /**
         * Letzte Änderung
         * @var string
         */
        protected $editedtime       = 0;
        
        /**
         * Kommentare aktiv
         * @var int
         */
        protected $comments_active  = 1;
        
        /**
         * News gelöscht (nur wenn Papierkorb aktiv)
         * @var int
         */
        protected $is_deleted = 0;

        /**
         * 
         * @param int $id
         */
        public function __construct($id = null) {
            $this->table = "newsposts";
            
            parent::__construct($id);
        }
        
        /**
         * 
         * @return string
         */
        public function getCategory() {
            return $this->category;
        }

        /**
         * 
         * @return string
         */
        public function getAuthor() {
            return $this->author;
        }

        /**
         * 
         * @return string
         */
        public function getTitel() {
            return $this->titel;
        }

        /**
         * 
         * @return string
         */
        public function getContent() {
            return $this->content;
        }

        /**
         * 
         * @return int
         */
        public function isPreview() {
            return $this->ispreview;
        }

        /**
         * 
         * @return int
         */
        public function isArchived() {
            return $this->is_archived;
        }

        /**
         * 
         * @return int
         */
        public function isPinned() {
            return $this->is_pinned;
        }

        /**
         * 
         * @return int
         */
        public function getWrittentime() {
            return $this->writtentime;
        }

        /**
         * 
         * @return int
         */
        public function getEditedtime() {
            return $this->editedtime;
        }

        /**
         * 
         * @return int
         */
        public function getCommentsActive() {
            return $this->comments_active;
        }

        /**
         * 
         * @return bool
         * @since FPCM 2.5
         */
        function isDeleted() {
            if(!fpConfig::get('use_trash')) return false;
            
            return $this->is_deleted;
        }        
        
        /**
         * 
         * @param string $category
         * @return \fpArticle
         */
        public function setCategory($category) {
            $this->category = $category;
            return $this;
        }

        /**
         * 
         * @param string $author
         * @return \fpArticle
         */
        public function setAuthor($author) {
            $this->author = $author;
            return $this;
        }

        /**
         * 
         * @param string $titel
         * @return \fpArticle
         */
        public function setTitel($titel) {
            $this->titel = $titel;
            return $this;
        }

        /**
         * 
         * @param int $content
         * @return \fpArticle
         */
        public function setContent($content) {
            $this->content = $content;
            return $this;
        }

        /**
         * 
         * @param int $ispreview
         * @return \fpArticle
         */
        public function setPreview($ispreview) {
            $this->ispreview = $ispreview;
            return $this;
        }

        /**
         * 
         * @param int $is_archived
         * @return \fpArticle
         */
        public function setArchived($is_archived) {
            $this->is_archived = $is_archived;
            return $this;
        }

        /**
         * 
         * @param int $is_pinned
         * @return \fpArticle
         */
        public function setPinned($is_pinned) {
            $this->is_pinned = $is_pinned;
            return $this;
        }

        /**
         * 
         * @param int $writtentime
         * @return \fpArticle
         */
        public function setWrittentime($writtentime) {
            $this->writtentime = $writtentime;
            return $this;
        }

        /**
         * 
         * @param int $editedtime
         * @return \fpArticle
         */
        public function setEditedtime($editedtime) {
            $this->editedtime = $editedtime;
            return $this;
        }

        /**
         * 
         * @param int $comments_active
         * @return \fpArticle
         */
        public function setCommentsActive($comments_active) {
            $this->comments_active = $comments_active;
            return $this;
        }
        
        /**
         * 
         * @param int $is_deleted
         * @return \fpArticle
         * @since FPCM 2.5
         */
        function setIsDeleted($is_deleted) {
            if(!fpConfig::get('use_trash')) $this;
            
            $this->is_deleted = $is_deleted;
            return $this;
        }        
        
        /**
         * 
         * @return string
         */
        public function getAuthorName() {            
            $author = new fpAuthor($this->getAuthor());
            return $author->getDisplayName();            
        }
        
        /**
         * 
         * @return string
         */
        public function getArticleUrl() {
            return fpConfig::get('system_url')."?fn=cmt&nid=".$this->getId();
        }
        
        /**
         * 
         * @param bool $fullDelete
         * @since FPCM 2.5
         */
        public function setFullDelete($fullDelete) {
            $this->forceFullDelete = $fullDelete;
        }

        /**
         * Speichert eine neuen Artikel in der Datenbank
         * @return boolean
         */        
        public function save() {
            $params = $this->getPreparedSaveParams();
            $params = fpModuleEventsAcp::runOnNewsSave($params);

            $return = $this->dbcon->insert($this->table, implode(',', array_keys($params)), '?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', array_values($params));
            if(!$return) { return false; }
            
            $this->cache->cleanup();
            
            return $return;            
        }

        /**
         * Aktualisiert einen Artikel in der Datenbank
         * @return boolean
         */        
        public function update() {
            $this->editedtime = time();
            
            $params     = $this->getPreparedSaveParams();
            $fields     = array_keys($params);
            
            $params[]   = $this->getId();
            $params     = fpModuleEventsAcp::runOnNewsUpdate($params);

            $return = false;
            if($this->dbcon->update($this->table, $fields, array_values($params), 'id = ?')) {
                $return = true;
            }
            
            $this->cache->cleanup(); 
            
            $this->init();
            
            return $return;            
        }
        
        /**
         * Löscht News in der Datenbank
         * @return bool
         */
        public function delete() {            
            if(fpConfig::get('use_trash') && !$this->forceFullDelete) {
                $this->cache->cleanup();
                $this->setIsDeleted(1);
                return $this->update();
            }
            
            $revisionIds = array_keys($this->getRevisions());
            $this->deleteRevisions($revisionIds);             
            
            return parent::delete();
        }

        /**
         * Erzeugt eine Revision des Artikels
         * @return boolean
         */
        public function createRevision() {
            
            $fileNameTime = $this->editedtime ? $this->editedtime : $this->writtentime;
            $revisionFile = FPREVISIONFOLDER.'revisions_'.$this->id.'/'.$fileNameTime.'.php';
            
            $revDir       = dirname($revisionFile);
            if(!is_dir(dirname($revisionFile))) {
                mkdir ($revDir);
            }

            $data = $this->getPreparedSaveParams();            
            $data = json_encode($data);
            
            if(!$data) {
                fpMessages::writeToSysLog('Unable to create revision '.$fileNameTime.' for article with id '.$this->id);
                return false;
            }
            
            file_put_contents($revisionFile, '<?php die(); ?>'.PHP_EOL.$data);                        
        }
        
        /**
         * Gib Revisionen des Artikels zurück
         * @param bool $full Soll die Revision ganz zurückgegebn werden oder nur Titel
         * @return array
         */
        public function getRevisions($full = false) {
            
            $revisionArray = array();
            
            $revisionFiles = glob(FPREVISIONFOLDER."revisions_".$this->id."/*.php");
            
            if(!is_array($revisionFiles) || !count($revisionFiles)) return $revisionArray;

            foreach ($revisionFiles as $revisionFile) {                
                $revFileContent = array();
                $revFileContent = json_decode(trim(file_get_contents($revisionFile, false, null, 16)), true);
                
                $revisionTime   = basename($revisionFile);
                $revisionTime   = substr($revisionTime, 0, strlen($revisionTime) - 4);
                
                $revisionArray[$revisionTime] =  ($full) ? $revFileContent : $revFileContent['titel'];
            }
            
            krsort($revisionArray, SORT_NUMERIC);

            return $revisionArray;
        }

                /**
         * Stellt Revision eines Artikels wieder her
         * @param int $revisionTime Revisions-ID
         * @return boolean
         */
        public function getRevision($revisionTime) {
            
            $revisions = $this->getRevisions(true);
            
            if(!isset($revisions[$revisionTime])) return false;
            
            $revision = $revisions[$revisionTime];

            $this->titel            = $revision['titel'];
            $this->category         = $revision['category'];
            $this->content          = $revision['content'];
            $this->is_archived      = $revision['is_archived'];
            $this->is_pinned        = $revision['is_pinned'];
            $this->is_archived      = $revision['is_archived'];
            $this->ispreview        = $revision['ispreview'];
            $this->comments_active  = $revision['comments_active'];            
        }
        
        /**
         * Stellt Revision eines Artikels wieder her
         * @param int $revisionTime Revisions-ID
         * @return boolean
         */
        public function restoreRevision($revisionTime) {
            
            $revisions = $this->getRevisions(true);
            
            if(!isset($revisions[$revisionTime])) return false;
            
            $this->createRevision();
            
            $revision = $revisions[$revisionTime];

            $this->titel            = $revision['titel'];
            $this->category         = $revision['category'];
            $this->content          = $revision['content'];
            $this->is_archived      = $revision['is_archived'];
            $this->is_pinned        = $revision['is_pinned'];
            $this->is_archived      = $revision['is_archived'];
            $this->ispreview        = $revision['ispreview'];
            $this->comments_active  = $revision['comments_active'];
            
            return $this->update();
            
        }
        
        /**
         * Löscht Revisionen
         * @param array $revisionList Liste von Revisions-IDs
         * @return boolean
         */
        public function deleteRevisions(array $revisionList) {
            
            foreach ($revisionList as $revision) {
                $revisionFile = FPREVISIONFOLDER.'revisions_'.$this->id.'/'.trim($revision).'.php';
                
                if(!file_exists($revisionFile)) continue;
                
                unlink($revisionFile);
            }
            
            return true;
        }
        
    }
