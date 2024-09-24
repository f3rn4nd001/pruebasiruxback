<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
/*use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;*/
use DB;

class permisosMiddlaware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

     public $CHAR_RANGE = 126 - 32 + 1;
     function shiftChar($char, $shift)
    {
        $charCode = ord($char);  // Obtener el código ASCII del carácter
        $newCharCode = (($charCode - 32 + $shift + $this->CHAR_RANGE) % $this->CHAR_RANGE) + 32;
        return chr($newCharCode); // Convertir el código ASCII de vuelta a carácter
    }
    function shiftText($text, $shift)
    {
        $result = array_map(function($char) use ($shift) {
            return $this->shiftChar($char, $shift);
        }, str_split($text));

        return implode('', $result);
    }

    public function handle(Request $request, Closure $next)
    {
        $reqs=$request->all();
        foreach ($reqs as $key => $value) {
            if(array_key_exists($key, $reqs) ){
				if ($value != ''){
					${$key} =$value ;
                  	}
			}
        }
        

        $urls =json_decode($this->shiftText($request['datos'], -23));
        $descompriheader =json_decode($this->shiftText($request['headers'], -23));
        $Url = (isset($urls->urls) && $urls->urls != "" ? "'/" . (trim($urls->urls)) . "'" : "");
        $selectEcodCorreo = "SELECT * FROM bitcorreo bc WHERE bc.ecodCorreo = ".$descompriheader->ecodCorreo."
        AND bc.tToken = ".$descompriheader->token;
        $sqlEcodCorreo = DB::select(($selectEcodCorreo)); 
        if($sqlEcodCorreo){
            $ecodCorreo = (isset($sqlEcodCorreo[0]->ecodCorreo) && $sqlEcodCorreo[0]->ecodCorreo != "" ? "'" . (trim($sqlEcodCorreo[0]->ecodCorreo)) . "'" : "");             
            $selectEcodUsuario="SELECT * FROM relusuariocorreo ruc WHERE ruc.ecodCorreo =".$ecodCorreo;
            $sqlEcodUsuario = DB::select(($selectEcodUsuario));  
            $tokencontroll = (isset($request->tokencontroll) && $request->tokencontroll != "" ? "'" . (trim($request->tokencontroll)) . "'" : "");             
           
           
            $ecodUsuario = (isset($sqlEcodUsuario[0]->ecodUsuario) && $sqlEcodUsuario[0]->ecodUsuario != "" ? "'" . (trim($sqlEcodUsuario[0]->ecodUsuario)) . "'" : "");             
            $seleVAlicacion="SELECT count(*) AS dl FROM relusuariomenusubmenucontroller rumsc 
            LEFT JOIN catsubmenu csm ON csm.ecodSubmenu = rumsc.ecodSubmenu
            LEFT JOIN catcontroller cct on cct.ecodControler =rumsc.ecodController
            WHERE rumsc.ecodUsuario =".$ecodUsuario. 
            " AND csm.tUrl = ".$Url. 
            " AND rumsc.tToken =".$tokencontroll.
            " OR cct.turl =".$Url.
            " AND rumsc.tToken =".$tokencontroll.
            " AND rumsc.ecodUsuario =".$ecodUsuario;
            $sqlact = DB::select($seleVAlicacion);
            if ($sqlact[0]->dl >= 1) {
                return $next($request);
            }  
            else {
                return response()->json(['mensaje'=>"Usuario invalido, No cuenta con los permisos",],401);    
            }     
        }
        else{
            return response()->json(['mensaje'=>"Token invalido, Inicie sesion nuevamente",],401);    
        }
    }
}