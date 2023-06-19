<?php
namespace App;

class DB {
    private static $instance = null;
    private $_sql = 'sql';

    public static function getInstance()
    {        
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getLastInsertedID(){
        return $this->_mysqli->insert_id;
    }

    public function connect() {
        if (!class_exists('mysqli')) {
            if ($goOffline) {                
                exit('503');
            }
        }
        
        $this->_mysqli = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($this->_mysqli->connect_error) {
            if ($goOffline) {
                exit('503');
            }
        }
        
        $this->_table_prefix = $table_prefix;

        $this->_mysqli->query("SET NAMES 'utf8'");
        $this->_mysqli->query("SET CHARACTER SET 'utf8'");
        $this->_mysqli->query("SET SESSION collation_connection = 'utf8_general_ci'");

        $this->_ticker = 0;
        $this->_log = array();
    }

    private function getEscaped( $text, $extra = false ) {
        $string = $this->_mysqli->real_escape_string($text);

        if ($extra) {
            $string = addcslashes( $string, '%_' );
        }
        return $string;
    }

    public function Quote( $text, $escaped = true )
    {
        return '\''.($escaped ? $this->getEscaped( $text ) : $text).'\'';
    }

    public function setQuery( $sql, $offset = 0, $limit = 0, $prefix='#__' ) {
        $this->_sql = $sql;
        $this->_limit = intval( $limit );
        $this->_offset = intval( $offset );
    }

    public function query() {
        global $bConfig_debug;
        if ($this->_limit > 0 && $this->_offset == 0) {
            $this->_sql .= "\nLIMIT $this->_limit";
        } else if ($this->_limit > 0 || $this->_offset > 0) {
            $this->_sql .= "\nLIMIT $this->_offset, $this->_limit";
        }       
        if ($this->_debug) {
            $this->_ticker++;
            $this->_log[] = $this->_sql;
        }
        $this->_errorNum = 0;
        $this->_errorMsg = '';
        $this->_cursor = $this->_mysqli->query($this->_sql);
        if (!$this->_cursor) {
            $this->_errorNum = $this->_mysqli->mysqli_errno;
            $this->_errorMsg = $this->_mysqli->mysqli_error . " SQL= " . $this->_sql;
            if ($this->_debug) {
                trigger_error($this->_mysqli->mysqli_error, E_USER_NOTICE);                
                if (function_exists( 'debug_backtrace' )) {
                    foreach( debug_backtrace() as $back) {
                        if (@$back['file']) {
                            echo '<br />'.$back['file'].':'.$back['line'];
                        }
                    }
                }
            }
            return false;
        }
        return $this->_cursor;
    }
}
?>
