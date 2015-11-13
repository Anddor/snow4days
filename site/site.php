<?php
/**
 * Created by PhpStorm.
 * User: thorben
 * Date: 12.10.15
 * Time: 11:33
 */
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
        if (preg_match('/\.html$/i', $server['PHP_SELF'])) {
            header("HTTP/1.1 301 Moved Permanently");
            $new_url = URL_ROOT . "site.php" . parseUrl($server['REQUEST_URI']);
            header("Location: " . $new_url);
            die("Redirecting to <a href='$new_url'>$new_url</a>");
        }
        $resource = parseUrl($server['REQUEST_URI']);
        // Assume it is a file if it has a file extension
        if (strpos($resource, ".") !== false) {
            // It is a file, don't output using PHP
            header("HTTP/1.1 307 Temporary Redirect");
            $filepath = CONTENT_DIR . $resource;
            if (file_exists($filepath)) {
                header("Location: " . URL_ROOT . "content" . $resource);
                die();
            } else {
                header("HTTP/1.1 404 File Not Found");
                die('File not found');
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
    } catch (NotFoundException $e) {
        header("HTTP/1.1 404 File Not Found");
        die("<h1>404 File not found</h1>" . (isset($_SERVER['SERVER_SIGNATURE']) ? $_SERVER['SERVER_SIGNATURE'] : "We're sorry"));
    } catch (Exception $e) {
        printErrorPage();
    }
}

main($_SERVER);