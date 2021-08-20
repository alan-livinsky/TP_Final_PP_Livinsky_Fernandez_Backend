<?php

function busquedaSimple($tabla,$campoCondicion,$dato){

    //ACA IRIA UN FILTRO POR TIPO DE DATO

    $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
    $consulta=$accesoDatos->prepararConsulta("SELECT * FROM $tabla WHERE $campoCondicion='$dato'");
    $consulta->execute();
    $resultadoConsulta=$consulta->fetchAll(PDO::FETCH_ASSOC);
    return $resultadoConsulta;
}

function eliminacionSimple($tabla,$campo,$condicion,$datoAcomparar,$tipoDeDato){//TIPO DE DATO ->String,Number,etc

    $datoAcomparar=prepararDatoParaConsulta($datoAcomparar,$tipoDeDato);

    $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
    $consulta=$accesoDatos->prepararConsulta("DELETE FROM $tabla WHERE $campo $condicion $datoAcomparar");
    //echo "DELETE FROM $tabla WHERE $condicion";
    $consulta->execute();
}

function prepararDatoParaConsulta($datoAPreparar,$tipoDeDato){

    if($tipoDeDato=="String"){
        $datoAPreparar="'".$datoAPreparar."'";
        return $datoAPreparar;
    }
    else{
        return $datoAPreparar;
    }
  
}

?>





