<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Alumno extends Model
{
    use HasFactory;
    /* @var string */
    protected $table = 'alumno';

    /* @var array<int, string> */
    protected $fillable = [
        'nombre',
    ];

    /* @return BelongsToMany */
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
