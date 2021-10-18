<?php

    function retornarListaDeTitulos($request,$response,$args){
        $teoria_sistema=buscarListaDeTitulos($args['id_ejercicio']);
        $response->getBody()->write(json_encode($teoria_sistema));
        return $response->withHeader('Content-type','application/json');
    }

    function retornarBuscarTeorias($request,$response,$args){
        $teorias=buscarTeorias();
        $response->getBody()->write(json_encode($teorias));
        return $response->withHeader('Content-type','application/json');
    }

    function retornarContenidoTeoriaSistema($request,$response,$args){
        $teoria_sistema=buscarContenidoTeoriaSistema($args['titulo']);
        $response->getBody()->write(json_encode($teoria_sistema));
        return $response->withHeader('Content-type','application/json');
    }
    
    function retornarResultadoCrearTeoria($request,$response,$args){
        $json = $request->getBody();
        $data = json_decode($json,true);
       
        $resultado_crear_teoria=crearTeoriaSistema($data);
        $response->getBody()->write(json_encode( $resultado_crear_teoria));
        return $response->withHeader('Content-type','application/json');
    }

    function retornarEstadoActualizacionContenido($request,$response,$args){
        $estadoActualizacionContenido=actualizarContenidoTeoriaSistema($args['titulo'], $args['contenidonuevo']);
        $response->getBody()->write(json_encode($estadoActualizacionContenido));
        return $response->withHeader('Content-type','application/json');
    }

    function retornarEstadoBorrarTeoria($request,$response,$args){
        $estadoBorrarContenido=borrarTeoria($args['titulo']);
        $response->getBody()->write(json_encode($estadoBorrarContenido));
        return $response->withHeader('Content-type','application/json');
    }

?>