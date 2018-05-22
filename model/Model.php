<?php
require_once "../config.php";

class Model {
  const TABLE = "";
  public static function printQuery($query) {
    if (PRINT_QUERY) {
      echo "<div>" . $query . "</div>";
    }
  }
}
?>