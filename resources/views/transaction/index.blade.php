@extends("layout.layout")
@section("content")
    <div class="container-fluid">
        <div class="table-responsive">
            <div class="container-fluid">
                <table class="table responsive table-vcenter dataTable no-footer" id="tabla_transaction">
                    <thead class="">
                    <tr>
                        <th>TransactionID</th>
                        <th>Pagador</th>
                        <th>Comprador</th>
                        <th>Receptor</th>
                        <th>Valor total</th>
                        <th>Estado</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
@section("title")
    Transacciones
@endsection
@push("js")
@include("plugins.datatable")
<script>
    $(function () {
        var tabla_transaction = $("#tabla_transaction");

        function inicializar() {
            tabla_transaction.DataTable({
                'ajax': {
                    "url": '{{url("/transactions")}}',
                    "type": "GET",
                    dataSrc: '',
                },
                'columns': [
                    {data: 'transactionID'},
                    {data: 'player.full_name'},
                    {data: 'buyer.full_name'},
                    {data: 'shipping.full_name'},
                    {data: 'totalAmount',className: "text-right"},
                    {
                        render: function (data, type, row) {
                            var text = row.responseReasonText;
                            var state = "";
                            switch (row.responseCode) {
                                case 0:
                                    state = "danger";
                                    break;
                                case 1:
                                    state = "success";
                                    break;
                                case 2:
                                    state = "danger";
                                    break;
                                case 3:
                                    state = "warning";
                                    break;
                            }
                            state = "<span class='label label-" + state + "'></span>";
                            return state;
                        },
                        className: "text-center"
                    },

                ]
            });
        }

        inicializar();

    });
</script>
@endpush