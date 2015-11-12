<?php

/**
 * Converts an absolute path into a relative path.
 * @param $source string Absolute path to the base, which the path should be relative to. Must start with slash.
 * @param $destination string Absolute path to the destination, which the relative path should point to. Must start with slash.
 * @return string The relative path to destination, as seen from source.
 */
    function absolute_to_relative($source, $destination) {
        // Assuming both paths are absolute, so skip to the first slash
        $source = substr($source, strpos($source, '/'));
        $destination = substr($destination, strpos($destination, '/'));

        // Make an array, which represents the path
        $source = explode('/', $source);
        $destination = explode('/', $destination);

        // Are we referring to the top level directory from within it? (special case)
        if (count($destination) === 2 && empty($destination[1]) && count($source) === 2) {
            return '.';
        }

        // What is the last, common directory?
        $i = 0;
        while (
            $i < count($source) - 1   // As long as we have further to go in source…
            && $i < count($destination) - 1 // and in destination…
            && strcasecmp($source[$i], $destination[$i]) === 0 // and the paths aren't diverging yet
        ) {
            // increment the counter, and continue deeper
            ++$i;
        }
        // The last increment wasn't good, decrease by one
        // (because of the starting slash, we won't risk going below zero)
        $last_common = $i - 1;

        // how many directories must we go back from source?
        // + 1 to ignore file name, + 1 so that we end up _inside_ the common dir
        $num_parents = count($source) - ($i + 1);
        // go back that many directories
        $rel_path = str_repeat("../", $num_parents);
        // append the path from the last common directory to the destination
        $rel_path .= join('/', array_slice($destination, $last_common + 1));

        return $rel_path;
    }


    function print_list_entry(Page $currentPage, Page $page, $level) {
        $current = ($currentPage == $page) ? ' class="current"' : '';
        $url = absolute_to_relative($currentPage->url, $page->url);
        $underpagesExists = count($page->underpages);
        $name = $underpagesExists ? $page->name . " ▾" : $page->name;
        echo <<<EOL
<li><a href="$url" title="$page->description"$current>$name</a>
EOL;
        if ($underpagesExists) {
            $new_level = $level + 1;
            echo "<ul class='level$new_level'>";
            foreach ($page->underpages as $underpage) {
                print_list_entry($currentPage, $underpage, $level + 1);
            }
            echo "</ul>";
        }
        echo "</li>";
    }

    function print_html_start(Page $currentPage) {


?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $currentPage->name ?> - Snow4Days</title>
    <link rel="stylesheet"
          href="<?= absolute_to_relative($currentPage->url, "/styling.css"); ?>"/>
    <link
        href='https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700'
        rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Arvo:400,700'
          rel='stylesheet' type='text/css'>
    <!-- insert meta-data here -->
    <?php // include the relevant javascript files
    $javascriptFiles = array(
        JS_COLLAPSE => "collapse.js",
        JS_ANIMATION => "animation.js",
        JS_VIDEO => "video.js",
        JS_MENU => "menu.js",
    );
    foreach ($javascriptFiles as $bitvalue => $file) {
        if (($currentPage->javascripts & $bitvalue) == $bitvalue) {
            echo "\t<script src='" . absolute_to_relative($currentPage->url, "/js/$file") . "'></script>\n";
        }
    }
    ?>
</head>
<body>
<?php
}

function print_navbar(Page $currentPage, $pages) {
?>
    <nav>
        <ul class="level1">
            <?php
            if ($pages['/'] instanceof Page) {
                print_list_entry($currentPage, $pages['/'], 1);
            }
            ?>
        </ul>
    </nav>

<?php
}