$.test.spectate = {
	displayRunes: function (btn) {
		var row = $(btn).closest('tr'),
			rowID = row.attr('id')
			ajaxRow = "ajaxRow-" + rowID;


		var findNextRowId = $("#" + ajaxRow);
		if (findNextRowId.length > 0) {
			findNextRowId.remove();
			return;
		}

		$(".RunesDetailed").remove();


		var nextRow = $("<tr><td></td></tr>")
				.attr('id', ajaxRow).attr('class', 'RunesDetailed'),
			nextRowCell = nextRow.find('td')
				.attr('colspan', 12).attr('class', 'Cell');

		row.after(nextRow);

		var setHTML = function(html){
			nextRowCell.html(html);
		};
		var url_string = window.location.href;
		var url = new URL(url_string);
		var get = url.searchParams.get("name");
		var id = rowID;

		$.ajax({
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			type: "POST",
			url: "/summoner/champions/ajax/liveGameRunes",
			data: {name: get, id: id},
			error: function (jqxhr, status, exception) {
				console.log("an error occured", exception);
			},
			success: function (html) {
				setHTML(html);
			},
			statusCode: {
				404: function () {
					console.log("not found");
				}
			}
		});
	}
}




