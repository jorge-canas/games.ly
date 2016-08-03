<?php
    require("config.php");
    //Titular: Marte tuvo....

    //seleccionar base datos
    $db = $mongo->elpais;

    //seleccionar coleccion
    $col = $db->noticias;

    //selecciono todos los elstrongentos de la colección y los recorro

    $cursor = $col->find();
    foreach ($cursor as $key => $value) {
        $pending = $value['pending'];
        foreach ($etiquetas as $etiquetasKey => $etiquetasValue) {
            if (strcmp($etiquetasValue, "Ciencia") == 0) {
                echo "<strong>Titular</strong> ". $value['titular']."<br />";
                echo "<strong>Frases descatacas:</strong><br />";
                $frasesDestacadas = $value['frases_destacadas'];
                foreach ($frasesDestacadas as $subKey => $subValue) {
                    echo $subValue."<br />";
                }
                echo "<br />";
            }
        }
        
    }
?>