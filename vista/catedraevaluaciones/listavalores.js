	$(document).ready(function(){
		$("#momento").select2({
			ajax: {
				url: "../../prog/catedraevaluaciones/cboMomento.php",
				type: "post",
				dataType: "json",
				delay: 100,
				data: function (params){
					return {
						AreasdocumentoBusca: params.term
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
