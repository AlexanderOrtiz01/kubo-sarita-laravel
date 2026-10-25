<?php

namespace App\Http\Controllers;

use App\Models\Helado;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HeladoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = trim((string) $request->query('buscar', ''));
        $categoria = in_array($request->query('categoria'), Helado::CATEGORIAS, true) ? $request->query('categoria') : '';

        $helados = Helado::sinImagen()
            ->when($buscar !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('nombre', 'like', "%{$buscar}%")
                ->orWhere('codigo', 'like', "%{$buscar}%")
                ->orWhere('descripcion', 'like', "%{$buscar}%")))
            ->when($categoria !== '', fn ($q) => $q->where('categoria', $categoria))
            ->orderBy('categoria')->orderBy('nombre')
            ->paginate(12)
            ->withQueryString();

        $resumen = [
            'productos' => Helado::count(),
            'unidades' => (int) Helado::sum('existencias'),
            'agotados' => Helado::where('existencias', 0)->count(),
            'conFoto' => Helado::whereNotNull('imagen_tipo')->count(),
        ];

        return view('helados.index', compact('helados', 'buscar', 'categoria', 'resumen'));
    }

    public function create()
    {
        return view('helados.form', ['helado' => new Helado(['existencias' => 0, 'categoria' => 'Conos'])]);
    }

    public function store(Request $request)
    {
        $helado = new Helado($this->validar($request));
        $this->ponerImagen($request, $helado);
        $helado->save();

        return redirect()->route('helados.index')->with('ok', "«{$helado->nombre}» agregado al catálogo.");
    }

    public function edit(Helado $helado)
    {
        return view('helados.form', compact('helado'));
    }

    public function update(Request $request, Helado $helado)
    {
        $helado->fill($this->validar($request, $helado));
        if ($request->boolean('quitar_imagen')) {
            $helado->imagen = null;
            $helado->imagen_tipo = null;
        }
        $this->ponerImagen($request, $helado);
        $helado->save();

        return redirect()->route('helados.index')->with('ok', "«{$helado->nombre}» actualizado.");
    }

    public function destroy(Helado $helado)
    {
        $helado->delete();

        return redirect()->route('helados.index')->with('ok', "«{$helado->nombre}» eliminado del catálogo.");
    }

    // La foto sale de MySQL con su tipo guardado; nosniff evita que el navegador la interprete como otra cosa
    public function imagen(Helado $helado)
    {
        abort_unless($helado->imagen_tipo && $helado->imagen !== null, 404);

        return response($helado->imagen, 200, [
            'Content-Type' => $helado->imagen_tipo,
            'Cache-Control' => 'public, max-age=86400',
            'X-Content-Type-Options' => 'nosniff',
            'Content-Security-Policy' => "default-src 'none'; style-src 'unsafe-inline'",
        ]);
    }

    private function ponerImagen(Request $request, Helado $helado): void
    {
        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $helado->imagen = file_get_contents($archivo->getRealPath());
            $helado->imagen_tipo = $archivo->getMimeType();
        }
    }

    private function validar(Request $request, ?Helado $helado = null): array
    {
        return $request->validate([
            'codigo' => ['required', 'string', 'max:20', Rule::unique('helados')->ignore($helado)],
            'nombre' => ['required', 'string', 'max:120'],
            'categoria' => ['required', Rule::in(Helado::CATEGORIAS)],
            'descripcion' => ['nullable', 'string', 'max:300'],
            'precio' => ['required', 'numeric', 'min:0', 'max:9999'],
            'existencias' => ['required', 'integer', 'min:0', 'max:100000'],
            'imagen' => ['nullable', 'file', 'max:2048', 'mimetypes:'.implode(',', Helado::TIPOS_IMAGEN)],
        ], [
            'imagen.max' => 'La foto no puede pesar más de 2 MB.',
            'imagen.mimetypes' => 'La foto debe ser JPG, PNG o WebP.',
        ], [
            'codigo' => 'código', 'descripcion' => 'descripción', 'imagen' => 'foto',
        ]);
    }
}
