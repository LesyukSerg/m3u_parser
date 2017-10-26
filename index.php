<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>M3U Downloader</title>

        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/m3u.css">
        <script defer src="/js/jquery.min.js"></script>
        <script defer src="/js/bootstrap.min.js"></script>
        <script defer src="/js/my.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <h1>m3u Playlist Downloader</h1>
                </div>
                <div class="col-xs-12 col-sm-6 text-right">
                    <button type="button" class="btn btn-primary">Download m3u</button>
                    <button type="button" class="btn btn-info">Process</button>
                    <button type="button" class="btn btn-danger">Stop</button>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <div class="alert alert-success">
                        <strong>Success!</strong> Indicates a successful or positive action.
                    </div>

                    <div class="alert alert-info">
                        <strong>Info!</strong> Indicates a neutral informative change or action.
                    </div>

                    <div class="alert alert-warning">
                        <strong>Warning!</strong> Indicates a warning that might need attention.
                    </div>

                    <div class="alert alert-danger">
                        <strong>Danger!</strong> Indicates a dangerous or potentially negative action.
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>