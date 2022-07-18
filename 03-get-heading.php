<?php
$DOMDoc = new DOMDocument();
@$DOMDoc->loadHTMLFile('./sample.html', LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);

// echo $DOMDoc->saveHTML();

$programmingLanguagesSection = $DOMDoc->getElementById('programming-languages');

$H2 = $programmingLanguagesSection->firstElementChild;

echo $H2->tagName;

echo ' contains: ';

echo $H2->nodeValue;
