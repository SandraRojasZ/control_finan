<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class resultado extends Model
{
    /** @use HasFactory<\Database\Factories\ResultadoFactory> */
    use HasFactory;

    protected $table = 'items';
    protected $fillable = ['tipo', 'valor'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    public static function getSaldoTotalPorTipo()
    {
        $resultados = self::select('tipo', DB::raw('SUM(valor) as total'))
                        ->groupBy('tipo')
                        ->get();

        return $resultados;
    }
}
