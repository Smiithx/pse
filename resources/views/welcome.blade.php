@extends("layout.layout")
@section("content")
    <form action="{{url("/transactions")}}" method="post" id="form_transaction">
        {{ csrf_field() }}
        <div class="row">
            <fieldset>
                <legend>Datos de compra:</legend>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="pagador_id">Pagador</label>
                        <select name="pagador_id" id="pagador_id" class="form-control select2">
                            @foreach($people as $person)
                                <option value="{{$person->id}}">{{$person->full_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="comprador_id">Comprador</label>
                        <select name="comprador_id" id="comprador_id" class="form-control select2">
                            @foreach($people as $person)
                                <option value="{{$person->id}}">{{$person->full_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="receptor_id">Receptor</label>
                        <select name="receptor_id" id="receptor_id" class="form-control select2">
                            @foreach($people as $person)
                                <option value="{{$person->id}}">{{$person->full_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="referencia">Referencia</label>
                        <input type="text" name="referencia" id="referencia" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="descripcion">Descripcion</label>
                        <input type="text" name="descripcion" id="descripcion" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="totalAmount">Valor total</label>
                        <input type="number" name="totalAmount" id="totalAmount" class="form-control" step="0.01">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="taxAmount">Impuesto aplicado</label>
                        <input type="number" name="taxAmount" id="taxAmount" class="form-control" step="0.01">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tipAmount">Propina u otros valores exentos de impuesto</label>
                        <input type="number" name="tipAmount" id="tipAmount" class="form-control" step="0.01">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="devolutionBase">Base de devolución para el impuesto</label>
                        <input type="number" name="devolutionBase" id="devolutionBase" class="form-control" step="0.01">
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
                            <option value="0">Persona</option>
                            <option value="1">Empresa</option>
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
                                <option value="">No se pudo obtener la lista de Entidades Financieras, por favor intente
                                    más
                                    tarde
                                </option>
                            @endif
                        </select>
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="row">
            <div class="col-md-12 campos_pse">
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
                        switch (res.responseCode){
                            case 1:
                                toastr.error(res.responseReasonText);
                                break;
                            case 2:
                                toastr.success(res.responseReasonText);
                                break;
                            case 3:
                                toastr.error(res.responseReasonText);
                                break;
                            case 4:
                                toastr.warning(res.responseReasonText);
                                break;
                        }
                        toastr.options.onHidden = function() {
                            window.location = res.bankURL;
                        };
                        toastr.options.onCloseClick = function() { window.location = res.bankURL; };
                    } else {
                        toastr.error(res.responseReasonText);
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