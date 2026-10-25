@extends('layouts.app')

@section('contenido')
    <section class="portada">
        <div>
            <h1>Nuestro catálogo</h1>
            <p>Conos, paletas, bombas y pasteles de helado disponibles en las sucursales.</p>
        </div>
        <dl class="resumen">
            <div><dt>Productos</dt><dd>{{ $resumen['productos'] }}</dd></div>
            <div><dt>Unidades</dt><dd>{{ number_format($resumen['unidades']) }}</dd></div>
            <div><dt>Agotados</dt><dd class="{{ $resumen['agotados'] ? 'alerta' : '' }}">{{ $resumen['agotados'] }}</dd></div>
            <div><dt>Con foto</dt><dd>{{ $resumen['conFoto'] }}</dd></div>
        </dl>
    </section>

    <form class="filtros" method="get" action="{{ route('helados.index') }}">
        <input type="search" name="buscar" value="{{ $buscar }}" placeholder="Buscar por nombre, código o descripción" aria-label="Buscar">
        <select name="categoria" aria-label="Categoría" onchange="this.form.submit()">
            <option value="">Todas las categorías</option>
            @foreach (\App\Models\Helado::CATEGORIAS as $c)
                <option @selected($categoria === $c)>{{ $c }}</option>
            @endforeach
        </select>
        <button class="boton">Buscar</button>
        @if ($buscar !== '' || $categoria !== '')<a class="limpiar" href="{{ route('helados.index') }}">Limpiar</a>@endif
    </form>

    @if ($helados->isEmpty())
        <p class="vacio">No hay helados{{ $buscar !== '' || $categoria !== '' ? ' que coincidan con la búsqueda' : ' en el catálogo' }}.</p>
    @else
        <ul class="rejilla">
            @foreach ($helados as $h)
                <li class="tarjeta">
                    <div class="foto">
                        @if ($h->urlImagen())
                            <img src="{{ $h->urlImagen() }}" alt="{{ $h->nombre }}" loading="lazy">
                        @else
                            <span class="sin-foto">Sin foto</span>
                        @endif
                        <span class="chip">{{ $h->categoria }}</span>
                    </div>
                    <div class="tarjeta-cuerpo">
                        <code>{{ $h->codigo }}</code>
                        <h2>{{ $h->nombre }}</h2>
                        @if ($h->descripcion)<p>{{ $h->descripcion }}</p>@endif
                        <div class="precio-fila">
                            <strong class="precio">${{ number_format($h->precio, 2) }}</strong>
                            <span class="{{ $h->existencias === 0 ? 'alerta' : 'stock' }}">{{ $h->existencias === 0 ? 'Agotado' : $h->existencias.' disp.' }}</span>
                        </div>
                    </div>
                    <div class="acciones">
                        <a class="boton boton-sec" href="{{ route('helados.edit', $h) }}">Editar</a>
                        <form method="post" action="{{ route('helados.destroy', $h) }}" onsubmit="return confirm('¿Eliminar «{{ $h->nombre }}» del catálogo?')">
                            @csrf @method('DELETE')
                            <button class="enlace-peligro">Eliminar</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

    @if ($helados->hasPages())
        <nav class="paginas">
            @if ($helados->previousPageUrl())<a href="{{ $helados->previousPageUrl() }}">← Anterior</a>@endif
            <span>Página {{ $helados->currentPage() }} de {{ $helados->lastPage() }}</span>
            @if ($helados->nextPageUrl())<a href="{{ $helados->nextPageUrl() }}">Siguiente →</a>@endif
        </nav>
    @endif
@endsection
