<?php
$DOMDoc = new DOMDocument();
@$DOMDoc->loadHTMLFile('./sample.html', LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);

$UL = $DOMDoc->getElementById('programming-languages-list');

if ($UL->hasChildNodes()) {
    echo 'Programming Languages List:';
    foreach ($UL->childNodes as $LI) {
        if (isset($LI->tagName) && $LI->tagName === 'li') {
            echo "\r\n\t* ", $LI->nodeValue;
        }
    }
}
