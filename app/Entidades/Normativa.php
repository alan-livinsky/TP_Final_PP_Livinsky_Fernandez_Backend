<?php

  function buscarListaNormativa(){
        $accesoDatos = Acceso_a_datos::obtenerConexionBD();
        $consulta = $accesoDatos->prepararConsulta("SELECT * FROM normativa_relacionada");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }


?>