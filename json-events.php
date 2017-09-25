<?php
/**
 * Created by PhpStorm.
 * User: wurz89
 * Date: 17/09/17
 * Time: 21:45
 */
    session_start();
    $year = date('Y');
    $month = date('m');

    if ($_GET['orario']){
        $orario = $_GET['orario'];
        $giorno = $_GET['giorno'];
        echo json_encode(array(
            array(
                'id' => 111,
                'title' => "Appuntamento",
                'start' => "$giorno",
                'url' => "http://www.mrwebmaster.it/"
            )));
    } else {

        //il passaggio dei data devo provare a farlo in ajax

        $_SESSION['orario'] = $_GET['orario'];
        $orario = $_SESSION['orario'];
        $data = $_GET['giorno'];
        echo json_encode(array(
            array(
                'id' => 111,
                'title' => "Appuntamento",
                'start' => "$datasel."." ".".$orario"
            )));
    };