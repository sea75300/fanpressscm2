<?php
    /**
     * FanPress CM cache class
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2012-2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 2.0
     */

    final class fpCache {
        
        private $cacheName;
        
        private $fileName;

        private $data;
        
        private $expirationTime;

        /**
         * Der Konstruktur
         * @param string $cacheName
         */
        public function __construct($cacheName = null) {
            $this->cacheName = $cacheName;
            
            if(!is_null($cacheName)) {
                $this->fileName = FPCACHEFOLDER.'/'.md5(strtolower($this->cacheName)).'.cache';

                if(file_exists($this->fileName)) {
                    $data = unserialize(base64_decode(file_get_contents($this->fileName)));
                    $this->data = $data['data'];
                    $this->expirationTime = $data['expires'];
                }
            }
        }
        
        /**
         * Gibt Dateiname von aktuellem Cache zurück
         * @return string
         * @since FPCM 2.5
         */
        public function getCacheFileName() {
            return $this->fileName;
        }

        /**
         * Ist Cache-Inhalt veraltet
         * @return bool
         */
        public function isExpired() {
            return $this->expirationTime <= time() ?  true : false;
        }

        /**
         * Cache-Inhalt schreiben
         * @param string $dataString
         * @param int $timeToExpire
         */
        public function write($dataString, $timeToExpire) {
            if(!is_null($this->fileName)) {
                file_put_contents($this->fileName, base64_encode(serialize(array('data' => $dataString,'expires' => time() + $timeToExpire))));
            }
        }
        
        /**
         * Cache-Inhalt lesen
         * @return string
         */
        public function read() {
            if(!is_null($this->fileName)) { 
                $data = unserialize(base64_decode(file_get_contents($this->fileName)));
                return $data['data'];
            }               
        }
        
        /**
         * Cache-Inhalt leeren
         * @param string $path
         * @return bool
         */
        public function cleanup($path = false) {
            
            $cacheFiles = ($path) ? glob(FPCACHEFOLDER.'/'.md5($path).'.cache') : glob(FPCACHEFOLDER.'/*.cache');

            if(!is_array($cacheFiles) || !count($cacheFiles)) return false;
            
            foreach ($cacheFiles as $cacheFile) {
                if(file_exists($cacheFile)) {
                    unlink($cacheFile);
                }
            }
 
            return true;
        }

    }

?>
