<?php
  class Encuesta extends CI_Controller {


    public function __construct(){
      parent::__construct();
      $this->load->model('EncuestasM_Model');
      $this->load->model('Categorias_Model');
      $this->load->model('PreguntasM_Model');
      $this->load->model('RespuestasM_Model');
      $this->load->model('Newsletter_Model');
      header('Cache-Control: no cache');
    }

    public function index($page = 'index'){
      if(!file_exists('application/views/encuesta/'.$page.'.php')){
        show_404();
      }
      $data['title']=ucfirst($page);

      $this->load->view('templates/header', $data);
      $this->load->view('encuesta/'.$page, $data);
      $this->load->view('templates/footer', $data);

    }

    public function view($page = 'encuesta'){
      if(!file_exists('application/views/encuesta/'.$page.'.php')){
        show_404();
      }
      $data['title']=ucfirst($page);

      $this->load->view('templates/header', $data);
      $this->load->view('encuesta/'.$page, $data);
      $this->load->view('templates/footer', $data);
    }

    public function existe(){
      $this->load->helper('url');
      $this->session->set_userdata('codigo', '');
      $codigo = $this->input->post('codigo');
      $this->session->set_userdata('codigo', $codigo);

      $data = $this->checkStatus($codigo);

      $this->load->view('templates/header', $data);
      /*Cargar la vista correspondiente al status de la encuesta*/
      switch ($data['status']) {
        case 0:
            //No existe la encuesta con ese código
            echo 'La encuesta no existe';
            break;
        case 1:
            //Encuesta sin iniciar
        case 2:
            //Encuesta sin terminar
            $this->load->view('about', $data);
            break;
        case 3:
            //Encuesta ya contestada
            header('Location: /encuesta-intermed-mx/encuesta');
            die();
        default:
            break;
      }
      /**/
      $this->load->view('templates/footer', $data);
    }

    public function newsletter(){
      $this->load->helper('url');
      $nombre = $this->input->post('nombre');
      $email = $this->input->post('email');

      if (!$nombre){
        header('Location: /encuesta-intermed-mx');
        die();
      }

      $this->Newsletter_Model->create_newsletter($nombre,$email);

      echo '<script type="text/javascript">
      history.pushState(null, null, location.href);
      window.onpopstate = function(event) {
          history.go(1);
      };</script>';
      echo 'Hola ' . $nombre. ', ya estas registrado en el newsletter<br/>';
      echo '<a href="/encuesta-intermed-mx">Ir a inicio</a>';

    }

    public function encuesta(){
      $this->load->helper('url');
      $codigo = $this->session->userdata('codigo');

      if(!$codigo){
        //redireccionar
        header('Location: /encuesta-intermed-mx');
        die();
      }

      $etapaResp = $this->input->post('etapaResp');
      $continuarEnc = $this->input->post('continuar');
      $guardado = false;
      $irEtapa = $this->input->post('irEtapa');
      $finalizar = false;

      $data = $this->checkStatus($codigo);

      $actualizar = '';
      if ($data['status'] == 1 || $data['status'] == 2){
        //Volver a checar el status de la encuesta
        $data = $this->checkStatus($codigo);
        /*Revisar si hay preguntas por guardar*/
        //post: respuesta_# complemento_# en la encuesta con el codigo $codigo
        //
        $POSTS = $this->input->post();
        //echo 'POSTS: <pre>' . print_r($POSTS,1) . '</pre>';
        $last = '';
        foreach ($POSTS as $post => $value) {
          if (substr($post,0,9) == "respuesta"){
            $id = explode('_', $post)[1];
            if ($last != $id){
              if (array_key_exists("respuesta_" . $id . '_1',$POSTS)){
                $value = '';
                $last = $id;
                $next = true;
                $total = 1;
                $value =  $POSTS["respuesta_" . $id . '_' . $total];
                $total++;
                while ($next == true){
                  if (array_key_exists("respuesta_" . $id . '_' . $total,$POSTS)){
                    $value .= '|' . $POSTS["respuesta_" . $id . '_' . $total];
                    $total++;
                  } else {
                    $next = false;
                  }
                }
                if (array_key_exists("complemento_" . $id,$POSTS) && !empty($POSTS['complemento_' . $id])){
                  $value .= '|comp:' . $POSTS['complemento_' . $id];
                }
              } else {
                if (array_key_exists("complemento_" . $id,$POSTS) && !empty($POSTS['complemento_' . $id])){
                  $value .= '|comp:' . $POSTS['complemento_' . $id];
                  //echo "Es una respuesta! de la pregunta: ". $id ." y tiene complemento<br/>";
                } else {
                  //echo "Es una respuesta! de la pregunta: ". $id ."<br/>";
                }
              }
              //echo $id . ': ' . $value .'<br/>';
              $update = array(
                 'pregunta_' . $id => $value,
              );
              $resultado = $this->RespuestasM_Model->update_respuestamByEncuesta($data['encuesta_id'], $update);
              //echo 'resultado: ' . $resultado;
              if ($resultado > 0){
                if ($actualizar === '') $actualizar = true;
              } else $actualizar = false;
            }
          }
        }
        if ($actualizar === true){
          $update = array(
            'etapa_' . $etapaResp => 1
          );
          $this->EncuestasM_Model->update_encuestam($data['encuesta_id'], $update);
          $data = $this->checkStatus($codigo);
          $guardado = true;
          if ($data['terminado'] == 4){
            $finalizar = true;
          }
        }
      }
      $data['finalizar'] = $finalizar;

      $data['codigo'] = $codigo;
      $data['title'] = "Encuesta";

      if ($continuarEnc === "0"){
        header('Location: /encuesta-intermed-mx');
        die();
      }

      $contenido = '';
      if (($data['status'] == 1 || $data['status'] == 2) && !$finalizar){
        //Mostrar la encuesta
        $this->load->view('templates/header', $data);

        if (!$irEtapa){
          if ($etapaResp && $etapaResp < 4) $etapa = ++$etapaResp;
          else {
            $etapa = '1';
            if (isset($data['paso'])){
              $etapa = $data['paso'];
            }
          }
        } else {
          $etapa = $irEtapa;
        }
        $data['etapa'] = $etapa;

        $contenido .= '<input type="hidden" name="etapaResp" id="etapaResp" value="'. $etapa .'">';

        $resultado = $this->Categorias_Model->get_categoriasByEtapa($etapa);

        foreach ($resultado as $categoria) {
          if ($categoria){
            $contenido .= '<div class="block-container-category"><span class="glyphicon glyphicon-asterisk"></span><span class="category">' . $categoria['categoria'] . '</span></div>';
            $contenido .= '<table class="table table-striped block-container-table">';
            $preguntas = $this->PreguntasM_Model->get_preguntamByCategoria($categoria['id']);

            foreach ($preguntas as $pregunta) {
                $respuesta = $this->RespuestasM_Model->get_respuestaByEncuestaPregunta($data['encuesta_id'], $pregunta['id']);
                $respuesta = explode('|',$respuesta['pregunta_' . $pregunta['id']]);
                $respuestas = array();
                $complemento = '';
                foreach ($respuesta as $resp) {
                  if (substr($resp, 0, 5) === "comp:"){
                      $complemento = substr($resp, 5);
                  } else {
                    $respuestas[] = $resp;
                  }
                }
                //echo 'respuesta: <pre>' . print_r($respuestas, 1) . '</pre>';
                //echo 'Complemento: <pre>' . print_r($complemento, 1) . '</pre>';


                $contenido .= '<tr><td>';
                $contenido .= '<div class="block-container-table-pregunta">' . $pregunta['pregunta'] . '</div>';
                $contenido .= '<div class="block-container-table-respuesta">';
                $opciones = explode('|', $pregunta['opciones']);
                switch ($pregunta['tipo']) {
                  case 'text':
                      $contenido .= '<input type="text" name="respuesta_' . $pregunta['id'] . '" value="' . $respuestas[0] .'" required class="form-control" >&nbsp;&nbsp;<br/>';
                      break;
                  case 'radio':
                      $total = 0;
                      foreach ($opciones as $opcion) {
                        $checked = '';
                        if ($opcion === $respuestas[0]){
                            $checked = 'checked';
                        }
                        $contenido .= '<input type="radio" name="respuesta_' . $pregunta['id'] . '" value="' . $opcion . '" required  onchange="LimpiarComplementos('. $pregunta['id'] .','. ++$total .')" '. $checked .' > '. $opcion . '&nbsp;&nbsp;';
                        if (substr($opcion, -1) == ":"){
                          $valorComp = '';
                          $disabled = 'disabled';
                          if ($checked){
                            $valorComp = $complemento;
                            $disabled = '';
                          }
                          $contenido .= '<input type="text" ' . $disabled . ' onkeyup="validarFormulario()" onpaste="validarFormulario()" name="complemento_' . $pregunta['id'] . '" id="complemento_' . $pregunta['id'] . '_' . $total . '" value="' . $valorComp . '"  >';
                        }
                        $contenido .= '&nbsp;&nbsp;';
                      }
                      break;
                  case 'text|enum':
                      $contenido .= '<ul class="sortable">';
                      if (count($respuestas) > 1){
                        asort($respuestas);
                        foreach ($respuestas as $index => $value) {
                          if (array_key_exists($index, $opciones)){
                            $contenido .= '<li class="ui-state-default"><input type="hidden" name="respuesta_' . $pregunta['id'] . '_' . ($index+1) . '" value="' . $value . '" class="form-control" > '. $opciones[$index] . '</li>';
                          }
                        }
                      } else {
                        $total = 0;
                        $cantidad = count($opciones);
                        foreach ($opciones as $opcion) {
                          $valorNum = '';
                          $contenido .= '<li class="ui-state-default"><input type="hidden" name="respuesta_' . $pregunta['id'] . '_' . ++$total . '" value="' . $total . '" class="form-control" > '. $opcion . '</li>';
                        }
                      }
                      break;
                  default:
                      break;
                }
                $contenido .= '</div>';
                $contenido .= '</td></tr>';
            }
            $contenido .= '</table>';
          }
          $data['contenido'] = $contenido;
        }

        $this->load->view('encuesta/encuesta', $data);
        $this->load->view('templates/footer', $data);
      } else if ($data['status'] != 0) {
        $this->load->view('templates/header', $data);
        $contenido .= 'Gracias por contestar la encuesta<br/>';
        $contenido .= '<input type="checkbox" id="promo" value="si" onchange="aceptarPromocion()">';
        $contenido .= 'Quiero recibir correos y promociones<br/>';
        $contenido .= '<div id="contenido"><a href="/encuesta-intermed-mx">Ir a inicio</a></div>';
        $data['contenido'] = $contenido;

        $this->load->view('encuesta/encuesta', $data);
        $this->load->view('templates/footer', $data);
        //Redirect /about o index
      }

    }

    public function checkStatus($codigo){
      /*
      --STATUS--
      0-La encuesta no existe
      1-La encuesta existe y esta sin usar
      2-La encuesta existe y esta sin terminar
      3-La encuesta existe y ya la terminaron de contenstar
      */
      $data = array();
      $resultado = $this->EncuestasM_Model->get_encuestamByCodigo($codigo);

      $status = -1;
      if ($resultado){
        $data['encuesta_id'] = $resultado['id'];
        $paso = 0;
        $terminado = 0;
        if ($resultado['etapa_1'] == 1) {
          $paso = 1;
          $terminado++;
        }
        if ($resultado['etapa_2'] == 1){
          if ($paso == 1) $paso = 2;
          $terminado++;
        }
        if ($resultado['etapa_3'] == 1) {
          if ($paso == 2) $paso = 3;
          $terminado++;
        }
        if ($resultado['etapa_4'] == 1){
          if ($paso == 3) $paso = 4;
          $terminado++;
        }
        $data['terminado'] = $terminado;
        if ($paso == 4) $status = 3;
        else if ($paso > 0) {
          $status = 2;
          $data['paso'] = ++$paso;
        } else $status = 1;
      } else {
        $status = 0;
      }

      $data['codigo'] = $codigo;
      $data['status'] = $status;
      return $data;
    }
  }
?>
