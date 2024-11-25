<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /** @use HasFactory<\Database\Factories\ItemFactory> */
    use HasFactory;
    protected $fillable = ['user_id', 'data', 'name', 'valor', 'tipo', 'saldo'];
    
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function getSaldoAttribute()
    {
        // Verificamos se o tipo é 'receita' ou 'gasto'
        if ($this->tipo === 'receita') {
            return $this->valor;
        } elseif ($this->tipo === 'gasto') {
            // Para gastos, retornamos o valor negativo para indicar saída de dinheiro
            return -$this->valor;
        } else {
            return 0; // Retorna 0 para tipos inválidos
        }
    }
    public function scopeActive($query)
    {
        return $query->where('tipo', 'receita');
    }

    
}
