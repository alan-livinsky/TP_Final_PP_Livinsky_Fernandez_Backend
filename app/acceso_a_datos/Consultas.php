<?php

function busquedaSimple($tabla,$campoCondicion,$dato){

    //ACA IRIA UN FILTRO POR TIPO DE DATO
    
    $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
    $consulta=$accesoDatos->prepararConsulta("SELECT * FROM $tabla WHERE $campoCondicion='$dato'");
    $consulta->execute();
    $resultadoConsulta=$consulta->fetchAll(PDO::FETCH_ASSOC);
    return $resultadoConsulta;
}

function eliminacionSimple($tabla,$campo,$tipoCondicion,$datoAEvaluar){

    //ACA IRIA UN FILTRO POR TIPO DE DATO

    $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
    echo "DELETE FROM $tabla WHERE vencimiento".$condicion."'$datoAEvaluar'";
   /*
    $consulta=$accesoDatos->prepararConsulta("DELETE FROM $tabla WHERE vencimiento".$condicion."'$datoAEvaluar'");

    echo "DELETE FROM $tabla WHERE vencimiento".$condicion."'$datoAEvaluar'";
    $consulta->execute();*/
}

?>