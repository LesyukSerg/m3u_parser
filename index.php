<?
    session_start();
    require "functions.php";
    define("DIR", getDir());
    //unset($_SESSION['songs']);
    check_playlist();
    $playlist = isset($_SESSION['playlist']);
    $s = [0 => 'info', '1' => 'success', 2 => 'danger', 3 => 'warning'];

    if ($playlist) {
        show_songs();
    } else {
        unset($_SESSION['songs']);
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>M3U Downloader</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script src="<?=DIR?>/js/jquery.min.js"></script>
        <!--<link rel="stylesheet" href="/css/bootstrap.min.css">
        <script src="/js/bootstrap.min.js"></script>-->
        <link rel="stylesheet" href="<?=DIR?>/css/m3u.css">

        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="<?=DIR?>/js/my.js"></script>
        <script src="<?=DIR?>/js/functions.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <h1>m3u Playlist Downloader</h1>
                </div>
                <div class="col-xs-12 col-sm-6 text-right">
                    <button type="button" class="download btn btn-primary ">
                        <? if ($playlist): ?>
                            Download new m3u
                        <? else: ?>
                            Download m3u
                        <? endif; ?>
                    </button>
                    <input style="display:none" class="playlist" type="file" onchange="uploadFile($(this))" accept=".m3u">
                    <button type="button" class="process btn btn-info <?=(!$playlist ? 'disabled' : '')?>">Process</button>
                    <button type="button" class="stop btn btn-danger <?=(!$playlist ? 'disabled' : '')?>">Stop</button>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 song-container">
                    <div class="loader"></div>
                    <? if ($playlist): ?>
                        <? foreach ($_SESSION['songs'] as $k => $song): ?>
                            <div class="song-item alert alert-<?=$s[$song['status']]?>" data-id="<?=$k?>">
                                <strong>Info!</strong> <?=$song['name']?>
                            </div>
                        <? endforeach; ?>
                    <? else: ?>
                        <div class="song-item alert alert-info">
                            <strong>Info!</strong> Download .m3u file to start
                        </div>
                    <? endif; ?>
                </div>
            </div>
        </div>
        <form id="load_file" action="process.php">
            <input type="hidden" name="action" value="LOAD"/>
        </form>
    </body>
</html>
