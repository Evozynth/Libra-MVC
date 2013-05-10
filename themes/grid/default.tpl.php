<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$title?></title>
    <link rel="stylesheet" href="<?=$stylesheet?>">
</head>
<body>
    <div id="outer-wrap-header">
        <div id="inner-wrap-header">
            <div id="header">
                <div id="login-menu"><?=login_menu()?></div>
                <?=$header?>
            </div>
        </div>
    </div>
    
    <div id="outer-wrap-flash">
        <div id="inner-wrap-flash">
            <div id="flash">Flash</div>
        </div>
    </div>
    
    <div id="outer-wrap-featured">
        <div id="inner-wrap-featured">
            <div id="featured-first">Featured</div>
            <div id="featured-middle">Featured</div>
            <div id="featured-last">Featured</div>
        </div>
    </div>
    
    <div id="outer-wrap-main">
        <div id="inner-wrap-main">
            <div id="primary">
                <?=get_messages_from_session()?>
                <?=@$main?>
                <?=render_views()?>
            </div>
            <div id="sidebar">Sidebar</div>
        </div>
    </div>
    
    <div id="outer-wrap-triptych">
        <div id="inner-wrap-triptych">
            <div id="triptych-first">Triptych</div>
            <div id="triptych-middle">Triptych</div>
            <div id="triptych-last">Triptych</div>
        </div>
    </div>
    
    <div id="outer-wrap-footer-column">
        <div id="inner-wrap-footer-column">
            <div id="footer-column-one">Footer column one</div>
            <div id="footer-column-two">Footer column two</div>
            <div id="footer-column-three">Footer column three</div>
            <div id="footer-column-four">Footer column four</div>
        </div>
    </div>
    <div id="outer-wrap-footer">
        <div id="inner-wrap-footer">
             <div id="footer"><?=$footer?><?=get_debug()?></div>
        </div>
    </div>
   
</body>
</html>