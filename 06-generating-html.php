<?php
$DOMDoc = new DOMDocument();

$HTML = $DOMDoc->createElement('HTML');
$DOMDoc->append($HTML);

$HEAD = $DOMDoc->createElement('HEAD');
$HTML->append($HEAD);

$TITLE = $DOMDoc->createElement('TITLE', 'PHP Web Page');
$HEAD->append($TITLE);

$BODY = $DOMDoc->createElement('BODY');
$HTML->append($BODY);

$H1 = $DOMDoc->createElement('H1', 'PHP Web Page');
$BODY->append($H1);

$P = $DOMDoc->createElement('P', 'This web page was generated using PHP\'s ');
$BODY->append($P);

$A = $DOMDoc->createElement('A', 'DOM');
$A->setAttribute('HREF', 'https://www.php.net/manual/en/book.dom.php');
$A->setAttribute('TARGET', '_blank');
$P->append($A);

$endOfPContent = $DOMDoc->createTextNode(' implementation.');
$P->append($endOfPContent);

echo $DOMDoc->saveHTML();
