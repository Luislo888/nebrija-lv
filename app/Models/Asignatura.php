<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Asignatura extends Model
{
    use HasFactory;
    /**
     * Tabla asociada al modelo
     *
     * @var string
     */
    protected $table = 'asignatura';

    /**
     * Atributos que pueden ser asignados masivamente
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'idEstudio',
    ];

    /**
     * Relación: Una Asignatura pertenece a un Estudio
     *
     * @return BelongsTo
     */
    public function estudio(): BelongsTo
    {
        return $this->belongsTo(Estudio::class, 'idEstudio');
    }

    /**
     * Relación: Una Asignatura tiene muchos Alumnos (relación N:M)
     *
     * @return BelongsToMany
     */
    public function alumnos(): BelongsToMany
    {
        return $this->belongsToMany(
            Alumno::class,
            'alumno_asignatura',
            'idAsignatura',
            'idAlumno'
        );
    }
}
