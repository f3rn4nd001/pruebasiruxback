<?php

namespace App\Http\Controllers\Catalogo;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class estatus extends Controller
{
    public function objeto_a_array($data){
        if (is_array($data) || is_object($data)){
            $result = array();
            foreach ($data as $key => $value){$result[$key] = $this->objeto_a_array($value);}
            return $result;
        }
        return $data;
    }
 
    public function getRegistro(Request $request){
        $jsonX = json_decode( $request['datos'] ,true);
        $json               = isset($jsonX['filtros']) ? $jsonX['filtros'] : [];
        $metodos            = isset($jsonX['metodos']) ? $jsonX['metodos'] : [];
        foreach ($json as $key => $value) {
            if(array_key_exists($key, $json) ){
				if ($value != ''){
					${$key} =$value ;
				}
			}
        }
        
        $selectEstustus="SELECT * FROM catestatus WHERE 1=1 ".  
        (isset($ecodEstatus)       ? " AND ecodEstatus LIKE ('%".$ecodEstatus."%')"        : '').
        (isset($tNombre)        ? " AND tNombre LIKE ('%".$tNombre."%')"        : '').
        (isset($metodos['orden']) ? 'ORDER BY '.$metodos['tMetodoOrdenamiento']." ".$metodos['orden'] : 'ASC')." ".
        (isset($metodos['eNumeroRegistros']) && (int)$metodos['eNumeroRegistros']>0 ? 'LIMIT '.$metodos['eNumeroRegistros'] : '');
        $sql = DB::select($selectEstustus);


        return response()->json(($sql));
    }

        public function getcompremento(Request $request) {
        $selecatEstatus="SELECT * FROM catestatus";
        $sqlcatEstatus = DB::select($selecatEstatus);
           return response()->json([ 'sqlcatEstatus'=>(isset($sqlcatEstatus) ? $sqlcatEstatus : "")]);
    }
}
