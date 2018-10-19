@extends("layout.layout")
@section("content")
    <form action="{{url("/transactions")}}" method="post" id="form_transaction">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="pagador">Pagador</label>
                    <input type="text" name="pagador" id="pagador" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="comprador">Comprador</label>
                    <input type="text" name="comprador" id="comprador" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="medio_pago">Medio de pago</label>
                    <select name="medio_pago" id="medio_pago" class="form-control">
                        <option value="">Seleccionar</option>
                        <option value="PSE">PSE</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6 campos_pse">
                <div class="form-group">
                    <label for="">Tipo de cliente</label>
                    <select name="tipo_cliente" id="tipo_cliente" class="form-control">
                        <option value="persona">Persona</option>
                        <option value="empresa">Empresa</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6 campos_pse">
                <div class="form-group">
                    <label for="banco">Banco</label>
                    <select name="banco" id="banco" class="form-control select2">
                        @if($bancos)
                            @foreach($bancos as $banco)
                                <option value="{{$banco->bankCode}}">{{$banco->bankName}}</option>
                            @endforeach
                        @else
                            <option value="">No se pudo obtener la lista de Entidades Financieras, por favor intente más
                                tarde
                            </option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-md-6 campos_pse">
                <div class="form-group">
                    <br>
                    <button class="btn btn-primary form-control" type="submit">Comprar</button>
                </div>
            </div>
        </div>
    </form>
@endsection
@section("title")
    Compra
@endsection
@push("js")
@include("plugins.select2")
<script>
    $(function () {
        var medio_pago = $("#medio_pago");
        var campos_pse = $(".campos_pse");
        var form_transaction = $("#form_transaction");

        medio_pago.change(function () {
            if (medio_pago.val() == "PSE") {
                campos_pse.fadeIn();
            } else {
                campos_pse.hide();
            }
        });
        medio_pago.change();

        form_transaction.submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: form_transaction.attr("action"),
                data: form_transaction.serialize(),
                type: form_transaction.attr("method"),
                dataType: "json",
                beforeSend: function () {
                    form_transaction.find("button[type=submit]").button("loading");
                },
                success: function (res) {
                    if (res.success) {

                    } else {

                    }
                },
                error: function (e) {
                    console.log(e);
                    form_transaction.find("button[type=submit]").button("reset");
                    if (e.responseJSON.errors) {
                        $.each(e.responseJSON.errors, function (index, element) {
                            $.each(element, function (i, error) {
                                toastr.error(error);
                            });
                        });
                    } else {
                        toastr.error("Error!");
                    }

                },
                complete: function () {
                    form_transaction.find("button[type=submit]").button("reset");
                }
            });
        });
    });
</script>
@endpush