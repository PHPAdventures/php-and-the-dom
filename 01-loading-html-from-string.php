<?php
// Load HTML as a string:
$DOMStringDoc = new DOMDocument();
$DOMStringDoc->loadHTML('<html>
    <head>
        <title>Hello, World!</title>
    </head>
    <body>
        <h1>Hello, World!</h1>
    </body>
</html>');

// Print HTML as a string:
echo $DOMStringDoc->saveHTML();
