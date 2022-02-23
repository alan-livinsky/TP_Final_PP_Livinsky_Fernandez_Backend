<?php

    function retornarEstadoAsociarCurso($request,$response,$args){
        
        $datosUsuario=$request->getAttribute("token");
        $id_usuario=$datosUsuario['sub'];
        $jsonCursos=$request->getBody();
        $CursosAsociar=json_decode($jsonCursos);

        //var_dump($CursosAsociar);

        $insertPreparado="INSERT INTO usuarios_por_curso values";

        for($i=0;$i<count($CursosAsociar);$i++){
            $curso=$CursosAsociar[$i];
            if($i<(count($CursosAsociar)-1)){
                $insertPreparado=$insertPreparado."($id_usuario,$curso),";
            }
            else{
                $insertPreparado=$insertPreparado."($id_usuario,$curso)";
            }
        }

        //echo $insertPreparado;
      
        $estadoAsociacion=asociarProfesorCurso($insertPreparado);
        $response->getBody()->write(Json_encode($estadoAsociacion));                                    
        return $response->withHeader('Content-type','application/json');
    }

?>