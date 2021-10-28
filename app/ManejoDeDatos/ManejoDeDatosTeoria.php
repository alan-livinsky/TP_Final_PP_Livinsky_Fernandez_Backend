<?php

//SE MANEJAN LOS DATOS TANTO DE LA TEORIA DEL SISTEMA COMO DE LA TEORIA DEL CURSO.

    //AMBOS TIPOS DE TEORIA
        function retornarListaGeneralDeTitulos($request,$response,$args){
            $listaGeneral=buscarListaGenaralDeTitulos();
            $response->getBody()->write(json_encode($listaGeneral));
            return $response->withHeader('Content-type','application/json');
        }

        function retornarListaTitulosEjercicios($request,$response,$args){
            $id_ejercicio=$args['id_ejercicio'];
            $listaTitulosEjercicio=buscarTitulosSengunEjercicio($id_ejercicio);
            $response->getBody()->write(json_encode($listaTitulosEjercicio));
            return $response->withHeader('Content-type','application/json');
        }

        function retornarTeoria($request,$response,$args){
            $datosUsuario=$request->getBody();
            var_dump($datosUsuario);
            $titulo=$args['titulo'];

            $teoria=buscarTeoria($titulo,$datosUsuario);
            $response->getBody()->write(json_encode($teoria));
            return $response->withHeader('Content-type','application/json');
        }







        /*
        function retornarBuscarTeorias($request,$response,$args){
            $teorias=buscarTeorias();
            $response->getBody()->write(json_encode($teorias));
            return $response->withHeader('Content-type','application/json');
        }
        */
    //


//TEORIA SISTEMA

    function retornarResultadoCrearTeoria($request,$response,$args){
        $json = $request->getBody();
        $data = json_decode($json,true);
        $resultado_crear_teoria=crearTeoriaSistema($data);
        $response->getBody()->write(json_encode( $resultado_crear_teoria));
        return $response->withHeader('Content-type','application/json');
    }

    function retornarContenidoTeoriaSistema($request,$response,$args){
        $teoria_sistema=buscarContenidoTeoriaSistema($args['titulo']);
        $response->getBody()->write(json_encode($teoria_sistema));
        return $response->withHeader('Content-type','application/json');
    }
//

//TEORIA CURSO

    function retornarResultadoCrearTeoriaCurso($request,$response,$args){
        $json = $request->getBody();
        $data = json_decode($json,true);
        $resultado_crear_teoria=crearTeoriaCurso($data);
        $response->getBody()->write(json_encode( $resultado_crear_teoria));
        return $response->withHeader('Content-type','application/json');
    }



    

    /*
    function retornarResultadoCrearTeoria($request,$response,$args){
        $json = $request->getBody();
        $data = json_decode($json,true);
        $resultado_crear_teoria=crearTeoriaSistema($data);
        $response->getBody()->write(json_encode( $resultado_crear_teoria));
        return $response->withHeader('Content-type','application/json');
    }
    */

    /*EN TEORIA TODA EDICION SE VUELVE CUSTOM
    function retornarEstadoActualizacionContenido($request,$response,$args){
        $estadoActualizacionContenido=actualizarContenidoTeoriaSistema($args['titulo'], $args['contenidonuevo']);
        $response->getBody()->write(json_encode($estadoActualizacionContenido));
        return $response->withHeader('Content-type','application/json');
    }
    */
    
    /*EN TEORIA BORRAR TEORIA SISTEMA NO TIENE SENTIDO
    function retornarEstadoBorrarTeoria($request,$response,$args){
        $estadoBorrarContenido=borrarTeoria($args['titulo']);
        $response->getBody()->write(json_encode($estadoBorrarContenido));
        return $response->withHeader('Content-type','application/json');
    }
    */

//

?>