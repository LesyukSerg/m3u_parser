<?php
    session_start();
    require "functions.php";

    if (isset($_POST['ajax'])) {
        if (isset($_POST['action'])) {
            if ($_POST['action'] == 'LOAD') {
                echo load_playlist($_FILES['file']);

            } elseif ($_POST['action'] == 'getSong') {
                echo get_song($_POST['song'], date("Y"));

            } elseif ($_POST['action'] == 'complete') {
                move_old_playlist();
                echo get_archive();
            }
        }
    }
