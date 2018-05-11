<?php
class MysqlDb {
    // mysql server configuration
    private $dbhost = 'localhost';
    private $dbuser = 'unmanned_api';
    private $dbpass = 'eGSFZ9Op#NZ%';
    private $dbname = 'unmanned_database';
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
        while( $result = mysqli_fetch_array($mysql_query, MYSQLI_ASSOC ) ) {
            $return[] = $result;
        }
        return $return;
    }

    // handles statements: update, insert etc.
    public function query( $query ) {
        return mysqli_query($this->mysql, $query);
    }
}

?>