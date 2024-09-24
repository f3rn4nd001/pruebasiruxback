<?php

namespace App\Http\Controllers\Catalogo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class usuario extends Controller
{
    private $CHAR_RANGE = 126 - 32 + 1;

    private function shiftChar($char, $shift)
    {
        $charCode = ord($char);  // Obtener el código ASCII del carácter
        $newCharCode = (($charCode - 32 + $shift + $this->CHAR_RANGE) % $this->CHAR_RANGE) + 32;
        return chr($newCharCode); // Convertir el código ASCII de vuelta a carácter
    }

    public function shiftText($text, $shift)
    {
        $result = array_map(function($char) use ($shift) {
            return $this->shiftChar($char, $shift);
        }, str_split($text));

        return implode('', $result);
    }

    public function objeto_a_array($data){
        if (is_array($data) || is_object($data)){
            $result = array();
            foreach ($data as $key => $value){$result[$key] = $this->objeto_a_array($value);}
            return $result;
        }
        return $data;
    }
 
    public function getRegistro(Request $request){
        $jsonX =json_decode($this->shiftText($request['datos'], -23));
        $json               = isset($jsonX->filtros) ? $jsonX->filtros : [];
        $metodos            = isset($jsonX->metodos) ? $jsonX->metodos : [];
        if (is_array($json) || is_object($json)){
            $result = array();
            foreach ($json as $key => $value){
                $result[$key] = $this->objeto_a_array($value);
            }
            $result; 
        }

        foreach ($result as $key => $value) {
            if(array_key_exists($key, $result) ){
				if ($value != ''){
					${$key} =$value ;
				}
			}
        }
        $selectEstustus="SELECT cu.ecodUsuario, concat_ws('',cu.tNombre,' ', cu.tApellido) as tNombre ,cu.tApellido,cu.tRFC,cu.tCRUP, ce.tNombre AS estatus FROM catusuarios AS cu 
        LEFT JOIN catestatus ce ON ce.ecodEstatus =cu.ecodEstatus". " WHERE 1=1 ".  
        (isset($ecodUsuario)    ? " AND cu.ecodUsuario  LIKE ('%".$ecodUsuario ."%')"   : '').
        (isset($tNombre)        ? " AND  concat_ws('',cu.tNombre,' ', cu.tApellido) LIKE ('%".$tNombre."%')"             : '').
        (isset($tRFC)           ? " AND cu.tRFC LIKE ('%".$tRFC."%')"                   : '').
        (isset($tCRUP)          ? " AND cu.tCRUP LIKE ('%".$tCRUP."%')"                 : '').
        (isset($estatus)        ? " AND ce.tNombre LIKE ('%".$estatus."%')"             : '').
        (isset($metodos->orden) ? 'ORDER BY '.$metodos->tMetodoOrdenamiento." ".$metodos->orden : 'ASC')." ".
        (isset($metodos->eNumeroRegistros) && (int)$metodos->eNumeroRegistros>0 ? 'LIMIT '.$metodos->eNumeroRegistros : '');
        $sql = DB::select($selectEstustus);
        
        $jsonData = json_encode($sql);
        $returResponse =$this->shiftText($jsonData, 23);
      
        return response()->json( $returResponse);  
    }
    
    public function getDetalles(Request $request){
        $jsonX =json_decode($this->shiftText($request['datos'], -23));
        $json = (isset($jsonX->data)&&$jsonX->data!="" ? "'".(trim($jsonX->data))."'":   "NULL");
        $selectUsuario="SELECT concat_ws('',cuc.tNombre,' ',cuc.tApellido) AS nombresCreador,cu.tNotas,cu.ecodEdicion,cu.iUsuario, cu.ecodEstatus,concat_ws('',cu.tNombre,' ',cu.tApellido) as Nombre,
        cu.tCRUP,cu.tRFC,ce.tNombre as Estatus, cu.ecodUsuario,cu.fhCreacion,cu.tNombre, cu.tApellido,cu.nEdad,cu.nTelefono,cu.tSexo,
        cu.ecodCreacion,cu.fhEdicion, concat_ws('',cue.tNombre,' ',cue.tApellido) AS nombresEditor, cu.fhNacimiento FROM catusuarios cu
        LEFT JOIN catusuarios cuc ON cuc.ecodCreacion = cu.ecodCreacion
        LEFT JOIN catestatus ce ON ce.EcodEstatus = cu.ecodEstatus 
        LEFT JOIN catusuarios cue ON cue.ecodUsuario = cu.ecodEdicion
        WHERE cu.ecodUsuario =".$json;
        $sqlUsusario = DB::select($selectUsuario);   
        $selectCorreo="SELECT bc.ecodCorreo,bc.tCorreo,bc.tToken FROM relusuariocorreo ruc
        LEFT JOIN bitcorreo bc ON bc.ecodCorreo=ruc.ecodCorreo
        WHERE ruc.ecodUsuario =".$json;
        $sqlCorreo = DB::select($selectCorreo);
        $data = [
            'sqlUsusario'=>(isset($sqlUsusario[0]) ? $sqlUsusario[0] : ""),
            'sqlCorreo'=>(isset($sqlCorreo) ? $sqlCorreo : "")
        ];
        $jsonData = json_encode($data);
        $returResponse =$this->shiftText($jsonData, 23);
        return response()->json($returResponse);
    }


    
    public function postRegistro(Request $request){
        $jsonX =json_decode($this->shiftText($request['datos'], -23));
        $json  = isset($jsonX->Usuario) ? $jsonX->Usuario : [];
        $jsonH =json_decode($this->shiftText($request['headers'], -23));
        $jsonarrCorreo  = isset($json->arrCorreo) ? $json->arrCorreo : [];
       $tNombre    = (isset($json->tNombre)&&$json->tNombre!=""        ? "'".(trim($json->tNombre))."'":   "NULL");
        $tApellido  = (isset($json->tApellido)&&$json->tApellido!=""    ? "'".(trim($json->tApellido))."'":   "NULL");
        $tCRUP      = (isset($json->tCRUP)&&$json->tCRUP!=""            ? "'".(trim($json->tCRUP))."'":   "NULL");
        $tRFC       = (isset($json->tRFC)&&$json->tRFC!=""              ? "'".(trim($json->tRFC))."'":   "NULL");
        $tSexo      = (isset($json->tSexo)&&$json->tSexo!=""            ? "'".(trim($json->tSexo))."'":   "NULL");
        $nEdad      = (isset($json->nEdad)&&$json->nEdad!=""            ? "".(trim($json->nEdad))."":   "NULL");
        $nTelefono  = (isset($json->nTelefono)&&$json->nTelefono!=""    ? "".(trim($json->nTelefono))."":   "NULL");
        $fhNacimiento = (isset($json->fhNacimiento)&&$json->fhNacimiento!="" ? "'".(trim($json->fhNacimiento))."'":   "NULL");
        $iUsuario   = (isset($json->iUsuario)&&$json->iUsuario!=""      ? "'".(trim($json->iUsuario))."'":   "NULL");
        $ecodUsuario = (isset($json->ecodUsuario)&&$json->ecodUsuario!="" ? "'".(trim($json->ecodUsuario))."'":   "NULL");
        $tNotas = (isset($json->tNotas)&&$json->tNotas!="" ? "'".(trim($json->tNotas))."'":   "NULL");
        $selectEcodUsuario="SELECT * FROM relusuariocorreo ruc WHERE ruc.ecodCorreo =".$jsonH->ecodCorreo;
        $sqlEcodUsuario = DB::select($selectEcodUsuario); 
        $InsertecodUsuario = (isset($sqlEcodUsuario[0]->ecodUsuario) && $sqlEcodUsuario[0]->ecodUsuario != "" ? "'" . (trim($sqlEcodUsuario[0]->ecodUsuario)) . "'" : "");  
        if ($ecodUsuario == 'NULL') {
            $uuiecodUsuario = Uuid::uuid4();
            $uuid2ecodUsuario = (isset($uuiecodUsuario)&&$uuiecodUsuario!="" ? "'".(trim($uuiecodUsuario))."'":   "NULL");
            $ecodEstatus = "'2660376e-dbf8-44c1-b69f-b2554e3e5d4c'";
            $inserUsuario=" CALL `stpInsertarCatUsuario`(".$uuid2ecodUsuario.",".$tNombre.",".$tApellido.",".$tCRUP.",".$tRFC.",".$tSexo.",".$nTelefono.",".$tNotas.",".$nEdad.",".$ecodEstatus.",".$InsertecodUsuario.",".$fhNacimiento.",".$iUsuario.")";
            $responseUsuario = DB::select($inserUsuario); 
            if (count($jsonarrCorreo) > 0) {
                foreach ($jsonarrCorreo as $key => $value) {
                    $uuiecodCorreo = Uuid::uuid4();   
                    $uuid2uuiecodCorreo = (isset($uuiecodCorreo)&&$uuiecodCorreo!="" ? "'".(trim($uuiecodCorreo))."'":   "NULL");
                    $tCorreo  = (isset($value->tCorreo)&&$value->tCorreo!=""            ? "'".(trim($value->tCorreo))."'":   "NULL");
                    $tContrasena  = (isset($value->tContrasena)&&$value->tContrasena!=""            ? "'".(trim($value->tContrasena))."'":   "NULL");  
                    $inserCorreo=" CALL `stpInsertarBitCorreo`(".$uuid2uuiecodCorreo.",".$tCorreo.",".$tContrasena.")";
                    $responseCorreo = DB::select($inserCorreo); 
                    $uuiecodRelUsuarioCorreo = Uuid::uuid4();
                    $uuid2uuiecodRelUsuarioCorreo = (isset($uuiecodRelUsuarioCorreo)&&$uuiecodRelUsuarioCorreo!="" ? "'".(trim($uuiecodRelUsuarioCorreo))."'":   "NULL");
                    $inserRelUsuarioCorreo=" CALL `stpInsertarRelUsuarioCorreo`(".$uuid2uuiecodRelUsuarioCorreo.",".$uuid2uuiecodCorreo.",".$uuid2ecodUsuario.")";
                    $responseRelUsuarioCorreo = DB::select($inserRelUsuarioCorreo); 
                }
            } 
        }
        else{
            $selectlogcatUsuario="SELECT * FROM catusuarios cu WHERE cu.ecodUsuario =".$ecodUsuario;
            $sqllogCatUsuario = DB::select($selectlogcatUsuario);
            $logtNombre     = (isset($sqllogCatUsuario[0]->tNombre) && $sqllogCatUsuario[0]->tNombre != ""      ? "'" . (trim($sqllogCatUsuario[0]->tNombre)) . "'" : "");             
            $logtApellido   = (isset($sqllogCatUsuario[0]->tApellido) && $sqllogCatUsuario[0]->tApellido != ""  ? "'" . (trim($sqllogCatUsuario[0]->tApellido)) . "'" : "");             
            $logtCRUP       = (isset($sqllogCatUsuario[0]->tCRUP) && $sqllogCatUsuario[0]->tCRUP != ""          ? "'" . (trim($sqllogCatUsuario[0]->tCRUP)) . "'" : "NULL");             
            $logtRFC        = (isset($sqllogCatUsuario[0]->tRFC) && $sqllogCatUsuario[0]->tRFC != ""            ? "'" . (trim($sqllogCatUsuario[0]->tRFC)) . "'" : "NULL");             
            $logtSexo       = (isset($sqllogCatUsuario[0]->tSexo) && $sqllogCatUsuario[0]->tSexo != ""          ? "'" . (trim($sqllogCatUsuario[0]->tSexo)) . "'" : "NULL");             
            $lognEdad       = (isset($sqllogCatUsuario[0]->nEdad) && $sqllogCatUsuario[0]->nEdad != ""          ? "" . (trim($sqllogCatUsuario[0]->nEdad)) . "" : "NULL");             
            $lognTelefono   = (isset($sqllogCatUsuario[0]->nTelefono) && $sqllogCatUsuario[0]->nTelefono != ""  ? "" . (trim($sqllogCatUsuario[0]->nTelefono)) . "" : "NULL");             
            $logecodEstatus = (isset($sqllogCatUsuario[0]->ecodEstatus) && $sqllogCatUsuario[0]->ecodEstatus != ""  ? "'" . (trim($sqllogCatUsuario[0]->ecodEstatus)) . "'" : "");             
            $logfhCreacion  = (isset($sqllogCatUsuario[0]->fhCreacion) && $sqllogCatUsuario[0]->fhCreacion != "" ? "'" . (trim($sqllogCatUsuario[0]->fhCreacion)) . "'" : "");             
            $logecodCreacion = (isset($sqllogCatUsuario[0]->ecodCreacion) && $sqllogCatUsuario[0]->ecodCreacion != "" ? "'" . (trim($sqllogCatUsuario[0]->ecodCreacion)) . "'" : "");             
            $logecodEdicion  = (isset($sqllogCatUsuario[0]->ecodEdicion) && $sqllogCatUsuario[0]->ecodEdicion != "" ? "'" . (trim($sqllogCatUsuario[0]->ecodEdicion)) . "'" : "NULL");             
            $logfhEdicion   = (isset($sqllogCatUsuario[0]->fhEdicion) && $sqllogCatUsuario[0]->fhEdicion != ""   ? "'" . (trim($sqllogCatUsuario[0]->fhEdicion)) . "'" : "NULL");             
            $logfhNacimiento = (isset($sqllogCatUsuario[0]->fhNacimiento) && $sqllogCatUsuario[0]->fhNacimiento != "" ? "'" . (trim($sqllogCatUsuario[0]->fhNacimiento)) . "'" : "NULL");             
            $logiUsuario    = (isset($sqllogCatUsuario[0]->iUsuario) && $sqllogCatUsuario[0]->iUsuario != ""     ? "'" . (trim($sqllogCatUsuario[0]->iUsuario)) . "'" : "NULL");             
            $logtNotas    = (isset($sqllogCatUsuario[0]->tNotas) && $sqllogCatUsuario[0]->tNotas != ""     ? "'" . (trim($sqllogCatUsuario[0]->tNotas)) . "'" : "NULL");             
           
            $loguuid = Uuid::uuid4();
            $loguuid2 = (isset($loguuid)&&$loguuid!="" ? "'".(trim($loguuid))."'":   "NULL");
            $inserLogUsuario=" CALL `stpInsertarLogCatUsuario`(".$loguuid2.",".$ecodUsuario.",".$logtNombre.",".$logtApellido.",".$logtCRUP.",".$logtRFC.",".$logtSexo.",".$lognTelefono.",".$logtNotas.",".$lognEdad.",".$logecodEstatus.",".$logfhNacimiento.",".$logecodCreacion.",".$logfhCreacion.",".$logecodEdicion.",".$logfhEdicion.",".$logiUsuario.")";
            $responseLogUsuario = DB::select($inserLogUsuario); 
            $ecodEstatus = (isset($json->ecodEstatus)&&$json->ecodEstatus!="" ? "'".(trim($json->ecodEstatus))."'":   "NULL");
            $inserUsuario=" CALL `stpInsertarCatUsuario`(".$ecodUsuario.",".$tNombre.",".$tApellido.",".$tCRUP.",".$tRFC.",".$tSexo.",".$nTelefono.",".$tNotas.",".$nEdad.",".$ecodEstatus.",".$InsertecodUsuario.",".$fhNacimiento.",".$iUsuario.")";
            $responseUsuario = DB::select($inserUsuario); 
            if (count($jsonarrCorreo) > 0) {
                foreach ($jsonarrCorreo as $key => $value) {
                    $tCorreo  = (isset($value->tCorreo)&&$value->tCorreo!=""            ? "'".(trim($value->tCorreo))."'":   "NULL");
                    $ecodCorreo  = (isset($value->ecodCorreo)&&$value->ecodCorreo!=""   ? "'".(trim($value->ecodCorreo))."'":   "NULL");
                    if ($ecodCorreo == 'NULL') {
                        $uuiecodCorreo = Uuid::uuid4();
                        $uuid2uuiecodCorreo = (isset($uuiecodCorreo)&&$uuiecodCorreo!="" ? "'".(trim($uuiecodCorreo))."'":   "NULL");
                        $tContrasena  = (isset($value->tContrasena)&&$value->tContrasena!=""  ? "'".(trim($value->tContrasena))."'":   "NULL");      
                        $inserCorreo=" CALL `stpInsertarBitCorreo`(".$uuid2uuiecodCorreo.",".$tCorreo.",".$tContrasena.")";
                        $responseCorreo = DB::select($inserCorreo); 
                        $uuiecodRelUsuarioCorreo = Uuid::uuid4();
                        $uuid2uuiecodRelUsuarioCorreo = (isset($uuiecodRelUsuarioCorreo)&&$uuiecodRelUsuarioCorreo!="" ? "'".(trim($uuiecodRelUsuarioCorreo))."'":   "NULL");
                        $inserRelUsuarioCorreo=" CALL `stpInsertarRelUsuarioCorreo`(".$uuid2uuiecodRelUsuarioCorreo.",".$uuid2uuiecodCorreo.",".$ecodUsuario.")";
                        $responseRelUsuarioCorreo = DB::select($inserRelUsuarioCorreo); 
                    }
                    else {
                        $tContrasena  =  "NULL";      

                        $inserCorreo=" CALL `stpInsertarBitCorreo`(".$ecodCorreo.",".$tCorreo.",".$tContrasena.")";
                        $responseCorreo = DB::select($inserCorreo); 
                      
                    }

                }
            }
        }
        $jsonData = json_encode($responseUsuario[0]);
        $returResponse2 =$this->shiftText($jsonData, 23);
        return response()->json($returResponse2);
    }
    
    public function postEliminar(Request $request){
        $jsonX =json_decode($this->shiftText($request['datos'], -23));
        $json = isset($jsonX->Usuario) ? $jsonX->Usuario : [];
        $jsonH =json_decode($this->shiftText($request['headers'], -23));
      
        $selectEcodUsuario="SELECT * FROM relusuariocorreo ruc WHERE ruc.ecodCorreo =".$jsonH->ecodCorreo;
        $sqlEcodUsuario = DB::select($selectEcodUsuario); 
        $InsertecodUsuario = (isset($sqlEcodUsuario[0]->ecodUsuario) && $sqlEcodUsuario[0]->ecodUsuario != "" ? "'" . (trim($sqlEcodUsuario[0]->ecodUsuario)) . "'" : "");  
       
        $ecodUsuario = (isset($jsonX->ecodUsuario)&&$jsonX->ecodUsuario!="" ? "'".(trim($jsonX->ecodUsuario))."'":   "NULL");
        $mEliminacion = (isset($json->mEliminacion)&&$json->mEliminacion!="" ? "'".(trim($json->mEliminacion))."'":   "NULL");

        $selectlogcatUsuario="SELECT * FROM catusuarios cu WHERE cu.ecodUsuario  =".$ecodUsuario;
        $sqllogCatUsuario= DB::select($selectlogcatUsuario); 
        $logtNombre  = (isset($sqllogCatUsuario[0]->tNombre) && $sqllogCatUsuario[0]->tNombre != ""          ? "'" . (trim($sqllogCatUsuario[0]->tNombre)) . "'" : "NULL");             
        $logtApellido  = (isset($sqllogCatUsuario[0]->tApellido) && $sqllogCatUsuario[0]->tApellido != ""          ? "'" . (trim($sqllogCatUsuario[0]->tApellido)) . "'" : "NULL");             
        $logtCRUP  = (isset($sqllogCatUsuario[0]->tCRUP) && $sqllogCatUsuario[0]->tCRUP != ""          ? "'" . (trim($sqllogCatUsuario[0]->tCRUP)) . "'" : "NULL");             
        $logtRFC  = (isset($sqllogCatUsuario[0]->tRFC) && $sqllogCatUsuario[0]->tRFC != ""          ? "'" . (trim($sqllogCatUsuario[0]->tRFC)) . "'" : "NULL");             
        $lognEdad  = (isset($sqllogCatUsuario[0]->nEdad) && $sqllogCatUsuario[0]->nEdad != ""          ? "" . (trim($sqllogCatUsuario[0]->nEdad)) . "" : "NULL");             
        $logtSexo  = (isset($sqllogCatUsuario[0]->tSexo) && $sqllogCatUsuario[0]->tSexo != ""          ? "'" . (trim($sqllogCatUsuario[0]->tSexo)) . "'" : "NULL");             
        $lognTelefono  = (isset($sqllogCatUsuario[0]->nTelefono) && $sqllogCatUsuario[0]->nTelefono != ""          ? "" . (trim($sqllogCatUsuario[0]->nTelefono)) . "" : "NULL");             
        $logtNotas  = (isset($sqllogCatUsuario[0]->tNotas) && $sqllogCatUsuario[0]->tNotas != ""          ? "'" . (trim($sqllogCatUsuario[0]->tNotas)) . "'" : "NULL");             
        $logfhCreacion  = (isset($sqllogCatUsuario[0]->fhCreacion) && $sqllogCatUsuario[0]->fhCreacion != ""          ? "'" . (trim($sqllogCatUsuario[0]->fhCreacion)) . "'" : "NULL");             
        $logiUsuario  = (isset($sqllogCatUsuario[0]->iUsuario) && $sqllogCatUsuario[0]->iUsuario != ""          ? "'" . (trim($sqllogCatUsuario[0]->iUsuario)) . "'" : "NULL");             
        $logecodCreacion  = (isset($sqllogCatUsuario[0]->ecodCreacion) && $sqllogCatUsuario[0]->ecodCreacion != ""          ? "'" . (trim($sqllogCatUsuario[0]->ecodCreacion)) . "'" : "NULL");             
        $logfhNacimiento  = (isset($sqllogCatUsuario[0]->fhNacimiento) && $sqllogCatUsuario[0]->fhNacimiento != ""          ? "'" . (trim($sqllogCatUsuario[0]->fhNacimiento)) . "'" : "NULL");             
        $logecodEstatus = "'fa6cc9a2-f221-4e27-b575-1fac2698d27a'";

        
        $loguuid = Uuid::uuid4();
        $loguuid2 = (isset($loguuid)&&$loguuid!="" ? "'".(trim($loguuid))."'":   "NULL");
        $inserLogUsuario=" CALL `stpInsertarLogCatUsuarioEliminar`(".$loguuid2.",".$ecodUsuario.",".$logtNombre.",".$logtApellido.",".$logtCRUP.",".$logtRFC.",".$logtSexo.",".$lognTelefono.",".$logtNotas.",".$lognEdad.",".$logecodEstatus.",".$logfhNacimiento.",".$logecodCreacion.",".$logfhCreacion.",".$mEliminacion.",".$InsertecodUsuario.",".$logiUsuario.")";
        $responseLogUsuario = DB::select($inserLogUsuario); 
      

        return response()->json($responseLogUsuario);
    }
}
