<?php
    /**
     * Created by PhpStorm.
     * User: Serg
     * Date: 20.06.2017
     * Time: 16:17
     */

    function curl($url, $proxy = false, $post = [], $headers = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0); // return headers 0 no 1 yes
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return page 1:yes
        curl_setopt($ch, CURLOPT_TIMEOUT, 200); // http request timeout 20 seconds
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects, need this if the url changes
        curl_setopt($ch, CURLOPT_MAXREDIRS, 2); //if http server gives redirection responce
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_COOKIEJAR, "cookies.txt"); // cookies storage / here the changes have been made
        curl_setopt($ch, CURLOPT_COOKIEFILE, "cookies.txt");
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // false for https
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip"); // the page encoding

        if (count($post)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        }

        if (count($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        if ($proxy) {
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
        }

        $output = curl_exec($ch);
        $error = curl_error($ch);

        if (strlen($error)) {
            var_dump($error);
        }

        curl_close($ch);

        return $output;
    }

    function progress($total, $step)
    {
        $total = ceil($total / 2);
        $step = ceil($step / 2);
        $completed = str_repeat('#', $step);
        $left = str_repeat('--', $total - $step);

        return '[' . $completed . $left . ']<br>';
    }

    function check_dir($dir)
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    function show_songs($rebuild = 0)
    {
        if ($rebuild) {
            unset($_SESSION['songs']);
        }

        if (isset($_SESSION['playlist'])) {
            $source = $_SESSION['playlist'];
            $data = file($source);
            $number = 0;
            foreach ($data as $k => $line) {
                if (strstr($line, '//') || strstr($line, 'http')) {
                    $name = preg_replace("#\#EXTINF\:\d+,#", "", $data[$k - 1]);
                    $name = trim($name);
                    if (!isset($_SESSION['songs'][$name])) {
                        $_SESSION['songs'][$name] = [
                            'index'  => ++$number,
                            'name'   => $name,
                            'link'   => trim($line),
                            'status' => '0'
                        ];
                    }
                }
            }
        }
    }

    function check_playlist()
    {
        $source = $_SERVER['DOCUMENT_ROOT'] . "/m3u-files/";
        $found = glob($source . "*.m3u");

        if (isset($found[0])) {
            $_SESSION['playlist'] = $found[0];
        } else {
            unset($_SESSION['playlist']);
        }
    }

    function move_old_playlist()
    {
        $source = $_SERVER['DOCUMENT_ROOT'] . "/m3u-files/";

        if (isset($_SESSION['playlist']) && is_file($_SESSION['playlist'])) {
            $name = preg_replace("#.*\/([^\/]+)$#", "$1", $_SESSION['playlist']);
            if (copy($_SESSION['playlist'], $source . 'old/' . $name)) {
                unlink($_SESSION['playlist']);
                unset($_SESSION['playlist']);
            }
        }
    }

    function load_playlist($file)
    {
        $source = $_SERVER['DOCUMENT_ROOT'] . "/m3u-files/";

        if (isset($_SESSION['playlist']) && is_file($_SESSION['playlist'])) {
            move_old_playlist();
        }

        if (isset($file['type']) && $file['type'] == 'audio/x-mpegurl') {
            if (move_uploaded_file($file['tmp_name'], $source . $file['name'])) {
                unset($_SESSION['songs']);

                return 1;
            }
        }

        return 0;
    }

    function get_song($songID, $year = "2017")
    {
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/downloaded';

        $album = preg_replace("#.*\/([^\/]+) \d{4}.*#", "$1", $_SESSION['playlist']);
        $dir .= "/" . $album;
        check_dir($dir);

        $song = $_SESSION['songs'][$songID];
        $_SESSION['songs'][$songID]['status'] = 3;

        $file = $song['name'] . '.mp3';
        $file = str_replace(["\\", "/", ":"], "", $file);

        if (!file_exists($dir . '/' . $file)) {
            $songData = curl(trim($song['link']));

            if (file_put_contents($dir . '/' . $file, $songData)) {
                require_once 'id.php';
                $id3 = &new MP3_Id(); // создаем объект, считываем данные

                $res = $id3->read($dir . '/' . $file);
                $songName = explode('-', $song['name']);

                $id3->setTag('track', $song['index']);
                //echo "Записал тег <b>track</b> - " . ceil($k / 2) . "<br>";

                $id3->setTag('name', trim(end($songName)));
                //echo "Записал тег <b>name</b> - " . trim(end($song)) . "<br>";

                $id3->setTag('artists', trim($songName[0]));
                //echo "Записал тег <b>artists</b> - " . trim($song[0]) . "<br>";

                $id3->setTag('year', $year);
                //echo "Записал тег <b>year</b> - " . $year . "<br>";

                $id3->setTag('album', $album);
                //echo "Записал тег <b>album</b> - " . $album . "<br>";

                $id3->write();
                $_SESSION['songs'][$songID]['status'] = 1;

                return filesize($dir . '/' . $file);
            }

            $_SESSION['songs'][$songID]['status'] = 2;

        } else {
            $_SESSION['songs'][$songID]['status'] = 1;

            return filesize($dir . '/' . $file);
        }

        return 0;
    }
