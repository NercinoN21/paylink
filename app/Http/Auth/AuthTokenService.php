<?php

namespace App\Http\Auth;

use App\Models\AuthToken;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

class AuthTokenService{

    const ALGORITHM = 'sha256';
    const TEMPO_EXPIRACAO_TOKEN_MINUTOS = 30;

    private  $authToken;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuthToken $authToken)
    {
        $this->authToken = $authToken;
    }

    public function criarToken($user) { 
        $dateTime = date('Y-m-d H:i:s');
        $dataToDate = $user['email'] . $dateTime;
        $currentDate = Carbon::now();
        $hash = hash(self::ALGORITHM, $dataToDate);

        $newAuthToken['token'] = $hash;
        $newAuthToken['user_id'] = $user['id'];
        $newAuthToken['data_expiracao'] = $currentDate->addMinute(self::TEMPO_EXPIRACAO_TOKEN_MINUTOS);

        $this->authToken->create($newAuthToken);

        return $hash;
    }

    public function validar($token)
    {
        $authToken = AuthToken::where('token', $token)->first();
        $currentDate = Carbon::now();

        if($authToken){
            if($authToken['data_expiracao'] < $currentDate )
                throw new AuthTokenException('Token expirado!');
        }else{
            throw new AuthTokenException('Token invalido!');
        }
    }
}

