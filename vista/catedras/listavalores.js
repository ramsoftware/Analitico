$(document).ready(function () {
    $("#periodo").select2({
        ajax: {
            url: "../../prog/catedras/cboPeriodo.php",
            type: "post",
            dataType: "json",
            delay: 100,
            data: function (params) {
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
    $("#docente").select2({
            ajax: {
                url: "../../prog/catedras/cboDocente.php",
                type: "post",
                dataType: "json",
                delay: 100,
                data: function (params) {
                    return {
                        DocenteBusca: params.term
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
        $("#cicloformacion").select2({
            ajax: {
                url: "../../prog/catedras/cboCicloformacion.php",
                type: "post",
                dataType: "json",
                delay: 100,
                data: function (params) {
                    return {
                        CicloformacionBusca: params.term
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
        $("#areaconocimiento").select2({
            ajax: {
                url: "../../prog/catedras/cboAreaconocimiento.php",
                type: "post",
                dataType: "json",
                delay: 100,
                data: function (params) {
                    return {
                        AreaconocimientoBusca: params.term
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
        $("#componenteformacion").select2({
            ajax: {
                url: "../../prog/catedras/cboComponenteformacion.php",
                type: "post",
                dataType: "json",
                delay: 100,
                data: function (params) {
                    return {
                        ComponenteformacionBusca: params.term
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
        $("#nivelformacion").select2({
            ajax: {
                url: "../../prog/catedras/cboNivelformacion.php",
                type: "post",
                dataType: "json",
                delay: 100,
                data: function (params) {
                    return {
                        NivelformacionBusca: params.term
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
        $("#modalidad").select2({
            ajax: {
                url: "../../prog/catedras/cboModalidad.php",
                type: "post",
                dataType: "json",
                delay: 100,
                data: function (params) {
                    return {
                        ModalidadBusca: params.term
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
        $("#tipo").select2({
            ajax: {
                url: "../../prog/catedras/cboTipo.php",
                type: "post",
                dataType: "json",
                delay: 100,
                data: function (params) {
                    return {
                        TipoBusca: params.term
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
        $("#editar").select2();
});
