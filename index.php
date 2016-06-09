<?php

require 'vendor/autoload.php';

use App\ResponsiveChecker;

$url = $_POST['url'];
$error = false;
$fullUrl = "http://{$url}";

if ($_POST) {
    if ($url == "") {
        $error = true;
    } else {      
        $result = new ResponsiveChecker($url);
    }
}

?>

<html>
<head>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <form class="well" method="POST" style="margin-top: 10%;">
            <h2>
                Enter a URL
            </h2>
            <p>
                To check to see if the website is responsive...
            </p>
            <div class="row">
                <div class="form-group col-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">http://</span>
                        <input type="text" name="url" class="form-control input-lg" placeholder="fabspider.com" value="<?php echo $url; ?>">
                    </div>
                </div>
                <div class="col-sm-2">
                    <button type="submit" class="btn btn-primary default btn-lg btn-block">Check URL</button>
                </div>
            </div>
            <?php if ($error) : ?>
                <p class="alert alert-danger">"<strong><?php echo $url; ?></strong>" is not a valid URL</p>
            <?php endif; ?>
        </form>
    <?php if (isset($result)) :?>
        <?php if ($result->isResponsive()) : ?>
            <div class="panel panel-success">
        <?php else: ?>
            <div class="panel panel-danger">
        <?php endif; ?>
                <div class="panel-heading">
                    Result
                </div>
                <div class="panel-body">
<?php 
if ($result->isResponsive()) {
    echo "{$url} shows signs of being responsive";
    if ($result->isRedirected()) { 
        echo "<strong> via redirecting</strong> to <a href='{$result->finalUrl()}' target='_blank'>{$result->finalUrl()}</a>";
    }
} else {
    echo "{$url} doesn't look to be responsive";
}
?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>
