$(document).ready(function() {

	$('.data-table').dataTable({
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"sDom": '<""l>t<"F"fp>',
		"oLanguage": {
			"sUrl": urlApp + "/js/vendor/dataTables.spanish.txt"
		}
	});
});