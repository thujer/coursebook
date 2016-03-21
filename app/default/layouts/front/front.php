<?php
    namespace Runtime\App\Layout;

    use \Runtime\javascript;
    use \Runtime\css;

?>
<!DOCTYPE html>
<html lang="<?=$this->a_template['language'];?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="<?=$this->a_template['content_type'];?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2, user-scalable=yes" />
        <meta name="description" content="<?=$this->a_template['descriptions'];?>" />
        <meta name="keywords" content="<?=$this->a_template['keywords'];?>" />
        <meta name="author" content="<?=$this->a_template['web_author'];?>" />
        <meta name="googlebot" content="nosnippet, noarchive">
        <meta name="robots" content="<?=$this->a_template['robots'];?>" />

        <title><?=$this->a_template['title'];?></title>

        <link rel="home" href="/" />
        <link rel="shortcut icon" href="<?=$this->a_template['favicon'];?>">

        <base href="<?=CONFIG_WEB_ROOT;?>" />

        <?=css::load_styles(); ?>

        <?=javascript::load_scripts('start'); ?>

    </head>

    <body>
    <div id="wrapper">
        <div id="content">

            <div class="background"></div>

            <div id="right" data-id="content">
                <?=$this->a_template['messages'];?>
                <?=$this->a_template['content'];?>
            </div>

            <div class="clear">&nbsp;</div>

        </div>

        <div id="footer">
            <span id="copyleft" title="thujer at gmail dot com">© 2016 <a href="http://tomas.hujer.eu" target="_blank">Tomas Hujer</a></span>
        </div>

    </div>

    <?=javascript::load_scripts('end'); ?>

    </body>
</html>
