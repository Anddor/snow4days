<?php
/**
 * Created by PhpStorm.
 * User: thorben
 * Date: 22.10.15
 * Time: 11:11
 */
// Redirect to site.php script
require_once('include.php');

header("HTTP/1.1 Moved Permanently");
header("Location: " . URL_ROOT . "site.php/");

?>Redirecting to <a href="site.php/">site.php/</a>…