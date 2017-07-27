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
        $completed = str_repeat('#', $step);
        $left = str_repeat('-', $total - $step);

        return '[' . $completed . $left . ']<br>';
    }

    function check_dir($dir)
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    $time = microtime(1);
    $dir = $_SERVER['DOCUMENT_ROOT'] . '/downloaded';
    check_dir($dir);

    //$files = scandir($_SERVER['DOCUMENT_ROOT']);
    //unset($files[array_search('.', $files)]);
    //unset($files[array_search('..', $files)]);
    //unset($files[array_search('.idea', $files)]);
    $found = glob("*.m3u");

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

