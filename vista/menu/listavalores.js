	$(document).ready(function(){
		$("#periodo").select2({
			ajax: {
				url: "../../prog/menu/cboPeriodo.php",
				type: "post",
				dataType: "json",
				delay: 100,
				data: function (params){
					return {
						PeriodoBusca: params.term
					};
				},
				processResults: function (response) {
					return {
						results: response
					};
				},
				cache: true
			}
		}),
			$("#periodo2").select2({
				ajax: {
					url: "../../prog/menu/cboPeriodo.php",
					type: "post",
					dataType: "json",
					delay: 100,
					data: function (params){
						return {
							PeriodoBusca: params.term
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
