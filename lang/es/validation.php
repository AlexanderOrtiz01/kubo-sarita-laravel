<?php

return [
    'required' => 'El campo :attribute es obligatorio.',
    'unique' => 'Ya existe un producto con ese :attribute.',
    'numeric' => 'El campo :attribute debe ser un número.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'in' => 'El valor de :attribute no es válido.',
    'string' => 'El campo :attribute debe ser texto.',
    'min' => ['numeric' => 'El campo :attribute no puede ser menor que :min.'],
    'max' => ['numeric' => 'El campo :attribute no puede ser mayor que :max.', 'string' => 'El campo :attribute admite hasta :max caracteres.'],
];
