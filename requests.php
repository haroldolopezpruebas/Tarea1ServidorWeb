<?php

    // Arreglo donde se guardará la respuesta final
    $response = array();

    // Función que evalua qué respuesta HTML deberá generar
    function evaluarFormulario($jsonTmp,&$formFinal) {
        $formFinal .= "\t<div class='formGroup'>\n";
        if($jsonTmp["tipo"] == "text"){
            $formFinal .= "\t\t<label class='labelChild'>".$jsonTmp["label"]."</label>\n";
            $formFinal .= "\t\t<input class='inputChild' type='text' value='".$jsonTmp["valor"]."'>\n\n";
        }elseif($jsonTmp["tipo"] == "button"){
            $formFinal .= "\t\t<label class='labelChild'>".$jsonTmp["label"]."</label>\n";
            $formFinal .= "\t\t<button>".$jsonTmp["valor"]."</button>\n";
        }elseif($jsonTmp["tipo"] == "date"){
            $formFinal .= "\t\t<label class='labelChild'>".$jsonTmp["label"]."</label>\n";
            $formFinal .= "\t\t<input class='inputChild' type='date' value='".$jsonTmp["valor"]."'>\n\n";
        }elseif($jsonTmp["tipo"] == "number"){
            $formFinal .= "\t\t<label class='labelChild'>".$jsonTmp["label"]."</label>\n";
            $formFinal .= "\t\t<input class='inputChild' type='number' value='".$jsonTmp["valor"]."'>\n\n";
        }elseif($jsonTmp["tipo"] == "select"){
            $formFinal .= "\t\t<label class='labelChild'>".$jsonTmp["label"]."</label>\n";
            $formFinal .= "\t\t<select>\n";
            $j = 0;
            while($j < count($jsonTmp["opciones"])){
                $selected = "";

                if($jsonTmp["opciones"][$j][valor] == $jsonTmp["valor"])
                    $selected = "selected";

                $formFinal .= "\n\t\t\t<option ".$selected." value='".$jsonTmp["opciones"][$j]["valor"]."'>".$jsonTmp["opciones"][$j]["text"]."</option>";
                $j++;
            }
            $formFinal .= "\n\t\t</select>\n";
        }

        $formFinal .= "\t</div>\n\n";
    }


    // Se recibe desde la variable $_POST en la posición opcion qué acción debe tomar
    if(isset($_POST["opcion"])){

        // Variable que almacena lo que ingresó el usuario
        $texto = $_POST["texto"];

        // Se utiliza la estructura switch para evaluar la opción
        switch($_POST["opcion"]){

            // El caso de generación de tabla desde un excel
            case "tablaExcel":

                // Se utiliza la variable temporal para guardar el string de HTML que retornará
                $tablaFinal = "<table class='tabla' border=1>\n";

                if($texto != ""){ 

                    // Se separa la cadena recibida por bajados de línea para obtener las filas
                    $rowTmp = explode("\n", $texto);

                    foreach($rowTmp as $indexRows => $rows){
                        // Se separa la cadena recibida por tabulaciones para obtener las columnas
                        $colTmp = explode("\t", $rows);
                        $tablaFinal .= "\t<tr>\n\t\t";
                        foreach($colTmp as $indexCols => $cols){
                            // Se eliminan los espacios en blanco que se generan
                            $valorTmp = rtrim($cols);
                            $tablaFinal .= "<td>$valorTmp</td>\n\t\t";
                        }
                        $tablaFinal .= "\n\t</tr>\n";

                    }

                    $tablaFinal .= "</table>";

                    // Se guarda en la variable $response un arreglo con el status y la información a mostrar
                    $response = array(
                        "status" => "ok",
                        "data" => $tablaFinal
                    );

                    // Se utiliza esta función para convertir de arreglo de php a un string tipo JSON
                    echo json_encode($response);

                } else {
                    echo "Ingrese un texto válido";
                }

            break;

            // El caso de generación de formularios HTML
            case 'formularioJSON':
                
                // Se transforma el string a un arreglo de php
                $jsonTmp = json_decode($texto,true);

                // En esta variable se almancenará lo que se mostrará en HTML
                $formFinal = "<form class='formGen' action='' method=''>\n\n";

                for($i = 0; $i< count($jsonTmp); $i++){
                    // Se utiliza la función para evaluar qué control de formulario deberá escribir en la variable
                    evaluarFormulario($jsonTmp[$i],$formFinal);
                }

                $formFinal .= "</form>";

                $response = array(
                    "status" => "ok",
                    "data" => $formFinal
                );

                echo json_encode($response);
                
            break;

            default:
                echo "ninguna";
        }

    }else{
        echo "Error en la petición ...";
    }
