<?php

include_once("design.inc");

// Displays the inital page for bugTracker
function loadFrontView() { 
   bugTrackerHeader();
   loadBugTrackerFooterView();
   bugTrackerFooter();
}

// Displays the Search page for bugTracker
function loadSearchView() {
   bugTrackerHeader();
   searchView();
   loadBugTrackerFooterView();
   bugTrackerFooter();
}

// Displays the page for new bugs bugTracker
function loadNewBugView() {
   bugTrackerHeader();
   loadBugTrackerFooterView();
   bugTrackerFooter();
}

function loadBugTrackerFooterView() {
   include "bugFooter.html";
}

// Displays the page for a particular bug 
function loadBugView($bug) {
   bugTrackerHeader();
   if ($bug) {
     $bugId = $bug[0][0];
     $bugTitle = $bug[0][1];

     echo "<p>";
     echo "<text>BugId:  </text>" . $bugId . "<br>";
     echo "<text>Title:  </text>"  . $bugTitle . "<br>";
 
     echo "<table border=1> ";
     echo "<tr> <th> Status </th> <th> Date Updated </th> <th> Description </th></tr>";
     foreach ($bug as $col) {
       echo "<tr> ";
       for ($i=2; $i<count($col); $i++) {
          echo "<td> " . $col[$i] . " </td>";
       }
       echo "</tr> ";
     }
     echo "</p>";
   }
   loadBugTrackerFooterView();
   bugTrackerFooter();
}

// Displays all currently open bugs
function loadListView($result) {
   bugTrackerHeader();
   if ($result !== false) {
      echo "<table border=1> <tr>";
      echo "<th scope='col'> <a href='index.php?orderBy=bugId'> BugID </a></th> ";
      echo "<th scope='col'> <a href='index.php?orderBy=title'> Title </a></th> ";
      echo "<th scope='col'> <a href='index.php?orderBy=status'> Status </a></th> ";
      echo "<th scope='col'> <a href='index.php?orderBy=dateUpdated'> Date Updated </a></th> ";
      echo "</tr>";
      foreach ($result as $row) {
         echo "<tr> ";
         foreach ($row as $r) {
           echo "<td> " . $r . "</td>";
         }
         echo "</tr> ";
      }
      echo "</table> ";
   }
   loadBugTrackerFooterView();
   bugTrackerFooter();
}
?>
