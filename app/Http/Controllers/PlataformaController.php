<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

// Lo que corre de verdad: la página lo muestra para comprobar el stack desplegado en Kubo
class PlataformaController extends Controller
{
    public static function info(): array
    {
        try {
            $bd = DB::selectOne('SELECT VERSION() AS v')->v;
        } catch (\Throwable) {
            $bd = 'sin conexión';
        }

        return [
            'PHP' => PHP_VERSION,
            'Laravel' => app()->version(),
            'Servidor web' => $_SERVER['SERVER_SOFTWARE'] ?? 'Apache',
            'MySQL' => $bd,
            'Pod' => env('KUBO_POD', gethostname()),
            'Commit' => substr((string) env('KUBO_COMMIT', 'local'), 0, 7),
        ];
    }

    public function salud()
    {
        DB::select('SELECT 1');

        return response()->json(['estado' => 'ok'] + self::info());
    }
}
