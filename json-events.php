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

    if ($_SESSION['title']) {

        echo json_encode(array(
            array(
                'id' => 111,
                'title' => "alessandro",
                'start' => "$year-$month-10",
                'url' => "http://www.mrwebmaster.it/"
            ),
            array(
                'id' => 222,
                'title' => "Evento 2",
                'start' => "2010-04-06T13:15:30Z",
                'end' => "2010-04-06T14:15:30Z",
                'allDay' => false
            ),
            array(
                'id' => 333,
                'title' => "Evento 3",
                'start' => "$year-$month-20",
                'end' => "$year-$month-22",
                'url' => "http://www.google.it/"
            )
        ));
    } else {
        if ($_SESSION['note'] !== true) {
            $_SESSION['orario'] = $_GET['orario'];
            $titolo = $_SESSION['orario'];
            echo json_encode(array(
                array(
                    'id' => 111,
                    'title' => "Appuntamento",
                    'start' => "$year-$month-10",
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
        }
    }