<?php

namespace App\Http\Controllers\Login;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginReuest;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class loginController extends Controller
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

    function posLogin(LoginReuest $request) {
        $dadsad = (isset($request['haders']) && $request['haders'] != "" ? "" . (trim($request['haders'])) . "" : "" );           
        if ($dadsad=="Ox_mSak@t~r}uh_GoerfQly_=EM$4iIYk#v4oFguL)TY2b0~O[") {
            $datos =json_decode($this->shiftText($request['datos'], -23)) ;
            if (is_array($datos) || is_object($datos)){
                $result = array();
                foreach ($datos as $key => $value){
                    $result[$key] = $this->objeto_a_array($value);
                }
                $result; 
            }
            try{
                $exito = 1;
                $Email = (isset($result['email']) && $result['email'] != "" ? "'" . (trim($result['email'])) . "'" : "");           
                if ((preg_match('/^[a-zA-Z0-9.,"]+$/u', $result['password']) == 1)  && (preg_match('/^[a-zA-Z0-9.,"@]+$/u', $result['email']) == 1)) {
                    $password = (isset($result['password']) && $result['password'] != "" ? "'" . (trim($result['password'])) . "'" : "");
                }
                else { 
                    return response()->json([
                        'mensaje'=>"No dijite caracteres especiales ni espacios",
                    ],401); 
                }
                $selectEcodCorreo = "SELECT * FROM bitcorreo bc WHERE bc.tCorreo =".$Email."AND bc.tpassword =".$password;
                $sqlEcodCorreo = DB::select(($selectEcodCorreo)); 
                if($sqlEcodCorreo){
                    $ecodCorreo = (isset($sqlEcodCorreo[0]->ecodCorreo) && $sqlEcodCorreo[0]->ecodCorreo != "" ? "'" . (trim($sqlEcodCorreo[0]->ecodCorreo)) . "'" : "");                                 $selectEcodUsuario="SELECT * FROM relusuariocorreo ruc WHERE ruc.ecodCorreo =".$ecodCorreo;
                    $selectEcodUsuario = "SELECT * FROM relusuariocorreo ruc WHERE ruc.ecodCorreo =".$ecodCorreo;
                    $sqlEcodUsuario = DB::select(($selectEcodUsuario));                   
                    $ecodUsuario = (isset($sqlEcodUsuario[0]->ecodUsuario) && $sqlEcodUsuario[0]->ecodUsuario != "" ? "'" . (trim($sqlEcodUsuario[0]->ecodUsuario)) . "'" : "");             
                    $selectact="SELECT ce.tNombre as Estatus, ctu.tNombre AS TipoUsuario FROM catusuarios cu 
                    LEFT JOIN catestatus ce ON ce.ecodEstatus = cu.ecodEstatus
                    LEFT JOIN cattipousuario ctu ON ctu.ecodTipoUsuario = cu.ecodTipoUsuario
                    WHERE cu.ecodUsuario=".$ecodUsuario;
                    $sqlact = DB::select(($selectact));            
                    $Estatus = (isset($sqlact[0]->Estatus) && $sqlact[0]->Estatus != "" ? "'" . (trim($sqlact[0]->Estatus)) . "'" : "");             
                    if ($Estatus == "'Activo'") {
                        $user=User::all()->where('tCorreo', $result['email'] )->first();
                        $token=JWTAuth::fromUser($user);
                        $tokenv   = (isset($token) && $token != "" ? "'" . (trim($token)) . "'" : ""); 
                        $ip_address='127.0.0.1';
                        $ip = (isset($ip_address) && $ip_address != "" ? "'" . (trim($ip_address)) . "'" : "");
                        $insert=" CALL `stpInsertarLogin`(".$ecodCorreo.",".$tokenv.",".$ip.")";
                        $response = DB::select($insert);
                        $selectMenu="SELECT rumspc.ecodRelusRarioMenuSubmenuController, cm.tNombre AS Menu,rumspc.tToken,cs.tNombre AS submenuNombre, ci.tIcono AS Iconos,
                        cs.tUrl as urlSubMenu, cct.tNombre AS nombreController, cct.turl AS urlController, cp.tNombre AS Permisos, cp.tNombreCorto As PermisosCorto
                        FROM relusuariomenusubmenucontroller rumspc 
                            LEFT JOIN catmenu cm ON cm.ecodMenu= rumspc.ecodMenu 
                            LEFT JOIN catsubmenu cs ON cs.ecodSubmenu = rumspc.ecodSubmenu
                            LEFT JOIN catcontroller cct on cct.ecodControler = rumspc.ecodController
                            LEFT JOIN catpermisos cp ON cp.ecodPermisos = rumspc.ecodPermisos
                            LEFT JOIN caticono ci ON ci.ecodIcono =cm.ecodIconos
                            WHERE rumspc.ecodUsuario=".$ecodUsuario.
                            " ORDER BY cm.tNombre, cs.tNombre ASC";
                        $sqlMenu = DB::select(($selectMenu)); 
                        foreach ($sqlMenu as $key => $v){
                            $arrsqlmenu[]=array(
                                'Menu' => $v->Menu,
                                'submenu'=>$v->submenuNombre,
                                'urlSubMenu'=>$v->urlSubMenu,
                                'Permisos'=>$v->Permisos,
                                'PermisosCorto'=>$v->PermisosCorto,
                                'Controller' => $v->nombreController,
                                'urlController'=>$v->urlController,
                                'Iconos'=>$v->Iconos,
                                'Token'=>$v->tToken,
                                'ecod'=>$v->ecodRelusRarioMenuSubmenuController
                            );
                        }         
                        $exito = 0;
                    }
                    else {
                        return response()->json([
                            'mensaje'=>"Esta cuenta no se encuentra activa",
                        ],202);
                    }
                }
                else {
                    return response()->json([
                        'mensaje'=>"Usuario o contraseña imbalida",
                    ],401);
                }
                if ($exito == 0) {
                    DB::rollback();
                } 
                else {
                    DB::commit();
                }
            }
            catch (Exception $e) {
                DB::rollback();
                $exito = $e->getMessage();
            }
            $data = [
                'token' => $token,
                'Menu' => isset($arrsqlmenu) ? $arrsqlmenu : "",
                'ecodCorreo' => isset($sqlEcodCorreo[0]->ecodCorreo) ? $sqlEcodCorreo[0]->ecodCorreo : "",
                'TipoUsuario' => isset($sqlact[0]->TipoUsuario) ? $sqlact[0]->TipoUsuario : ""
            ];
            $jsonData = json_encode($data);
            $returResponse =$this->shiftText($jsonData, 23);
          
            return response()->json( $returResponse);
        }
        return response()->json(['mensaje' => "No cuenta con los permisos"],202);
    }  
    function postValidadContrasena(Request $request){
        $jsonX =json_decode($this->shiftText($request['datos'], -23));

        $contrasena    = (isset($jsonX->contrasena) && $jsonX->contrasena != "" ? "'" . (trim($jsonX->contrasena)) . "'" : "");           
        $ecodCorreo    = (isset($jsonX->ecodCorreo) && $jsonX->ecodCorreo != "" ? "'" . (trim($jsonX->ecodCorreo)) . "'" : "");            
        if (preg_match('/^[a-zA-Z0-9.,"]+$/u', $jsonX->contrasena) == 1) {
          
                $selectcontra="SELECT count(*) AS dl FROM bitcorreo bc WHERE bc.ecodCorreo = ".$jsonX->ecodCorreo."  AND bc.tpassword =".$contrasena;
                $sqlcontra = DB::select($selectcontra);
                $jsonData = json_encode($sqlcontra[0]);
                $returResponse =$this->shiftText($jsonData, 23);
              
                return response()->json( $returResponse);
          
                return response()->json(['valContra'=>$returResponse]);
        }
        else { 
            return response()->json([
                'mensaje'=>"No dijite caracteres especiales ni espacios"
            ],401); 
        }

    }
}
