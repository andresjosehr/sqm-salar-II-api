<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiResponseController;
use MikeMcLin\WpPassword\Facades\WpPassword;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use JWTAuth;
use Auth;
use Mail;
use DB;


class PassportAuthController extends Controller
{


    

    public function register(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|min:4',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
       
        $token = $user->createToken('LaravelAuthApp')->accessToken;
 
        return response()->json(['token' => $token], 200);
    }


    public function login(Request $request)
    {

        
        $input = $request->only('email','password', 'password', 'passwordConfirm');

        $validator = Validator::make($input, [
            'email'     => 'required',
            // 'password'  => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponseController::respond(400, "Datos invalidos, corriga y vuelva a intentar", $validator->errors(), "validation");
        }

        $credentials = [
            'email' => $request->email,
            // 'password' => $request->password
        ];

        $user = User::where('email', $request->email)->first();

        if (! $token = JWTAuth::fromUser($user)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        

        return ApiResponseController::respond(200, "Inicio de sesion exitoso, redirigiendo...", [
            'token' => $token, 
            'token_expire_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user
        ]);


        return ApiResponseController::respond(422, "Las credenciales ingresadas son incorrectas");
    }  

    public function checkLogin(Request $request){

        if (Auth::guard('api')->check()) {
            $user=User::where("id", $request->user()->id)->with("roleInstalations", "userMetadata", "instalations")->first();

            if(isset($request->fcm_token)){
                if($request->fcm_token!=""){
                    User::where("id", $request->user()->id)->update(["fcm_token" => $request->fcm_token]);
                }
            }

            return ApiResponseController::respond(200, "Usuario en sesion", ["user" => $user]);
        }
    
        return ApiResponseController::respond(401, "Debes iniciar sesion para continuar");
    }

    public function logout(){

        
        $user = auth()->user()->token();

        $user->revoke();

        return ApiResponseController::respond(200, "Has cerrado sesion correctamente");

    }

    public function resetPasswordRequest(Request $request){


        $credentials = $request->validate(['email' => 'required|email']);

        if(!User::where("email", $request->email)->first()){
            return ApiResponseController::respond(404, "El correo electronico ingresado no existe en el sistema");
        }

        Password::sendResetLink($credentials);
        
        return ApiResponseController::respond(200, "Se ha enviado un email a la direccion de correo electronico proporcionada para continuar con el reestablecimiento de contraseña");
        

    }

    function resetPassword(Request $request){


        $input = $request->only('email','token', 'password', 'passwordConfirm');

        $validator = Validator::make($input, [
            'token'     => 'required',
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponseController::respond(400, "Datos invalidos o incompletos", $validator->errors(), "validation");
        }

        $response = Password::reset($input, function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        if($response == Password::PASSWORD_RESET){
            return ApiResponseController::respond(200, "La contraseña fue actualizada exitosamente");
        }
        return ApiResponseController::respond(200, "Ha ocurrido un error intentando actualizar su contraseña");
    }

    public function getUser(Request $request){

        if($request->user()){
            $user=User::where("id", $request->user()->id)->with("roleInstalations", "userMetadata")->first();
            return ApiResponseController::respond(200, "Consulta exitosa", $user);
        }

        return ApiResponseController::respond(401, "Debes iniciar sesion para continuar");
    }

    public function getAllRoles()
    {
        return ApiResponseController::respond(200, "Consulta exitosa", Role::all());
    }

}
