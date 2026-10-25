<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Helado extends Model
{
    public const CATEGORIAS = ['Conos', 'Paletas', 'Sorbetes', 'Bombas', 'Pasteles', 'Malteadas'];

    // Tipos de imagen que se aceptan al subir una foto (la imagen se guarda en MySQL, no en el disco del pod)
    public const TIPOS_IMAGEN = ['image/jpeg', 'image/png', 'image/webp'];

    protected $fillable = ['codigo', 'nombre', 'categoria', 'descripcion', 'precio', 'existencias'];

    protected $hidden = ['imagen'];

    protected $casts = ['precio' => 'decimal:2', 'existencias' => 'integer'];

    // Listados sin la columna de la imagen (puede pesar hasta 2 MB por producto)
    public function scopeSinImagen(Builder $q): Builder
    {
        return $q->select('id', 'codigo', 'nombre', 'categoria', 'descripcion', 'precio', 'existencias', 'imagen_tipo', 'updated_at');
    }

    public function urlImagen(): ?string
    {
        return $this->imagen_tipo ? route('helados.imagen', [$this, 'v' => $this->updated_at?->timestamp]) : null;
    }
}
