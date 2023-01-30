<html>
<head>
<title>Event Calender</title>
<meta charset="UTF-8">
<link rel="stylesheet" href="style.css">
<style>
body {
    font-family: Arial;
}

div#event {
    box-sizing: content-box;
    margin-top: 2px;
    width: 100%;
    text-align: center;
    padding: 10px;
}

.event-row {
    padding: 5px;
}
</style>
</head>
<body>
    <div class="container">
        <div class="cal1"></div>
        <div id="event"></div>
    </div>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
    <!-- <script src="clndr.js"></script>
    <script src="event.js"></script> -->
</body>

<script>
    var calendar = {};
	$(document).ready( function() {
    var lotsOfEvents = [];
    
    $.ajax({
        url: 'getEventList.php',
        dataType:'json',
        success: function(data){
        	var datax = [];
        		for(var i = 0;i<data.length;i++) {
        			datax = {"title":data[i].title, "date": data[i].date};
        			lotsOfEvents.push(datax);
        		}
        	    
        	    calendar.clndr = $('.cal1').clndr({
        	        events: lotsOfEvents,
        	        clickEvents: {
        	            click: function (target) {
        	                $.ajax({
        	                    url: 'getEventByDate.php',
        	                    dataType:'json',
        	                    type : 'POST',
        	                    data: {
        	                        eventDate : target['date']['_i']                         
        	                    },
        	                    success: function(json){
        	                    Results(json);
        	                    }
        	                    });
        	                function Results(json){
        	                    $("#event").html("");
        	                		$(".day .day-contents").css("color", "#000");
        	                		$(".calendar-day-"+target['date']['_i']+ " .day-contents").css("color", "#FF0000");
        	                		for(var i = 0;i<json.length;i++) {
        	                			$("#event").append("<div class='event-row'>"+json[i].title+"</div>");
        	                		}
        	                    }
        	                    $("#event").empty();
        	            }
        	        },
        	        
        	        showAdjacentMonths: true,
        	        adjacentDaysChangeMonth: false
        	    });
        }
        });
});
</script>
</html>