<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Transaction extends Model
{
    protected $table = "transactions";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $appends = ["updated"];

    public function getUpdatedAttribute(){
        $bool = false;
        if ($this->responseCode == 3) {
            if (!Cache::has("transaction_$this->id")) {
                // actualizar data
                try{
                    $url = config("services.endpoint.url");
                    $seed = date('c');
                    $param_auth = [
                        "login" => config("services.endpoint.auth.login"),
                        "tranKey" => sha1($seed . config("services.endpoint.auth.trankey")),
                        "seed" => $seed,
                    ];
                    $client = new \SoapClient($url, []);
                    $result = $client->getTransactionInformation([
                        "auth" => $param_auth,
                        "transactionID" => $this->transactionID,
                    ]);

                    switch ($result->transactionState){
                        case "OK":
                            $this->responseCode = 1;
                            break;
                        case "NOT_AUTHORIZED":
                            $this->responseCode = 2;
                            break;
                        case "PENDING":
                            $this->responseCode = 3;
                            break;
                        case "FAILED":
                            $this->responseCode = 0;
                            break;
                    }
                    $this->responseReasonText = $result->responseReasonText;
                    $this->save();
                    $expiresAt = now()->addMinutes(12);
                    Cache::put("transaction_$this->id", true, $expiresAt);
                }catch (\SoapFault $fault) {
                    $error = [
                        "message" => $fault->getMessage(),
                        "code" => $fault->getCode(),
                        "file" => $fault->getFile(),
                        "line" => $fault->getLine(),
                    ];
                    Log::error(json_encode($error));
                }

            }
        }
        return $bool;
    }

    public function player(){
        return $this->belongsTo(Person::class,"player_id");
    }

    public function buyer(){
        return $this->belongsTo(Person::class,"buyer_id");
    }
    public function shipping(){
        return $this->belongsTo(Person::class,"shipping_id");
    }

}
