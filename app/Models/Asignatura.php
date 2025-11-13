<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Asignatura extends Model
{
    use HasFactory;
    /* @var string */
    protected $table = 'asignatura';

    /* @var array<int, string> */
    protected $fillable = [
        'nombre',
        'idEstudio',
    ];

    /* @return BelongsTo */
    public function estudio(): BelongsTo
    {
        return $this->belongsTo(Estudio::class, 'idEstudio');
    }

    /* @return BelongsToMany */
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
