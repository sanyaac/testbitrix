//Активация/Деактивация элемента инфоблока";
function sendCntAjax(id){
$.ajax({
	  type: 'POST',
	  url: 'active.php',
	  data: 'ID=' + id,
	  success: function(data) {
		$("#a_button" + id).val(data);
	  }
	});
}
