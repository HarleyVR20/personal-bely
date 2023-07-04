<?php

namespace App\Imports;

use App\Models\Area;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class AreasImport
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

                // Obtener los valores de las celdas
                $gerencia = $cells[0]->getValue();
                $sub_area = $cells[1]->getValue();

                // Crear un nuevo objeto Area y guardar en la base de datos
                $area = new Area([
                    'gerencia' => $gerencia,
                    'sub_area' => $sub_area,
                ]);
                $area->save();
            }
        }

        // Cerrar el lector de Excel
        $reader->close();

        return true;
    }
}
