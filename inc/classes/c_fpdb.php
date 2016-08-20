<?php
    /**
     * FanPress CM database PDO abtraction layer
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2012-2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 2.0
     */

    final class fpDB {
        
        // Datenbank Verbindung
        protected $connection = null;

        // Platzhalter für Autoincrement in DB
        public static $queryAutoInc;

        /**
         * Der Konstruktor
         * @return void
         */
        public function __construct() {
            try {
                $this->connection = new PDO(DBTYPE.':dbname='.DBNAME.';host='.DBSRV, DBUSR, DBPASSWD);
            } catch(PDOException $e) {
                fpMessages::writeToSysLog("FPDB: ".$e->getMessage());
                die('Connection to database failed!');
            }
            
            static::$queryAutoInc = "NULL";
        }
        
        /**
         * Der Destruktor
         */
        public function __destruct() {
            $this->connection = null;
        }
        

        /**
         * Parst einfach Anfürungszeichen in Zichenkette
         * @param string $param Text mit einfachen Anfürungszeichen
         * @return string
         */
        public function parseSingleQuotes($param) {
            return str_replace("'", "\'", $param);
        }
        

        /**
         * Führt SELECT-Befehl auf DB aus
         * @param string $table select table
         * @param string $item select items
         * @param string $where select condition
         * @param array $params select condition params
         * @param bool $distinct Distinct select
         * @return mixed
         * @since FPCM 2.4.0
         */
        public function select($table, $item = '*', $where = null, $params = null, $distinct = false) {            
            $table = (is_array($table)) ? FP_PREFIX.'_'.implode(', '.FP_PREFIX.'_', $table) : FP_PREFIX."_$table";
            $sql = $distinct ? "SELECT DISTINCT $item FROM $table" : "SELECT $item FROM $table";
            if(!is_null($where)) $sql .= " WHERE $where";
            return $this->query($sql, $params);            
        }
        
        /**
         * Führt INSERT-Befehl auf DB aus
         * @param string $table
         * @param string $fields
         * @param string $values
         * @param array $params
         * @return bool|int
         * @since FPCM 2.4.0
         */
        public function insert($table, $fields, $values, $params = null) {
            $table = (is_array($table)) ? FP_PREFIX.'_'.implode(', '.FP_PREFIX.'_', $table) : FP_PREFIX."_$table";
            $sql = "INSERT INTO $table ($fields) VALUES ($values);";
            $this->exec($sql, $params);
            return $this->getLastInsertId();
        }
        
        /**
         * Führt UPDATE-Befehl auf DB aus
         * @param string $table
         * @param array $fields
         * @param array $values
         * @param array $params
         * @param string $where
         * @return bool
         * @since FPCM 2.4.0
         */
        public function update($table, array $fields, $params = null, $where = null) {
            $table = (is_array($table)) ? FP_PREFIX.'_'.implode(', '.FP_PREFIX.'_', $table) : FP_PREFIX."_$table";            
            $sql = "UPDATE $table SET ";                        
            $sql .= implode(' = ?, ', $fields).' = ?';            
            if(!is_null($where)) $sql .= " WHERE $where";

            return $this->exec($sql, $params);            
        }
        
        /**
         * Führt DELETE-Befehl auf DB aus
         * @param string $table
         * @param string $where
         * @param array $params
         * @return bool
         * @since FPCM 2.4.0
         */
        public function delete($table, $where = null, $params = null) {
            $table = (is_array($table)) ? FP_PREFIX.'_'.implode(', '.FP_PREFIX.'_', $table) : FP_PREFIX."_$table";
            $sql    = "DELETE FROM $table";
            if(!is_null($where)) $sql .= " WHERE $where";    

            return $this->exec($sql, $params);
        }
        
        /**
         * Ändert Tabellenstruktur
         * @param string $table
         * @param string $methode
         * @param string $field
         * @param string $condition
         * @since FPCM 2.4.0
         */
        public function alter($table, $methode, $field, $condition = "") {
            $table = (is_array($table)) ? FP_PREFIX.'_'.implode(', '.FP_PREFIX.'_', $table) : FP_PREFIX."_$table";
            $sql = "ALTER TABLE $table $methode $field $condition";
            return $this->exec($sql);
        }         

        /**
         * Führt ein SQL Kommando aus
         * @param string $command SQL String
         * @param array $bindParams Paramater, welche gebunden werden sollen
         * @return void
         */
        public function exec($command, $bindParams = null) {
            $statement = $this->connection->prepare($command);     
            if(defined('FPCM_LOGSQLQUERY') && FPCM_LOGSQLQUERY) {
                fpMessages::writeToSqlLog(trim($statement->queryString));
                
                if(FPCM_LOGSQLQUERY > 1) {
                    ob_start();
                    $statement->debugDumpParams();                
                    fpMessages::writeToSqlLog(ob_get_contents());                
                    ob_clean();
                }
            }

            if(!$statement->execute($bindParams)) {
                $this->getError();
                return false;
            }
            
            return true;
        }

        /**
         * Führt ein SQL Kommando aus und gibt Result-Set zurück
         * @param string $command SQL String
         * @param array $bindParams Paramater, welche gebunden werden sollen
         * @return PDOStatement Zeilen in der Datenbank
         */
        public function query($command, $bindParams = null) {            
            $statement = $this->connection->prepare($command);       	
            if(defined('FPCM_LOGSQLQUERY') && FPCM_LOGSQLQUERY) {
                fpMessages::writeToSqlLog(trim($statement->queryString));
                
                if(FPCM_LOGSQLQUERY > 1) {
                    ob_start();
                    $statement->debugDumpParams();                
                    fpMessages::writeToSqlLog(ob_get_contents());                
                    ob_clean();
                }
            }
            
            if($bindParams != null) { $statement->execute($bindParams); } else { $statement->execute(); }
            return $statement;
        }
        
        /**
         * Zählt nach den angebenen Einstellungen
         * @param string $table In welcher Tabelle soll gezählt werden
         * @param string $countitem Welche Spalte soll gezählt werden
         * @param string $where Nach welchen Filterkriterien soll gezählt werden
         * @return int
         */
        public function count($table, $countitem = '*',$where = null) {
            $sql = "SELECT count(".$countitem.") AS counted FROM ".FP_PREFIX."_".$table;
            if($where != null) { $sql .= " WHERE ".$where.";"; }
            
            $result = $this->query($sql);	
            if($result === false) { $this->getError();return false; }
            $row = $this->fetch($result);

            return isset($row->counted) ? $row->counted : 0;
        }

        /**
         * Liefert den letzten Fehler der DB Verbindung zurück
         * @return array
         */
        public function getError() {	
            $errorArray = $this->connection->errorInfo();   
            fpMessages::writeToSqlLog(implode(PHP_EOL, $errorArray));
        }

        /**
         * Liefert eine Zeile des results als Objekt zurück
         * @param PDOStatement $result Resultset
         * @param bool $getAll soll fetchAll() erzwungen werden
         * @return object
         */		
        public function fetch($result,$getAll = false) {
            if($result->rowCount() > 1 || $getAll == true) {
                return $result->fetchAll(PDO::FETCH_OBJ);
            } else {
                return $result->fetch(PDO::FETCH_OBJ);
            }
        }
        
        /**
         * Liefert ID des letzten Insert-Eintrags
         * @return string
         * @since FPCM 2.1.2
         */
        public function getLastInsertId() {
            $return = $this->connection->lastInsertId();
            if(defined('FPCM_LOGSQLQUERY')) { fpMessages::writeToSqlLog('Last insert id was '.$return); }
            return $return;
        }
        
        /**
         * Liefert höchten Wert einer Tabellen-ID
         * @param string $table Tabellen-Name
         * @return int
         * @since FPCM 2.2.0
         */
        public function getMaxTableId($table) {
            $sql = "SELECT max(id) as maxid from ". FP_PREFIX. "_".$table.";";            
            $data = $this->fetch($this->query($sql));
            
            if(defined('FPCM_LOGSQLQUERY')) { fpMessages::writeToSqlLog('Max ID in table '.$table.' is '.$data->maxid); }
            
            return $data->maxid;
        }
    }
?>