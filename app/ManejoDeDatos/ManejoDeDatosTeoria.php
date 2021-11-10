<?php

//SE MANEJAN LOS DATOS TANTO DE LA TEORIA DEL SISTEMA COMO DE LA TEORIA DEL CURSO.


   //MANEJO DE BUSQUEDA GENERAL DE LISTA DE TEORIA PARA EL EDITOR
    function retornarListaTeoriasEditor($request,$response,$args){
        $id_usuario=$request->getBody();
        $listaGeneral=buscarListaGeneralDeTitulos($id_usuario);
        $response->getBody()->write(json_encode($listaGeneral));
        return $response->withHeader('Content-type','application/json');
    }

    function retornarTeoriaAEditar($request,$response,$args){
        $datos=$request->getBody();
        $datos=json_decode($datos,true);

        $id_usuario=$datos['id_usuario'];
        $titulo=$datos['titulo'];

        $teoria=buscarTeoriaAEditar($titulo,$id_usuario);

        if($teoria=="error"){
            return $response->withStatus(404);
        }

        $response->getBody()->write(json_encode($teoria));
        return $response->withHeader('Content-type','application/json');
    }

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

    function retornarEstadoActualizacionContenido($request,$response,$args){
        $datosTeoriaEditar=$request->getBody();
        $datosTeoriaEditar=json_decode($datosTeoriaEditar,true);

        $estadoActualizacionContenido=actualizarContenidoTeoriaCursos($datosTeoriaEditar);
        $response->getBody()->write(json_encode($estadoActualizacionContenido));
        return $response->withHeader('Content-type','application/json');
    }
    

//APOYO TEORICO
function retornarListaOpcionesBarraApoyo($request,$response,$args){
    $datosUsuario=$request->getBody();
    $datosUsuario=json_decode($datosUsuario);
    $id_usuario=$datosUsuario->id_usuario;
    $id_ejercicio=$datosUsuario->id_ejercicio;

    $listaContenidoBarraApoyo=buscarListaOpcionesBarraApoyo($id_usuario,$id_ejercicio);


    $response->getBody()->write(json_encode($listaContenidoBarraApoyo));
    return $response->withHeader('Content-type','application/json');
}

function retornarListaTeoriaVisualizar($request,$response,$args){
    $datosUsuario=$request->getBody();
    $datosUsuario=json_decode($datosUsuario);

    $id_usuario=$datosUsuario->id_usuario;
    $titulo=$datosUsuario->titulo;

    //$id_ejercicio=$datosUsuario->id_ejercicio;

    $contenidosAVisualizar=buscarContenidosTeoricosAvizualizar($id_usuario,$titulo);

    $response->getBody()->write(json_encode($contenidosAVisualizar));
    return $response->withHeader('Content-type','application/json');
}

function retornarNormativaRelacionada($request,$response,$args){
    $ejercicio=$request->getBody();
    $ejercicio=json_decode($ejercicio);
    $id_ejercicio=$ejercicio->id_usuario;


    $normativaRelacionada=buscarNormativaRelacionada($id_ejercicio);

    $response->getBody()->write(json_encode($normativaRelacionada));
    return $response->withHeader('Content-type','application/json');
}




?>