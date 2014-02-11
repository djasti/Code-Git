<?php

require_once("Model/BugModel.php");
require_once("View/BugTrackView.php");

class Controller {
    private $model;

    public function __construct() {
      $this->model = new BugModel();
    }

    // Start processing request
    public function start() {

       // Check the requests
       if ($_SERVER['REQUEST_METHOD'] == 'GET') {
          if (isset($_GET['orderBy'])) {
               $orderBy = $_GET['orderBy'];
               $this->listPage($orderBy);
          } else if (isset($_GET['BugId'])) {
              $bugId = $_GET['BugId'];
              $this->displayBug($bugId); 
          } else if (isset($_GET['SearchForm'])) {
              $this->searchPage();
          
          } else {
              // Base page is the list of bugs
              $this->listPage("bugId");

          }
       } 
    }

    public function searchPage() {
       loadSearchView();
    }

    public function newPage() {
        loadNewBugView();
    }

    public function listPage($orderBy = "") {
        loadListView($this->model->listOfBugs($orderBy));
    }

    public function bugPage($bug) {
       loadBugView($bug);
    }

    // This will be displayed on all pages for navigation
    public function bugTrackerFooterPage() {
        loadBugTrackerFooterView();
    }

    // create new bug
    public function createBug() {

    }

    public function listOfBugs($orderBy = "") {
        return $this->model->listOfBugs($orderBy);
    }

    // Get list of bugs with a particular status and order
    public function listOfBugsWithStatus($status, $orderBy="") {
        return $this->model->listOfBugsWithStatus($status);
    }

    // Display bug information
    public function displayBug($bugId) {
      // do input validation
      if (isset($bugId)) {
        $result = $this->model->search($bugId);
        if ($result) {
          loadBugView($result);
        }
      }
    }
}
?>
