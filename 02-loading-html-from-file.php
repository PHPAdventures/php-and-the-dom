<?php
// Load HTML from a document:
$DOMDoc = new DOMDocument();
$DOMDoc->loadHTMLFile('./hello.html');

// Retrieve HTML as a string.
echo $DOMDoc->saveHTML();
