<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Asistencia</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; font-size: 11px; }
        h1 { text-align: center; font-size: 16px; }
        .details { margin-bottom: 15px; font-size: 11px; }
    </style>
</head>
<body>

    <h1>Reporte de Asistencia</h1>

    <div class="details">
        <p><strong>Rango de Fechas:</strong> {{ \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y') }}</p>
        @if ($docente)
            <p><strong>Docente:</strong> {{ $docente->persona->nombre }} (CI: {{ $docente->nombre }})</p>
        @endif
        @if ($materia)
            <p><strong>Materia:</strong> {{ $materia->nombre }} ({{ $materia->sigla }})</p>
        @endif
    </div>

    @if ($asistencias->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Docente</th>
                    <th>Materia</th>
                    <th>Aula</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($asistencias as $asistencia)
                    <tr>
                        <td>{{ $asistencia->id }}</td>
                        
                        @php
                            // Definimos variables para que sea más limpio y seguro
                            $horario = $asistencia->horarioClase;
                            $persona = $horario->docentePersona ?? null;
                            $materia = $horario->grupoMateria->materia ?? null;
                            $aula = $horario->aula ?? null;
                        @endphp
                        
                        <td>{{ $persona->nombre ?? 'N/A' }}</td>
                        <td>{{ $materia->sigla ?? 'N/A' }}</td>
                        <td>{{ $aula->numero ?? 'N/A' }}</td>
                        <td>{{ $asistencia->fecha_hora->format('d/m/Y') }}</td>
                        <td>{{ $asistencia->fecha_hora->format('H:i:s') }}</td>
                        <td>{{ $asistencia->estado }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No se encontraron registros de asistencia para los filtros seleccionados.</p>
    @endif

    <p style="margin-top: 20px; font-size: 9px; text-align: right;">
        Reporte generado el: {{ now()->format('d/m/Y H:i:s') }}
    </p>

</body>
</html>