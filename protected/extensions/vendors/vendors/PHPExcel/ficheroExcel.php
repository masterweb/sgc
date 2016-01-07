<?php
//ini_set('display_errors', 1);
require_once 'includes/PHPExcel.php';
$objPHPExcel = new PHPExcel();

$estiloTituloReporte = array(
    'font' => array(
        'name' => 'Tahoma',
        'bold' => true,
        'italic' => false,
        'strike' => false,
        'size' => 11,
        'color' => array(
            'rgb' => 'B6121A'
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'rotation' => 0,
        'wrap' => TRUE
    )
);
$estiloSubTituloReporte = array(
    'font' => array(
        'name' => 'Tahoma',
        'bold' => true,
        'italic' => false,
        'strike' => false,
        'size' => 11,
        'color' => array(
            'rgb' => '000000'
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'rotation' => 0,
        'wrap' => TRUE
    )
);

$estiloTituloColumnas = array(
    'font' => array(
        'name' => 'Arial',
        'bold' => true,
        'size' => 9,
        'color' => array(
            'rgb' => '333333'
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array(
            'rgb' => 'F1F1F1')
    ),
    'borders' => array(
        'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array(
                'rgb' => '143860'
            )
        ),
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array(
                'rgb' => '143860'
            )
        ),
        'left' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array(
                'rgb' => '143860'
            )
        ),
        'right' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array(
                'rgb' => '143860'
            )
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'wrap' => TRUE
    )
);

$estiloInformacion = new PHPExcel_Style();
$estiloInformacion->applyFromArray(array(
    'font' => array(
        'name' => 'Arial',
        'color' => array(
            'rgb' => '000000'
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array(
            'argb' => 'FFd9b7f4')
    ),
    'borders' => array(
        'left' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array(
                'rgb' => '3a2a47'
            )
        )
    )
));

require_once 'includes/encuesta.php';
require_once 'includes/Ips.php';
$enc = new EncuestaSat();

if (isset($_POST['concesionario'])) {
    $idConcesionario = $_POST['concesionario'];
    if (isset($_POST['fecha']) && isset($_POST['fecha2'])) {// para resultados de encuesta por fecha limite de 7, 14, 21 y 30 dias
        $result = $enc->getEncuestaConcesionarioExcelDataDate($idConcesionario, $_POST['fecha'], $_POST['fecha2']);
    } else {
        $result = $enc->getEncuestaConcesionarioExcelData($idConcesionario);
    }
    $idCiudad = $enc->getIdCiudad($idConcesionario);
    $nombreCiudad = $enc->getNombreCiudad($idCiudad);
    $nombreConcesionario = $enc->getNombreConcesionario($idConcesionario);
    // Se asignan las propiedades del libro
    $objPHPExcel->getProperties()->setCreator("Kia Ecuador") // Nombre del autor
            ->setLastModifiedBy("Kia Ecuador") //Ultimo usuario que lo modificó
            ->setTitle("Reporte Resultados de Encuestas de Satisfaccion") // Titulo
            ->setSubject("Reporte Excel con PHP y MySQL") //Asunto
            ->setDescription("Reporte de encuestas") //Descripción
            ->setKeywords("Reporte de encuestas") //Etiquetas
            ->setCategory("Reporte excel"); //Categorias

    $dt = time();
    $fecha_actual = strftime("%Y-%m-%d", $dt);

    if (isset($_POST['fecha'])) {
        $tituloReporte = "Reporte Resultados Encuestas de Satisfacción - Concesionario: " . $nombreConcesionario;
        $subTituloReporte = "Desde el ".$_POST['fecha']." hasta el ".$_POST['fecha2'];
        $titulosColumnas = array(
            'Id Formulario',
            '¿Usted recibió la información de forma adecuada y oportuna?',
            '¿Se contactó con usted un asesor comercial?',
            '¿Le ofrecieron una prueba de manejo en el concesionario o su lugar de conveniencia?',
            'Nombre',
            'Cédula',
            'Dirección',
            'Teléfono',
            'Celular',
            'Email',
            'Fecha'
        );

        // Se combinan las celdas A1 hasta F1, para colocar ahí el titulo del reporte
        $objPHPExcel->setActiveSheetIndex(0)
                ->mergeCells('A1:L1');
        $objPHPExcel->setActiveSheetIndex(0)
                ->mergeCells('A2:L2');

        // Se agregan los titulos del reporte
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', $tituloReporte) // Titulo del reporte
                ->setCellValue('A2', $subTituloReporte) // Subtitulo del reporte
                ->setCellValue('A3', $titulosColumnas[0])  //Titulo de las columnas
                ->setCellValue('B3', $titulosColumnas[1])
                ->setCellValue('C3', $titulosColumnas[2])
                ->setCellValue('D3', $titulosColumnas[3])
                ->setCellValue('E3', $titulosColumnas[4])
                ->setCellValue('F3', $titulosColumnas[5])
                ->setCellValue('G3', $titulosColumnas[6])
                ->setCellValue('H3', $titulosColumnas[7])
                ->setCellValue('I3', $titulosColumnas[8])
                ->setCellValue('J3', $titulosColumnas[9])
                ->setCellValue('K3', $titulosColumnas[10]);

        //Se agregan los datos del concesionario

        $i = 4; //Numero de fila donde se va a comenzar a rellenar
        while ($fila = mysql_fetch_assoc($result)) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $fila['id_atencion_detalle'])
                    ->setCellValue('B' . $i, ($fila['preg1'] == 1 ? 'Si' : 'No'))
                    ->setCellValue('C' . $i, ($fila['preg2'] == 1 ? 'Si' : 'No'))
                    ->setCellValue('D' . $i, ($fila['preg3'] == 1 ? 'Si' : 'No'))
                    ->setCellValue('E' . $i, $fila['nombre'])
                    ->setCellValue('F' . $i, $fila['cedula'])
                    ->setCellValue('G' . $i, $fila['direccion'])
                    ->setCellValue('H' . $i, $fila['telefono'])
                    ->setCellValue('I' . $i, $fila['celular'])
                    ->setCellValue('J' . $i, $fila['email'])
                    ->setCellValue('K' . $i, $fila['fecha']);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($estiloTituloReporte);
        $objPHPExcel->getActiveSheet()->getStyle('A2:K2')->applyFromArray($estiloSubTituloReporte);
        $objPHPExcel->getActiveSheet()->getStyle('A3:K3')->applyFromArray($estiloTituloColumnas);
        //$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:F" . ($i - 1));

        for ($i = 'A'; $i <= 'K'; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setWidth(27);
        }

        // Se asigna el nombre a la hoja
        $objPHPExcel->getActiveSheet()->setTitle("{$nombreConcesionario}");

// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
        $objPHPExcel->setActiveSheetIndex(0);

// Inmovilizar paneles
//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
        $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0, 4);

        // Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reporte-Resultados-Encuestas-Web-' . $nombreConcesionario . '.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    } else {
        $tituloReporte = "Reporte de Satisfaccion Ciudad: " . $nombreCiudad . " - Concesionario: " . $nombreConcesionario;

        $titulosColumnas = array('Ips', '¿Usted recibió la información de forma adecuada y oportuna?',
            '¿Se contacto con usted un asesor comercial?',
            '¿Le ofrecieron una prueba de manejo en el concesionario o su lugar de conveniencia?',
            '¿Cómo califica la marca Kia?',
            '¿Cuál es su calificación de la página web?'
        );

        // Se combinan las celdas A1 hasta F1, para colocar ahí el titulo del reporte
        $objPHPExcel->setActiveSheetIndex(0)
                ->mergeCells('A1:H1');

        // Se agregan los titulos del reporte
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', $tituloReporte) // Titulo del reporte
                ->setCellValue('A3', $titulosColumnas[0])  //Titulo de las columnas
                ->setCellValue('B3', $titulosColumnas[1])
                ->setCellValue('C3', $titulosColumnas[2])
                ->setCellValue('D3', $titulosColumnas[3])
                ->setCellValue('E3', $titulosColumnas[4])
                ->setCellValue('F3', $titulosColumnas[5]);

        //Se agregan los datos del concesionario

        $i = 4; //Numero de fila donde se va a comenzar a rellenar
        while ($fila = mysql_fetch_assoc($result)) {
            switch ($fila['preg4']) {
                case 1:
                    $respuestaKia = 'Vibrante';
                    break;
                case 2:
                    $respuestaKia = 'Confiable';
                    break;
                case 3:
                    $respuestaKia = 'Particular';
                    break;

                default:
                    break;
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $fila['ips'])
                    ->setCellValue('B' . $i, ($fila['preg1'] == 1 ? 'Si' : 'No'))
                    ->setCellValue('C' . $i, ($fila['preg2'] == 1 ? 'Si' : 'No'))
                    ->setCellValue('D' . $i, ($fila['preg3'] == 1 ? 'Si' : 'No'))
                    ->setCellValue('E' . $i, $respuestaKia)
                    ->setCellValue('F' . $i, $fila['preg5']);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($estiloTituloReporte);
        $objPHPExcel->getActiveSheet()->getStyle('A3:F3')->applyFromArray($estiloTituloColumnas);
        $objPHPExcel->getActiveSheet()->getStyle('A3:F3')->applyFromArray($estiloTituloColumnas);
        //$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:F" . ($i - 1));

        for ($i = 'A'; $i <= 'F'; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setWidth(27);
        }

        // Se asigna el nombre a la hoja
        $objPHPExcel->getActiveSheet()->setTitle("{$nombreConcesionario}");

// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
        $objPHPExcel->setActiveSheetIndex(0);

// Inmovilizar paneles
//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
        $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0, 4);

        // Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteEncuestaConcesionario' . $nombreConcesionario . '.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
}
if (isset($_POST['ciudad'])) {
    $idCiudad = $_POST['ciudad'];
    $nameCiudad = $enc->getNombreCiudad($idCiudad);
    $result = $enc->getEncuestaCiudadExcelData($idCiudad);
    $objPHPExcel->getProperties()->setCreator("Kia Ecuador") // Nombre del autor
            ->setLastModifiedBy("Kia Ecuador") //Ultimo usuario que lo modificó
            ->setTitle("Reporte de Encuestas de Satisfaccion por Fecha") // Titulo
            ->setSubject("Reporte Excel con PHP y MySQL") //Asunto
            ->setDescription("Reporte de encuestas de satisfaccion") //Descripción
            ->setKeywords("Reporte de encuestas de satisfaccion") //Etiquetas
            ->setCategory("Reportes Satisfaccion"); //Categorias

    $tituloReporte = "Reporte de Satisfaccion para: " . $nameCiudad;
    $titulosColumnas = array('Ips', 'Pregunta 1', 'Pregunta 2', 'Pregunta 3', 'Pregunta 4', 'Pregunta 5');

    // Se combinan las celdas A1 hasta F1, para colocar ahí el titulo del reporte
    $objPHPExcel->setActiveSheetIndex(0)
            ->mergeCells('A1:F1');

    // Se agregan los titulos del reporte
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', $tituloReporte) // Titulo del reporte
            ->setCellValue('A3', $titulosColumnas[0])  //Titulo de las columnas
            ->setCellValue('B3', $titulosColumnas[1])
            ->setCellValue('C3', $titulosColumnas[2])
            ->setCellValue('D3', $titulosColumnas[3])
            ->setCellValue('E3', $titulosColumnas[4])
            ->setCellValue('F3', $titulosColumnas[5]);

    //Se agregan los datos del concesionario

    $i = 4; //Numero de fila donde se va a comenzar a rellenar
    while ($fila = mysql_fetch_assoc($result)) {
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $fila['ips'])
                ->setCellValue('B' . $i, $fila['preg1'])
                ->setCellValue('C' . $i, $fila['preg2'])
                ->setCellValue('D' . $i, $fila['preg3'])
                ->setCellValue('E' . $i, $fila['preg4'])
                ->setCellValue('F' . $i, $fila['preg5']);
        $i++;
    }

    $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($estiloTituloReporte);
    $objPHPExcel->getActiveSheet()->getStyle('A3:F3')->applyFromArray($estiloTituloColumnas);
//    $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:F" . ($i - 1));

    for ($i = 'A'; $i <= 'V'; $i++) {
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
    }

    // Se asigna el nombre a la hoja
    $objPHPExcel->getActiveSheet()->setTitle("{$nameCiudad}");

    // Se activa la hoja para que sea la que se muestre cuando el archivo se abre
    $objPHPExcel->setActiveSheetIndex(0);

    // Inmovilizar paneles
    //$objPHPExcel->getActiveSheet(0)->freezePane('A4');
    $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0, 4);

    // Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ReporteSatisfaccionCiudad' . $nameCiudad . '.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}
if (isset($_POST['fecha'])) {
    $idFecha = $_POST['fecha'];
    $idFecha2 = $_POST['fecha2'];
    if (isset($idFecha) && isset($idFecha2)):
        $result = $enc->getEncuestaFechaExcelData2($idFecha, $idFecha2);
    else:
        $result = $enc->getEncuestaFechaExcelData($idFecha);
    endif;

    // Se asignan las propiedades del libro
    $objPHPExcel->getProperties()->setCreator("Kia Ecuador") // Nombre del autor
            ->setLastModifiedBy("Kia Ecuador") //Ultimo usuario que lo modificó
            ->setTitle("Reporte de Encuestas de Satisfaccion por Fecha") // Titulo
            ->setSubject("Reporte Excel con PHP y MySQL") //Asunto
            ->setDescription("Reporte de encuestas") //Descripción
            ->setKeywords("Reporte de encuestas") //Etiquetas
            ->setCategory("Reporte excel"); //Categorias

    if (isset($idFecha) && isset($idFecha2)):
        $tituloReporte = "Reporte de Satisfaccion desde el " . $idFecha . " hasta el " . $idFecha2;
    else:
        $tituloReporte = "Reporte de Satisfaccion para el " . $idFecha;
    endif;

    $titulosColumnas = array(
        'Id Encuesta',
        'Nombre',
        'Cédula',
        'Dirección',
        'Teléfono',
        'Celular',
        'Email',
        'Id modelo',
        'Id versión',
        'City id',
        'Dealer id',
        'Id atención',
        'Fecha form',
        'Ips',
        'Pregunta 1',
        'Pregunta 2',
        'Pregunta 3',
        'Pregunta 4',
        'Pregunta 5'
    );

    // Se combinan las celdas A1 hasta F1, para colocar ahí el titulo del reporte
    $objPHPExcel->setActiveSheetIndex(0)
            ->mergeCells('A1:J1');

    // Se agregan los titulos del reporte
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', $tituloReporte) // Titulo del reporte
            ->setCellValue('A3', $titulosColumnas[0])  //Titulo de las columnas
            ->setCellValue('B3', $titulosColumnas[1])
            ->setCellValue('C3', $titulosColumnas[2])
            ->setCellValue('D3', $titulosColumnas[3])
            ->setCellValue('E3', $titulosColumnas[4])
            ->setCellValue('F3', $titulosColumnas[5])
            ->setCellValue('G3', $titulosColumnas[6])
            ->setCellValue('H3', $titulosColumnas[7])
            ->setCellValue('I3', $titulosColumnas[8])
            ->setCellValue('J3', $titulosColumnas[9])
            ->setCellValue('K3', $titulosColumnas[10])
            ->setCellValue('L3', $titulosColumnas[11])
            ->setCellValue('M3', $titulosColumnas[12])
            ->setCellValue('N3', $titulosColumnas[13])
            ->setCellValue('O3', $titulosColumnas[14])
            ->setCellValue('P3', $titulosColumnas[15])
            ->setCellValue('Q3', $titulosColumnas[16])
            ->setCellValue('R3', $titulosColumnas[17])
            ->setCellValue('S3', $titulosColumnas[18]);

    //Se agregan los datos del concesionario

    $i = 4; //Numero de fila donde se va a comenzar a rellenar
    while ($fila = mysql_fetch_assoc($result)) {

        if ($fila['preg4'] == '1') {
            $value4 = 'Vibrante';
        } elseif ($fila['preg4'] == '2') {
            $value4 = 'Confiable';
        } elseif ($fila['preg4'] == '3') {
            $value4 = 'Particular';
        }

        if (!empty($fila['id_modelos'])):
            $vehiculo = $enc->getVehiculo($fila['id_modelos']);
        else:
            $vehiculo = $fila['id_modelos'];
        endif;

        if (!empty($fila['id_version'])):
            $vehiculoModelo = $enc->getVehiculoModelo($fila['id_version']);
        else:
            $vehiculoModelo = $fila['id_version'];
        endif;
        
        if (!empty($fila['cityid'])):
            $nombreCiudad = $enc->getNombreCiudad($fila['cityid']);
        else:
            $nombreCiudad = $fila['cityid'];
        endif;
        
        if (!empty($fila['dealerid'])):
            $nombreConcesionario = $enc->getNombreConcesionario($fila['dealerid']);
        else:
            $nombreConcesionario = $fila['dealerid'];
        endif;

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $fila['id_atencion_detalle'])
                ->setCellValue('B' . $i, $fila['nombre'])
                ->setCellValue('C' . $i, $fila['cedula'])
                ->setCellValue('D' . $i, $fila['direccion'])
                ->setCellValue('E' . $i, $fila['telefono'])
                ->setCellValue('F' . $i, $fila['celular'])
                ->setCellValue('G' . $i, $fila['email'])
                //->setCellValue('G' . $i, $fila['id_modelos'])
                ->setCellValue('H' . $i, $vehiculo)
                ->setCellValue('I' . $i, $vehiculoModelo)
                ->setCellValue('J' . $i, $nombreCiudad)
                ->setCellValue('K' . $i, utf8_decode($nombreConcesionario))
                ->setCellValue('L' . $i, $enc->getAtencion($fila['id_atencion']))
                ->setCellValue('M' . $i, $fila['fecha_form'])
                ->setCellValue('N' . $i, $fila['ips'])
                ->setCellValue('O' . $i, ($fila['preg1'] == 1) ? 'Si' : 'No')
                ->setCellValue('P' . $i, ($fila['preg2'] == 1) ? 'Si' : 'No')
                ->setCellValue('Q' . $i, ($fila['preg3'] == 1) ? 'Si' : 'No')
                ->setCellValue('R' . $i, $value4)
                ->setCellValue('S' . $i, $fila['preg5']);
        $i++;
    }

    $objPHPExcel->getActiveSheet()->getStyle('A1:S1')->applyFromArray($estiloTituloReporte);
    $objPHPExcel->getActiveSheet()->getStyle('A3:S3')->applyFromArray($estiloTituloColumnas);
//    $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:F" . ($i - 1));

    for ($i = 'A'; $i <= 'V'; $i++) {
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
    }

    // Se asigna el nombre a la hoja
    $objPHPExcel->getActiveSheet()->setTitle("{$idFecha}");

    // Se activa la hoja para que sea la que se muestre cuando el archivo se abre
    $objPHPExcel->setActiveSheetIndex(0);

    // Inmovilizar paneles
    //$objPHPExcel->getActiveSheet(0)->freezePane('A4');
    $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0, 4);

    // Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ReporteSatisfaccionFecha' . $idFecha . '.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}
?>
