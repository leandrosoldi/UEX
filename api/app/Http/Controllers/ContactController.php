<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Traits\GeoTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    use GeoTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Contact::orderBy('nome')->get());
    }

   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $dados = $request->input('contact');
            $location = $this->getLatLong(
                            $dados['logradouro'].', '.
                            $dados['numero'].' '.
                            $dados['complemento'].' - '.
                            $dados['bairro'].' - '.
                            $dados['localidade'].' - '.
                            $dados['uf']
                        );
            if(!$location){
                return response()->json(['status' => 2]);
            }
    
            $contact['latitude'] = $location[0];
            $contact['longitude'] = $location[1];
            $contact['nome'] = $dados['nome'];
            $contact['cpf'] = $dados['cpf'];
            $contact['telefone'] = $dados['telefone'];
            $contact['cep'] = $dados['cep'];
            $contact['logradouro'] = $dados['logradouro'];
            $contact['numero'] = $dados['numero'];
            $contact['complemento'] = $dados['complemento'];
            $contact['bairro'] = $dados['bairro'];
            $contact['localidade'] = $dados['localidade'];
            $contact['uf'] = $dados['uf'];
            $contact['id'] = isset($dados['id']) ? $dados['id'] : -1;

            Contact::updateOrCreate(['id' => $contact['id']],$contact);
            return response()->json(['status' => 1]);
        } catch (\Throwable $th) {
            Log::info($th);
            return response()->json(['status' => 2]);
        }
        
    }

    /**
     * Validate a CPF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function validateCPF(Request $request)
    {
        
        $contact = Contact::where('cpf',$request->input('cpf'))->first();
        if($contact){
            if($request->input('id')){
                return  response()->json($request->input('id') == $contact->id ? false : true);
            }
            return response()->json(true);
        }

        return response()->json(false);
    }
}
