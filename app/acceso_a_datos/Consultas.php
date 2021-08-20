<?php

function busquedaSimple($tabla,$campoCondicion,$dato){

    //ACA IRIA UN FILTRO POR TIPO DE DATO

    $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
    $consulta=$accesoDatos->prepararConsulta("SELECT * FROM $tabla WHERE $campoCondicion='$dato'");
    $consulta->execute();
    $resultadoConsulta=$consulta->fetchAll(PDO::FETCH_ASSOC);
    return $resultadoConsulta;
}

function eliminacionSimple($tabla,$campo/*,$condicion,$dato*/){

    //ACA IRIA UN FILTRO POR TIPO DE DATO

    //$condicion=$campo.''.$condicion.''.$dato;

    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $date=date('Y-m-d H:i:s');

    $accesoDatos=Acceso_a_datos::obtenerConexionBD(); 
    $consulta=$accesoDatos->prepararConsulta("DELETE FROM $tabla WHERE vencimiento<'$date'");
    //echo "DELETE FROM $tabla WHERE $condicion";
    $consulta->execute();
}


?>