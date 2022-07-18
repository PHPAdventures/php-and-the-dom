# PHP and the DOM

The third PHPAdventures meet-up: 2022-07-14 @ 06:00 PM MT.

This topic was covered on 2022-07-14 @ 06:00 PM MT (Thursday.)

For more information about our meet-up, please visit our website:
[https://phpadventures.com](https://phpadventures.com)

## Running these Examples

With PHP installed, clone this project into a target directory and run any of:

* `php 01-loading-html-from-string.php`
* `php 02-loading-html-from-file.php`
* `php 03-get-heading.php`
* `php 04-get-list-items.php`
* `php 05-xpath-list-items.php`
* `php 06-generating-html.php`
* `php 07-moving-and-deleting-elements.php`

## What is the DOM?

The Document Object Model is a method of representing the structure of an HTML web page or document. On the web, the most popular API for engaging with the DOM is JavaScript, as it can be used in web browsers to read and manipulate HTML elements in real-time. With JavaScript it can be used to develop very interactive and engaging user interfaces, and is utilized under-the-hood in popular and powerful libraries like React.

It is important to note, however, that JavaScript is not the only language with DOM support—PHP also has this feature. The use-cases in a back-end language like PHP most certainly differ from those of a language like JavaScript, but this feature still offers us an amazing level of utility and flexibility in our PHP programs.

To learn more about DOM in general I recommend checking out Mozilla's article on the subject, [here](https://developer.mozilla.org/en-US/docs/Web/API/Document_Object_Model).

## PHP's DOM Implementation

The DOM is not necessarily only limited to HTML files, it can usually represent other [SGML](https://en.wikipedia.org/wiki/Standard_Generalized_Markup_Language)-style formats like [SVG](https://en.wikipedia.org/wiki/Scalable_Vector_Graphics) or [XML](https://en.wikipedia.org/wiki/XML).

PHP's official documentation covers its DOM implementation, specification, and features [here](https://www.php.net/manual/en/book.dom.php). Today we'll be covering some core concepts, classes, and methods that will get you started.

## Loading HTML as a DOM Object

When writing DOM code, you'll need an instance of [`DOMDocument`](https://www.php.net/manual/en/class.domdocument.php). Once we have it, your next step is to load either an HTML string, or a document.

```PHP
<?php
// Load an HTML string.
$DOM = new DOMDocument();
$DOM->loadHTML('<p>Hello, World!</p>'); 

// Load an HTML document.
$DOMDoc = new DOMDocument();
$DOMDoc->loadHTMLFile('./hello.html');
```

There are some options you may want to consider including when running the [`loadHTML`](https://www.php.net/manual/en/domdocument.loadhtml.php) or [`loadHTMLFile`](https://www.php.net/manual/en/domdocument.loadhtmlfile.php) methods. [Peruse the predefined constants list for the "LibXML" extension](https://www.php.net/manual/en/libxml.constants.php). You'll find some that will help make your  life a bit easier when working with DOM in PHP, some examples may include:

* `LIBXML_HTML_NODEFDTD`: Prevents insertion of a `<!DOCTYPE>` if one is not found in the provided HTML.
* `LIBXML_HTML_NOIMPLIED`: Disables `<html>` and `<body>` additions in cases where they are not otherwise detected in the provided HTML.

These, as an example, may be implemented like so:

```PHP
<?php
// Load an HTML string.
$DOM = new DOMDocument();
$DOM->loadHTML('<p>Hello, World!</p>', LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);


// Load an HTML document.
$DOMDoc = new DOMDocument();
$DOMDoc->loadHTMLFile('hello.html', LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);
```

They're very handy when dealing with snippets of HTML, or when focusing on pieces of a larger page.

How do we access the HTML again, once it is loaded into a `DOMDocument` object? If you find you need that HTML back as a string, especially after making changes to that HTML, you'll want to use the [`saveHTML`](https://www.php.net/manual/en/domdocument.savehtml.php) method. Consider the following adjustment:

```PHP
// Load an HTML string.
$DOM = new DOMDocument();
$DOM->loadHTML('<p>Hello, World!</p>', LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);
$DOMString = $DOM->saveHTML();
var_dump($DOMString);

// Load an HTML document.
$DOMDoc = new DOMDocument();
$DOMDoc->loadHTMLFile('hello.html', LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);
$DOMDocString = $DOMDoc->saveHTML();
var_dump($DOMDocString);
```

If you are just worried about loading HTML, storing it, and getting a string back, that's enough to get started! To begin making this a lot more useful, however, we'll have a look at traversing the tree of nodes in a document.

## Node Traversal in a DOM Document

Let's assume a more complex HTML structure:

```HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Web Page</title>
</head>
<body>
    <h1>Sample Web Page</h1>
    <p>This is a sample web page. There are a variety of elements available for your perusal.</p>
    <section id="programming-languages">
      <h2>List of some Programming Languages</h2>
      <p>Please find below a list containing the names of a few different programming languages.</p>
      <ul id="programming-languages-list">
        <li class="language">C</li>
        <li class="language">C++</li>
        <li class="language">C#</li>
        <li class="language">Elixir</li>
        <li class="language">Go</li>
        <li class="language">Java</li>
        <li class="language">JavaScript</li>
        <li class="language">Julia</li>
        <li class="language">Kotlin</li>
        <li class="language">Lua</li>
        <li class="language">Perl</li>
        <li class="language">PHP</li>
        <li class="language">Python</li>
        <li class="language">R</li>
        <li class="language">Ruby</li>
        <li class="language">Rust</li>
        <li class="language">Swift</li>
      </ul>
    </section>
    <section id="php-frameworks">
      <h2>List of some PHP Frameworks</h2>
      <p>Here we have a list of a few different PHP frameworks; feel free to explore.</p>
      <ul id="list-of-php-frameworks">
        <li class="framework">CakePHP</li>
        <li class="framework">CodeIgniter</li>
        <li class="framework">Laminas Project</li>
        <li class="framework">Laravel</li>
        <li class="framework">Phalcon</li>
        <li class="framework">Symfony</li>
        <li class="framework">Yii</li>
      </ul>
    </section>
    <footer>
      <h2>Web Site Footer</h2>
      <p>&copy; 2022; PHPAdventures</p>
    </footer>
</body>
</html>
```

Given such a structure, we have the opportunity to explore via PHP and its DOM tools. Let's say we want to retrieve the first `H2` element in the page. As you may imagine, there are a few approaches we could take, but here is one:

```PHP
<?php
// Retrieve the target document.
$DOMDoc = new DOMDocument();
@$DOMDoc->loadHTMLFile('sample.html', LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);

// Retrieve the #programming-languages section.
$languagesSection = $DOMDoc->getElementById('programming-languages');

// Retrieve the first child of this element (H2, in our example.)
$languageSectionH2 = $languagesSection->firstElementChild;
// Note: if you used "firstChild" it would retrieve the empty text node present before the H2 instead.

// Print the element retrieved.
echo $languageSectionH2->tagName;

echo ' contains: ';

// Print the content inside.
echo $languageSectionH2->nodeValue;
```

In this example you can begin to see that there are many objects with useful properties and methods within our instance of `DOMDocument`. Many elements and nodes contain read and traversal options like:

* [`getElementById`](https://www.php.net/manual/en/domdocument.getelementbyid.php): Retrieve an element by its ID.
* [`getElementsByTagName`](https://www.php.net/manual/en/domelement.getelementsbytagname.php): Retrieve array of elements by name.
* [`tagName`](https://www.php.net/manual/en/class.domelement.php#domelement.props.tagname): This element's name.
* [`attributes`](https://www.php.net/manual/en/class.domnode.php#domnode.props.attributes): Array of element attributes.
* [`textContent`](https://www.php.net/manual/en/class.domnode.php#domnode.props.textcontent): String of the text in this node and any descendents.
* [`parentNode`](https://www.php.net/manual/en/class.domnode.php#domnode.props.parentnode): Parent of this node.
* [`nextSibling`](https://www.php.net/manual/en/class.domnode.php#domnode.props.nextsibling): Next node.
* [`previousSibling`](https://www.php.net/manual/en/class.domnode.php#domnode.props.previoussibling): Previous node.
* [`childNodes`](https://www.php.net/manual/en/class.domnode.php#domnode.props.childnodes): Array of this node's child nodes.
* [`firstChild`](https://www.php.net/manual/en/class.domnode.php#domnode.props.firstchild): First node inside this node (including text nodes.)
* [`lastChild`](https://www.php.net/manual/en/class.domnode.php#domnode.props.lastchild): Last node inside this node (including text nodes.)
* [`childElementCount`](https://www.php.net/manual/en/class.domelement.php#domelement.props.childelementcount): Number of child elements inside this element.
* [`firstElementChild`](https://www.php.net/manual/en/class.domelement.php#domelement.props.firstelementchild): First element inside this element.
* [`lastElementChild`](https://www.php.net/manual/en/class.domelement.php#domelement.props.lastelementchild): Last element inside this element.
* [`nextElementSibling`](https://www.php.net/manual/en/class.domelement.php#domelement.props.nextelementsibling): Next element.
* [`previousElementSibling`](https://www.php.net/manual/en/class.domelement.php#domelement.props.previouselementsibling): Previous element.

This is often flexible enough to get a target element in many cases. Let's suppose we want to retrieve the list of programming languages in the sample HTML document. We might try the following:

```PHP
<?php
// Retrieve the target document.
$DOMDoc = new DOMDocument();
@$DOMDoc->loadHTMLFile('sample.html', LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);

// Retrieve the #programming-languages section.
$languagesUL = $DOMDoc->getElementById('programming-languages-list');

if ($languagesUL->hasChildNodes()) {
    echo "Programming Languages Listing:";
    foreach($languagesUL->childNodes as $LI) {
        if (isset($LI->tagName) && $LI->tagName === 'li') {
            echo "\r\n\t* ", $LI->nodeValue;
        }
    }
}
```

Here we are able to loop through the entirety of the list and print the discovered data. The ability to perform targeted checks like this make it possible for PHP to be used in scraping information not only from formatted API end-points, but even HTML websites containing useful information.

## XPath

While very capable, the above strategy has its limitations. In more complex web pages, it becomes far more advantageous to quickly locate elements via their class (or another identifying factor.) While using traditional node targeting combined with attribute checks, it would be possible to loop through a page's contents and seek out an element in this way... but this would be inefficient and a re-invention of the wheel.

PHP has a tool called XPath designed to offer us similar functionality for the targeting of elements as we see in CSS selectors and JavaScript's `querySelector` method. Let us run an experiment to demonstrate:

```PHP
<?php
// Retrieve the target document.
$DOMDoc = new DOMDocument();
@$DOMDoc->loadHTMLFile('sample.html', LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);

// Retrieve all elements with class: "framework"
$XPath = new DOMXPath($DOMDoc); // Pass the DOMDocument into the DOMXPath constructor.
$frameworks = $XPath->query("//*[@class='framework']"); // Any element with class: "framework"

echo 'List of PHP Frameworks:';

foreach($frameworks as $framework) {
  echo "\r\n\t* ", $framework->textContent;
}
```

It is important to note that the syntax for XPath selections and CSS selectors *are* different, but we can do many of the same things (just with a different set of characters.) See the following examples:

| CSS Selector   | XPath Selector           | Description                                      |
|----------------|--------------------------|--------------------------------------------------|
| `h1`           | `//h1`                   | All `H1` elements.                               |
| `nav a`        | `//nav//a`               | All `A` elements anywhere inside any `NAV`s.     |
| `ul > li`      | `//ul/li`                | `LI` elements that are direct children of `UL`s. |
| `#my-id`       | `//*[@id='my-id']`       | Any element with an `id` of `my-id`.             |
| `.my-class`    | `//*[@class='my-class']` | Any elements with a `class` of `my-class`.       |
| `main#content` | `//main[@id='content']`  | Any `MAIN` element with an `id` of `content`.    |

[See this cheat sheet for quick tips and more examples.](https://devhints.io/xpath)

## Creating Elements

You can create elements and inject them into your HTML, you can move elements already existing in the document, or even remove elements:

* [`createElement`](https://www.php.net/manual/en/domdocument.createelement.php)
* [`appendChild`](https://www.php.net/manual/en/domnode.appendchild.php)
* [`insertBefore`](https://www.php.net/manual/en/domnode.insertbefore.php)
* [`insertAfter`](https://www.php.net/manual/en/domnode.insertafter.php)
* [`replaceChild`](https://www.php.net/manual/en/domnode.replacechild.php)
* [`removeChild`](https://www.php.net/manual/en/domnode.removechild.php)

Here is an example of an HTML web page that is designed programattically:

```PHP
<?php
$DOMDoc = new DOMDocument();

$HTML = $DOMDoc->createElement('HTML');
$DOMDoc->appendChild($HTML);

$HEAD = $DOMDoc->createElement('HEAD');
$HTML->appendChild($HEAD);

$TITLE = $DOMDoc->createElement('TITLE', 'PHP Web Page');
$HEAD->appendChild($TITLE);

$BODY = $DOMDoc->createElement('BODY');
$HTML->appendChild($BODY);

$H1 = $DOMDoc->createElement('H1', 'PHP Web Page');
$BODY->appendChild($H1);

$P = $DOMDoc->createElement('P', 'This web page was generated using PHP\'s ');
$BODY->appendChild($P);

$A = $DOMDoc->createElement('A', 'Document Object Model');
$A->setAttribute('HREF', 'https://www.php.net/manual/en/book.dom.php');
$A->setAttribute('TARGET', '_blank');
$P->appendChild($A);

$endOfPContent = $DOMDoc->createTextNode(' implementation.');
$P->appendChild($endOfPContent);

echo $DOMDoc->saveHTML();
```

Let's try re-ordering the `SECTION` elements in our sample HTML document from our earlier examples, and then deleting the `FOOTER` as a proof-of-concept:

```PHP
<?php
// Retrieve the target document.
$DOMDoc = new DOMDocument();
@$DOMDoc->loadHTMLFile('sample.html', LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);

// Retrieve the desired elements.
$body = $DOMDoc->getElementsByTagName('body')[0];
$programmingLanguagesSection = $DOMDoc->getElementById('programming-languages');
$phpFrameworksSection = $DOMDoc->getElementById('php-frameworks');
$footer = $DOMDoc->getElementsByTagName('footer')[0];

// Move "PHP Frameworks" SECTION up the page.
$phpFrameworksSection->insertBefore($programmingLanguagesSection);

// Remove the footer from the page.
$body->removeChild($footer);

echo $DOMDoc->saveHTML();
```

## Resources

* [Document Object Model PHP Docs](https://www.php.net/manual/en/book.dom.php)
* [W3Schools XPath Introduction](https://www.w3schools.com/xml/xpath_intro.asp)
* [XPath Cheat Sheet](https://devhints.io/xpath)

## Feedback

Questions? Feedback? Want to share your expertise in a talk of your own? [Let us know!](https://phpadventures.com/)
