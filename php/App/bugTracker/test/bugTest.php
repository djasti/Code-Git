<?php

require_once("Model/BugModel.php");
require_once("Controller/controller.php");

$mb = new BugModel();
$mb->create("Another new bug", "This is another bug for bug tracker");
?>
