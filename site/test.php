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
if (!empty($_POST['submitted'])) {
    // Yes, handle file upload
    // Validate data
    if (!isset($_FILES['file']) || empty($_FILES['file']['name'])) {
        die("Please upload a file");
    }
    if (!is_uploaded_file($_FILES['file']['tmp_name'])) {
        die("An error occurred");
    }
    if (empty($_POST['template'])) {
        die("Please pick a template to apply");
    }


    if (!array_key_exists($_POST['template'], Page::$webpages)) {
        die("Template not found");
    }

    // Display the page
    $page = Page::$webpages[$_POST['template']];
    $page->file = $_FILES['file']['tmp_name'];
    $template = $page->template;
    $template($page);
} else {
    // Display web form that lets the developer preview how their html will look
    ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Preview your HTML</title>
</head>
<body>
<form enctype="multipart/form-data" action="<?= basename(__FILE__) ?>" method="post">
    <h2>Preview your HTML file in context</h2>
    <p>The file you pick will not be saved on the server. Please use git to
    upload your document and make it publicly accessible.</p>
    <p>
        <label for="template">Which html page?</label><br/>
        <select id="template" name="template">
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
        <label for="file">Pick the HTML to preview: </label><br/>
        <input type="file" id="file" name="file">
    </p>
    <p>
        <input type="hidden" name="submitted" value="yes">
        <input type="submit" name="sup" value="Upload and generate preview">
    </p>
</form>
</body>
</html>
<?php
}