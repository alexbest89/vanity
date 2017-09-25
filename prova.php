<?php

    $connection = mysqli_connect(‘localost’,‘root’,‘25111989’,‘agenda’) or die(mysqli_error($connection));


    if(isset($_POST[‘action’]) or isset($_GET[‘view’])) //show all events

{

    if(isset($_GET[‘view’]))

    {

        header(‘Content-Type: application/json’);

        $start = mysqli_real_escape_string($connection,$_GET[“start”]);

        $end = mysqli_real_escape_string($connection,$_GET[“end”]);



        $result = mysqli_query($connection,“SELECT id, start ,end ,title FROM  events where (date(start) >= ‘$start’ AND date(start) <= ‘$end’)”);

        while($row = mysqli_fetch_assoc($result))

        {

            $events[] = $row;

        }

        echo json_encode($events);

        exit;

    }

    elseif($_POST[‘action’] == “add”) // add new event

    {

        mysqli_query($connection,“INSERT INTO events (

        title ,

        start ,

        end

    )

                    VALUES (

                    ‘”.mysqli_real_escape_string($connection,$_POST[“title”]).“‘,

                    ‘”.mysqli_real_escape_string($connection,date(‘Y-m-d H:i:s’,strtotime($_POST[“start”]))).“‘,

                    ‘”.mysqli_real_escape_string($connection,date(‘Y-m-d H:i:s’,strtotime($_POST[“end”]))).“‘

                    )”);

        header(‘Content-Type: application/json’);

        echo ‘{“id”:”‘.mysqli_insert_id($connection).‘”}’;

        exit;

    }

    elseif($_POST[‘action’] == “update”)  // update event

    {

        mysqli_query($connection,“UPDATE events set

            start = ‘”.mysqli_real_escape_string($connection,date(‘Y-m-d H:i:s’,strtotime($_POST[“start”]))).“‘,

            end = ‘”.mysqli_real_escape_string($connection,date(‘Y-m-d H:i:s’,strtotime($_POST[“end”]))).“‘

            where id = ‘”.mysqli_real_escape_string($connection,$_POST[“id”]).“‘”);

        exit;

    }

    elseif($_POST[‘action’] == “delete”)  // remove event

    {

        mysqli_query($connection,“DELETE from events where id = ‘”.mysqli_real_escape_string($connection,$_POST[“id”]).“‘”);

        if (mysqli_affected_rows($connection) > 0) {

            echo “1”;

        }

        exit;

    }

}

?>
?>
<html>

<head>

<title>Prova</title>

    <script src=“https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js”></script>
    <script src=“https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js” crossorigin=“anonymous”></script>

    <link  href=“https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css” rel=“stylesheet” >

    <!— fullcalendar —>

    <link href=“css/fullcalendar.css” rel=“stylesheet” />

    <link href=“css/fullcalendar.print.css” rel=“stylesheet” media=“print” />

    <script src=“js/moment.min.js”></script>

    <script src=“js/fullcalendar.js”></script>

</head>
<body>

<div class=“container”>

    <div class=“row”>

        <div id=“calendar”></div>



    </div>

</div>





<!— Modal  to Add Event —>

<div id=“createEventModal” class=“modal fade” role=“dialog”>

    <div class=“modal-dialog”>



        <!— Modal content—>

        <div class=“modal-content”>

            <div class=“modal-header”>

                <button type=“button” class=“close” data–dismiss=“modal”>&times;</button>

                <h4 class=“modal-title”>Add Event</h4>

            </div>

            <div class=“modal-body”>

                <div class=“control-group”>

                    <label class=“control-label” for=“inputPatient”>Event:</label>

                    <div class=“field desc”>

                        <input class=“form-control” id=“title” name=“title” placeholder=“Event” type=“text” value=“”>

                    </div>

                </div>



                <input type=“hidden” id=“startTime”/>

                <input type=“hidden” id=“endTime”/>







                <div class=“control-group”>

                    <label class=“control-label” for=“when”>When:</label>

                    <div class=“controls controls-row” id=“when” style=“margin-top:5px;”>

                    </div>

                </div>



            </div>

            <div class=“modal-footer”>

                <button class=“btn” data–dismiss=“modal” aria–hidden=“true”>Cancel</button>

                <button type=“submit” class=“btn btn-primary” id=“submitButton”>Save</button>

            </div>

        </div>



    </div>

</div>



<!— Modal to Event Details —>

<div id=“calendarModal” class=“modal fade”>

    <div class=“modal-dialog”>

        <div class=“modal-content”>

            <div class=“modal-header”>

                <button type=“button” class=“close” data–dismiss=“modal”>&times;</button>

                <h4 class=“modal-title”>Event Details</h4>

            </div>

            <div id=“modalBody” class=“modal-body”>

                <h4 id=“modalTitle” class=“modal-title”></h4>

                <div id=“modalWhen” style=“margin-top:5px;”></div>

            </div>

            <input type=“hidden” id=“eventID”/>

            <div class=“modal-footer”>

                <button class=“btn” data–dismiss=“modal” aria–hidden=“true”>Cancel</button>

                <button type=“submit” class=“btn btn-danger” id=“deleteButton”>Delete</button>

            </div>

        </div>

    </div>

</div>

<script>

    $(document).ready(function(){

        var calendar = $(‘#calendar’).fullCalendar({  // assign calendar

            header:{

                left: ‘prev,next today’,

        center: ‘title’,

        right: ‘agendaWeek,agendaDay’

    },

        defaultView: ‘agendaWeek’,

        editable: true,

            selectable: true,

            allDaySlot: false,



            events: “index.php?view=1”,  // request to load current events





        eventClick:  function(event, jsEvent, view) {  // when some one click on any event

            endtime = $.fullCalendar.moment(event.end).format(‘h:mm’);

            starttime = $.fullCalendar.moment(event.start).format(‘dddd, MMMM Do YYYY, h:mm’);

            var mywhen = starttime + ‘ – ‘ + endtime;

            $(‘#modalTitle’).html(event.title);

            $(‘#modalWhen’).text(mywhen);

            $(‘#eventID’).val(event.id);

            $(‘#calendarModal’).modal();

        },



        select: function(start, end, jsEvent) {  // click on empty time slot

            endtime = $.fullCalendar.moment(end).format(‘h:mm’);

            starttime = $.fullCalendar.moment(start).format(‘dddd, MMMM Do YYYY, h:mm’);

            var mywhen = starttime + ‘ – ‘ + endtime;

            start = moment(start).format();

            end = moment(end).format();

            $(‘#createEventModal #startTime’).val(start);

            $(‘#createEventModal #endTime’).val(end);

            $(‘#createEventModal #when’).text(mywhen);

            $(‘#createEventModal’).modal(‘toggle’);

        },

        eventDrop: function(event, delta){ // event drag and drop

            $.ajax({

                url: ‘index.php’,

            data: ‘action=update&title=’+event.title+‘&start=’+moment(event.start).format()+‘&end=’+moment(event.end).format()+‘&id=’+event.id ,

                type: “POST”,

            success: function(json) {

                //alert(json);

            }

        });

        },

        eventResize: function(event) {  // resize to increase or decrease time of event

            $.ajax({

                url: ‘index.php’,

            data: ‘action=update&title=’+event.title+‘&start=’+moment(event.start).format()+‘&end=’+moment(event.end).format()+‘&id=’+event.id,

                type: “POST”,

            success: function(json) {

                //alert(json);

            }

        });

        }

    });



        $(‘#submitButton’).on(‘click’, function(e){ // add event submit

            // We don’t want this to act as a link so cancel the link action

            e.preventDefault();

            doSubmit(); // send to form submit function

        });



        $(‘#deleteButton’).on(‘click’, function(e){ // delete event clicked

            // We don’t want this to act as a link so cancel the link action

            e.preventDefault();

            doDelete(); send data to delete function

        });



        function doDelete(){  // delete event

            $(“#calendarModal”).modal(‘hide’);

            var eventID = $(‘#eventID’).val();

            $.ajax({

                url: ‘index.php’,

            data: ‘action=delete&id=’+eventID,

                type: “POST”,

            success: function(json) {

                if(json == 1)

                    $(“#calendar”).fullCalendar(‘removeEvents’,eventID);

            else

                return false;





            }

        });

        }

        function doSubmit(){ // add event

            $(“#createEventModal”).modal(‘hide’);

            var title = $(‘#title’).val();

            var startTime = $(‘#startTime’).val();

            var endTime = $(‘#endTime’).val();



            $.ajax({

                url: ‘index.php’,

            data: ‘action=add&title=’+title+‘&start=’+startTime+‘&end=’+endTime,

                type: “POST”,

            success: function(json) {

                $(“#calendar”).fullCalendar(‘renderEvent’,

                {

                    id: json.id,

                        title: title,

                    start: startTime,

                    end: endTime,

                },

                true);

            }

        });



        }

    });

</script>
</body>
</html>

<script src=“https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js”></script>