<?php
use App\ViewHelpers\MimicViewHelper;

print_r($_SESSION['mimicSpeech']);

if (!$_SESSION['error'] && $data === []){
	$_SESSION['errorMessage'] = 'There are no mimics in the database.';
	$mimics = [];
	$processedTexts = [];
} else {
    $mimics = $data['mimics'];
    $processedTexts = $data['processedTexts'];
}
?>
<!DOCTYPE html>
<html lang="en-gb">
<head>
    <meta charset="utf-8"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU"
          crossorigin="anonymous">
    <link type="text/css" rel="stylesheet" href="/css/taskStyling.css">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ"
            crossorigin="anonymous"></script>
    <title>Slim ToDo App</title>
</head>
<body>
<?php
foreach ($mimics as $mimic){
    //echo MimicViewHelper::createHTMLForMimic($mimic);
}
echo MimicViewHelper::createHTMLForMimicGenerator($processedTexts);
?>
</body>
</html>

<?php
$_SESSION['error'] = false;
$_SESSION['errorMessage'] = '';
