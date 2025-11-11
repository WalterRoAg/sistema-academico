<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AsistenciasExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $datos;
    public function __construct(array $datos) { $this->datos = $datos; }
    public function collection() { return $this->datos['asistencias']; }
    public function headings(): array {
        return [
            'ID Asistencia', 'Docente (CI)', 'Nombre Docente',
            'Materia', 'Sigla', 'Aula',
            'Fecha Marcada', 'Hora Marcada', 'Estado',
        ];
    }

    /**
     * Mapea los datos de cada fila (VERSIÓN CORREGIDA)
     */
    public function map($asistencia): array
    {
        // Accedemos a los datos a través de las relaciones
        $horario = $asistencia->horarioClase;
        
        // --- RUTA CORREGIDA ---
        $persona = $horario->docentePersona;
        $user_ci = $persona->user->nombre ?? 'N/A';    // Asumiendo CI está en user.nombre
        $user_nombre = $persona->nombre ?? 'N/A'; // Nombre de la persona
        
        $materia = $horario->grupoMateria->materia;
        $materia_nombre = $materia->nombre ?? 'N/A';
        $materia_sigla = $materia->sigla ?? 'N/A';
        
        $aula_numero = $horario->aula->numero ?? 'N/A';

        return [
            $asistencia->id,
            $user_ci,         // CI
            $user_nombre,     // Nombre
            $materia_nombre,
            $materia_sigla,
            $aula_numero,
            $asistencia->fecha_hora->format('Y-m-d'),
            $asistencia->fecha_hora->format('H:i:s'),
            $asistencia->estado,
        ];
    }

    public function styles(Worksheet $sheet) {
        return [ 1 => ['font' => ['bold' => true, 'size' => 12]] ];
    }
}