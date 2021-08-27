<?php

    function retornarEstadoAsociarCurso($request,$response,$args){
        
        $datosUsuario=$request->getAttribute("token");
        $jsonCursos=$request->getBody();
        $CursosAsociar=json_decode($jsonCursos);

        echo "pepe";
        echo $CursosAsociar;
      

        $estadoAsociacion=asociarProfesorCurso($insertPreparado);
        $response->getBody()->write(Json_encode($estadoAsociacion));                                    
        return $response->withHeader('Content-type','application/json');
    }

?>