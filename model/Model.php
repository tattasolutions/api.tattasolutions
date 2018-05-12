<?php
require_once "../config.php";

class Model {
  public static function printQuery($query) {
    if (PRINT_QUERY) {
      echo "<br>" . $query;
    }
  }
}
?>