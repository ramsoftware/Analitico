	$(document).ready(function(){
		$("#facultad").select2({
			ajax: {
				url: "../../prog/programas/cboFacultad.php",
				type: "post",
				dataType: "json",
				delay: 100,
				data: function (params){
					return {
						FacultadBusca: params.term
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
