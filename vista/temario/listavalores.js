$(document).ready(function () {
        $("#pedagogico").select2({
            ajax: {
                url: "../../prog/temario/cboPedagogico.php",
                type: "post",
                dataType: "json",
                delay: 100,
                data: function (params) {
                    return {
                        PedagogicoBusca: params.term
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
        $("#materialapoyo").select2({
            ajax: {
                url: "../../prog/temario/cboMaterialapoyo.php",
                type: "post",
                dataType: "json",
                delay: 100,
                data: function (params) {
                    return {
                        MaterialapoyoBusca: params.term
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
        $("#herramientatic").select2({
            ajax: {
                url: "../../prog/temario/cboHerramientatic.php",
                type: "post",
                dataType: "json",
                delay: 100,
                data: function (params) {
                    return {
                        HerramientaticBusca: params.term
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
