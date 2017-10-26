<?
    session_start();
    require "functions.php";
    $_SESSION['playlist'] = 'Monstercat - Best Of 2016 2017-10-26 10-15.m3u';
    $playlist = isset($_SESSION['playlist']);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>M3U Downloader</title>

        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/m3u.css">
        <script src="/js/jquery.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/my.js"></script>
        <script src="/js/functions.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <h1>m3u Playlist Downloader</h1>
                </div>
                <div class="col-xs-12 col-sm-6 text-right">
                    <button type="button" class="download btn btn-primary <?=($playlist ? 'disabled' : '')?>">Download m3u</button>
                    <input style="display:none" class="playlist" type="file" onchange="uploadFile($(this))">
                    <button type="button" class="process btn btn-info <?=(!$playlist ? 'disabled' : '')?>">Process</button>
                    <button type="button" class="stop btn btn-danger <?=(!$playlist ? 'disabled' : '')?>">Stop</button>
                </div>
            </div>
            <?
                show_songs();
                $s = [0 => 'info', '1' => 'success', 2 => 'danger'];
            ?>
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <? foreach ($_SESSION['songs'] as $song): ?>
                        <div class="alert alert-<?=$s[$song['status']]?>">
                            <strong>Info!</strong> <?=$song['name']?>
                        </div>
                    <? endforeach; ?>
                    <div class="alert alert-success"></div>
                    <div class="alert alert-warning"></div>
                    <div class="alert alert-danger"></div>
                </div>
            </div>
        </div>
        <form id="load_file">
            <input type="hidden" name="action" value="LOAD_FILE"/>
        </form>
    </body>
</html>
