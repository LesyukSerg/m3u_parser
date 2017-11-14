<?php
    session_start();
    require "functions.php";

    if (isset($_POST['ajax'])) {
        if (isset($_POST['action'])) {
            if ($_POST['action'] == 'LOAD') {
                echo load_playlist($_FILES['file']);

            } elseif ($_POST['action'] == 'getSong') {
                echo get_song($_POST['song'], date("Y"));
            }
        }
    }
    die;


    $time = microtime(1);
    $dir = $_SERVER['DOCUMENT_ROOT'] . '/downloaded';
    check_dir($dir);

    //$files = scandir($_SERVER['DOCUMENT_ROOT']);
    //unset($files[array_search('.', $files)]);
    //unset($files[array_search('..', $files)]);
    //unset($files[array_search('.idea', $files)]);
    $found = glob("*.m3u");

    if (isset($found[0])) {
        $data = file($found[0]);
        $album = explode(' ', $found[0]);
        $album = trim($album[0]);

        if ($album) {
            $dir .= '/' . $album;
            check_dir($dir);
        }

        preg_match("/(\d{4})-\d\d-\d\d/", $found[0], $f);
        $year = $f[1] ? intval($f[1]) : '';

        if ($data) {
            require_once 'id.php';
            $id3 = &new MP3_Id(); // создаем объект, считываем данные
            $total = count($data);

            foreach ($data as $k => $line) {
                if (strstr($line, '//') || strstr($line, 'http')) {

                    $name = explode(',', $data[$k - 1]);
                    $file = trim($name[1]) . '.mp3';
                    $file = str_replace(["\\", "/", ":"], "", $file);

                    if (!file_exists($dir . '/' . $file)) {
                        echo progress($total, $k);

                        $song = curl(trim($line));
                        file_put_contents($dir . '/' . $file, $song);

                        echo '[DOWNLOADED] ' . $file . " " . round(filesize($dir . '/' . $file) / (1024 * 1024), 2) . " мб <br>";

                        $res = $id3->read($dir . '/' . $file);
                        $song = explode('-', $name[1]);

                        $id3->setTag('track', ceil($k / 2));
                        echo "Записал тег <b>track</b> - " . ceil($k / 2) . "<br>";

                        $id3->setTag('name', trim(end($song)));
                        echo "Записал тег <b>name</b> - " . trim(end($song)) . "<br>";

                        $id3->setTag('artists', trim($song[0]));
                        echo "Записал тег <b>artists</b> - " . trim($song[0]) . "<br>";

                        $id3->setTag('year', $year);
                        echo "Записал тег <b>year</b> - " . $year . "<br>";

                        $id3->setTag('album', $album);
                        echo "Записал тег <b>album</b> - " . $album . "<br>";

                        $id3->write();
                        flush();

                        echo "<script>window.location = window.location.href</script>";
                        break;
                    }
                }
            }
        }

        echo "done";
    } else {
        echo "playlist not found";
    }


