<!DOCTYPE html>


prova
<html>
  <head>
      <title>My App</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui">

      <link rel="stylesheet" href="app.min.css">

      <link rel='stylesheet' href='fullcalendar/fullcalendar.css' />
      <script src='fullcalendar/lib/jquery.min.js'></script>
      <script src='fullcalendar/lib/moment.min.js'></script>
      <script src='fullcalendar/fullcalendar.js'></script>

      <style>
			@-webkit-keyframes pulse {
				0% {
					background-color: #CCC;
				}
				25% {
					background-color: #EEE;
				}
				50% {
					background-color: #CCC;
				}
				75% {
					background-color: #EEE;
				}
				100% {
					background-color: #CCC;
				}
			}

            #calendar {
                width: 100%;
                height: 100%;
                margin: 0 auto;
            }

          p {
              text-align: center;
          }
            #btn-calen{
                border: 1px solid grey;
                background-color: red;
                border-radius: 15%;
                width: 80px;
                height: 30px;
            }

      </style>
  </head>

  <body>
    <div class="app-page" data-page="home">
      <div class="app-topbar blue">
        <div class="app-title">Claudia Beauty</div>
      </div>
      <div class="app-content">
          <p class="app-section">
              Prenota il tuo trattamento comodamente!
          </p>
          
          <div class="app-section">
            <div class="app-button" id="parru">Claudia Parrucchieri</div>
          </div>
          <div class="app-section">
              <div class="app-button" id="este">Centro Estetico</div>
          </div>
      </div>
    </div>

  <div class="app-page" data-page="sendemail">
      <div class="app-topbar">
          <div class="app-title"><span class="app-icon"></span>Prenotazione</div>
          <div class="right app-button" data-back>Fine</div>
      </div>

      <div class="app-content">

          <div class="app-section" id="message"></div>

          <div class="app-section">
              Nome: <input class="app-input" id="sender" placeholder="Nome">
          </div>

          <div class="app-section">
              Telefono: <input class="app-input" id="telefono" placeholder="telefono">
          </div>

          <div class="app-button" id="trat_par">Trattamenti</div>

          <div class="app-section" id="scelta">
              <ul class="app-list">
                  <li class="app-button">Unghie</li>
                  <li class="app-button">Ceretta</li>
                  <li class="app-button">Piega</li>
              </ul>
          </div>

          <div class="app-button" id="day">Calendario</div>

          <form class="app-section">
              <div class="app-section" id="servizio"><strong>I trattamenti scelti:<br></strong> </div>

              <textarea class="app-input" name="message" placeholder="Message" id="content"></textarea>

              <div class="app-button green app-submit" id="send-button">Prenota</div>
          </form>

      </div>

      <div class="app-page" data-page="day">
          <div class="app-topbar">
              <div class="app-title"><span class="app-icon"></span>Calendario</div>
              <div class="right app-button" data-back>Fine</div>
          </div>
          <div class="app-section" id="appuntamento">
              <form>
                  <input name="giorno" class="app-input" id="giorno"><strong></strong></input>
                <input class="app-input" type="datetime" name="orario" id="orario" placeholder="Inserisci l'ora">
                <button type="submit" id="btn-calen">Success</button>
              </form>
          </div>
          <div class="app-section">
            <div id="calendar"></div>
          </div>
      </div>

  </div>


    <script src="zepto.js"></script>
    <script src="app.min.js"></script>
    <script>

        App.controller('day', function (page) {
            $(page).find("#appuntamento").hide();

            $(document).ready(function() {
                var date = new Date();
                var d = date.getDate();
                var m = date.getMonth();
                var y = date.getFullYear();

                $('#calendar').fullCalendar({
                    navLinks: true,
                    navLinkDayClick: function(date, jsEvent) {
                        var datasel = date.format(); // date is a moment
                        $("#giorno").val(datasel);

                        $(page).find("#appuntamento").show();
                        $(page).find("#calendar").hide();

                        $("#btn-calen").click(function () {
                            alert("tasto premuto" + $("#giorno").val() + " " + $("#orario").val());
                        })
                    }
                });

                /*$(document).ready(function() {

                    $('#calendar').fullCalendar({
                        dayClick: function() {

                            $(page).find("#appuntamento").show();
                            $(page).find("#calendar").hide();

                            $("#btn-calen").click(function () {
                                //alert("tasto premuto");
                            })

                        },
                        editable: true,
                        events: "json-events.php",
                        eventDrop: function(event, delta) {
                            alert(event.title + ' modificato ');
                        },
                        loading: function(bool) {
                            if (bool) $('#loading').show();
                            else $('#loading').hide();
                        }
                    });
                });*/
                });
            });

      App.controller('home', function (page) {
    
          if (typeof localStorage !== 'undefined') {
              
              $(page).find("#parru")
                .clickable()
                .click(function () {
                  
                  if (localStorage.getItem("recipient-email") !== null) {
                  
                      localStorage.removeItem("recipient-email");
                      
                      
                  }
                  
                  App.load("sendemail");
                  
              })
              
              if (localStorage.getItem("recipient-list") !== null) {
                  
                  var recipientList = JSON.parse(localStorage.getItem("recipient-list"));
                  
                  $.each(recipientList, function( index, value ) {
                    
                      $(page).find("#contact-list").append('<div class="app-button redirect">' + value + '</div>');
                
                  });
                  
                  $(page).find("#contact-list").show();
                  
                  $(page).find(".redirect")
                      .clickable()
                      .on("click", function() {
                      
                      localStorage.setItem("recipient-email", $(this).html());
                      
                      App.load('sendemail');
                      
                      
                      
                  });
                  
                  
              } else {
                  
                  $(page).find("#contact-list").hide();
                  
              }
              
              
          }
          
      });
        
        App.controller('sendemail', function (page) {
            
            $(page).find("#message").hide();

            $(page).find("#scelta").hide();

            $(page).find("#servizio").hide();

            $(page).find("#day")
                   .clickable()
                   .click(function () {
                       App.load('day');
                   });

            $(page).find("#trat_par")
                .clickable()
                .click(function () {
                    $(page).find("#scelta").show();
                });

            $(page).find("li")
                   .clickable()
                   .click(function () {
                       $(page).find("#servizio").html($(page).find("#servizio").html() + " <br>- " + $(this).html()).show();
                       $(page).find("#scelta").hide();
                   });
            
            if (typeof localStorage !== 'undefined') {
                
                if (localStorage.getItem("sender") !== null) {
                    
                    
                    $(page).find("#sender-email").val(localStorage.getItem("sender-email"));
                    
                }
                
                if (localStorage.getItem("recipient-email") !== null) {
                    
                    $(page).find("#recipient-email").val(localStorage.getItem("recipient-email"));
                    
                }
                
            }
            
            
            
          $(page).find('#send-button')
					.clickable()
					.on('click', function () {
              
                 $.ajax({
                          type: 'GET',
                          url: 'http://completewebdevelopercourse.com/content/9-mobileapps/sendemail.php?callback=response',
                          // data to be added to query string:
                          data: { to: $("#recipient-email").val(), from: $("#sender").val(), subject: $("#subject").val(), content: $("#content").val()},
                          // type of data we are expecting in return:
                          dataType: 'jsonp',
                          timeout: 300,
                          context: $('body'),
                          success: function(data){

                              if (data.success == true) {

                                  $(page).find("#message").html("Your email was sent successfully!").show();

                              } else {

                                  $(page).find("#message").html("Your email could not be sent - please try again later.").show();

                              }


                          },
                          error: function(xhr, type){

                                $(page).find("#message").html("Your email could not be sent - please try again later.").show();

                          }
                 });
    
              
              if (typeof localStorage !== 'undefined') {
                  
                  localStorage.setItem("sender-email", $("#sender").val());
                  
                  var recipientList = new Array();
                  
                  if (localStorage.getItem("recipient-list") !== null) {
                      
                      recipientList = JSON.parse(localStorage.getItem("recipient-list"));
                      
                  }
                  
                  if ($.inArray($("#recipient-email").val(), recipientList) == -1) {
                      
                      recipientList.push($("#recipient-email").val());
                      
                      recipientList.sort();
                      
                      localStorage.setItem("recipient-list", JSON.stringify(recipientList));
                      
                      console.log(recipientList);
                      
                  }
                  
              } else {
                  
                  // alert user that we couldn't save data
                  
              }
              
          });
      });
        
       

      try {
        App.restore();
      } catch (err) {
        App.load('home');
      }
    </script>
  </body>
</html>
