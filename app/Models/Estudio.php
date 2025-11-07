<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estudio extends Model
{
    use HasFactory;
    /**
     * Tabla asociada al modelo
     *
     * @var string
     */
    protected $table = 'estudio';

    /**
     * Atributos que pueden ser asignados masivamente
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
    ];

    /**
     * Relación: Un Estudio tiene muchas Asignaturas
     *
     * @return HasMany
     */
    public function asignaturas(): HasMany
    {
        return $this->hasMany(Asignatura::class, 'idEstudio');
    }
}
