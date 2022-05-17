<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $cpf
 * @property string $nome
 * @property string $telefone
 * @property string $cep
 * @property string $logradouro
 * @property string $numero
 * @property string $complemento
 * @property string $bairro
 * @property string $localidade
 * @property string $uf
 * @property string $latitude
 * @property string $longitude
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class Contact extends Model
{
    Use HasFactory;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'contact';

    /**
     * @var array
     */
    protected $fillable = [
        'cpf', 
        'nome', 
        'telefone', 
        'cep', 
        'logradouro', 
        'numero', 
        'complemento',
        'bairro', 
        'localidade', 
        'uf', 
        'latitude', 
        'longitude', 
        'created_at', 
        'updated_at', 
        'deleted_at'
    ];
}
