<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiResponseController;
use Illuminate\Http\Request;
use DB;

class TerminalsController extends Controller
{
    public function index(Request $request)
    {
        $size=$request->size ? $request->size : 20;
        
        $terminals = DB::table("sensor_estaciones_out")->where("id_movil", $request->id_movil)->paginate($size);

        return ApiResponseController::respond(200, 'Success', $terminals);
    }

    public function getTerminalsList()
    {
        $terminalsList = DB::table("sensor_estaciones_out")
        ->select("id_movil")
        ->where("id_movil", 'SOP')
        ->orWhere("id_movil", 'KCL')
        
        ->groupBy('id_movil')->get();

        return ApiResponseController::respond(200, 'Success', $terminalsList);
    }
    
    public function getTerminalChartData(Request $request)
    {

        $fechaMedicion = DB::table("sensor_estaciones_out")
                                ->where("id_movil", $request->id_movil)
                                ->orderBy("fecha_medicion", "DESC")
                                ->first()->fecha_medicion;
                                
        $terminalChartData = DB::table("sensor_estaciones_out")
                                ->where("id_movil", $request->id_movil)
                                ->take(96)
                                ->orderBy("fecha_medicion", "desc")
                                ->get();

        return ApiResponseController::respond(200, 'Success', $terminalChartData);
                                    
    }
}
