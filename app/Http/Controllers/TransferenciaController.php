<?php

namespace App\Http\Controllers;

use App\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

//use SoapClient;

class TransferenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //return response()->json((new \League\ISO3166\ISO3166)->all());
        if (!Cache::has("bancos")) {
            Log::debug("cargando bancos");
            try {
                $url = config("services.endpoint.url");
                $seed = date('c');
                $param = [
                    "login" => config("services.endpoint.auth.login"),
                    "tranKey" => sha1($seed . config("services.endpoint.auth.trankey")),
                    "seed" => $seed,
                    "additional" => []
                ];
                $client = new \SoapClient($url, []);
                $expiresAt = now()->addMinutes(24 * 60);
                Cache::put("bancos", $client->getBankList(["auth" => $param])->getBankListResult->item, $expiresAt);
                Log::debug("bancos cargados: " . json_encode($expiresAt));
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
        }else{
            //Log::debug("eliminando bancos");
            //Cache::forget("bancos");
        }
        $bancos = Cache::get("bancos");
        $people = Person::all();
        //return response()->json($bancos);
        return view("welcome", compact("bancos","countries","people"));
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
        //
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
