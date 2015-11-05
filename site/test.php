<?php

/**
 * For development use only: allow developers to preview how their file will
 * look when accessed through the server.
 *
 */
require_once('prepend.php');
require_once('include.php');

generatePagesDictionary();

// Is the form submitted?
if (!empty($_GET['submitted'])) {
    if (empty($_GET['template'])) {
        die("Please pick a page");
    }


    if (!array_key_exists($_GET['template'], Page::$webpages)) {
        die("Template not found");
    }

    // Display the contents of that page
    $page = Page::$webpages[$_GET['template']];
    require('navbar.php');
    // Catch the output
    ob_start();
    print_navbar($page, Page::$webpages);
    $header = ob_get_clean();

    $footer = file_get_contents('footer.html');

    ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Here's your HTML</title>
</head>

<body>
<h1>HTML for <?= $page->name ?></h1>
<label for="header"><h2>Header:</h2></label>
<textarea id="header" rows="5" cols="80" readonly onclick="this.focus();this.select()"><!-- start header -->
<?= $header ?>
<!-- end header --></textarea>

<label for="footer"><h2>Footer:</h2></label>
<textarea id="footer" rows="4" cols="80" readonly onclick="this.focus();this.select()"><!-- start footer -->
<?= $footer ?>
<!-- end footer --></textarea>

<form action="<?= basename(__FILE__) ?>" method="get">
    <p>
        <label for="template">Get HTML for another page:</label><br/>
        <select id="template" name="template" onchange="this.form.submit();">
            <option selected value="">- PICK ONE -</option>
<?php
        foreach (Page::$webpages as $url => $page) {
            echo <<<EOL
            <option value="$url">$url</option>
EOL;
        } ?>
        </select>
    </p>
    <p>
        <input type="hidden" name="submitted" value="yes">
        <input type="submit" name="sup" value="Generate HTML">
    </p>
</form>
</body>
</html>

<?php

} else {
    // Display web form that lets the developer preview how their html will look
    ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Get your HTML</title>
</head>
<body>
<form action="<?= basename(__FILE__) ?>" method="get">
    <h2>Get the HTML that will surround your HTML file</h2>
    <p>Pick a target page, and you will get HTML which you can put in the file for previewing.
    Just remember to remove it before commiting it to Git!</p>
    <p>
        <label for="template">Which page?</label><br/>
        <select id="template" name="template" onchange="this.form.submit();">
            <option selected value="">- PICK ONE -</option>
<?php
        foreach (Page::$webpages as $url => $page) {
            echo <<<EOL
            <option value="$url">$url</option>
EOL;
        } ?>
        </select>
    </p>
    <p>
        <input type="hidden" name="submitted" value="yes">
        <input type="submit" name="sup" value="Generate HTML">
    </p>
</form>
</body>
</html>
<?php
}