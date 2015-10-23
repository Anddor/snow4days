<?php
/**
 * Created by PhpStorm.
 * User: thorben
 * Date: 12.10.15
 * Time: 11:33
 */
require_once('prepend.php');

require_once('include.php');


function main($server) {
    // What url did the user request?
    try {
        // Was this page accessed without any url after? (that is, was the file name the last part of the url?)
        if (preg_match("/" . preg_quote(basename(__FILE__)) . "\$/i", $server['PHP_SELF'])) {
            // Redirect to index page
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: " . URL_ROOT . "site.php/");
            die("Redirecting to <a href='site.php/'>site.php/</a>");
        }
        $resource = parseUrl($server['REQUEST_URI']);
        // Assume it is a file if it has a file extension
        $fileExtension = explode(".", $resource);
        if (count($fileExtension) > 1) {
            // It is a file
            $fileExtension = $fileExtension[1];
            $filepath = CONTENT_DIR . $resource;
            if (file_exists($filepath)) {
                sendMimeType($fileExtension);
                readfile($filepath);
            } else {
                throw new NotFoundException();
            }
        } else {
            // Is this an existing url?
            generatePagesDictionary();
            if (array_key_exists($resource, Page::$webpages)) {
                // Yes, do stuff with it!
                $page = Page::$webpages[$resource];
                $pageFunction = $page->template;
                $pageFunction($page);
            } else {
                throw new NotFoundException();
            }

        }

    } catch (Exception $e) {
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
        printErrorPage();
    }
}

main($_SERVER);