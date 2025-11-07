<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Alumno extends Model
{
    use HasFactory;
    /**
     * Tabla asociada al modelo
     *
     * @var string
     */
    protected $table = 'alumno';

    /**
     * Atributos que pueden ser asignados masivamente
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
    ];

    /**
     * Relación: Un Alumno tiene muchas Asignaturas (relación N:M)
     *
     * @return BelongsToMany
     */
    public function asignaturas(): BelongsToMany
    {
        return $this->belongsToMany(
            Asignatura::class,
            'alumno_asignatura',
            'idAlumno',
            'idAsignatura'
        );
    }
}
