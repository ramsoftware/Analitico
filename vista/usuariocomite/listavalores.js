	$(document).ready(function(){
		$("#programa").select2({
			ajax: {
				url: "../../prog/usuariocomite/cboPrograma.php",
				type: "post",
				dataType: "json",
				delay: 100,
				data: function (params){
					return {
						ProgramaBusca: params.term
					};
				},
				processResults: function (response) {
					return {
						results: response
					};
				},
				cache: true
			}
		})
	});
