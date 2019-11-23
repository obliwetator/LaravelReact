$(document).ready(function() {
    $("#getRequest").click(function(err) {
        $.ajax({
            type: "GET",
            url: "summoner/champions",
            error: function(jqxhr, status, exception) {
                console.log("an error occured", exception);
            },
            success: function(data) {
                $("#getRequestData").append(data);
            },
            statusCode: {
                404: function() {
                    console.log("not found");
                }
            }
        });
    });
});

var url_string = window.location.href;
var url = new URL(url_string);
var get = url.searchParams.get("name");




$('#LiveGame-tab').on('click', function (e) {
	if ($('#LiveGame').text().length > 0) {

	}
	else{
		// Hack add a space 
		$('#LiveGame').append(" ");
		$.ajax({
			type: "GET",
			url: "summoner/champions/ajax/liveGame?name="+get,
			error: function(jqxhr, status, exception) {
				console.log("an error occured", exception);
			},
			success: function(data) {
				$("#LiveGame").append(data);
			},
			statusCode: {
				404: function() {
					console.log("not found");
				}
			}
		});
	}
})


$(document).on("click", '.test', function(e){
	e.preventDefault();
	var link = $(this),
		linkClass = link.data('tab-show-class');

	console.log(linkClass);
	// Get the parent element in order to grab all the children an dhide them
	link.parent().children().removeClass('active');
	// Now to the clicked element add the active class
	link.addClass('active');
	
	var children;

	children = link.parent().parent().parent().children('.MatchDetailContent');


	children.children().removeClass('active');

	var targetClass = children.children("."+linkClass);

	targetClass.addClass('active');
	console.log(targetClass);
});

$( document ).ready(function() {
    $('.Stats.Button').on('click', function (e) {
		e.preventDefault();
		var link = $(this);

		// Get the parent element in order to grab all the children an dhide them
		var gameId = link.parent().parent().data("gameid");
		var Span = link.children().children().children().children();
		// Now to the clicked element add the active class
		
		var GameItem = link.parent().parent();
		var GameItemData = GameItem.data("game-result");


		var children = link.parent().parent().children('.GameDetails');
		var targetClass = children;

		if (GameItem.hasClass("active")) {
			children.hide();
			GameItem.removeClass("active");
			Span.text("More");
		}
		else{
			if (children.is(":visible")) {
				$.test.summoner.renewBtn.individualGame.ajax(gameId, targetClass);
			}
			else{
				children.show();
			}
			GameItem.addClass("active");

			Span.text("Less");
				
		}

	  });
});


$.test.summoner = {
	renewBtn: {
		start: function (btn){
			$.ajax({
				headers :{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				type: "POST",
				url: "summoner?name=1234",
				data: {name2: "???", value: 3},
				error: function(jqxhr, status, exception) {
					console.log("an error occured", exception);
				},
				success: function(data) {
					$("#getRequestData").append(data);
				},
				statusCode: {
					404: function() {
						console.log("not found");
					}
				}
			});
		},
		individualGame: {
			ajax: function(gameId, targetClass){
				$.ajax({
					headers :{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					type: "POST",
					url: "/summoner/individualGameAjax?gameId="+ gameId,
					data: gameId,
					error: function(jqxhr, status, exception) {
						console.log("an error occured", exception);
					},
					success: function(data) {
						targetClass.append(data);
					},
					statusCode: {
						404: function() {
							console.log("not found");
						}
					}
				});
			},
		},
	}
};
// declare json object in JS
var JSONa = [10,20]
console.log(JSONa.data);
// Convert JSON to object
var decoded = JSON.parse('{ "data":[10,20]}');
console.log(decoded.data);

var WinLoss = [10,20];
var data = {
	labels: ['Win', 'Loss'],
	datasets: [{
		data: WinLoss,
		backgroundColor: [
			'rgba(54, 162, 235, 0.7)',
			'rgba(255, 99, 132, 0.7)',
		],
		borderColor: [
			'rgba(54, 162, 235, 1)',
			'rgba(255, 99, 132, 1)',
		]
	}],
};
var settings =  {
				tooltips:{
					enabled: false,
				},
				legend:{
					display: false,
				}
			}

var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx, {
	type: 'doughnut',
	data: data,
	options: settings
});

