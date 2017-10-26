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

    function show_songs()
    {
        if (isset($_SESSION['playlist'])) {
            $source = $_SERVER['DOCUMENT_ROOT'] . "/m3u-files/" . $_SESSION['playlist'];
            $data = file($source);

            foreach ($data as $k => $line) {
                if (strstr($line, '//') || strstr($line, 'http')) {
                    $name = preg_replace("#\#EXTINF\:\d+,#", "", $data[$k - 1]);

                    if (!isset($_SESSION['songs'][$name])) {
                        $_SESSION['songs'][$name] = [
                            'name'   => $name,
                            'link'   => trim($line),
                            'status' => '0'
                        ];
                    }
                }
            }
        }
    }
