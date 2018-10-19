<?php

namespace App\Http\Controllers;

use App\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "pagador_id" => "required|exists:people,id",
            "comprador_id" => "required|exists:people,id",
            "receptor_id" => "required|exists:people,id",
            "medio_pago" => "required",
            "tipo_cliente" => "required",
            "banco" => "required"
        ]);

        $pagador = Person::findOrFail($request->pagador_id);
        $comprador = Person::findOrFail($request->comprador_id);
        $receptor = Person::findOrFail($request->receptor_id);
        try {
            $url = config("services.endpoint.url");
            $seed = date('c');
            $param_auth = [
                "login" => config("services.endpoint.auth.login"),
                "tranKey" => sha1($seed . config("services.endpoint.auth.trankey")),
                "seed" => $seed,
            ];
            $tipo_cliente = $request->tipo_cliente == 1 ? 1 : 0;

            $param_transaction = [
                "bankCode" => $request->banco,
                "bankInterface" => $tipo_cliente,
                "returnURL" => url("/transactions/register"),
                "reference" => $request->reference,
                "description" => $request->description,
                "language" => "ES",
                "currency" => "COP",
                "totalAmount" => $request->totalAmount,
                "taxAmount" => $request->taxAmount,
                "devolutionBase" => $request->devolutionBase,
                "tipAmount" => $request->tipAmount,
                "ipAddress" => $request->ip(),
                "userAgent" => $request->userAgent(),
                "player" => $pagador->toArray(),
                "buyer" => $comprador->toArray(),
                "shipping" => $receptor->toArray(),
            ];
            $client = new \SoapClient($url, []);
            $result = $client->createTransaction([
                "auth" => $param_auth,
                "transaction" => $param_transaction,
            ]);
            Log::debug(json_encode($result));
            return response()->json($result);
        } catch (\SoapFault $fault) {
            $error = [
                "message" => $fault->getMessage(),
                "code" => $fault->getCode(),
                "file" => $fault->getFile(),
                "line" => $fault->getLine(),
            ];
            Log::error(json_encode($error));
            flash("No se pudo obtener la lista de Entidades Financieras, por favor intente más tarde")->error();
            Cache::forget("bancos");
        }

        return response()->json([
            "success" => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
