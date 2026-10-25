@extends('layouts.app')

@section('titulo', $helado->exists ? 'Editar '.$helado->nombre : 'Nuevo helado')

@section('contenido')
    <h1>{{ $helado->exists ? 'Editar «'.$helado->nombre.'»' : 'Nuevo helado' }}</h1>

    @if ($errors->any())
        <ul class="aviso aviso-error" role="alert">
            @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    @endif

    <form class="panel formulario" method="post" enctype="multipart/form-data"
          action="{{ $helado->exists ? route('helados.update', $helado) : route('helados.store') }}">
        @csrf
        @if ($helado->exists) @method('PUT') @endif

        <div class="campo-foto ancho">
            <div class="foto foto-grande">
                <img id="vista" @if ($helado->urlImagen()) src="{{ $helado->urlImagen() }}" @else hidden @endif alt="">
                <span id="sin-vista" class="sin-foto" @if ($helado->urlImagen()) hidden @endif>Sin foto</span>
            </div>
            <div class="foto-controles">
                <label>Foto del producto
                    <input id="imagen" name="imagen" type="file" accept="image/jpeg,image/png,image/webp">
                </label>
                <small>JPG, PNG o WebP de hasta 2 MB. Se guarda en la base de datos MySQL.</small>
                @if ($helado->urlImagen())
                    <label class="check"><input type="checkbox" name="quitar_imagen" value="1"> Quitar la foto actual</label>
                @endif
            </div>
        </div>

        <label>Código <input name="codigo" value="{{ old('codigo', $helado->codigo) }}" maxlength="20" required placeholder="CON-010"></label>
        <label>Categoría
            <select name="categoria" required>
                @foreach (\App\Models\Helado::CATEGORIAS as $c)
                    <option @selected(old('categoria', $helado->categoria) === $c)>{{ $c }}</option>
                @endforeach
            </select>
        </label>
        <label class="ancho">Nombre <input name="nombre" value="{{ old('nombre', $helado->nombre) }}" maxlength="120" required></label>
        <label class="ancho">Descripción <textarea name="descripcion" maxlength="300" rows="3">{{ old('descripcion', $helado->descripcion) }}</textarea></label>
        <label>Precio (USD) <input name="precio" type="number" step="0.01" min="0" value="{{ old('precio', $helado->precio) }}" required></label>
        <label>Existencias <input name="existencias" type="number" min="0" value="{{ old('existencias', $helado->existencias) }}" required></label>

        <div class="botones ancho">
            <a class="boton boton-sec" href="{{ route('helados.index') }}">Cancelar</a>
            <button class="boton">{{ $helado->exists ? 'Guardar cambios' : 'Agregar al catálogo' }}</button>
        </div>
    </form>

    <script>
        // Vista previa de la foto antes de subirla
        document.getElementById('imagen').addEventListener('change', (e) => {
            const archivo = e.target.files[0];
            const vista = document.getElementById('vista');
            if (!archivo) return;
            vista.src = URL.createObjectURL(archivo);
            vista.hidden = false;
            document.getElementById('sin-vista').hidden = true;
        });
    </script>
@endsection
