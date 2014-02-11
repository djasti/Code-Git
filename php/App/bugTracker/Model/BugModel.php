<?php

require_once("db/DB.inc");

/**
 * class that manages bugs
 */

class BugModel {

   // constructor for BugModel that access
   // database and performs operations on bugs
   public function __construct() {
     date_default_timezone_set('UTC');
   }

   // Method to convert unix timestamp to MySql timestamp
   private function unixToMySQLTS($ts) {
      return date('Y-m-d H:i:s', $ts);
   }

   // Query the databas
   private function query($sql) {
      // Get the database connection
      $db = new mysqli(HOST, USER, PWD, DB) ;
      if ($db->connect_errno > 0) {
         return false;
      }
      // Query database
      if (($result = $db->query($sql)) === FALSE) {
        return false;
      }
     return $result;
   }

   // Insert values in Table bugs
   private function createInBugs($title, $ts) {
      $id = 0;
      $createSQL = "INSERT INTO ". BUGS_TABLE . " VALUES " ;
      // Do an input check and insert if valid
      if (isset($title)) {
        $createSQL .= "($id, '$title', '$ts');";
        if ($this->query($createSQL) === false) {
          echo "Issue with $createSQL due to $db->error\n";
          return null;
        } else {
          $currentId = $db->insert_id;
        }
      }
      return $currentId;
    }

    // Insert values in Table bugUpdates
    private function createInBugUpdates($currentId, $description, $ts, $status = "NEW") {
      // Add the description in bugUpdates and Status table
      $createDescriptionSQL = "INSERT INTO " . BUGUPDATES_TABLE . " VALUES " ;
      $createDescriptionSQL .= "('$status', $currentId, '$ts', '$description')";

      if ($this->query($createDescriptionSQL) === false) {
        //echo ("Query unsuccessul " . $db->error);
        return false;
      }
      return true;
    }

   // create a new bug in the database
   // return true if successful.
   public function create($title, $description) {
      // Convert to MySql timestamp
      $ts = $this->unixToMySQLTS(time());

      // Create a new bug in BUGS table
      $currentId = $this->createInBugs($title, $ts);

      // If previous query was successful
      if ($currentId != null) {
         return $this->createInBugUpdates($currentId, $title, $description, $ts);
      } else {
         return false;
      }
      return true;
   }

   public function update($id, $status, $description) {
       $ts = $this->unixToMySQLTS(time());
     
       return $this->createInBugUpdates($id, $description, $ts, $status);
   }


   // Searches the database for bug with id and returns the bug 
   public function search($id) {
      $title = "title";
      $bugId = "bugId";
      $status = "status";
      $dateUpdated = "dateUpdated";
      $description = "description";

      // Search SQL
      $sql = "Select ".BUGS_TABLE.".$bugId, $title, $status, $dateUpdated, $description from ".BUGS_TABLE.",".BUGUPDATES_TABLE. " where ".BUGS_TABLE.".$bugId=".BUGUPDATES_TABLE.".$bugId AND ".BUGS_TABLE.".$bugId=$id  ORDER BY $dateUpdated DESC;";

      $queryResult = $this->query($sql);
      if ($queryResult) {
        $result = array();
        while( $row = $queryResult->fetch_row()) {
           array_push($result, $row);
        }
        return $result;
      } else {
        return false;
      }
   }


   // Get the list of Bugs
   function listOfBugs($order = "") { 
      $title = "title";
      $bugId = "bugId";
      $status = "status";
      $dateUpdated = "dateUpdated";
      $orderBy = "bugId";

      if (strlen($order) > 0) {
         $orderBy = $order;
      }

      // sql query for getting list of bugs 
      $sql = " SELECT " .BUGS_TABLE. ".$bugId, $title, $status, $dateUpdated FROM " .BUGS_TABLE.",".BUGUPDATES_TABLE. " WHERE ".BUGS_TABLE.".$bugId=".BUGUPDATES_TABLE.".$bugId ORDER BY $orderBy ;"; 
      $queryResult = $this->query($sql);
      if ($queryResult) {
        $result = array();
        while( $row = $queryResult->fetch_row()) {
           array_push($result, $row);   
        }
        return $result;
      } else {
        return false;
      }
   }

   // Get a list of bugs with a particular status
   public function listOfBugsWithStatus($requestedStatus) { 
      $title = "title";
      $bugId = "bugId";
      $status = "status";
      $dateUpdated = "dateUpdated";

      // sql query for getting list of bugs with a certain status
      $sql = "SELECT ".BUGS_TABLE.".$bugId, $title, $status, $dateUpdated FROM ".BUGS_TABLE." JOIN ".BUGUPDATES_TABLE. " ON ".BUGS_TABLE.".$bugId=".BUGUPDATES_TABLE.".$bugId WHERE $status='$requestedStatus';";

      $queryResult = $this->query($sql);
      if ($queryResult) {
        $result = array();
        while( $row = $queryResult->fetch_row()) {
           array_push($result, $row);   
        }
        return $result;
      } else {
        return false;
      }
   }
}

?>
