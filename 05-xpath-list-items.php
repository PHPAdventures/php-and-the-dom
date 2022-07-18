<?php
$DOMDoc = new DOMDocument();
@$DOMDoc->loadHTMLFile('./sample.html', LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);

// Pass the XPath your target DOMDocument object.
$XPath = new DOMXPath($DOMDoc);

/*
 * CSS / document.querySelector:
 * .framework
 */
$frameworksLIs = $XPath->query("//*[@class='framework']");

echo 'List of PHP Frameworks:';

foreach ($frameworksLIs as $LI) {
    echo "\r\n\t* ", $LI->nodeValue;
}
