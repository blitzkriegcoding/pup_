<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\UploadFileRequest;

use App\LoteCredito;
use App\LogCargaCrediticia;
use Excel;
use App\Cliente;
use App\ClienteEmpresa;
use App\PlanCuota;
use App\Cuota;

class MassiveCreditLoaderController extends Controller
{
    //
    private $bulk = [];
    public function newLoad()
    {
    	return view('massive_upload');
    }

    public function uploadFile(UploadFileRequest $request)
    {
    	//dd($request->lote_credito);
        # return;
        @session()->forget('id_carga');
        @session()->forget('nro_registros');
        @session()->forget('qty_new_clients');
        @session()->forget('total_credit_plans');
        @session()->forget('total_new_quotes');
        @session()->forget('fecha_hora_carga');        
    	$bulk = [];
        $bulk_result = [];
    	$path_file = $request->file('lote_credito')->getRealPath();
    	$data_file = Excel::load($path_file, function($reader){})->get();
    	if(!empty($data_file) && $data_file->count() > 0)
    	{
    		#dd($data_file->toArray());
    		foreach($data_file->toArray() as $key => $value)
    		{
				$this->bulk[] = ['nro_cuota'	=> $value['nro_cuota'],
    						'fecha_vencimiento' => $value['fecha_vencimiento'],
    						'interes'			=> $value['interes'],
    						'amortizacion'		=> $value['amortizacion'],
    						'valor_cuota'		=> $value['valor_cuota'],
    						'saldo_insoluto'	=> $value['saldo_insoluto'],
    						'estado_cuota'		=> trim($value['estado_cuota']),
    						'tipo_cuota'		=> trim($value['tipo_cuota']),
    						'fecha_pago'		=> NULL,
    						'rut_cliente'		=> $value['rut_cliente'],
    						'nro_credito'		=> $value['nro_credito'],
    						'nombres_cliente'	=> $value['nombres_cliente'],
    						'apellidos_cliente'	=> $value['apellidos_cliente']];
    		}
    		if(!empty($this->bulk))
    		{
                try
                {
                    $log_id_carga = LogCargaCrediticia::createNewLog($this->bulk);    
                }
                catch(Exception $e)
                {
                    throw($e);
                }
                LoteCredito::createNewLote($this->bulk, $log_id_carga);
                session(['id_carga' => $log_id_carga]);
                # \DB::commit();
                return json_encode(['ruta_redireccion' => route('admin.authorize_commit_credits'), 
                                    'success' => true, 'mensaje' => 'Archivo cargado con éxito']);
                // return redirect()->route('admin.authorize_commit_credits');

    		}
            else
            {
                // flash('Lote vacío o memoria insuficiente', 'error');
                // return back();
                return json_encode(['ruta_redireccion' => route('admin.authorize_commit_credits'), 
                                    'success' => false, 'mensaje' => 'Lote vacío o memoria insuficiente']);
            }
    	}
        else
        {
            // flash('Lote vacío o con valores inválidos', 'error');
            // return back();
            return json_encode(['ruta_redireccion' => route('admin.authorize_commit_credits'), 
                            'success' => false, 'mensaje' => 'Lote vacío o con valores inválidos']);
        }


    }

    public function resultsLoad()
    {   
        if(empty(session('nro_registros')) || NULL == session('nro_registros'))
        {
            flash('Debe efectuar una carga de archivos antes de pasar a ver los resultados', 'warning');
            return redirect()->route('admin.massive_upload_credits');
        }
        return view('result_massive_upload');
    }

    public function presetCommitOrRollBack()
    {
        return view('authorize_commit_credits');   
    }

    public function rollBackUpload()
    {
        session()->forget('nro_registros');
        session()->forget('qty_new_clients');
        session()->forget('total_credit_plans');
        session()->forget('total_new_quotes');
        session()->forget('fecha_hora_carga');
        LogCargaCrediticia::deleteLog(session('id_carga'));
        LoteCredito::deleteLote(session('id_carga'));
        return redirect()->route('admin.massive_upload_credits');
    }

    public function commitUpload()
    {
        Cliente::addNewClients(session('id_carga'));
        ClienteEmpresa::associateClientsEnterprise($this->bulk, session('id_carga'));
        PlanCuota::addNewClientQuotePlan(session('id_carga'));
        Cuota::addQuotes(session('id_carga'));
        return redirect()->route('admin.massive_upload_result');
    }

    public function historyReport()
    {
        return view('uploads_history_report');
    }

    public function getUploadsHistory()
    { 
        return LogCargaCrediticia::getHistory();
    }
    
    public function getFilteredHistory(Request $request)
    { 
        return LogCargaCrediticia::getFilteredHistory($request);
    }
    public function getCurrentLoadedLote()
    {
        return LoteCredito::getCurrentLoadedLote();
    }

    public function getFilteredLoadedLote(Request $req)
    {
        return LoteCredito::getFilteredLoadedLote($req);
    }
    
    public function deleteItemFromLoadedLote($item)
    {
        return LoteCredito::deleteItemFromLoadedLote($item);
    }
}
