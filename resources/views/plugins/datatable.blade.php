@push('css')
<link rel="stylesheet" href="{{asset('plugins/datatables/css/dataTables.bootstrap.css')}}">
@endpush

@push('js')
<script src="{{asset('plugins/datatables/js/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables/js/dataTables.bootstrap.js')}}"></script>
<script>
    $(function () {
        $.fn.dataTable.ext.errMode = 'none';

        var config_datatable = {
            "language": {
                infoEmpty: 'Sin registros disponibles',
            },
            "fnDrawCallback": function( oSettings ) {
                reloadTooltips();
            },
        };

        $.extend($.fn.dataTable.defaults, config_datatable);
    });
</script>
@endpush
