<?php

namespace Database\Seeders;

use App\Models\Helado;
use Illuminate\Database\Seeder;

// Catálogo inicial (solo si la tabla está vacía: se ejecuta en cada arranque del contenedor).
// Cada producto trae una ilustración SVG de ejemplo; desde el CRUD se reemplaza por una foto real.
class HeladoSeeder extends Seeder
{
    public function run(): void
    {
        if (Helado::exists()) {
            return;
        }

        $catalogo = [
            ['CON-001', 'Cono Sarita de vainilla', 'Conos', 'Cono crujiente con una bola de vainilla cremosa.', 1.25, 180, ['#fff3c4', '#f7d774']],
            ['CON-002', 'Cono doble chocolate', 'Conos', 'Dos bolas de chocolate con chispas.', 2.10, 140, ['#7a4a2f', '#5a3220']],
            ['CON-003', 'Cono fresa y crema', 'Conos', 'Fresa natural con crema dulce.', 1.50, 0, ['#ffb3c1', '#f27a93']],
            ['PAL-001', 'Paleta de leche', 'Paletas', 'La clásica paleta de leche con canela.', 0.75, 320, ['#fffaf0', '#efe2c8']],
            ['PAL-002', 'Paleta de mango con chile', 'Paletas', 'Mango, limón y un toque de chile.', 0.90, 260, ['#ffc233', '#ff8c1a']],
            ['PAL-003', 'Paleta choco-almendra', 'Paletas', 'Vainilla bañada en chocolate con almendras.', 1.35, 95, ['#6b3b22', '#f2e2c4']],
            ['SOR-001', 'Sorby Twist', 'Sorbetes', 'Sorbete de dos sabores en remolino.', 1.60, 150, ['#ff5c8a', '#7ad3ff']],
            ['SOR-002', 'Vaso de sorbete de coco', 'Sorbetes', 'Sorbete de coco con trocitos de coco.', 1.40, 70, ['#ffffff', '#e8e3d8']],
            ['BOM-001', 'Bomba Sarita', 'Bombas', 'Bola de helado cubierta de chocolate.', 1.10, 210, ['#5a3220', '#e81d2c']],
            ['PAS-001', 'Pastel de helado tres leches', 'Pasteles', 'Pastel helado para 10 porciones.', 14.95, 12, ['#fff1d6', '#e81d2c']],
            ['PAS-002', 'Pastel de helado de galleta', 'Pasteles', 'Capas de helado y galleta de chocolate.', 16.50, 8, ['#3a2a24', '#f5f0e6']],
            ['MAL-001', 'Malteada de fresa', 'Malteadas', 'Malteada de 16 oz con crema batida.', 2.75, 60, ['#ff8fab', '#ffffff']],
        ];

        foreach ($catalogo as [$codigo, $nombre, $categoria, $descripcion, $precio, $existencias, $colores]) {
            $h = new Helado(compact('codigo', 'nombre', 'categoria', 'descripcion', 'precio', 'existencias'));
            $h->imagen = self::ilustracion($categoria, ...$colores);
            $h->imagen_tipo = 'image/svg+xml';
            $h->save();
        }
    }

    private static function ilustracion(string $categoria, string $a, string $b): string
    {
        $figura = match ($categoria) {
            'Conos' => "<path d='M120 150 L160 270 L200 150 Z' fill='#e0a458'/><path d='M132 170 L188 170 M140 195 L180 195 M148 220 L172 220' stroke='#b87a35' stroke-width='5'/>"
                ."<circle cx='160' cy='128' r='48' fill='$a'/><circle cx='138' cy='112' r='14' fill='#fff' opacity='.45'/>",
            'Paletas' => "<rect x='152' y='190' width='16' height='80' rx='8' fill='#e0b27a'/><rect x='112' y='60' width='96' height='150' rx='46' fill='$a'/>"
                ."<rect x='112' y='60' width='96' height='60' rx='46' fill='$b'/><rect x='128' y='80' width='14' height='90' rx='7' fill='#fff' opacity='.4'/>",
            'Sorbetes' => "<path d='M106 150 L122 262 L198 262 L214 150 Z' fill='#e81d2c'/><rect x='100' y='140' width='120' height='18' rx='9' fill='#c4141f'/>"
                ."<circle cx='138' cy='128' r='32' fill='$a'/><circle cx='182' cy='128' r='32' fill='$b'/><circle cx='160' cy='100' r='30' fill='$a'/>",
            'Bombas' => "<ellipse cx='160' cy='250' rx='80' ry='14' fill='#000' opacity='.12'/><circle cx='160' cy='170' r='78' fill='$a'/>"
                ."<path d='M98 128 Q160 80 222 128' stroke='$b' stroke-width='12' fill='none' stroke-linecap='round'/><circle cx='130' cy='140' r='16' fill='#fff' opacity='.3'/>",
            'Pasteles' => "<rect x='70' y='150' width='180' height='100' rx='16' fill='$a'/><rect x='70' y='190' width='180' height='22' fill='$b' opacity='.85'/>"
                ."<path d='M70 162 Q100 140 130 162 T190 162 T250 162 L250 150 L70 150 Z' fill='#fff'/><circle cx='160' cy='126' r='14' fill='#e81d2c'/><rect x='157' y='96' width='6' height='22' fill='#4a4a4a'/>",
            default => "<path d='M118 110 L130 262 L190 262 L202 110 Z' fill='$a'/><ellipse cx='160' cy='104' rx='50' ry='22' fill='$b'/>"
                ."<rect x='176' y='40' width='10' height='80' rx='5' fill='#e81d2c' transform='rotate(12 181 80)'/><circle cx='160' cy='84' r='12' fill='#e81d2c'/>",
        };

        return "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 320 320'><rect width='320' height='320' fill='#fff1f1'/>"
            ."<circle cx='160' cy='160' r='130' fill='#ffe1e3'/>$figura</svg>";
    }
}
