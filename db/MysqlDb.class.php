<?php
require_once "../config.php";

class MysqlDb {
    // mysql server configuration
    private $dbhost = DB_HOST;
    private $dbuser = DB_USER;
    private $dbpass = DB_PASS;
    private $dbname = DB_NAME;
    protected $mysql;

    // check if connection is alive, if not establish it
    function conect() {
        if ( !is_resource($this->mysql) ) {
            if($this->mysql = mysqli_connect( $this->dbhost, $this->dbuser, $this->dbpass )) {
                mysqli_select_db($this->mysql, $this->dbname) or $this->error();
            }
        }
    }
  
  function disconect() {
    if ( is_resource($this->mysql) ) {
      mysqli_close($this->mysql);
    }
  }

    // error reporting
    private function error() {
        return printf( 'MySQL ERROR: %s (%d)', mysqli_error(), mysqli_errno() );
    }

    // handles queries resulting in output
    public function fetch_array( $query ) {
        $mysql_query = mysqli_query($this->mysql, $query);
      $return = [];
        while( $result = mysqli_fetch_array($mysql_query, MYSQLI_ASSOC ) ) {
            $return[] = $result;
        }
        return $return  ;
    }
  
  public function fetch_single( $query ) {
    $mysql_query = mysqli_query($this->mysql, $query);
    $result = mysqli_fetch_array($mysql_query, MYSQLI_ASSOC );
    return $result;
  }

    // handles statements: update, insert etc.
    public function query( $query ) {
        return mysqli_query($this->mysql, $query);
    }
}

?>