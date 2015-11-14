<?php

define('URL_ROOT', "http://{$_SERVER['HTTP_HOST']}" . dirname($_SERVER['SCRIPT_NAME']) . '/');

// Configuration
define('DEFAULT_LAYOUT', 'default_layout');
define('SPLASH', 'splash_layout');
define('CONTENT_DIR', realpath('content'));

// Javascripts
define('JS_COLLAPSE', 1);
define('JS_ANIMATION', 2);
define('JS_VIDEO', 4);
define('JS_MENU', 8);
define('JS_ALL', 15);

error_reporting(E_ALL);
ini_set('display_errors', "On");

class Page {
    public $url;
    public $file;
    public $template;
    public $name;
    public $description;
    public $javascripts = JS_ALL;
    public $underpages = array();
    public static $webpages = array();

    function __construct($url, $file, $name) {
        $this->url = $url;
        $this->file = CONTENT_DIR . $file;
        $this->name = $name;
        $this->template = DEFAULT_LAYOUT;
        if (func_num_args() > 3) {
            $this->underpages = array_slice(func_get_args(), 3);
        }
        self::$webpages[$url] = $this;
    }

    function setTemplate($template) {
        $this->template = $template;
        return $this;
    }

    function setJavascripts($javascripts) {
        $this->javascripts = $javascripts;
        return $this;
    }

    function setDescription($description) {
        $this->description = $description;
        return $this;
    }
}

function default_layout(Page $page) {

    require('navbar.php');
    print_html_start($page);
    echo '<header>';
    print_navbar($page, Page::$webpages);
    echo '</header>';
    print_page_content($page);
    require('footer.html');
}


function print_page_content(Page $page) {
    if (file_exists($page->file)) {
        $file_contents = file_get_contents($page->file);
        // Truncate everything before and after the <main> element
        echo preg_replace("/^.*?(<main.*?<\\/main>).*\$/is", '$1', $file_contents, 1);
    } else {
        echo "<p>This page does not exist yet.</p>";
    }
}


function index_layout(Page $page) {
    require('navbar.php');
    print_html_start($page);
    // Get the navbar HTML
    ob_start();
    print_navbar($page, Page::$webpages);
    $navbarHtml = ob_get_clean();
    // Get the page HTML
    ob_start();
    print_page_content($page);
    $pageHtml = ob_get_clean();
    // Do the replacing and print it out
    echo str_replace('{MENU HERE}', $navbarHtml, $pageHtml);
    require('footer.html');
}


/**
 * Find the mime type to be used, based on the given file extension.
 * @param string $fileExtension File extension which determines the mime type.
 * Do not include the dot.
 * @return string Mime type, which can be used in a Content-Type header.
 */
function getMimeType($fileExtension)
{
    $fileExtension = strtolower($fileExtension);
    switch ($fileExtension) {
        case 'html':
        case 'htm':
        case 'twig':
            return 'text/html';
        case 'jpg':
        case 'jpeg':
            return 'image/jpeg';
        case 'png':
            return 'image/png';
        case 'css':
            return 'text/css';
        case 'js':
            return 'text/javascript';
        case 'pdf':
            return 'application/pdf';
        case 'vob':
            return 'video/dvd';
        case 'mp4':
            return 'video/mp4';
        case 'gif':
            return 'image/gif';
        case 'wmv':
            return 'video/x-ms-wmv';
        default:
            trigger_error("Unknown file extension $fileExtension");
            return 'text/plain';
    }
}

class NotFoundException extends RuntimeException {}
/**
 * Send correct Content-Type headers, based on the given file extension.
 * @param string $fileExtension File extension to use when determining the
 * mime type. Do not include the first dot.
 */
function sendMimeType($fileExtension)
{
    $mimeType = getMimeType($fileExtension);
    header("Content-Type: $mimeType");
}

function parseUrl($url) {
    $url = strtolower($url);
    static $thisScript;
    if (!isset($thisScript)) {
        $thisScript = preg_quote(basename($_SERVER['SCRIPT_FILENAME']), '/');
    }
    $filter = "/^.*\\/$thisScript(\\/[a-z0-9_\\-\\/\\.]*)\\/?\$/i";
    $fullPath = preg_filter($filter, '$1', $url);
    if ($fullPath === null) {
        throw new InvalidArgumentException();
    }
    return str_replace('.html', '', $fullPath);
}

function generatePagesDictionary()
{

    new Page('/', '/index.html', 'Front page',
        new Page('/locations/france/about', '/locations/france/france.html', 'France',
            new Page('/locations/france/charmonix', '/locations/france/charmonix.html', 'Charmonix'),
            new Page('/locations/france/val_thorens', '/locations/france/val_thorens.html', 'Val Thorens')
        ),

        new Page('/locations/austria/about', '/locations/austria/austria.html', 'Austria',
            new Page('/locations/austria/bad_gastein', '/locations/austria/bad_gastein.html', 'Bad Gastein'),
            new Page('/locations/austria/st_anton', '/locations/austria/st_anton.html', 'St. Anton')
        ),

        new Page('/locations/colorado/about', '/locations/colorado/colorado.html', 'Colorado',
            new Page('/locations/colorado/aspen', '/locations/colorado/aspen.html', 'Aspen'),
            new Page('/locations/colorado/telluride', '/locations/colorado/telluride.html', 'Telluride')
        ),

        new Page('/about', '/about_us.html', 'About us'),
        new Page('/contact', '/contact_us.html', 'Contact'),
        new Page('/sitemap', '/sitemap.html', 'Sitemap')
    );
    Page::$webpages['/']->setTemplate('index_layout');
}

function printErrorPage() {
    readfile("error.html");
}