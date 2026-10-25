<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#e81d2c">
    <title>@yield('titulo', 'Catálogo') · {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('img/icono-sarita.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Montserrat:wght@600;700;800&display=swap">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="barra">
        <div class="barra-in">
            <a class="marca" href="{{ route('helados.index') }}">
                <img src="{{ asset('img/logo-sarita.png') }}" alt="Sarita" width="140" height="62">
                <span class="marca-texto"><strong>Sorbetería</strong><small>Catálogo de helados · El Salvador</small></span>
            </a>
            <a class="boton boton-amarillo" href="{{ route('helados.create') }}">+ Nuevo helado</a>
        </div>
    </header>

    <main class="contenido">
        @if (session('ok'))
            <p class="aviso aviso-ok" role="status">{{ session('ok') }}</p>
        @endif
        @yield('contenido')
    </main>

    @php($info = \App\Http\Controllers\PlataformaController::info())
    <footer class="pie">
        <div class="pie-in">
            <p class="pie-lema">Comparte tu alegría</p>
            <span class="pie-titulo">Desplegado en Kubo PaaS · plan básico</span>
            <ul class="stack">
                @foreach ($info as $nombre => $valor)
                    <li><b>{{ $nombre }}</b> {{ $valor }}</li>
                @endforeach
            </ul>
            <p class="pie-nota">Proyecto académico de Informática en la Nube: caso simulado, sin relación oficial con Helados Sarita.</p>
        </div>
    </footer>
</body>
</html>
