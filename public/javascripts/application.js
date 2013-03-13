
$(document).ready(function() {
	$('a[type="submit"]').click(function () {
		$(this).parent("form").submit();
	});


	$(".date-field").datepicker({
								"dateFormat": "yy-mm-dd",
								"regional" : "es"
								});
	$(".time-field").timePicker();

	$("#listado-buques .flag").tooltip();

	$('.typeahead').typeahead({
	    source: function (query, typeahead) {
	        return $.get('/buque/search', { query: query }, function (data) {
	        	var json_data = $.parseJSON(data);
	            return typeahead(json_data.results);
	        });
	    }
	});

	$("#lang").dropkick();
});