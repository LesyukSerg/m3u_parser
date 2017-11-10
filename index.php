<?
    session_start();
    require "functions.php";
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

        <script src="/js/jquery.min.js"></script>
        <!--<link rel="stylesheet" href="/css/bootstrap.min.css">
        <script src="/js/bootstrap.min.js"></script>-->
        <link rel="stylesheet" href="/css/m3u.css">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="/js/my.js"></script>
        <script src="/js/functions.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <h1>m3u Playlist Downloader</h1>
                </div>
                <div class="col-xs-12 col-sm-6 text-right">
                    <button type="button" class="download btn btn-primary ">Download m3u</button>
                    <input style="display:none" class="playlist" type="file" onchange="uploadFile($(this))" accept=".m3u">
                    <button type="button" class="process btn btn-info <?=(!$playlist ? 'disabled' : '')?>">Process</button>
                    <button type="button" class="stop btn btn-danger <?=(!$playlist ? 'disabled' : '')?>">Stop</button>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 song-container">
                    <? if ($playlist): ?>
                        <? foreach ($_SESSION['songs'] as $k => $song): ?>
                            <div class="song-item alert alert-<?=$s[$song['status']]?>" data-id="<?=$k?>">
                                <strong>Info!</strong> <?=$song['name']?>
                            </div>
                        <? endforeach; ?>
                    <? endif; ?>
                </div>
            </div>
        </div>
        <form id="load_file" action="process.php">
            <input type="hidden" name="action" value="LOAD"/>
        </form>
    </body>
</html>
