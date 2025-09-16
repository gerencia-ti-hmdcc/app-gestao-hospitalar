<?php
    $this->session = \Config\Services::session();
    function iniciais($str){
        if(mb_strpos(strtoupper(substr($str,0,4)),'SIC ')!==false){
            $str = substr($str,3);
        }
        $pos = 0;
        $saida = '';
        while(($pos = strpos($str, ' ', $pos)) !== false ){
            if(isset($str[$pos +1]) && $str[$pos +1] != ' '){
                $saida .= substr($str, $pos +1, 1);
            }   
            $pos++;
        }
        return $str[0]. $saida;
    }

    $variavel_controle_margem_tv = 4;
    $usuario_logado = $this->session->get("usuario_logado");
    if($usuario_logado["TIPO_PERFIL"]=='P'){ 
        $variavel_controle_margem_tv = 2;
        echo '<meta http-equiv="refresh" content="300" />';
    } 
?>

<div class="row">
    
    <?php 
        if($_SESSION["usuario_logado"]["TIPO_PERFIL"]!='P'){
            echo "<div class='col-lg-12 mb-".$variavel_controle_margem_tv."'>
                    <a class='btn btn-primary' href='../detalhada/setores?l=".$_GET['l']."'>Voltar</a>
                    <div class='card z-index-2'>
                        <div class='card-header pb-0'>
                            <div class='row justify-center lead text-dark active breadcrumb-item font-weight-bolder'>
                                Leitos
                            </div>
                            <div class='text-sm flex row justify-center'>
                                ".$setor_atend["DS_SETOR_ATENDIMENTO"]."
                            </div>
                        </div>
                        <div class='card-body pt-2 text-end text-xs'>
                            Última Atualização: ".date('d/m/Y H:i:s', strtotime($ultima_atualizacao["ultima_atualizacao"]))."
                        </div>
                    </div>
                </div>";
        }else{
            echo "<div class='text-xs'>Última Atualização: ".date('d/m/Y H:i:s', strtotime($ultima_atualizacao["ultima_atualizacao"]))."</div>";
        }
    ?>
    
        
    <?php
        echo '<div class="flex flex-wrap">';
        for($i = 0; $i < count($leitos); $i++) {
            
            if($_SESSION["usuario_logado"]["TIPO_PERFIL"]=='P' && $mostrar_menus==0){
                echo '<div class="card-wrapper responsividade_leitos_painel p-2">';
                $cor_card_avaliacao_verde_vermelho  = "<div class='mt-2' style='height:15px;'></div>";
                $tipo_de_body_card                  = "card-body-painel-leitos";
                $tamanho_linha_card_painel          = 'style="height:33px"';
                $tamanho_icones_dados_clinicos      = "100%";
            }else{
                echo '<div class="card-wrapper responsividade_leitos p-2">';
                $cor_card_avaliacao_verde_vermelho  = "<div class='mt-4' style='height:15px;'></div>";
                $tipo_de_body_card                  = "card-body";
                $tamanho_linha_card_painel          = "";
                $tamanho_icones_dados_clinicos      = "95%";
            }

            $icone_fugulin          = "";
            $icone_news             = "";
            $icones_sinais_vitais   = "";

            if($leitos[$i]["cd_agrupamento"]!=4){
                //FUGULIN, 'VERDE E VERMELHO' E NEWS NAO DEVEM APARECER NO CTI
                if(isset($leitos[$i]["nr_seq_gradacao"])){
                    //SE HOUVE AVALIAÇÃO FUGULIN
                    if($leitos[$i]["nr_seq_gradacao"]!=0){
                        $funcao_isolada = 'onclick="detalhesIsolados('.$leitos[$i]["nr_atendimento"].',\''.$leitos[$i]["ds_leito_atual"].'\',\'fugulin\')"';
                        if($leitos[$i]["nr_seq_gradacao"]==2){
                            $icone_fugulin = '<div '.$funcao_isolada.' class="col-1emeio"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="Fugulin: Mínimo" width="'.$tamanho_icones_dados_clinicos.'" src ="'.base_url("public/assets/img/icons/fugulin_minimo.png").'"/></div>';
                        }else if($leitos[$i]["nr_seq_gradacao"]==3){
                            $icone_fugulin = '<div '.$funcao_isolada.' class="col-1emeio"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="Fugulin: Intermediário" width="'.$tamanho_icones_dados_clinicos.'" src ="'.base_url("public/assets/img/icons/fugulin_intermediario.png").'"/></div>';
                        }else if($leitos[$i]["nr_seq_gradacao"]==4){
                            $icone_fugulin = '<div '.$funcao_isolada.' class="col-1emeio"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="Fugulin: Alta Dependência" width="'.$tamanho_icones_dados_clinicos.'" src ="'.base_url("public/assets/img/icons/fugulin_alta_dependencia.png").'"/></div>';
                        }else if($leitos[$i]["nr_seq_gradacao"]==5){
                            $icone_fugulin = '<div '.$funcao_isolada.' class="col-1emeio"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="Fugulin: Semi-Intensivo" width="'.$tamanho_icones_dados_clinicos.'" src ="'.base_url("public/assets/img/icons/fugulin_semi_intensivo.png").'"/></div>';
                        }else if($leitos[$i]["nr_seq_gradacao"]==6){
                            $icone_fugulin = '<div '.$funcao_isolada.' class="col-1emeio"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="Fugulin: Intensivo" width="'.$tamanho_icones_dados_clinicos.'" src ="'.base_url("public/assets/img/icons/fugulin_intensivo.png").'"/></div>';
                        }
                    }
                }
    
                if(isset($leitos[$i]["score"])){
                    if(strlen($leitos[$i]["score"])>0){
                        //SE HOUVE AVALIAÇÃO NEWS
                        $funcao_isolada = 'onclick="detalhesIsolados('.$leitos[$i]["nr_atendimento"].',\''.$leitos[$i]["ds_leito_atual"].'\',\'news\')"';
                        if($leitos[$i]["score"]>=0 && $leitos[$i]["score"]<=3){
                            $icone_news = '<div '.$funcao_isolada.' class="col-1emeio"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="News: nota <= 3" width="'.$tamanho_icones_dados_clinicos.'" src ="'.base_url("public/assets/img/icons/news_verde.png").'"/></div>';
                        }else if($leitos[$i]["score"]>=4 && $leitos[$i]["score"]<=6){
                            $icone_news = '<div '.$funcao_isolada.' class="col-1emeio"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="News: nota >= 4 e <= 6" width="'.$tamanho_icones_dados_clinicos.'" src ="'.base_url("public/assets/img/icons/news_laranja.png").'"/></div>';
                        }else if($leitos[$i]["score"]>=7){
                            $icone_news = '<div '.$funcao_isolada.' class="col-1emeio"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="News: nota >= 7" width="'.$tamanho_icones_dados_clinicos.'" src ="'.base_url("public/assets/img/icons/news_vermelho.png").'"/></div>';
                        }
                    }
                }

                if($leitos[$i]["ds_verde_ou_vermelho"]){
                    //SE HOUVE AVALIAÇÃO VERDE_VERMELHO
                    if(trim(strtoupper($leitos[$i]["ds_verde_ou_vermelho"]))=='VERDE'){
                        $cor_avaliacao_verde_vermelho="#00ff00";
                    }else{
                        $cor_avaliacao_verde_vermelho="#ff0000";
                    }
                    if($_SESSION["usuario_logado"]["TIPO_PERFIL"]=='P' && $mostrar_menus==0){
                        $cor_card_avaliacao_verde_vermelho = '<div class="mt-2" style="height:7px; background-color:'.$cor_avaliacao_verde_vermelho.'; border-radius:2px"></div>';
                    }else{
                        $cor_card_avaliacao_verde_vermelho = '<div class="mt-4" style="height:15px; background-color:'.$cor_avaliacao_verde_vermelho.'; border-radius:2px"></div>';
                    }
                }
            }

            $icone_braden = "";
            if(isset($leitos[$i]["braden"])){
                //SE HÁ DADO CLÍNICO DE BRADEN

                if(count($leitos[$i]["braden"])>0){
                    $funcao_isolada = 'onclick="detalhesIsolados('.$leitos[$i]["nr_atendimento"].',\''.$leitos[$i]["ds_leito_atual"].'\',\'braden\',\''.$leitos[$i]["braden"]["DT_LIBERACAO_BRADEN"].'\',\''.$leitos[$i]["braden"]["CLASSIFICACAO_BRADEN"].'\',\''.$leitos[$i]["braden"]["PROFISSIONAL_BRADEN"].'\','.$leitos[$i]["braden"]["PONTOS_BRADEN"].')"';

                    if($leitos[$i]["braden"]["PONTOS_BRADEN"]<10){
                        $icone_braden_nome = 'public/assets/img/icons/braden5.png';
                    }else if($leitos[$i]["braden"]["PONTOS_BRADEN"]>=10 && $leitos[$i]["braden"]["PONTOS_BRADEN"]<=12){
                        $icone_braden_nome = 'public/assets/img/icons/braden4.png';
                    }else if($leitos[$i]["braden"]["PONTOS_BRADEN"]>12 && $leitos[$i]["braden"]["PONTOS_BRADEN"]<=14){
                        $icone_braden_nome = 'public/assets/img/icons/braden3.png';
                    }else if($leitos[$i]["braden"]["PONTOS_BRADEN"]>=15 && $leitos[$i]["braden"]["PONTOS_BRADEN"]<=18){
                        $icone_braden_nome = 'public/assets/img/icons/braden2.png';
                    }else if($leitos[$i]["braden"]["PONTOS_BRADEN"]>18){
                        $icone_braden_nome = 'public/assets/img/icons/braden1.png';
                    }
                    $icone_braden = '<div '.$funcao_isolada.' class="col-1emeio"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="Braden: '.mb_convert_case($leitos[$i]["braden"]["CLASSIFICACAO_BRADEN"], MB_CASE_TITLE, "UTF-8").'" width="'.$tamanho_icones_dados_clinicos.'" src ="'.base_url("$icone_braden_nome").'"/></div>';
                }
            }

            $icone_morse = "";
            if(isset($leitos[$i]["morse"])){
                //SE HÁ DADO CLÍNICO DE MORSE
                if(count($leitos[$i]["morse"])>0){
                    $funcao_isolada = 'onclick="detalhesIsolados('.$leitos[$i]["nr_atendimento"].',\''.$leitos[$i]["ds_leito_atual"].'\',\'morse\',\''.$leitos[$i]["morse"]["DT_LIBERACAO_MORSE"].'\',\''.$leitos[$i]["morse"]["CLASSIFICACAO_MORSE"].'\',\''.$leitos[$i]["morse"]["PROFISSIONAL_MORSE"].'\','.$leitos[$i]["morse"]["PONTOS_MORSE"].')"';
                    if($leitos[$i]["morse"]["PONTOS_MORSE"]<24){
                        $icone_morse_nome   = 'public/assets/img/icons/morse1.png';
                    }else if($leitos[$i]["morse"]["PONTOS_MORSE"]>=25 && $leitos[$i]["morse"]["PONTOS_MORSE"]<=44){
                        $icone_morse_nome   = 'public/assets/img/icons/morse4.png';
                    }else if($leitos[$i]["morse"]["PONTOS_MORSE"]>=45){
                        $icone_morse_nome   = 'public/assets/img/icons/morse6.png';
                    }
                    $icone_morse = '<div '.$funcao_isolada.' class="col-1emeio"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="Morse: '.mb_convert_case($leitos[$i]["morse"]["CLASSIFICACAO_MORSE"], MB_CASE_TITLE, "UTF-8").'" width="'.$tamanho_icones_dados_clinicos.'" src ="'.base_url("$icone_morse_nome").'"/></div>';
                }
            }
            
            if(isset($leitos[$i]["qt_freq_cardiac"])){
                //SE HOUVE MEDIÇÃO CARDIACA
                $funcao_isolada = 'onclick="detalhesIsolados('.$leitos[$i]["nr_atendimento"].',\''.$leitos[$i]["ds_leito_atual"].'\',\'cardiaca\')"';
                if($leitos[$i]["qt_freq_cardiac"]>90){
                    $icones_sinais_vitais .= '<div '.$funcao_isolada.' class="col-1emeio"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="Frequência Cardíaca > 90 bpm" width="'.$tamanho_icones_dados_clinicos.'" src ="'.base_url("public/assets/img/icons/coracao.png").'"/></div>';
                }
            }

            if(isset($leitos[$i]["qt_freq_resp"])){
                //SE HOUVE MEDIÇÃO RESPIRATORIA
                $funcao_isolada = 'onclick="detalhesIsolados('.$leitos[$i]["nr_atendimento"].',\''.$leitos[$i]["ds_leito_atual"].'\',\'respiratoria\')"';
                if($leitos[$i]["qt_freq_resp"]>20){
                    $icones_sinais_vitais .= '<div '.$funcao_isolada.' class="col-1emeio"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="Frequência Respiratória > 20 irpm" width="'.$tamanho_icones_dados_clinicos.'" src ="'.base_url("public/assets/img/icons/pulmao.png").'"/></div>';
                }
            }

            if(isset($leitos[$i]["qt_temp"])){
                //SE HOUVE MEDIÇÃO TEMPERATURA
                $funcao_isolada = 'onclick="detalhesIsolados('.$leitos[$i]["nr_atendimento"].',\''.$leitos[$i]["ds_leito_atual"].'\',\'temperatura\')"';
                if($leitos[$i]["qt_temp"]>0){
                    if($leitos[$i]["qt_temp"]<=35){
                        $icones_sinais_vitais .= '<div '.$funcao_isolada.' class="col-1emeio"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="Temperatura <= 35º" width="'.$tamanho_icones_dados_clinicos.'" src ="'.base_url("public/assets/img/icons/temperatura_azul.png").'"/></div>';
                    }else if($leitos[$i]["qt_temp"]>=37.8){
                        $icones_sinais_vitais .= '<div '.$funcao_isolada.' class="col-1emeio"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="Temperatura >= 37.8º" width="'.$tamanho_icones_dados_clinicos.'" src ="'.base_url("public/assets/img/icons/temperatura_vermelho.png").'"/></div>';
                    }
                }
            }

            // $icone_ia   = "";
            // if($leitos[$i]["nr_atendimento"] != 0){
            //     $funcao_isolada = 'onclick="iaAssistente('.$leitos[$i]["nr_atendimento"].')"';
            //     $icone_ia .= '<div '.$funcao_isolada.' class="col-2"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="Assistente IA" width="100%" src ="'.base_url($diretorio_raiz).'\assets\img\icons\ia.svg"/></div>';
            // } 

            if($_SESSION["usuario_logado"]["TIPO_PERFIL"]=='P'){
                $tamanho_icone_precaucao_painel = "style='max-width: 21px !important; margin-top:2px'";
                $tamanho_2 = "21px";
            }else{
                $tamanho_icone_precaucao_painel = "style='max-width: 34px !important;'";
                $tamanho_2 = "34px";
            }

            $nomes_precaucoes = "";

            if(isset($leitos[$i]["ds_precaucao_isolamento_2"]) && strlen($leitos[$i]["ds_precaucao_isolamento_2"])>0){
                if(isset($leitos[$i]["ds_precaucao_isolamento"])){
                    $funcao_isolada     = 'onclick="detalhesIsolados('.$leitos[$i]["nr_atendimento"].',\''.$leitos[$i]["ds_leito_atual"].'\',\'precaucoes_isolamento\')"';
                    $icones_precaucoes  = '<div '.$funcao_isolada.' class="d-flex">';
                    if($leitos[$i]["ds_precaucao_isolamento"]=="Precaução de contato"){
                        $icones_precaucoes .= '<img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip icone-precaucao" '.$tamanho_icone_precaucao_painel.' title="Precaução de contato " width="100%" height="100%" src ="'.base_url("public/assets/img/icons/contato.png").'"/>';
                        $nomes_precaucoes .= "Contato"."<br />";
                    }else if($leitos[$i]["ds_precaucao_isolamento"]=="Precaução de contato e aerossol"){
                        $icones_precaucoes .= '<img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip icone-precaucao" '.$tamanho_icone_precaucao_painel.' title="Precaução de contato e aerossol " width="100%" height="100%" src ="'.base_url("public/assets/img/icons/contato_aerossol.png").'"/>';
                        $nomes_precaucoes .= "Contato e aerossol"."<br />";
                    }else if($leitos[$i]["ds_precaucao_isolamento"]=="Precaução para gotículas"){
                        $icones_precaucoes .= '<img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip icone-precaucao" '.$tamanho_icone_precaucao_painel.' title="Precaução para gotículas " width="100%" height="100%" src ="'.base_url("public/assets/img/icons/goticulas.png").'"/>';
                        $nomes_precaucoes .= "Gotículas"."<br />";
                    }else if($leitos[$i]["ds_precaucao_isolamento"]=="Precaução de contato e gotículas"){
                        $icones_precaucoes .= '<img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip icone-precaucao" '.$tamanho_icone_precaucao_painel.' title="Precaução de contato e gotículas " width="100%" height="100%" src ="'.base_url("public/assets/img/icons/contato_goticulas").'"/>';
                        $nomes_precaucoes .= "Contato e gotículas"."<br />";
                    }else if($leitos[$i]["ds_precaucao_isolamento"]=="Precaução para aerossóis"){
                        $icones_precaucoes .= '<img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip icone-precaucao" '.$tamanho_icone_precaucao_painel.' title="Precaução para aerossóis " width="100%" height="100%" src ="'.base_url("public/assets/img/icons/aerossol.png").'"/>';
                        $nomes_precaucoes .= "Aerossóis"."<br />";
                    }
                    $icones_precaucoes .="</div>";
                }else{
                    $icones_precaucoes = "";
                }
                // $nomes_precaucoes = $leitos[$i]["ds_precaucao_isolamento"]."<br />";

                if(isset($leitos[$i]["ds_precaucao_isolamento_2"])){
                    $funcao_isolada         = 'onclick="detalhesIsolados('.$leitos[$i]["nr_atendimento"].',\''.$leitos[$i]["ds_leito_atual"].'\',\'precaucoes_isolamento_2\')"';
                    $icones_precaucoes_2    = '<div '.$funcao_isolada.' class="d-flex">';
                    if($leitos[$i]["ds_precaucao_isolamento"] !=$leitos[$i]["ds_precaucao_isolamento_2"]){
                        if($leitos[$i]["ds_precaucao_isolamento_2"]=="Precaução de contato"){
                            $icones_precaucoes_2 .= '<img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip icone-precaucao" '.$tamanho_icone_precaucao_painel.' title="Precaução de contato " src ="'.base_url("public/assets/img/icons/contato.png").'"/>';
                            $nomes_precaucoes .= "Contato";
                        }else if($leitos[$i]["ds_precaucao_isolamento_2"]=="Precaução de contato e aerossol"){
                            $icones_precaucoes_2 .= '<img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip icone-precaucao" '.$tamanho_icone_precaucao_painel.' title="Precaução de contato e aerossol " width="100%" height="100%" src ="'.base_url("public/assets/img/icons/contato_aerossol.png").'"/>';
                            $nomes_precaucoes .= "Contato e aerossol";
                        }else if($leitos[$i]["ds_precaucao_isolamento_2"]=="Precaução para gotículas"){
                            $icones_precaucoes_2 .= '<img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip icone-precaucao" '.$tamanho_icone_precaucao_painel.' title="Precaução para gotículas " width="100%" height="100%" src ="'.base_url("public/assets/img/icons/goticulas.png").'"/>';
                            $nomes_precaucoes .= "Gotículas";
                        }else if($leitos[$i]["ds_precaucao_isolamento_2"]=="Precaução de contato e gotículas"){
                            $icones_precaucoes_2 .= '<img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip icone-precaucao" '.$tamanho_icone_precaucao_painel.' title="Precaução de contato e gotículas " width="100%" height="100%" src ="'.base_url("public/assets/img/icons/contato_goticulas").'"/>';
                            $nomes_precaucoes .= "Contato e gotículas";
                        }else if($leitos[$i]["ds_precaucao_isolamento_2"]=="Precaução para aerossóis"){
                            $icones_precaucoes_2 .= '<img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip icone-precaucao" '.$tamanho_icone_precaucao_painel.' title="Precaução para aerossóis " width="100%" height="100%" src ="'.base_url("public/assets/img/icons/aerossol.png").'"/>';
                            $nomes_precaucoes .= "Aerossóis";
                        }
                        // $nomes_precaucoes .= $leitos[$i]["ds_precaucao_isolamento_2"];
                    }
                    $icones_precaucoes_2 .="</div>";
                }else{
                    $icones_precaucoes_2 = "";
                }
                $div_precaucoes_isolamento = '<div class="col-12 flex gap-1" style="min-height: '.$tamanho_2.'; place-content: end;white-space: nowrap;">'.$icones_precaucoes.''.$icones_precaucoes_2.'</div>';
            }else{
                if(isset($leitos[$i]["ds_precaucao_isolamento"])){
                    $funcao_isolada     = 'onclick="detalhesIsolados('.$leitos[$i]["nr_atendimento"].',\''.$leitos[$i]["ds_leito_atual"].'\',\'precaucoes_isolamento\')"';
                    $icones_precaucoes  = '<div '.$funcao_isolada.' class="d-flex">';
                    if($leitos[$i]["ds_precaucao_isolamento"]=="Precaução de contato"){
                        $icones_precaucoes .= '<img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip icone-precaucao" '.$tamanho_icone_precaucao_painel.' title="Precaução de contato " width="100%" height="100%" src ="'.base_url("public/assets/img/icons/contato.png").'"/>';
                        $nomes_precaucoes .= "Contato";
                    }else if($leitos[$i]["ds_precaucao_isolamento"]=="Precaução de contato e aerossol"){
                        $icones_precaucoes .= '<img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip icone-precaucao" '.$tamanho_icone_precaucao_painel.' title="Precaução de contato e aerossol " width="100%" height="100%" src ="'.base_url("public/assets/img/icons/contato_aerossol.png").'"/>';
                        $nomes_precaucoes .= "Contato e aerossol";
                    }else if($leitos[$i]["ds_precaucao_isolamento"]=="Precaução para gotículas"){
                        $icones_precaucoes .= '<img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip icone-precaucao" '.$tamanho_icone_precaucao_painel.' title="Precaução para gotículas " width="100%" height="100%" src ="'.base_url("public/assets/img/icons/goticulas.png").'"/>';
                        $nomes_precaucoes .= "Gotículas";
                    }else if($leitos[$i]["ds_precaucao_isolamento"]=="Precaução de contato e gotículas"){
                        $icones_precaucoes .= '<img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip icone-precaucao" '.$tamanho_icone_precaucao_painel.' title="Precaução de contato e gotículas " width="100%" height="100%" src ="'.base_url("public/assets/img/icons/contato_goticulas").'"/>';
                        $nomes_precaucoes .= "Contato e gotículas";
                    }else if($leitos[$i]["ds_precaucao_isolamento"]=="Precaução para aerossóis"){
                        $icones_precaucoes .= '<img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip icone-precaucao" '.$tamanho_icone_precaucao_painel.' title="Precaução para aerossóis " width="100%" height="100%" src ="'.base_url("public/assets/img/icons/aerossol.png").'"/>';
                        $nomes_precaucoes .= "Aerossóis";
                    }
                    $icones_precaucoes .="</div>";
                }else{
                    $icones_precaucoes = "";
                }
                // $nomes_precaucoes = $leitos[$i]["ds_precaucao_isolamento"];
                $div_precaucoes_isolamento = '<div class="gap-1 flex" style="min-height: '.$tamanho_2.';white-space: nowrap;">'.$icones_precaucoes.'</div>';
            }


            if($leitos[$i]["nr_atendimento"] != 0) {
                $classes_adicionais_card    =  "cursor-pointer text-dark";
                $leito                      = $leitos[$i]["ds_leito_atual"];
                $gambsClick                 = 'onclick="detalhesDoLeito('.$leitos[$i]["nr_atendimento"].',\''.$leito.'\')"';
                if($_SESSION["usuario_logado"]["TIPO_PERFIL"]=='P' && $mostrar_menus==0){
                    $dados_atd                  = "<div class='text-end text-xs'>".$leitos[$i]["ds_nome_paciente"]."</div>";
                }else{
                    $dados_atd                  = "<div class='text-end text-xs'>".$leitos[$i]["ds_nome_paciente"]."<br />".$leitos[$i]["nr_atendimento"]."</div>";
                }
                $info_paciente = iniciais($leitos[$i]["ds_nome_paciente"]);

                if($leitos[$i]["cd_agrupamento"]==4){
                    //SE CTI            
                    if($leitos[$i]["permanencia_linha_cuidado"]>=6){
                        $classes_adicionais_card .= " bg-gray-700 text-white";
                    }
                }else if($leitos[$i]["cd_agrupamento"]==99){
                    //SE CIRURGICA
                    if($leitos[$i]["permanencia_linha_cuidado"]>=8){
                        $classes_adicionais_card .= " bg-gray-700 text-white";
                    }
                }else if($leitos[$i]["cd_agrupamento"]==3){
                    //SE INTERNACAO
                    if($leitos[$i]["cd_setor_atendimento"]==145){
                        //SE AVC
                        if($leitos[$i]["permanencia_linha_cuidado"]>=6){
                            $classes_adicionais_card .= " bg-gray-700 text-white";
                        }
                    }
                    if(in_array($leitos[$i]["cd_setor_atendimento"],[33,76,34,55,36,56])){
                        //SE 5 AO 7 ANDAR
                        if($leitos[$i]["permanencia_linha_cuidado"]>=10){
                            $classes_adicionais_card .= " bg-gray-700 text-white";
                        }
                    }
                }
                if($_SESSION["usuario_logado"]["TIPO_PERFIL"]=='P' && $mostrar_menus==0){
                    $total_dias_internacao_atendimento = '<div class="text-xxs mt-1">*'.$leitos[$i]["tempo_internacao"].' dia(s) de internação</div>';
                }else{
                    $total_dias_internacao_atendimento = '<div class="text-xs mt-1">*'.$leitos[$i]["tempo_internacao"].' dia(s) de internação</div>';
                }
            } else {
                $classes_adicionais_card    = "bg-card-leito-livre text-white";
                $gambsClick                 = "";
                if(trim($leitos[$i]["ie_status_unidade"])!="Paciente"){
                    $dados_atd                      = "<div class='text-right text-xs'>".$leitos[$i]["ie_status_unidade"]."<br /></div>";
                }else{
                    $dados_atd                      = "<div class='text-right text-xs'><br /><br /></div>";
                }
                $total_dias_internacao_atendimento = "";
                $info_paciente = $leitos[$i]["ie_status_unidade"];
            }

            // <div class="col-12 flex">
            //     <div class="col-4 flex text-left">
            //         '.$div_precaucoes_isolamento.'
            //     </div>
            //     <div class="col-8 flex text-xxs">
            //         '.$nomes_precaucoes.'
            //     </div>
            // </div>
            //AQUI É FORMATADO O TIPO DE CARD
            if($_SESSION["usuario_logado"]["TIPO_PERFIL"]=='P' && $mostrar_menus==0){
                echo '<div class="h-full card-painel-leitos '.$classes_adicionais_card.'">
                        <div class="'.$tipo_de_body_card.'">
                            <div class="flex" '.$tamanho_linha_card_painel.'>
                                <div class="col-6">
                                    <p class="lead font-weight-bolder" '.$gambsClick.'>' . $leitos[$i]["ds_leito_atual"] . '</p>
                                </div>
                                <div class="col-6 text-xs justify-content-end flex">
                                    '.$info_paciente.'
                                </div>
                            </div> 
                            <div>
                                <div class="col-12 gap-1 text-xs justify-content-start text-end flex">
                                    '.$icone_morse.' '.$icone_braden.' '.$icone_fugulin.' '.$icone_news.' '.$icones_sinais_vitais.' 
                                </div>
                                '.$cor_card_avaliacao_verde_vermelho .'
                                '.$div_precaucoes_isolamento.'
                                '.$total_dias_internacao_atendimento.'
                            </div>
                        </div>
                    </div>
                </div>';
            }else{
                echo '<div class="h-full card '.$classes_adicionais_card.' my-1">
                        <div class="'.$tipo_de_body_card.'">
                            <div class="flex" '.$tamanho_linha_card_painel.'>
                                <div class="col-12 pb-1 d-flex">
                                    <div class="col-8">
                                        <span class="lead font-weight-bolder w-90" '.$gambsClick.'>' . $leitos[$i]["ds_leito_atual"] . '</span>
                                    </div>
                                    <div class="justify-content-end text-end flex col-4">
                                        '.$div_precaucoes_isolamento.'
                                    </div>
                                </div>
                                <!--
                                <div class="col-8 gap-1 text-xs justify-content-end text-end flex">
                                    '.$icone_morse.' '.$icone_braden.' '.$icone_fugulin.' '.$icone_news.' '.$icones_sinais_vitais.'
                                </div>
                                 -->
                            </div> 
                            <div class="col-12 gap-meio text-xs flex pb-2">
                                '.$icone_morse.' '.$icone_braden.' '.$icone_fugulin.' '.$icone_news.' '.$icones_sinais_vitais.'
                            </div> 
                            <div '.$gambsClick.'>
                                '.$dados_atd.'
                                '.$cor_card_avaliacao_verde_vermelho.'
                                '.$total_dias_internacao_atendimento.'
                            </div>
                        </div>
                    </div>
                </div>';
            }
        }
        echo '</div>';
        echo "<input type='hidden' id='cd_setor_atendimento_id' name='cd_setor_atendimento_id' value='".$_GET["s"]."'/>";
        echo "<input type='hidden' id='tipo_perfil_logado' name='tipo_perfil_logado' value='".$_SESSION["usuario_logado"]["TIPO_PERFIL"]."'/>";
        echo "<input type='hidden' id='linha_cuidado_id' name='linha_cuidado_id' value='".$_GET["l"]."'/>";
        // echo "<input type='hidden' id='usuario_ad' name='usuario_ad' value='".$_SESSION["usuario_logado"]["USUARIO_AD"] ? $_SESSION["usuario_logado"]["USUARIO_AD"] : ' '."'/>";
    ?>
</div>

<!-- Modal -->
<div class="modal fade w-full" id="modal_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Informações do leito</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="corpo_modal" name="corpo_modal">
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">OK</button>
        <!-- <button type="button" class="btn bg-gradient-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>

<!-- Modal IA-->

<div class="modal fade w-full" id="modal_ia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title title-ia" id="exampleModalLabel">Assistente IA</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span> 
        </button>
      </div>
        
      <div class="modal-body modal-body-ia" id="corpo_modal_ia" name="corpo_modal">
        
      </div>

    </div>
  </div>
</div>

<script>

    // função que posiciona o scroll no final
    function scrollPosicaoFinal() {
        const historicoScroll = document.getElementById("historico")
        historicoScroll.scrollTop = historicoScroll.scrollHeight;
    }

    let usuario_ad = document.getElementById("usuario_ad").value;
        
    async function enviarmensagem(nr_atendimento){

        const mensagem = document.getElementById('mensagem-input');
        const usermensagem = mensagem.value.trim();
        const btnSubmit = document.getElementById('btn-submit');

        if(!mensagem.value){
            alert('Campo em branco.');
            return
        }

        const status = document.getElementById('status');

        status.style.display = 'block';
        status.innerHTML = 'Carregando...';
        btnSubmit.disabled = true;
        btnSubmit.style.cursor = 'not-allowed';
        mensagem.disabled = true;

        historicomensagem(usermensagem);

        scrollPosicaoFinal();

        const RespostaIA = IAResponse(usermensagem, nr_atendimento, usuario_ad);
        let messageIA = ''
        
        //cria os elementos div e p da resposta recebida pela ia
        const historico = document.getElementById('historico');
        const caixaResposta = document.createElement('div');
        caixaResposta.className = 'caixa-resposta';

        const mensagemResposta = document.createElement('p');
        mensagemResposta.className = 'mensagem-resposta';

        caixaResposta.appendChild(mensagemResposta); 
        historico.appendChild(caixaResposta);

        scrollPosicaoFinal();
        
        let element = document.getElementsByClassName("mensagem-resposta");
        
        for await (chunk of RespostaIA) {                
            messageIA += chunk
            let mensagem = element.item(element.length - 1);
            mensagem.innerHTML += chunk;
            scrollPosicaoFinal();
        }
        
        mensagem.value = '';
        status.style.display = 'none';
        status.innerHTML = '';
        btnSubmit.disabled = false;
        btnSubmit.style.cursor = 'pointer';
        mensagem.disabled = false;

    } 

    async function* IAResponse(query, atendimento, username){
  
        const ollama_generate = {"atendimento": Number(atendimento), "query": query, "username": username}
        
        const response = await fetch("http://10.40.1.16:8084/api/v1/chat", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(ollama_generate),
        });

        const reader = response.body.getReader();
        while(true){
            const { done, value: encodedResponse } = await reader.read();
            if (done) {                
            break; // Fim do stream
            }
                
            const responseText = new TextDecoder("utf-8").decode(encodedResponse, {stream: true});

            try {
            
            // Itera sobre os objetos no array e extrai o conteúdo
            let assistantMessage = ""; // Variável para concatenar o conteúdo
            let messageChunk =""
            
            for (const chunk of responseText) {
                messageChunk += chunk        
            }

            let message = JSON.parse(messageChunk)
            if (message.role === 'assistant') {
                assistantMessage += message.content;        
            }
            
            yield message.content
            

            } catch (error) {
            console.error("Erro ao analisar JSON:", error);
            }
        }
    }
    
    //cria os elementos div e p da mensagem enviada para o chat
    function historicomensagem(mensagem){
        const historico = document.getElementById('historico');

        const caixamensagem = document.createElement('div');
        caixamensagem.className = 'caixa-mensagem';

        const mensagemEnviada = document.createElement('p');
        mensagemEnviada.className = 'mensagem-enviada';
        mensagemEnviada.innerHTML = mensagem;
        
        caixamensagem.appendChild(mensagemEnviada);
        historico.appendChild(caixamensagem);
    }

</script>

<script>
    function idade(ano_aniversario, mes_aniversario, dia_aniversario) {
        var d = new Date,
            ano_atual = d.getFullYear(),
            mes_atual = d.getMonth() + 1,
            dia_atual = d.getDate(),

            ano_aniversario = +ano_aniversario,
            mes_aniversario = +mes_aniversario,
            dia_aniversario = +dia_aniversario,

            quantos_anos = ano_atual - ano_aniversario;

        if(mes_atual < mes_aniversario || mes_atual == mes_aniversario && dia_atual < dia_aniversario) {
            quantos_anos--;
        }
        return quantos_anos < 0 ? 0 : quantos_anos;
    }

    function detalhesDoLeito(nr_atendimento,leito_atual){
        let html_leito="";
        $.ajax({
            url : "<?php echo base_url('detalhada/retornaTotaisAvaliacoesVerdeVermelho');?>",
            type : 'POST',
            data: 
            {
                "nr_atendimento" : nr_atendimento
            },
            dataType: "json",
            success : function(totaisVerdeVemelho){
                let html_totais_verde_vermelho  = "";

                if(parseInt(totaisVerdeVemelho.total)>0){
                    let regra3_verde                = Math.round(totaisVerdeVemelho.porcentagem_verde)*255/100
                    let regra3_vermelho             = Math.round(totaisVerdeVemelho.porcentagem_vermelho)*255/100;

                    html_totais_verde_vermelho =    "<tr><td colspan='2'></td></tr>"+
                                                    "<tr>" +
                                                        "<td class='font-weight-bold text-wrap text-xs'>" +
                                                            "Total de avaliações" +
                                                        "</td>" +
                                                        "<td class='text-wrap text-end justify-content-end text-xs'>" +
                                                            totaisVerdeVemelho.total+
                                                        "</td>" +
                                                    "</tr>"+
                                                    "<tr>" +
                                                        "<td class='font-weight-bold text-wrap text-xs'>" +
                                                            "Total verde" +
                                                        "</td>" +
                                                        "<td class='text-wrap text-end justify-content-end text-xs'>" +
                                                            totaisVerdeVemelho.total_verde+
                                                        "</td>" +
                                                    "</tr>"+
                                                    "<tr>" +
                                                        "<td class='font-weight-bold text-wrap text-xs'>" +
                                                            "% verde" +
                                                        "</td>" +
                                                        "<td class='text-wrap text-end justify-content-end text-xs'>" +
                                                            parseFloat(totaisVerdeVemelho.porcentagem_verde).toFixed(2)+"%"+
                                                        "</td>" +
                                                    "</tr>"+
                                                    "<tr>" +
                                                        "<td class='font-weight-bold text-wrap text-xs'>" +
                                                            "Total vermelho" +
                                                        "</td>" +
                                                        "<td class='text-wrap text-end justify-content-end text-xs'>" +
                                                            totaisVerdeVemelho.total_vermelho+
                                                        "</td>" +
                                                    "</tr>"+
                                                    "<tr>" +
                                                        "<td class='font-weight-bold text-wrap text-xs'>" +
                                                            "% vermelho" +
                                                        "</td>" +
                                                        "<td class='text-wrap text-end justify-content-end text-xs'>" +
                                                            parseFloat(totaisVerdeVemelho.porcentagem_vermelho).toFixed(2)+"%"+
                                                        "</td>" +
                                                    "</tr>"+
                                                    "<tr>" +
                                                        "<td colspan='2' class='text-wrap justify-content-center' style='text-align:-webkit-center'>" +
                                                            "<div style='background-color: rgb("+regra3_vermelho+" "+regra3_verde+" 0); border-radius:50%; width:50px;height:50px' ></div>"+
                                                        "</td>" +
                                                    "</tr>";

                }


                $.ajax({
                    url : "<?php echo base_url('detalhada/retornaDadosLeito');?>",
                    type : 'POST',
                    data: 
                    {
                        "nr_atendimento" : nr_atendimento,
                        "leito_atual": leito_atual,
                        "cd_setor_atendimento": $("#cd_setor_atendimento_id").val()
                    },
                    dataType: "json",
                    success : function(result){

                        $.ajax({
                            url : "<?php echo base_url('detalhada/retornaMovimentacoesAtendimento');?>",
                            type : 'POST',
                            data: 
                            {
                                "nr_atendimento" : nr_atendimento
                            },
                            dataType: "json",
                            success : function(resultadoMovimentacoes){
                                let data_entrada    = new Date(result["dt_entrada"]);
                                let data_prev_alta  = new Date(result["dt_previsao_alta"]);
                                let dt_liberacao    = new Date(result["dt_liberacao"]);

                                let verde_verm = " - ";

                                // if(data_entrada.toLocaleString()!="Invalid Date"){
                                //     data_entrada = data_entrada.toLocaleString().replace(',','');
                                // }else{
                                //     data_entrada = " - ";
                                // }

                                let hora_entrada_geral = result["dt_entrada"].split(" ");
                                hora_entrada_geral = hora_entrada_geral[1];
                                data_entrada = result["dt_entrada"].substr(0, 10).split('-').reverse().join('/')+" "+hora_entrada_geral;
                                if(data_entrada=="00/00/0000 00:00:00"){
                                    data_entrada = " - ";
                                }

                                let hora_liberacao = result["dt_liberacao"].split(" ");
                                hora_liberacao = hora_liberacao[1];
                                dt_liberacao = result["dt_liberacao"].substr(0, 10).split('-').reverse().join('/')+" "+hora_liberacao;
                                if(dt_liberacao=="00/00/0000 00:00:00"){
                                    dt_liberacao = " - ";
                                }

                                if (data_prev_alta.toLocaleString() !== "Invalid Date"){
                                    let dt_arr = result["dt_previsao_alta"].split('-');
                                    data_prev_alta = dt_arr[2]+'/'+dt_arr[1]+'/'+dt_arr[0];
                                } else {
                                    data_prev_alta = " - ";
                                }

                                let motivo_vermelho                 = "";
                                let conteudo_template_avaliacao     = "";
                                let html_movimentacoes_atendimento  = "";
                                let html_avaliacao_isolada          = "";

                                let data_nascimento     = result["dt_nascimento"].split("/");
                                let idade_paciente      = idade(data_nascimento[2],data_nascimento[1],data_nascimento[0]); 

                                let html_botoes_evolucoes = "";

                                /*INÍCIO PREENCHIMENTO TABELA DE AVALIAÇÃO VERDE/VERMELHO*/

                                if(result["ds_verde_ou_vermelho"] && result["cd_agrupamento"]!=4){
                                    let cor         = "";
                                    if(result["ds_verde_ou_vermelho"].trim().toUpperCase()=="VERDE"){
                                        cor = "#00ff00";
                                    }else{
                                        cor = "#ff0000";
                                    }

                                    // if($("#tipo_perfil_logado").val()=="A" || $("#tipo_perfil_logado").val()=="D" || 
                                    // $("#tipo_perfil_logado").val()=="E" || $("#tipo_perfil_logado").val()=="Y" || 
                                    // $("#tipo_perfil_logado").val()=="B" || $("#tipo_perfil_logado").val()=="F"){
                                    html_botoes_evolucoes = "<tr>"+
                                                                "<td class='font-weight-bold text-wrap text-center' colspan='2'>"+
                                                                    "<a class='btn btn-primary rounded flex' href='avaliacoesVerdeVermelho?a="+nr_atendimento+"&l="+$("#linha_cuidado_id").val()+"&s="+$("#cd_setor_atendimento_id").val()+"'>Histórico de avaliações</a>"+
                                                                    "<a class='btn hmdcc-color-1 rounded text-white flex' href='historicoEvolucoesPaciente?a="+nr_atendimento+"&l="+$("#linha_cuidado_id").val()+"&s="+$("#cd_setor_atendimento_id").val()+"'>Últimas evoluções</a>"+
                                                                    "<a class='btn hmdcc-color-2 rounded text-white flex' href='historicoInterconsultasPaciente?a="+nr_atendimento+"&l="+$("#linha_cuidado_id").val()+"&s="+$("#cd_setor_atendimento_id").val()+"'>Últimas interconsultas</a>"+
                                                                    "<a class='btn hmdcc-color-3 rounded text-white flex' href='historicoExamesLabPaciente?a="+nr_atendimento+"&l="+$("#linha_cuidado_id").val()+"&s="+$("#cd_setor_atendimento_id").val()+"'>Exames laboratoriais</a>"+
                                                                    // "<a class='btn hmdcc-color-4 rounded text-white flex' href='historicoPrescricoesPaciente?a="+nr_atendimento+"&l="+$("#linha_cuidado_id").val()+"&s="+$("#cd_setor_atendimento_id").val()+"'>Últimas prescrições</a>"+
                                                                    "<a class='btn hmdcc-color-5 rounded text-white flex' href='historicoExamesImagemPaciente?a="+nr_atendimento+"&l="+$("#linha_cuidado_id").val()+"&s="+$("#cd_setor_atendimento_id").val()+"'>Imagens e laudos</a>"+
                                                                "</td>"+
                                                            "</tr>";
                                    // }else{
                                    //     html_botoes_evolucoes = "";
                                    // }

                                    if(result["ds_motivo_vermelho"].length>0){
                                        
                                        motivo_vermelho = "<tr>" +
                                                                "<td class='font-weight-bold text-wrap'>" +
                                                                    "Motivo" +
                                                                "</td>" +
                                                                "<td class='text-wrap text-justify'>" +
                                                                    result["ds_motivo_vermelho"]+
                                                                "</td>" +
                                                            "</tr>";
                                    }
                                    
                                    conteudo_template_avaliacao =   "<table class='table align-items-center justify-content-center' width='100%'>"+
                                                                        "<tr>" +
                                                                            "<td colspan='2' class='text-center text-uppercase font-weight-bold text-wrap'>" +
                                                                                "Última Avaliação" +
                                                                            "</td>" +
                                                                        "</tr>"+
                                                                        "<tr>" +
                                                                            "<td class='font-weight-bold text-wrap'>" +
                                                                                "Data avaliação" +
                                                                            "</td>" +
                                                                            "<td class='text-wrap text-justify'>" +
                                                                                dt_liberacao.toLocaleString().replace(',','')+
                                                                            "</td>" +
                                                                        "</tr>"+
                                                                        "<tr>" +
                                                                            "<td class='font-weight-bold text-wrap'>" +
                                                                                "Profissional" +
                                                                            "</td>" +
                                                                            "<td class='text-wrap text-justify'>" +
                                                                                result["profissional_verde_vermelho"]+
                                                                            "</td>" +
                                                                        "</tr>"+
                                                                        "<tr>" +
                                                                            "<td class='font-weight-bold text-wrap'>" +
                                                                                "Condição" +
                                                                            "</td>" +
                                                                            "<td class='text-wrap'>" +
                                                                                "<div class='w-full text-center' style='border-radius:4px; color:#fff; background-color:"+cor+"'>"+
                                                                                    result["ds_verde_ou_vermelho"].trim().toUpperCase()+
                                                                                "</div>"+
                                                                            "</td>" +
                                                                        "</tr>" +
                                                                        motivo_vermelho+
                                                                        "<tr>" +
                                                                            "<td class='font-weight-bold text-wrap'>" +
                                                                                "Previsão de alta" +
                                                                            "</td>" +
                                                                            "<td class='text-wrap'>" +
                                                                                data_prev_alta +
                                                                            "</td>" +
                                                                        "</tr>"+
                                                                        html_totais_verde_vermelho+
                                                                        html_botoes_evolucoes+
                                                                    "</table>";
                                }else{
                                    // if($("#tipo_perfil_logado").val()=="A" || $("#tipo_perfil_logado").val()=="D" || 
                                    // $("#tipo_perfil_logado").val()=="E" || $("#tipo_perfil_logado").val()=="Y" || 
                                    // $("#tipo_perfil_logado").val()=="B" || $("#tipo_perfil_logado").val()=="F"){
                                        html_botoes_evolucoes = "<tr>"+
                                                                    "<td class='font-weight-bold text-wrap text-center' colspan='2'>"+
                                                                        "<a class='btn hmdcc-color-1 rounded text-white flex' href='historicoEvolucoesPaciente?a="+nr_atendimento+"&l="+$("#linha_cuidado_id").val()+"&s="+$("#cd_setor_atendimento_id").val()+"'>Últimas evoluções</a>"+
                                                                        "<a class='btn hmdcc-color-2 rounded text-white flex' href='historicoInterconsultasPaciente?a="+nr_atendimento+"&l="+$("#linha_cuidado_id").val()+"&s="+$("#cd_setor_atendimento_id").val()+"'>Últimas interconsultas</a>"+
                                                                        "<a class='btn hmdcc-color-3 rounded text-white flex' href='historicoExamesLabPaciente?a="+nr_atendimento+"&l="+$("#linha_cuidado_id").val()+"&s="+$("#cd_setor_atendimento_id").val()+"'>Exames laboratoriais</a>"+
                                                                        // "<a class='btn hmdcc-color-4 rounded text-white flex' href='historicoPrescricoesPaciente?a="+nr_atendimento+"&l="+$("#linha_cuidado_id").val()+"&s="+$("#cd_setor_atendimento_id").val()+"'>Últimas prescrições</a>"+
                                                                        "<a class='btn hmdcc-color-5 rounded text-white flex' href='historicoExamesImagemPaciente?a="+nr_atendimento+"&l="+$("#linha_cuidado_id").val()+"&s="+$("#cd_setor_atendimento_id").val()+"'>Imagens e laudos</a>"+
                                                                    "</td>"+
                                                                "</tr>";
                                    // }else{
                                    //     html_botoes_evolucoes = "";
                                    // }

                                    conteudo_template_avaliacao = "<table class='table align-items-center justify-content-center' width='100%'>"+
                                                                        html_botoes_evolucoes+
                                                                    "</table>";
                                }

                                /*FIM PREENCHIMENTO TABELA DE AVALIAÇÃO VERDE/VERMELHO*/

                                /*INÍCIO PROCESSAMENTO MOVIMENTAÇÕES*/ 

                                if(resultadoMovimentacoes.length>0){
                                    html_movimentacoes_atendimento =    "<table class='table align-items-center justify-content-center' width='100%'>"+
                                                                            "<tr>" +
                                                                                "<td class='text-center text-uppercase text-wrap font-weight-bold' colspan='5'>" +
                                                                                    "Movimentações" +
                                                                                "</td>" +
                                                                            "</tr>"+
                                                                            "<tr>" +
                                                                                "<td class='text-xs font-weight-bold text-wrap'>" +
                                                                                    "Setor" +
                                                                                "</td>" +
                                                                                "<td class='text-xs font-weight-bold text-wrap'>" +
                                                                                    "Leito" +
                                                                                "</td>" +
                                                                                "<td class='text-xs text-wrap font-weight-bold '>" +
                                                                                    "Entrada"+
                                                                                "</td>"+
                                                                                "<td class='text-xs text-wrap font-weight-bold '>" +
                                                                                    "Saída"+
                                                                                "</td>" +
                                                                                "<td class='text-xs text-wrap font-weight-bold '>" +
                                                                                    "Dias"+
                                                                                "</td>" +
                                                                            "</tr>";

                                    let data_entrada_unidade    = "";
                                    let data_saida_unidade      = "";

                                    for(let j=0;j<resultadoMovimentacoes.length;j++){

                                        data_entrada_unidade    = new Date(resultadoMovimentacoes[j]["dt_entrada_unidade"]);
                                        data_saida_unidade      = new Date(resultadoMovimentacoes[j]["dt_saida_unidade"]);

                                        let hora_entrada = resultadoMovimentacoes[j]["dt_entrada_unidade"].split(" ");
                                        hora_entrada = hora_entrada[1];
                                        data_entrada_unidade = resultadoMovimentacoes[j]["dt_entrada_unidade"].substr(0, 10).split('-').reverse().join('/')+" "+hora_entrada;
                                        if(data_entrada_unidade=="00/00/0000 00:00:00"){
                                            data_entrada_unidade = " - ";
                                        }

                                        let hora_saida = resultadoMovimentacoes[j]["dt_saida_unidade"].split(" ");
                                        hora_saida = hora_saida[1];
                                        data_saida_unidade = resultadoMovimentacoes[j]["dt_saida_unidade"].substr(0, 10).split('-').reverse().join('/')+" "+hora_saida;
                                        if(data_saida_unidade=="00/00/0000 00:00:00"){
                                            data_saida_unidade = " - ";
                                        }

                                        let cor_linha_cond_mesmo_setor = "";
                                        if($("#linha_cuidado_id").val()==resultadoMovimentacoes[j]["cd_agrupamento"]){
                                            cor_linha_cond_mesmo_setor = "text-white cor_card_mesma_linha_cuidado";
                                        }


                                        html_movimentacoes_atendimento += "<tr>" +
                                                                            "<td class='"+cor_linha_cond_mesmo_setor+" font-weight-bold text-wrap text-xs'>" +
                                                                                resultadoMovimentacoes[j]["ds_setor_atendimento"]+
                                                                            "</td>" +
                                                                            "<td class='"+cor_linha_cond_mesmo_setor+" text-wrap text-xs'>" +
                                                                                resultadoMovimentacoes[j]["leito"]+' '+resultadoMovimentacoes[j]["ds_complemento_leito"] +
                                                                            "</td>" +
                                                                            "<td class='"+cor_linha_cond_mesmo_setor+" text-wrap text-xs'>" +
                                                                                data_entrada_unidade+
                                                                            "</td>"+
                                                                            "<td class='"+cor_linha_cond_mesmo_setor+" text-wrap text-xs'>" +
                                                                                data_saida_unidade+
                                                                            "</td>" +
                                                                            "<td class='"+cor_linha_cond_mesmo_setor+" text-wrap text-xs'>" +
                                                                                resultadoMovimentacoes[j]["qt_dias_unidade"]+
                                                                            "</td>" +
                                                                        "</tr>";
                                    }

                                    //GUARDANDO PERMANENCIA (DIAS) NA MESMA LINHA DE CUIDADO
                                    let somatoria_dias_linha_cuidado = 0;
                                    for(let k=resultadoMovimentacoes.length-1;k>=0;k--){
                                        if(resultadoMovimentacoes[k]["cd_agrupamento"]==$("#linha_cuidado_id").val()){
                                            somatoria_dias_linha_cuidado = parseInt(resultadoMovimentacoes[k]["qt_dias_unidade"])+parseInt(somatoria_dias_linha_cuidado);
                                        }else{
                                            break;
                                        }
                                    }

                                    html_movimentacoes_atendimento  +=      "<tr class='my-4'>" +
                                                                                "<td colspan='4' class='text-xs font-weight-bold text-wrap text-white '>" +
                                                                                    
                                                                                "</td>" +
                                                                                "<td class='text-xs text-wrap font-weight-bold text-white'>" +
                                                                                    
                                                                                "</td>" +
                                                                            "</tr>"+
                                                                            "<tr>" +
                                                                                "<td colspan='4' class='text-xs font-weight-bold text-wrap text-white cor_card_mesma_linha_cuidado'>" +
                                                                                    "Total atual na Linha de Cuidado" +
                                                                                "</td>" +
                                                                                "<td class='text-xs text-wrap font-weight-bold text-white cor_card_mesma_linha_cuidado'>" +
                                                                                    somatoria_dias_linha_cuidado+" dia(s)"+
                                                                                "</td>" +
                                                                            "</tr>"+
                                                                            "<tr >" +
                                                                                "<td colspan='4' class='text-xs font-weight-bold text-wrap'>" +
                                                                                    "Total" +
                                                                                "</td>" +
                                                                                "<td class='text-xs text-wrap font-weight-bold'>" +
                                                                                    resultadoMovimentacoes[0]["total_dias_unidade"]+" dia(s)"+
                                                                                "</td>" +
                                                                            "</tr>"+
                                                                        "</table>";
                                }
                                
                                /*FIM PROCESSAMENTO MOVIMENTAÇÕES*/ 
                                
                                html_leito = "<table class='table align-items-center justify-content-center' width='100%'>" +
                                    "<tr>" +
                                        "<td class='font-weight-bold text-wrap'>" +
                                            "Paciente" +
                                        "</td>" +
                                        "<td class='text-wrap'>" +
                                            result['ds_nome_paciente'] +
                                        "</td>" +
                                    "</tr>" +
                                    "<tr>" +
                                        "<td class='font-weight-bold text-wrap'>" +
                                            "Idade" +
                                        "</td>" +
                                        "<td class='text-wrap'>" +
                                            idade_paciente + " anos"+
                                        "</td>" +
                                    "</tr>" +
                                    "<tr>" +
                                        "<td class='font-weight-bold text-wrap'>" +
                                            "Nº atendimento" +
                                        "</td>" +
                                        "<td class='text-wrap'>" +
                                            result['nr_atendimento'] +
                                        "</td>" +
                                    "</tr>" +
                                    "<tr>" +
                                        "<td class='font-weight-bold text-wrap'>" +
                                            "Leito" +
                                        "</td>" +
                                        "<td class='text-wrap'>" +
                                            result['ds_leito_atual'] +
                                        "</td>" +
                                    "</tr>" +
                                    "<tr>" +
                                        "<td class='font-weight-bold text-wrap'>" +
                                            "Data de entrada" +
                                        "</td>" +
                                        "<td class='text-wrap'>" +
                                            data_entrada +
                                        "</td>" +
                                    "</tr>" +
                                    "<tr>" +
                                        "<td class='font-weight-bold text-wrap'>" +
                                            "Tempo de internação" +
                                        "</td>" +
                                        "<td class='text-wrap'>" +
                                            result['tempo_internacao'] + " dia(s)"+
                                        "</td>" +
                                    "</tr>"+
                                    html_avaliacao_isolada+
                                "</table>"+
                                html_movimentacoes_atendimento+
                                conteudo_template_avaliacao;

                                $("#corpo_modal").html(html_leito);
                                $("#modal_info").modal('show');
                            },
                            error : function(data){
                                alert('Não foi possível abrir o detalhamento. Confira sua conexão!');
                            }
                        });
                        
                    },
                    error : function(data){
                        alert('Não foi possível abrir o detalhamento. Confira sua conexão!');
                    }
                });
            },error : function(data){
                alert('Não foi possível abrir o detalhamento. Confira sua conexão!');
            }
        });
    }

    function detalhesIsolados(nr_atendimento,leito_atual,tipo_avaliacao,dt_ultima_avaliacao="",classificacao="",profissional="",pontos=""){
        $.ajax({
            url : "<?php echo base_url('detalhada/retornaDadosLeito');?>",
            type : 'POST',
            data: 
            {
                "nr_atendimento" : nr_atendimento,
                "leito_atual": leito_atual,
                "cd_setor_atendimento": $("#cd_setor_atendimento_id").val()
            },
            dataType: "json",
            success : function(result){
                let data_entrada    = new Date(result["dt_entrada"]);
                let data_prev_alta  = new Date(result["dt_previsao_alta"]);

                let data_nascimento     = result["dt_nascimento"].split("/");
                let idade_paciente      = idade(data_nascimento[2],data_nascimento[1],data_nascimento[0]); 
                // if(data_entrada.toLocaleString()!="Invalid Date"){
                //     data_entrada = data_entrada.toLocaleString().replace(',','');
                // }else{
                //     data_entrada = " - ";
                // }

                let hora_entrada_geral = result["dt_entrada"].split(" ");
                hora_entrada_geral = hora_entrada_geral[1];
                data_entrada = result["dt_entrada"].substr(0, 10).split('-').reverse().join('/')+" "+hora_entrada_geral;
                if(data_entrada=="00/00/0000 00:00:00"){
                    data_entrada = " - ";
                }

                if (data_prev_alta.toLocaleString() !== "Invalid Date"){
                    let dt_arr = result["dt_previsao_alta"].split('-');
                    data_prev_alta = dt_arr[2]+'/'+dt_arr[1]+'/'+dt_arr[0];
                } else {
                    data_prev_alta = " - ";
                }

                if(tipo_avaliacao=='fugulin'){
                    let hora_entrada_fugulin = result["data_fugulin"].split(" ");
                    hora_entrada_fugulin = hora_entrada_fugulin[1];
                    let data_fugulin = result["data_fugulin"].substr(0, 10).split('-').reverse().join('/')+" "+hora_entrada_fugulin;
                    if(data_fugulin=="00/00/0000 00:00:00"){
                        data_fugulin = " - ";
                    }
                    html_avaliacao_isolada = "<tr>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    "Fugulin" +
                                                "</td>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    result['ds_gradacao'] + ' - ' + result['qt_pontuacao'] + " pontos"+
                                                "</td>" +
                                            "</tr>"+
                                            "<tr>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    "Profissional" +
                                                "</td>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    result['profissional_fugulin']+
                                                "</td>" +
                                            "</tr>"+
                                            "<tr>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    "Data Avaliação Fugulin" +
                                                "</td>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    data_fugulin+
                                                "</td>" +
                                            "</tr>";
                }else if(tipo_avaliacao=='news'){
                    let hora_entrada_news = result["dt_liberacao_news"].split(" ");
                    hora_entrada_news = hora_entrada_news[1];
                    let data_news = result["dt_liberacao_news"].substr(0, 10).split('-').reverse().join('/')+" "+hora_entrada_news;
                    if(data_news=="00/00/0000 00:00:00"){
                        data_news = " - ";
                    }
                    html_avaliacao_isolada = "<tr>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    "News" +
                                                "</td>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    result['score'] +
                                                "</td>" +
                                            "</tr>"+
                                            "<tr>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    "Profissional" +
                                                "</td>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    result['profissional_news']+
                                                "</td>" +
                                            "</tr>"+
                                            "<tr>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    "Data Avaliação News" +
                                                "</td>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    data_news+
                                                "</td>" +
                                            "</tr>";

                }else {
                    //SINAIS VITAIS
                    if(tipo_avaliacao=='temperatura'){
                        let hora_temperatura = result["dt_qt_temp"].split(" ");
                        hora_temperatura = hora_temperatura[1];
                        let data_temp = result["dt_qt_temp"].substr(0, 10).split('-').reverse().join('/')+" "+hora_temperatura;
                        if(data_temp=="00/00/0000 00:00:00"){
                            data_temp = " - ";
                        }
                        html_avaliacao_isolada = "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Temperatura" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        result['qt_temp'] +"º"+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Profissional" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        result['profissional_qt_temp']+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Data Medição" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        data_temp+
                                                    "</td>" +
                                                "</tr>";

                    }else if(tipo_avaliacao=='cardiaca'){
                        let hora_cardiaca = result["dt_qt_freq_cardiac"].split(" ");
                        hora_cardiaca = hora_cardiaca[1];
                        let data_card = result["dt_qt_freq_cardiac"].substr(0, 10).split('-').reverse().join('/')+" "+hora_cardiaca;
                        if(data_card=="00/00/0000 00:00:00"){
                            data_card = " - ";
                        }
                        html_avaliacao_isolada = "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Frequência Cardíaca" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        result['qt_freq_cardiac'] +" bpm"+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Profissional" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        result['profissional_qt_freq_cardiac']+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Data Medição" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        data_card+
                                                    "</td>" +
                                                "</tr>";

                    }else if(tipo_avaliacao=='respiratoria'){
                        let hora_respiratoria = result["dt_qt_freq_resp"].split(" ");
                        hora_respiratoria = hora_respiratoria[1];
                        let data_respiratoria = result["dt_qt_freq_resp"].substr(0, 10).split('-').reverse().join('/')+" "+hora_respiratoria;
                        if(data_respiratoria=="00/00/0000 00:00:00"){
                            data_respiratoria = " - ";
                        }
                        html_avaliacao_isolada = "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Frequência Respiratória" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        result['qt_freq_resp'] +" irpm"+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Profissional" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        result['profissional_qt_freq_resp']+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Data Medição" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        data_respiratoria+
                                                    "</td>" +
                                                "</tr>";

                    }else if(tipo_avaliacao=='braden'){
                        let hora_braden = dt_ultima_avaliacao.split(" ");
                        hora_braden = hora_braden[1];
                        let data_braden = dt_ultima_avaliacao.substr(0, 10).split('-').reverse().join('/')+" "+hora_braden;
                        if(data_braden=="00/00/0000 00:00:00"){
                            data_braden = " - ";
                        }
                        html_avaliacao_isolada = "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Pontuação Braden" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        pontos+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Classificação Braden" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        classificacao+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Profissional" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        profissional+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Data Medição" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        data_braden+
                                                    "</td>" +
                                                "</tr>";

                    }else if(tipo_avaliacao=='morse'){
                        let hora_morse = dt_ultima_avaliacao.split(" ");
                        hora_morse = hora_morse[1];
                        let data_morse = dt_ultima_avaliacao.substr(0, 10).split('-').reverse().join('/')+" "+hora_morse;
                        if(data_morse=="00/00/0000 00:00:00"){
                            data_morse = " - ";
                        }
                        html_avaliacao_isolada = "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Pontuação Morse" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        pontos+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Classificação Morse" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        classificacao+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Profissional" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        profissional+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Data Medição" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        data_morse+
                                                    "</td>" +
                                                "</tr>";

                    }else if(tipo_avaliacao=='precaucoes_isolamento'){
                        let hora_precaucao = result["dt_atualizacao_precaucao"].split(" ");
                        hora_precaucao = hora_precaucao[1];
                        let data_precaucao = result["dt_atualizacao_precaucao"].substr(0, 10).split('-').reverse().join('/')+" "+hora_precaucao;
                        if(data_precaucao=="00/00/0000 00:00:00"){
                            data_precaucao = " - ";
                        }

                        let cuidados_precaucao = "";
                        if(result['ds_cuidado_isolamento'].length>1){
                           cuidados_precaucao = "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Cuidados" +
                                                    "</td>" +
                                                    "<td class=' text-wrap'>" +
                                                        result['ds_cuidado_isolamento']+
                                                    "</td>" +
                                                "</tr>"; 
                        }

                        let observacao_precaucao = "";
                        if(result['ds_observacao_isolamento'].length>1){
                            observacao_precaucao = "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                    "Observação" +
                                "</td>" +
                                "<td class=' text-wrap'>" +
                                    result['ds_observacao_isolamento']+
                                "</td>" +
                            "</tr>"
                        }
                        
                        html_avaliacao_isolada = "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Tipo de Precaução" +
                                                    "</td>" +
                                                    "<td class=' text-wrap'>" +
                                                        result['ds_precaucao_isolamento']+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Motivo" +
                                                    "</td>" +
                                                    "<td class=' text-wrap'>" +
                                                        result['ds_motivo_isolamento']+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Profissional" +
                                                    "</td>" +
                                                    "<td class=' text-wrap'>" +
                                                        result['nome_profissional_precaucao']+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Data" +
                                                    "</td>" +
                                                    "<td class=' text-wrap'>" +
                                                        data_precaucao+
                                                    "</td>" +
                                                "</tr>"+
                                                cuidados_precaucao +
                                                observacao_precaucao;

                    }else if(tipo_avaliacao=='precaucoes_isolamento_2'){
                        let hora_precaucao = result["dt_atualizacao_precaucao_2"].split(" ");
                        hora_precaucao = hora_precaucao[1];
                        let data_precaucao = result["dt_atualizacao_precaucao_2"].substr(0, 10).split('-').reverse().join('/')+" "+hora_precaucao;
                        if(data_precaucao=="00/00/0000 00:00:00"){
                            data_precaucao = " - ";
                        }

                        let cuidados_precaucao = "";
                        if(result['ds_cuidado_isolamento_2'].length>1){
                            cuidados_precaucao = "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Cuidados" +
                                                    "</td>" +
                                                    "<td class=' text-wrap'>" +
                                                        result['ds_cuidado_isolamento_2']+
                                                    "</td>" +
                                                "</tr>"; 
                        }

                        let observacao_precaucao = "";
                        if(result['ds_observacao_isolamento_2'].length>1){
                            observacao_precaucao = "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                    "Observação" +
                                "</td>" +
                                "<td class=' text-wrap'>" +
                                    result['ds_observacao_isolamento_2']+
                                "</td>" +
                            "</tr>"
                        }
                        html_avaliacao_isolada = "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Tipo de Precaução" +
                                                    "</td>" +
                                                    "<td class=' text-wrap'>" +
                                                        result['ds_precaucao_isolamento_2']+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Motivo" +
                                                    "</td>" +
                                                    "<td class=' text-wrap'>" +
                                                        result['ds_motivo_isolamento_2']+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Profissional" +
                                                    "</td>" +
                                                    "<td class=' text-wrap'>" +
                                                        result['nome_profissional_precaucao_2']+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Data" +
                                                    "</td>" +
                                                    "<td class=' text-wrap'>" +
                                                        data_precaucao+
                                                    "</td>" +
                                                "</tr>"+
                                                cuidados_precaucao +
                                                observacao_precaucao;
                    }
                }

                html_leito = "<table class='table align-items-center justify-content-center' width='100%'>" +
                    "<tr>" +
                        "<td class='font-weight-bold text-wrap'>" +
                            "Paciente" +
                        "</td>" +
                        "<td class='text-wrap'>" +
                            result['ds_nome_paciente'] +
                        "</td>" +
                    "</tr>" +
                    "<tr>" +
                        "<td class='font-weight-bold text-wrap'>" +
                            "Idade" +
                        "</td>" +
                        "<td class='text-wrap'>" +
                            idade_paciente + " anos"+
                        "</td>" +
                    "</tr>" +
                    "<tr>" +
                        "<td class='font-weight-bold text-wrap'>" +
                            "Nº atendimento" +
                        "</td>" +
                        "<td class='text-wrap'>" +
                            result['nr_atendimento'] +
                        "</td>" +
                    "</tr>" +
                    "<tr>" +
                        "<td class='font-weight-bold text-wrap'>" +
                            "Leito" +
                        "</td>" +
                        "<td class='text-wrap'>" +
                            result['ds_leito_atual'] +
                        "</td>" +
                    "</tr>" +
                    "<tr>" +
                        "<td class='font-weight-bold text-wrap'>" +
                            "Data de entrada" +
                        "</td>" +
                        "<td class='text-wrap'>" +
                            data_entrada +
                        "</td>" +
                    "</tr>" +
                    "<tr>" +
                        "<td class='font-weight-bold text-wrap'>" +
                            "Tempo de internação" +
                        "</td>" +
                        "<td class='text-wrap'>" +
                            result['tempo_internacao'] + " dia(s)"+
                        "</td>" +
                    "</tr>" +
                    html_avaliacao_isolada+
                "</table>";

                $("#corpo_modal").html(html_leito);
                $("#modal_info").modal('show');
            },
            error : function(data){
                alert('Não foi possível abrir o detalhamento. Confira sua conexão!');
            }
        });

    }

    async function conteudoHistorico(username, atendimento){
        const historico_conversa_paciente = {"username": username , "atendimento": Number(atendimento)}

        const response = await fetch("http://10.40.0.3:8084/api/v1/chat-history/", 
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(historico_conversa_paciente)  
            }
        );

        let historico_api = await response.json()
       
        let j = 0;
        let historicoChat           = "";

        historico = document.getElementById('historico');

        for(j=0; j < historico_api.length; j++){
            let caixamensagem       = "";
            let caixaResposta       = "";
            let mensagemResposta    = "";
            let element             = "";
            let mensagemEnviada     = "";
            let mensagem = historico_api[j]["content"];

            console.log(mensagem);
            if(historico_api[j]["role"] == "assistant"){
              
                caixaResposta = document.createElement('div');
                caixaResposta.className = 'caixa-resposta';

                mensagemResposta = document.createElement('p');
                mensagemResposta.className = 'mensagem-resposta';
                mensagemResposta.innerHTML = mensagem;

                caixaResposta.appendChild(mensagemResposta); 
                historico.appendChild(caixaResposta);

            }else if(historico_api[j]["role"] == "user"){

                caixamensagem = document.createElement('div');
                caixamensagem.className = 'caixa-mensagem';

                mensagemEnviada = document.createElement('p');
                mensagemEnviada.className = 'mensagem-enviada';
                mensagemEnviada.innerHTML = mensagem;
                
                caixamensagem.appendChild(mensagemEnviada);
                historico.appendChild(caixamensagem);
            }
        }

        //manter scroll do histórico no fim
        setTimeout(scrollPosicaoFinal(), 3000);
    }
    
    function iaAssistente(nr_atendimento){

        let html_ia = "<div class='container chat'>"+

                            "<div id='historico'>"+
                                    
                            "</div>"+
                            "<span id='status'></span>"+

                            "<div class='cotainer border-top pergunta-ia'>"+
                                "<div class='row align-items-center'>"+
                                    "<div class='col-10 textarea-ia'><textarea type='text' id='mensagem-input' class='form-control flex-fill' placeholder='Faça uma pergunta...'></textarea></div>"+
                                    "<div class='col-2 mx-auto div-btn'><button id='btn-submit' class='btn-ia'><img src=<?php echo base_url("public/assets/img/icons/arrow-right.svg");?> alt=''></button></div>"+
                                "</div>"+
                            "</div>"+
                        "</div>";
        
        conteudoHistorico(usuario_ad,nr_atendimento);

        $("#corpo_modal_ia").html(html_ia);
        $("#modal_ia").modal('show');
        
        const btnSubmit = document.getElementById('btn-submit');
        btnSubmit.addEventListener("click", function() {
            enviarmensagem(nr_atendimento);
        }) 
        
        const mensagem = document.getElementById('mensagem-input');
        mensagem.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                enviarmensagem(nr_atendimento);
            }
        })
    }

</script>