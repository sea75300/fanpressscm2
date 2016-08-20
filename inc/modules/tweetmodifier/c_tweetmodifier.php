<?php

    final class fpModule_TweetModifier_TweetModifier {
        
        private $termList;
        
        private $termListFile;
        
        /**
         * Constructor
         */
        public function __construct() {
            $this->termListFile = __DIR__.'/termlist.txt';
            $this->load();
        }

        /**
         * Returns a term
         * @param string $term
         * @return array
         */
        public function getTerm($term) {
            return array('term' => $term, 'replace' => $this->termList[$term]);
        }
        
        /**
         * Returns an array of terms
         * @return array
         */
        public function getTerms() {
            return $this->termList;
        }

        /**
         * Adds a new term
         * @param array $newTerm
         */
        public function addTerm(array $newTerm) {
            $this->termList[$newTerm['term']] = $newTerm['replace'];
            $this->reload();
        }
        
        /**
         * Delete a term
         * @param string $term
         */
        public function deleteTerm($term) {
            unset($this->termList[$term]);
            $this->reload();
        }
        
        /**
         * Saves the termin list to file
         */
        private function save() {
            file_put_contents($this->termListFile, base64_encode(json_encode($this->termList)));
        }
        
        /**
         * Loads the termin list from file
         * @return array
         */
        private function load() {
            if(file_exists($this->termListFile)) {
                $this->termList = json_decode(base64_decode(file_get_contents($this->termListFile)), true);
                return;
            }            
            $this->termList = array();            
        }        
        
        /**
         * Runs Save and Load
         */
        private function reload() {
            $this->save();
            $this->load();            
        }        
    }
?>