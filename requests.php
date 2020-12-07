<?php

    $response = array();

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


    if(isset($_POST["opcion"])){

        $texto = $_POST["texto"];

        switch($_POST["opcion"]){
            case "tablaExcel":

                $tablaFinal = "<table class='tabla' border=1>\n";

                if($texto != ""){ 

                    $rowTmp = explode("\n", $texto);

                    foreach($rowTmp as $indexRows => $rows){
                        $colTmp = explode("\t", $rows);
                        $tablaFinal .= "\t<tr>\n\t\t";
                        foreach($colTmp as $indexCols => $cols){
                            $valorTmp = rtrim($cols);
                            $tablaFinal .= "<td>$valorTmp</td>\n\t\t";
                        }
                        $tablaFinal .= "\n\t</tr>\n";

                    }

                    $tablaFinal .= "</table>";

                    $response = array(
                        "status" => "ok",
                        "data" => $tablaFinal
                    );

                    echo json_encode($response);

                } else {
                    echo "Ingrese un texto válido";
                }

            break;

            case 'formularioJSON':
                
                $jsonTmp = json_decode($texto,true);

                $formFinal = "<form class='formGen' action='' method=''>\n\n";

                for($i = 0; $i< count($jsonTmp); $i++){
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
