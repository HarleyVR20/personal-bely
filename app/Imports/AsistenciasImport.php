<?php

namespace App\Imports;

use App\Models\Asistencia;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class AsistenciasImport
{
    public function import($filePath)
    {
        $reader = ReaderEntityFactory::createXLSXReader();

        // Abrir el archivo Excel
        $reader->open($filePath);

        $rowIndex = 0; // Variable para el índice de la fila

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $rowIndex++;

                $cells = $row->getCells();

                // Ignorar la primera fila si contiene encabezados
                if ($rowIndex === 1) {
                    continue;
                }

                // $asistencia->empleado_id = $validatedData['e_selectEmpleado'];
                // $asistencia->area_id = $validatedData['e_area'];
                // $asistencia->estado = $validatedData['e_asistencia'];
                // $asistencia->dia = $validatedData['e_fecha'];
                // Obtener los valores de las celdas
                $e_selectEmpleado = $cells[0]->getValue();
                $e_area = $cells[1]->getValue();
                $e_estado = $cells[2]->getValue();
                $e_dia = $cells[3]->getValue();
                $e_hora_entrada = $cells[4]->getValue();
                $e_hora_salida = $cells[5]->getValue();

                // Crear un nuevo objeto Area y guardar en la base de datos
                $asistencia = new Asistencia();
                $asistencia->empleado_id = $e_selectEmpleado;
                $asistencia->area_id = $e_area;
                $asistencia->estado = $e_estado;
                $asistencia->dia = $e_dia;
                $asistencia->hora_entrada = $e_hora_entrada;
                $asistencia->hora_salida = $e_hora_salida;
                $asistencia->save();
            }
        }

        // Cerrar el lector de Excel
        $reader->close();

        return true;
    }
}
