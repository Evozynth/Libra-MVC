<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?=$title?></title>
    <link rel="stylesheet" href="<?=$stylesheet?>">
</head>
<body>
    <div id="header">
        <?=$header?>
    </div>
    <div id="main" role="main">
        <?=@$main?>
        <?=render_views()?>
        <?=get_debug()?>
    </div>
    <div id="footer">
        <?=$footer?>
    </div>
</body>
</html>