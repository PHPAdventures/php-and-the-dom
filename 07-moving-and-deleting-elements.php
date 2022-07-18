<?php
$DOMDoc = new DOMDocument();
@$DOMDoc->loadHTMLFile('./sample.html', LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);
$XPath = new DOMXPath($DOMDoc);

// Re-order sections:
$languagesSection = $DOMDoc->getElementById('programming-languages');
$frameworksSection = $XPath->query("//*[@id='php-frameworks']")[0];

$frameworksSection->insertBefore($languagesSection);

// Delete footer:
$body = $XPath->query("//body")[0];
$footer = $XPath->query("//footer")[0];

$body->removeChild($footer);

// Print result:
echo $DOMDoc->saveHTML();
