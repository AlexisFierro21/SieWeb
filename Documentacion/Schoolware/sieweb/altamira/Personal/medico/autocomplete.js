function autocomplet() {
	var min_length = 0; // min caracters to display the autocomplete
	var keyword = $('#nombre').val();
	if (keyword.length >= min_length) {
		$.ajax({
			url: 'autocompletar.php',
			type: 'POST',
			data: {keyword:keyword},
			success:function(data){
				$('#medicamento').show();
				$('#medicamento').html(data);
			}
		});
	} else {
		$('#medicamento').hide();
	}
}
 
// set_item : this function will be executed when we select an item
function set_item(item) {
	// change input value
	$('#medicamento').val(item);
	// hide proposition list
	$('#medicamento').hide();
}