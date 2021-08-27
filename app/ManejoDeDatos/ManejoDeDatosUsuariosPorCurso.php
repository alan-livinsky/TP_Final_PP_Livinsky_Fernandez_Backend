<?php

    function retornarEstadoActualizacionContraseña($request,$response,$args){
        
        $datosUsuario=$request->getAttribute("token");
        $jsonCursos=$request->getBody();
        $CursosAsociar=json_decode($jsonCursos);

        echo $CursosAsociar;
      

        $estadoAsociacion=asociarProfesorCurso($insertPreparado);
        $response->getBody()->write(Json_encode($estadoAsociacion));                                    
        return $response->withHeader('Content-type','application/json');
    }

?>