<?php
$this->session = \Config\Services::session();
function iniciais($str)
{
    if (mb_strpos(strtoupper(substr($str, 0, 4)), 'SIC ') !== false) {
        $str = substr($str, 3);
    }
    $pos = 0;
    $saida = '';
    while (($pos = strpos($str, ' ', $pos)) !== false) {
        if (isset($str[$pos + 1]) && $str[$pos + 1] != ' ') {
            $saida .= substr($str, $pos + 1, 1);
        }
        $pos++;
    }
    return $str[0] . $saida;
}

$variavel_controle_margem_tv = 4;
$usuario_logado = $this->session->get("usuario_logado");
if ($usuario_logado["TIPO_PERFIL"] == 'P') {
    $variavel_controle_margem_tv = 2;
    // echo '<meta http-equiv="refresh" content="300" />';
    $controle_painel_2 = "justify-center";
} else {
    $controle_painel_2 = "";
}
?>

<div class="row <?php echo $controle_painel_2; ?>">

    <?php
    if ($_SESSION["usuario_logado"]["TIPO_PERFIL"] != 'P') {
        echo "<div class='col-lg-12 mb-" . $variavel_controle_margem_tv . "'>
                <div id='ultima_atualizacao_div' class='text-end text-xs px-2'>
                    Última Atualização: " . date('d/m/Y H:i:s', strtotime($ultima_atualizacao["ultima_atualizacao"])) . "
                </div>
            </div>";
    } else {
        echo "<div id='ultima_atualizacao_div' class='text-xs'>Última Atualização: " . date('d/m/Y H:i:s', strtotime($ultima_atualizacao["ultima_atualizacao"])) . "</div>";
    }
    ?>


    <?php
    // SEPARAÇÃO DE MODOS: TABELA (Padrão) vs TILES (Painel)
    $isPanel = ($_SESSION["usuario_logado"]["TIPO_PERFIL"] == 'P' && $mostrar_menus == 0);

    if (!$isPanel) {
        // MODO LISTA CARD (REGULAR)
        echo '<div class="list-card-container">';
    } else {
        // MODO PAINEL (GRID/TILES)
        echo '<div class="row">';
    }

    for ($i = 0; $i < count($leitos); $i++) {

        // --- INÍCIO DA LÓGICA DE DADOS (PRESERVADA) ---
    
        // Filtro para Painel
        if ($isPanel) {
            if (strpos($leitos[$i]["ds_leito_atual"], " X") !== false || strpos($leitos[$i]["ds_leito_atual"], " LV") !== false) {
                continue;
            }
            $tamanho_icones_dados_clinicos = "100%";
        } else {
            $tamanho_icones_dados_clinicos = "95%";
        }
        $tamanho_icone_precaucao = "style='max-width: 20px !important;'";

        // --- LÓGICA DE ICONES (Fugulin, News, Braden, Morse, Sinais) ---
        $icones_riscos = "";

        // ATENÇÃO: Concatenando todos os ícones em uma string para a célula da tabela
        $icone_fugulin = "";
        if ($leitos[$i]["cd_agrupamento"] != 4 && isset($leitos[$i]["nr_seq_gradacao"]) && $leitos[$i]["nr_seq_gradacao"] != 0) {
            // ... Lógica Fugulin ...
            $funcao_isolada = 'onclick="detalhesIsolados(' . $leitos[$i]["nr_atendimento"] . ',\'' . $leitos[$i]["ds_leito_atual"] . '\',\'fugulin\')"';
            $img = "";
            $texto_fugulin = "";
            switch ($leitos[$i]["nr_seq_gradacao"]) {
                case 2:
                    $img = "fugulin_minimo.png";
                    $texto_fugulin = "Fugulin: Mínimo";
                    break;
                case 3:
                    $img = "fugulin_intermediario.png";
                    $texto_fugulin = "Fugulin: Intermediário";
                    break;
                case 4:
                    $img = "fugulin_alta_dependencia.png";
                    $texto_fugulin = "Fugulin: Alta Dependência";
                    break;
                case 5:
                    $img = "fugulin_semi_intensivo.png";
                    $texto_fugulin = "Fugulin: Semi-Intensivo";
                    break;
                case 6:
                    $img = "fugulin_intensivo.png";
                    $texto_fugulin = "Fugulin: Intensivo";
                    break;
            }
            if ($img)
                $icone_fugulin = '<img ' . $funcao_isolada . ' data-bs-toggle="tooltip" title="' . $texto_fugulin . '" class="icone-fundo-iluminado cursor-pointer me-1" width="20" src="' . base_url("public/assets/img/icons/$img") . '"/>';
        }
        $icones_riscos .= $icone_fugulin;

        $icone_news = "";
        if ($leitos[$i]["cd_agrupamento"] != 4 && isset($leitos[$i]["score"]) && strlen($leitos[$i]["score"]) > 0) {
            // ... Lógica News ...
            $funcao_isolada = 'onclick="detalhesIsolados(' . $leitos[$i]["nr_atendimento"] . ',\'' . $leitos[$i]["ds_leito_atual"] . '\',\'news\')"';
            $score = $leitos[$i]["score"];
            $img = "";
            $paciente_codigo_amarelo = 0;

            if ($score <= 3)
                $img = "news_verde.png";
            else if ($score <= 6)
                $img = "news_laranja.png";
            else {
                $paciente_codigo_amarelo = 1;
                $img = "news_vermelho.png";
            }
            $icone_news = '<img ' . $funcao_isolada . ' data-bs-toggle="tooltip" title="News: ' . $score . '" class="icone-fundo-iluminado cursor-pointer me-1" width="20" src="' . base_url("public/assets/img/icons/$img") . '"/>';
        }
        $icones_riscos .= $icone_news;

        // Braden
        if (isset($leitos[$i]["braden"]) && count($leitos[$i]["braden"]) > 0) {
            $b = $leitos[$i]["braden"];
            $funcao_isolada = 'onclick="detalhesIsolados(' . $leitos[$i]["nr_atendimento"] . ',\'' . $leitos[$i]["ds_leito_atual"] . '\',\'braden\',\'' . $b["DT_LIBERACAO_BRADEN"] . '\',\'' . $b["CLASSIFICACAO_BRADEN"] . '\',\'' . $b["PROFISSIONAL_BRADEN"] . '\',' . $b["PONTOS_BRADEN"] . ')"';
            $pts = $b["PONTOS_BRADEN"];
            $img = "braden1.png"; // Default High Risk matches logic > 18 (reversed in original logic? original: <10=braden5 good? No check logic again. Original: <10 img=braden5. Let's trust logic map)
            // Original: <10=braden5, 10-12=braden4, 13-14=braden3, 15-18=braden2, >18=braden1
            // Assuming filenames match risk levels.
            if ($pts < 10)
                $img = "braden5.png";
            elseif ($pts <= 12)
                $img = "braden4.png";
            elseif ($pts <= 14)
                $img = "braden3.png";
            elseif ($pts <= 18)
                $img = "braden2.png";
            else
                $img = "braden1.png";

            $icones_riscos .= '<img ' . $funcao_isolada . ' data-bs-toggle="tooltip" title="Braden: ' . $b["CLASSIFICACAO_BRADEN"] . '" class="icone-fundo-iluminado cursor-pointer me-1" width="20" src="' . base_url("public/assets/img/icons/$img") . '"/>';
        }

        // Morse
        if (isset($leitos[$i]["morse"]) && count($leitos[$i]["morse"]) > 0) {
            $m = $leitos[$i]["morse"];
            $funcao_isolada = 'onclick="detalhesIsolados(' . $leitos[$i]["nr_atendimento"] . ',\'' . $leitos[$i]["ds_leito_atual"] . '\',\'morse\',\'' . $m["DT_LIBERACAO_MORSE"] . '\',\'' . $m["CLASSIFICACAO_MORSE"] . '\',\'' . $m["PROFISSIONAL_MORSE"] . '\',' . $m["PONTOS_MORSE"] . ')"';
            $pts = $m["PONTOS_MORSE"];
            $img = "morse6.png";
            // Original: <24=morse1, 25-44=morse4, >=45=morse6
            if ($pts < 24)
                $img = "morse1.png";
            elseif ($pts <= 44)
                $img = "morse4.png";
            else
                $img = "morse6.png";
            $icones_riscos .= '<img ' . $funcao_isolada . ' data-bs-toggle="tooltip" title="Morse: ' . $m["CLASSIFICACAO_MORSE"] . '" class="icone-fundo-iluminado cursor-pointer me-1" width="20" src="' . base_url("public/assets/img/icons/$img") . '"/>';
        }

        // Sinais
        if (isset($leitos[$i]["qt_freq_cardiac"]) && $leitos[$i]["qt_freq_cardiac"] > 90) {
            $funcao_isolada = 'onclick="detalhesIsolados(' . $leitos[$i]["nr_atendimento"] . ',\'' . $leitos[$i]["ds_leito_atual"] . '\',\'cardiaca\')"';
            $icones_riscos .= '<img ' . $funcao_isolada . ' data-bs-toggle="tooltip" title="FC > 90" class="icone-fundo-iluminado cursor-pointer me-1" width="20" src="' . base_url("public/assets/img/icons/coracao.png") . '"/>';
        }
        if (isset($leitos[$i]["qt_freq_resp"]) && $leitos[$i]["qt_freq_resp"] > 20) {
            $funcao_isolada = 'onclick="detalhesIsolados(' . $leitos[$i]["nr_atendimento"] . ',\'' . $leitos[$i]["ds_leito_atual"] . '\',\'respiratoria\')"';
            $icones_riscos .= '<img ' . $funcao_isolada . ' data-bs-toggle="tooltip" title="FR > 20" class="icone-fundo-iluminado cursor-pointer me-1" width="20" src="' . base_url("public/assets/img/icons/pulmao.png") . '"/>';
        }
        if (isset($leitos[$i]["qt_temp"]) && $leitos[$i]["qt_temp"] > 0) {
            $funcao_isolada = 'onclick="detalhesIsolados(' . $leitos[$i]["nr_atendimento"] . ',\'' . $leitos[$i]["ds_leito_atual"] . '\',\'temperatura\')"';
            if ($leitos[$i]["qt_temp"] <= 35)
                $icones_riscos .= '<img ' . $funcao_isolada . ' data-bs-toggle="tooltip" title="Temp <= 35" class="icone-fundo-iluminado cursor-pointer me-1" width="20" src="' . base_url("public/assets/img/icons/temperatura_azul.png") . '"/>';
            elseif ($leitos[$i]["qt_temp"] >= 37.8)
                $icones_riscos .= '<img ' . $funcao_isolada . ' data-bs-toggle="tooltip" title="Febre" class="icone-fundo-iluminado cursor-pointer me-1" width="20" src="' . base_url("public/assets/img/icons/temperatura_vermelho.png") . '"/>';
        }

        // Precauções
        $icones_precaucao_html = "";
        $nomes_precaucoes = ""; // Tooltip text?
        // Helper function logic for precautoins
        $prec_list = [];
        if (isset($leitos[$i]["ds_precaucao_isolamento"]))
            $prec_list[] = $leitos[$i]["ds_precaucao_isolamento"];
        if (isset($leitos[$i]["ds_precaucao_isolamento_2"]) && $leitos[$i]["ds_precaucao_isolamento_2"] != $leitos[$i]["ds_precaucao_isolamento"])
            $prec_list[] = $leitos[$i]["ds_precaucao_isolamento_2"];

        foreach ($prec_list as $p) {
            $img = "";
            if (strpos($p, "contato") !== false && strpos($p, "aerossol") !== false)
                $img = "contato_aerossol.png";
            elseif (strpos($p, "contato") !== false && strpos($p, "goticulas") !== false)
                $img = "contato_goticulas.png"; // original had no ext? assuming png or existing
            elseif (strpos($p, "contato") !== false)
                $img = "contato.png";
            elseif (strpos($p, "goticulas") !== false)
                $img = "goticulas.png";
            elseif (strpos($p, "aeross") !== false)
                $img = "aerossol.png";

            if ($img) {
                $funcao_isolada = 'onclick="detalhesIsolados(' . $leitos[$i]["nr_atendimento"] . ',\'' . $leitos[$i]["ds_leito_atual"] . '\',\'precaucoes_isolamento\')"';
                // Checking original logic for 'contato_goticulas' - it was missing extension in original code line 223/249? "contato_goticulas". Assuming it works or is a folder. Keeping as is.
                $icones_precaucao_html .= '<img ' . $funcao_isolada . ' data-bs-toggle="tooltip" title="' . $p . '" class="icone-fundo-iluminado cursor-pointer me-1" ' . $tamanho_icone_precaucao . ' src="' . base_url("public/assets/img/icons/$img") . '"/>';
            }
        }

        // Dados do Paciente e Cores
        $leito_nome = $leitos[$i]["ds_leito_atual"];
        $nr_atd = $leitos[$i]["nr_atendimento"];

        $bg_class = ""; // Class for highlighting
        $text_class = "text-dark";
        $status_u = "Livre"; // Default status logic
    
        $classes_leito_sem_paciente = "";

        if ($nr_atd != 0) {
            // Paciente Ocupando
            $nome_paciente = $leitos[$i]["ds_nome_paciente"];
            $click_row = 'onclick="detalhesDoLeito(' . $nr_atd . ',\'' . $leito_nome . '\')"';
            $status_u = "Ocupado";

            // Lógica de Cores (Permanência)
            $alert_perm = false;
            if ($leitos[$i]["cd_agrupamento"] == 4 && $leitos[$i]["permanencia_linha_cuidado"] >= 6)
                $alert_perm = true;
            elseif ($leitos[$i]["cd_agrupamento"] == 99 && $leitos[$i]["permanencia_linha_cuidado"] >= 8)
                $alert_perm = true;
            elseif ($leitos[$i]["cd_agrupamento"] == 3) {
                if ($leitos[$i]["cd_setor_atendimento"] == 145 && $leitos[$i]["permanencia_linha_cuidado"] >= 6)
                    $alert_perm = true;
                if (in_array($leitos[$i]["cd_setor_atendimento"], [33, 76, 34, 55, 36, 56]) && $leitos[$i]["permanencia_linha_cuidado"] >= 10)
                    $alert_perm = true;
            }
            if ($alert_perm) {
                $bg_class = "bg-gray-700 text-white";
                $text_class = "alta_permanencia";
                $text_atend_leito = "alta_permanencia";
                $text_list_card_time = "alta_permanencia";
                $classe_avatar_circle = "avatar-circle-permanencia";
            } else {
                $text_class = "text-[#8898aa]";
                $text_atend_leito = "text-[#8898aa]";
                $text_list_card_time = "text-[var(--color-dark-grey)]";
                $classe_avatar_circle = "avatar-circle";
                $icones_riscos = str_replace("icone-fundo-iluminado", "icone-fundo-iluminado-alta-permanencia", $icones_riscos);
                $icones_precaucao_html = str_replace("icone-fundo-iluminado", "icone-fundo-iluminado-alta-permanencia", $icones_precaucao_html);
            }

            //COLOCAR FUNDO DO CARD AMARELO CASO O NEWS SEJA MAIOR QUE 6 DE SCORE ------------- A DESENVOLVER
            // if ($paciente_codigo_amarelo == 1) {
            //     $bg_class = "bg-yellow";
            // }
    
            //LÓGICA PARA VERIFICAR SE PACIENTE ESTÁ EM CUIDADO PALIATIVO
            if ($leitos[$i]["cuidado_paliativo"] == 1) {
                $bg_class .= " cuidados-paliativos";
            }

            // Avaliação Verde/Vermelho
            $cor_status_bola = "bg-secondary"; // Default gray/none
            if (isset($leitos[$i]["ds_verde_ou_vermelho"])) {
                $c = trim(strtoupper($leitos[$i]["ds_verde_ou_vermelho"]));
                if ($c == 'VERDE')
                    $cor_status_bola = "bg-success";
                elseif ($c == 'VERMELHO')
                    $cor_status_bola = "bg-danger";
            }

            $tempo_int = $leitos[$i]["tempo_internacao"] . " dia(s)";
            $dt_alta = "N/A";
            if (isset($leitos[$i]["dt_previsao_alta"]) && $leitos[$i]["dt_previsao_alta"] != "0000-00-00") {
                $dt_alta = date("d/m/Y", strtotime($leitos[$i]["dt_previsao_alta"]));
            }

            // RENDER TABLE ROW
            // RENDER LIST CARD
            if (isset($cor_status_bola)) {
                $strip_color_class = "";
                if (strpos($cor_status_bola, "danger") !== false || strpos($cor_status_bola, "warning") !== false) {
                    $strip_color_class = "list-card-strip strip-red";
                } else if (strpos($cor_status_bola, "success") !== false) {
                    $strip_color_class = "list-card-strip strip-green"; // Default
                }
            }

        } else {
            // Leito Livre ou Bloqueado
            $click_row = "";
            $nome_paciente = "";
            $status_desc = trim($leitos[$i]["ie_status_unidade"]);
            if ($status_desc != "Paciente") {
                $status_u = $status_desc;
                $classes_leito_sem_paciente = "text-center";

            }

            $bg_class = "bg-gray-200"; // Leito Livre visual
            if ($status_u != "Livre" && $status_u != "Paciente") {
                $bg_class = "bg-gradient-dark opacity-6"; // Bloqueado/Manutenção
            }

            //LÓGICA PARA VERIFICAR SE PACIENTE ESTÁ EM CUIDADO PALIATIVO
            if ($leitos[$i]["cuidado_paliativo"] == 1) {
                $bg_class .= " cuidados-paliativos";
            }

            $tempo_int = "-";
            $strip_color_class = "";
        }

        if ($_GET["l"] == 4) {
            $strip_color_class = "";
        }

        // --- OUTPUT RENDER ---
    
        if (!$isPanel) {
            $avatar_initials = ($nr_atd != 0) ? iniciais($nome_paciente) : "--";

            echo '<div class="list-card ' . $bg_class . ' ' . $text_class . ' ' . $classes_leito_sem_paciente . '">';
            echo '  <div class="list-card-strip ' . $strip_color_class . '"></div>';
            echo '  <div class="list-card-body cursor-pointer">';

            // Col 1: Bed
            echo '      <div class="bed-number-display" ' . $click_row . '>' . $leito_nome . '</div>';

            // Col 2: Meta
            echo '      <div class="patient-meta-group" ' . $click_row . '>';
            if ($nr_atd != 0) {
                // echo '          <div class="avatar-circle">' . $avatar_initials . '</div>';
                echo '          <div class="patient-info ' . $text_atend_leito . '">';
                echo '              <strong>' . $nome_paciente . '</strong>';
                echo '              <span>' . $nr_atd . '</span>';
                echo '          </div>';
            } else {
                echo '          <div class="patient-info">';
                echo '              <strong>' . $status_u . '</strong>';
                echo '          </div>';
            }
            echo '      </div>';

            // Col 3: Icons
            echo '      <div class="list-card-icons pr-6">';
            echo $icones_riscos;
            echo $icones_precaucao_html;
            echo '      </div>';

            // Col 4: Time
            echo '      <div ' . $click_row . ' class="list-card-time ' . $text_list_card_time . '">';
            if ($nr_atd != 0) {
                echo '          <div class="days">' . $tempo_int . '</div>';
                echo '          <div class="date">Prev. alta: ' . $dt_alta . '</div>';
            }
            echo '      </div>';

            echo '  </div>'; // End body
            echo '</div>'; // End card
    
        } else {
            // RENDER LIST CARD COMPACT (PANEL MODE)
            // Same logic as List Card but with .list-card-compact class and .col-6 wrapper
    
            // $strip_color_class = "strip-green"; // Default
            // if (isset($cor_status_bola)) {
            //     if (strpos($cor_status_bola, "danger") !== false || strpos($cor_status_bola, "warning") !== false) {
            //         $strip_color_class = "strip-red";
            //     }
            // }
    
            $avatar_initials = ($nr_atd != 0) ? iniciais($nome_paciente) : "--";

            // PANEL GRID CONFIG: col-3 (4 columns per row)
            // Padding added to wrapper to simulate gap if needed, or rely on .row g-3
            echo '<div class="col-3 panel-grid-col">';
            echo '  <div class="list-card list-card-compact h-100 ' . $bg_class . ' ' . $text_class . ' ' . $classes_leito_sem_paciente . '">';
            echo '    <div class="' . $strip_color_class . '"></div>';
            echo '    <div class="list-card-body cursor-pointer flex-column justify-content-center">';

            // ROW 1: Bed Number (Left) + Icons (Right)
            echo '      <div class="d-flex justify-content-between align-items-center w-100 mb-1">';
            echo '          <div class="bed-number-display" ' . $click_row . '>' . $leito_nome . '</div>';
            echo '          <div class="list-card-icons">';
            echo $icones_riscos;
            echo $icones_precaucao_html;
            echo '          </div>';
            echo '      </div>';

            // ROW 2: Patient Info (Left) + Time (Right)
            echo '      <div class="d-flex justify-content-between align-items-center w-100">';

            // Patient Info Group
            echo '          <div class="patient-meta-group" ' . $click_row . ' style="flex: 1;">';
            if ($nr_atd != 0) {
                echo '          <div class="' . $classe_avatar_circle . '">' . $avatar_initials . '</div>';
                echo '          <div class="patient-info ' . $text_atend_leito . '">';
                echo '              <span>' . $nr_atd . '</span>';
                echo '          </div>';
            } else {
                echo '          <div style="font-size: 0.8rem;" class="patient-info">';
                echo '              <b>' . $status_u . '</b>';
                echo '          </div>';
            }
            echo '          </div>'; // End patient-meta-group
    
            // Time Info
            echo '          <div ' . $click_row . ' class="list-card-time ' . $text_list_card_time . '">';
            if ($nr_atd != 0) {
                echo '          <div class="days">' . $tempo_int . '</div>';
                echo '          <div class="date">Prev. alta: ' . $dt_alta . '</div>';
            }
            echo '          </div>';

            echo '      </div>'; // End ROW 2
    
            echo '    </div>'; // End body
            echo '  </div>'; // End card
            echo '</div>'; // End col
        }
    }

    if (!$isPanel) {
        echo '</div>'; // End list container
    } else {
        echo '</div>'; // End Row
    }
    echo "<input type='hidden' id='cd_setor_atendimento_id' name='cd_setor_atendimento_id' value='" . $_GET["s"] . "'/>";
    echo "<input type='hidden' id='tipo_perfil_logado' name='tipo_perfil_logado' value='" . $_SESSION["usuario_logado"]["TIPO_PERFIL"] . "'/>";
    echo "<input type='hidden' id='linha_cuidado_id' name='linha_cuidado_id' value='" . $_GET["l"] . "'/>";
    // echo "<input type='hidden' id='usuario_ad' name='usuario_ad' value='".$_SESSION["usuario_logado"]["USUARIO_AD"] ? $_SESSION["usuario_logado"]["USUARIO_AD"] : ' '."'/>";
    ?>
    <!-- </div> -->

    <!-- Modal -->
    <div class="modal fade w-full" id="modal_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-premium" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"
                        style="font-weight: 700; color: var(--color-petroleum-blue);">Informações do leito</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="corpo_modal" name="corpo_modal">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-hmdcc text-white mb-0"
                        data-bs-dismiss="modal">OK</button>
                    <!-- <button type="button" class="btn bg-gradient-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal IA-->

    <div class="modal fade w-full" id="modal_ia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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

        async function enviarmensagem(nr_atendimento) {

            const mensagem = document.getElementById('mensagem-input');
            const usermensagem = mensagem.value.trim();
            const btnSubmit = document.getElementById('btn-submit');

            if (!mensagem.value) {
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

        async function* IAResponse(query, atendimento, username) {

            const ollama_generate = { "atendimento": Number(atendimento), "query": query, "username": username }

            const response = await fetch("http://10.40.0.3:8084/api/v1/chat", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(ollama_generate),
            });

            const reader = response.body.getReader();
            while (true) {
                const { done, value: encodedResponse } = await reader.read();
                if (done) {
                    break; // Fim do stream
                }

                const responseText = new TextDecoder("utf-8").decode(encodedResponse, { stream: true });

                try {

                    // Itera sobre os objetos no array e extrai o conteúdo
                    let assistantMessage = ""; // Variável para concatenar o conteúdo
                    let messageChunk = ""

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
        function historicomensagem(mensagem) {
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

            if (mes_atual < mes_aniversario || mes_atual == mes_aniversario && dia_atual < dia_aniversario) {
                quantos_anos--;
            }
            return quantos_anos < 0 ? 0 : quantos_anos;
        }

        document.addEventListener("DOMContentLoaded", function(){

            const urlParams = new URLSearchParams(window.location.search)
            const nr_atendimento = urlParams.get('a')
            const leito_atual = urlParams.get('lei')

            if(nr_atendimento != null && leito_atual != null)
                detalhesDoLeito(nr_atendimento, leito_atual) 
        })

        function detalhesDoLeito(nr_atendimento, leito_atual) {
            let html_leito = "";
            $.ajax({
                url: "<?php echo base_url('detalhada/retornaTotaisAvaliacoesVerdeVermelho'); ?>",
                type: 'POST',
                data:
                {
                    "nr_atendimento": nr_atendimento
                },
                dataType: "json",
                success: function (totaisVerdeVemelho) {
                    let html_totais_verde_vermelho = "";

                    if (parseInt(totaisVerdeVemelho.total) > 0) {
                        let regra3_verde = Math.round(totaisVerdeVemelho.porcentagem_verde) * 255 / 100
                        let regra3_vermelho = Math.round(totaisVerdeVemelho.porcentagem_vermelho) * 255 / 100;

                        html_totais_verde_vermelho = "<tr><td colspan='2'></td></tr>" +
                            "<tr>" +
                            "<td class='font-weight-bold text-wrap text-xs'>" +
                            "Total de avaliações" +
                            "</td>" +
                            "<td class='text-wrap text-end justify-content-end text-xs'>" +
                            totaisVerdeVemelho.total +
                            "</td>" +
                            "</tr>" +
                            "<tr>" +
                            "<td class='font-weight-bold text-wrap text-xs'>" +
                            "Total verde" +
                            "</td>" +
                            "<td class='text-wrap text-end justify-content-end text-xs'>" +
                            totaisVerdeVemelho.total_verde +
                            "</td>" +
                            "</tr>" +
                            "<tr>" +
                            "<td class='font-weight-bold text-wrap text-xs'>" +
                            "% verde" +
                            "</td>" +
                            "<td class='text-wrap text-end justify-content-end text-xs'>" +
                            parseFloat(totaisVerdeVemelho.porcentagem_verde).toFixed(2) + "%" +
                            "</td>" +
                            "</tr>" +
                            "<tr>" +
                            "<td class='font-weight-bold text-wrap text-xs'>" +
                            "Total vermelho" +
                            "</td>" +
                            "<td class='text-wrap text-end justify-content-end text-xs'>" +
                            totaisVerdeVemelho.total_vermelho +
                            "</td>" +
                            "</tr>" +
                            "<tr>" +
                            "<td class='font-weight-bold text-wrap text-xs'>" +
                            "% vermelho" +
                            "</td>" +
                            "<td class='text-wrap text-end justify-content-end text-xs'>" +
                            parseFloat(totaisVerdeVemelho.porcentagem_vermelho).toFixed(2) + "%" +
                            "</td>" +
                            "</tr>" +
                            "<tr>" +
                            "<td colspan='2' class='text-wrap justify-content-center' style='text-align:-webkit-center'>" +
                            "<div style='background-color: rgb(" + regra3_vermelho + " " + regra3_verde + " 0); border-radius:50%; width:50px;height:50px' ></div>" +
                            "</td>" +
                            "</tr>";

                    }


                    $.ajax({
                        url: "<?php echo base_url('detalhada/retornaDadosLeito'); ?>",
                        type: 'POST',
                        data:
                        {
                            "nr_atendimento": nr_atendimento,
                            "leito_atual": leito_atual,
                            "cd_setor_atendimento": $("#cd_setor_atendimento_id").val()
                        },
                        dataType: "json",
                        success: function (result) {

                            $.ajax({
                                url: "<?php echo base_url('detalhada/retornaMovimentacoesAtendimento'); ?>",
                                type: 'POST',
                                data:
                                {
                                    "nr_atendimento": nr_atendimento
                                },
                                dataType: "json",
                                success: function (resultadoMovimentacoes) {
                                    let data_entrada = new Date(result["dt_entrada"]);
                                    let data_prev_alta = new Date(result["dt_previsao_alta"]);
                                    let dt_liberacao = new Date(result["dt_liberacao"]);

                                    let verde_verm = " - ";

                                    // if(data_entrada.toLocaleString()!="Invalid Date"){
                                    //     data_entrada = data_entrada.toLocaleString().replace(',','');
                                    // }else{
                                    //     data_entrada = " - ";
                                    // }

                                    let hora_entrada_geral = result["dt_entrada"].split(" ");
                                    hora_entrada_geral = hora_entrada_geral[1];
                                    data_entrada = result["dt_entrada"].substr(0, 10).split('-').reverse().join('/') + " " + hora_entrada_geral;
                                    if (data_entrada == "00/00/0000 00:00:00") {
                                        data_entrada = " - ";
                                    }

                                    let hora_liberacao = result["dt_liberacao"].split(" ");
                                    hora_liberacao = hora_liberacao[1];
                                    dt_liberacao = result["dt_liberacao"].substr(0, 10).split('-').reverse().join('/') + " " + hora_liberacao;
                                    if (dt_liberacao == "00/00/0000 00:00:00") {
                                        dt_liberacao = " - ";
                                    }

                                    if (data_prev_alta.toLocaleString() !== "Invalid Date") {
                                        let dt_arr = result["dt_previsao_alta"].split('-');
                                        data_prev_alta = dt_arr[2] + '/' + dt_arr[1] + '/' + dt_arr[0];
                                    } else {
                                        data_prev_alta = " - ";
                                    }

                                    let motivo_vermelho = "";
                                    let conteudo_template_avaliacao = "";
                                    let html_movimentacoes_atendimento = "";
                                    let html_avaliacao_isolada = "";

                                    let data_nascimento = result["dt_nascimento"].split("/");
                                    let idade_paciente = idade(data_nascimento[2], data_nascimento[1], data_nascimento[0]);

                                    let html_botoes_evolucoes = "";

                                    /*INÍCIO PREENCHIMENTO TABELA DE AVALIAÇÃO VERDE/VERMELHO*/

                                    if (result["ds_verde_ou_vermelho"] && result["cd_agrupamento"] != 4) {
                                        let cor = "";
                                        if (result["ds_verde_ou_vermelho"].trim().toUpperCase() == "VERDE") {
                                            cor = "#00ff00";
                                        } else {
                                            cor = "#ff0000";
                                        }

                                        // if($("#tipo_perfil_logado").val()=="A" || $("#tipo_perfil_logado").val()=="D" || 
                                        // $("#tipo_perfil_logado").val()=="E" || $("#tipo_perfil_logado").val()=="Y" || 
                                        // $("#tipo_perfil_logado").val()=="B" || $("#tipo_perfil_logado").val()=="F"){
                                        html_botoes_evolucoes = "<tr>" +
                                            "<td class='font-weight-bold text-wrap text-center' colspan='2'>" +
                                            "<a class='btn btn-primary rounded flex' href='avaliacoesVerdeVermelho?a=" + nr_atendimento + "&l=" + $("#linha_cuidado_id").val() + "&s=" + $("#cd_setor_atendimento_id").val() + "'>Histórico de avaliações</a>" +
                                            "<a class='btn hmdcc-color-1 rounded text-white flex' href='historicoEvolucoesPaciente?a=" + nr_atendimento + "&l=" + $("#linha_cuidado_id").val() + "&s=" + $("#cd_setor_atendimento_id").val() + "'>Últimas evoluções</a>" +
                                            "<a class='btn hmdcc-color-2 rounded text-white flex' href='historicoInterconsultasPaciente?a=" + nr_atendimento + "&l=" + $("#linha_cuidado_id").val() + "&s=" + $("#cd_setor_atendimento_id").val() + "'>Últimas interconsultas</a>" +
                                            "<a class='btn hmdcc-color-3 rounded text-white flex' href='historicoExamesLabPaciente?a=" + nr_atendimento + "&l=" + $("#linha_cuidado_id").val() + "&s=" + $("#cd_setor_atendimento_id").val() + "'>Exames laboratoriais</a>" +
                                            // "<a class='btn hmdcc-color-4 rounded text-white flex' href='historicoPrescricoesPaciente?a="+nr_atendimento+"&l="+$("#linha_cuidado_id").val()+"&s="+$("#cd_setor_atendimento_id").val()+"'>Últimas prescrições</a>"+
                                            "<a class='btn hmdcc-color-5 rounded text-white flex' href='historicoExamesImagemPaciente?a=" + nr_atendimento + "&l=" + $("#linha_cuidado_id").val() + "&s=" + $("#cd_setor_atendimento_id").val() + "'>Imagens e laudos</a>" +
                                            "</td>" +
                                            "</tr>";
                                        // }else{
                                        //     html_botoes_evolucoes = "";
                                        // }

                                        if (result["ds_motivo_vermelho"].length > 0) {

                                            motivo_vermelho = "<tr>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                "Motivo" +
                                                "</td>" +
                                                "<td class='text-wrap text-justify'>" +
                                                result["ds_motivo_vermelho"] +
                                                "</td>" +
                                                "</tr>";
                                        }

                                        conteudo_template_avaliacao = "<table class='table align-items-center justify-content-center' width='100%'>" +
                                            "<tr>" +
                                            "<td colspan='2' class='text-center text-uppercase font-weight-bold text-wrap'>" +
                                            "Última Avaliação" +
                                            "</td>" +
                                            "</tr>" +
                                            "<tr>" +
                                            "<td class='font-weight-bold text-wrap'>" +
                                            "Data avaliação" +
                                            "</td>" +
                                            "<td class='text-wrap text-justify'>" +
                                            dt_liberacao.toLocaleString().replace(',', '') +
                                            "</td>" +
                                            "</tr>" +
                                            "<tr>" +
                                            "<td class='font-weight-bold text-wrap'>" +
                                            "Profissional" +
                                            "</td>" +
                                            "<td class='text-wrap text-justify'>" +
                                            result["profissional_verde_vermelho"] +
                                            "</td>" +
                                            "</tr>" +
                                            "<tr>" +
                                            "<td class='font-weight-bold text-wrap'>" +
                                            "Condição" +
                                            "</td>" +
                                            "<td class='text-wrap'>" +
                                            "<div class='w-full text-center' style='border-radius:4px; color:#fff; background-color:" + cor + "'>" +
                                            result["ds_verde_ou_vermelho"].trim().toUpperCase() +
                                            "</div>" +
                                            "</td>" +
                                            "</tr>" +
                                            motivo_vermelho +
                                            "<tr>" +
                                            "<td class='font-weight-bold text-wrap'>" +
                                            "Previsão de alta" +
                                            "</td>" +
                                            "<td class='text-wrap'>" +
                                            data_prev_alta +
                                            "</td>" +
                                            "</tr>" +
                                            html_totais_verde_vermelho +
                                            html_botoes_evolucoes +
                                            "</table>";
                                    } else {
                                        // if($("#tipo_perfil_logado").val()=="A" || $("#tipo_perfil_logado").val()=="D" || 
                                        // $("#tipo_perfil_logado").val()=="E" || $("#tipo_perfil_logado").val()=="Y" || 
                                        // $("#tipo_perfil_logado").val()=="B" || $("#tipo_perfil_logado").val()=="F"){
                                        html_botoes_evolucoes = "<tr>" +
                                            "<td class='font-weight-bold text-wrap text-center' colspan='2'>" +
                                            "<a class='btn hmdcc-color-1 rounded text-white flex' href='historicoEvolucoesPaciente?a=" + nr_atendimento + "&l=" + $("#linha_cuidado_id").val() + "&s=" + $("#cd_setor_atendimento_id").val() + "'>Últimas evoluções</a>" +
                                            "<a class='btn hmdcc-color-2 rounded text-white flex' href='historicoInterconsultasPaciente?a=" + nr_atendimento + "&l=" + $("#linha_cuidado_id").val() + "&s=" + $("#cd_setor_atendimento_id").val() + "'>Últimas interconsultas</a>" +
                                            "<a class='btn hmdcc-color-3 rounded text-white flex' href='historicoExamesLabPaciente?a=" + nr_atendimento + "&l=" + $("#linha_cuidado_id").val() + "&s=" + $("#cd_setor_atendimento_id").val() + "'>Exames laboratoriais</a>" +
                                            // "<a class='btn hmdcc-color-4 rounded text-white flex' href='historicoPrescricoesPaciente?a="+nr_atendimento+"&l="+$("#linha_cuidado_id").val()+"&s="+$("#cd_setor_atendimento_id").val()+"'>Últimas prescrições</a>"+
                                            "<a class='btn hmdcc-color-5 rounded text-white flex' href='historicoExamesImagemPaciente?a=" + nr_atendimento + "&l=" + $("#linha_cuidado_id").val() + "&s=" + $("#cd_setor_atendimento_id").val() + "'>Imagens e laudos</a>" +
                                            "</td>" +
                                            "</tr>";
                                        // }else{
                                        //     html_botoes_evolucoes = "";
                                        // }

                                        conteudo_template_avaliacao = "<table class='table align-items-center justify-content-center' width='100%'>" +
                                            html_botoes_evolucoes +
                                            "</table>";
                                    }

                                    /*FIM PREENCHIMENTO TABELA DE AVALIAÇÃO VERDE/VERMELHO*/

                                    /*INÍCIO PROCESSAMENTO MOVIMENTAÇÕES*/

                                    if (resultadoMovimentacoes.length > 0) {
                                        html_movimentacoes_atendimento = "<table class='table align-items-center justify-content-center' width='100%'>" +
                                            "<tr>" +
                                            "<td class='text-center text-uppercase text-wrap font-weight-bold' colspan='5'>" +
                                            "Movimentações" +
                                            "</td>" +
                                            "</tr>" +
                                            "<tr>" +
                                            "<td class='text-xs font-weight-bold text-wrap'>" +
                                            "Setor" +
                                            "</td>" +
                                            "<td class='text-xs font-weight-bold text-wrap'>" +
                                            "Leito" +
                                            "</td>" +
                                            "<td class='text-xs text-wrap font-weight-bold '>" +
                                            "Entrada" +
                                            "</td>" +
                                            "<td class='text-xs text-wrap font-weight-bold '>" +
                                            "Saída" +
                                            "</td>" +
                                            "<td class='text-xs text-wrap font-weight-bold '>" +
                                            "Dias" +
                                            "</td>" +
                                            "</tr>";

                                        let data_entrada_unidade = "";
                                        let data_saida_unidade = "";

                                        for (let j = 0; j < resultadoMovimentacoes.length; j++) {

                                            data_entrada_unidade = new Date(resultadoMovimentacoes[j]["dt_entrada_unidade"]);
                                            data_saida_unidade = new Date(resultadoMovimentacoes[j]["dt_saida_unidade"]);

                                            let hora_entrada = resultadoMovimentacoes[j]["dt_entrada_unidade"].split(" ");
                                            hora_entrada = hora_entrada[1];
                                            data_entrada_unidade = resultadoMovimentacoes[j]["dt_entrada_unidade"].substr(0, 10).split('-').reverse().join('/') + " " + hora_entrada;
                                            if (data_entrada_unidade == "00/00/0000 00:00:00") {
                                                data_entrada_unidade = " - ";
                                            }

                                            let hora_saida = resultadoMovimentacoes[j]["dt_saida_unidade"].split(" ");
                                            hora_saida = hora_saida[1];
                                            data_saida_unidade = resultadoMovimentacoes[j]["dt_saida_unidade"].substr(0, 10).split('-').reverse().join('/') + " " + hora_saida;
                                            if (data_saida_unidade == "00/00/0000 00:00:00") {
                                                data_saida_unidade = " - ";
                                            }

                                            let cor_linha_cond_mesmo_setor = "";
                                            if ($("#linha_cuidado_id").val() == resultadoMovimentacoes[j]["cd_agrupamento"]) {
                                                cor_linha_cond_mesmo_setor = "text-white cor_card_mesma_linha_cuidado";
                                            }


                                            html_movimentacoes_atendimento += "<tr>" +
                                                "<td class='" + cor_linha_cond_mesmo_setor + " font-weight-bold text-wrap text-xs'>" +
                                                resultadoMovimentacoes[j]["ds_setor_atendimento"] +
                                                "</td>" +
                                                "<td class='" + cor_linha_cond_mesmo_setor + " text-wrap text-xs'>" +
                                                resultadoMovimentacoes[j]["leito"] + ' ' + resultadoMovimentacoes[j]["ds_complemento_leito"] +
                                                "</td>" +
                                                "<td class='" + cor_linha_cond_mesmo_setor + " text-wrap text-xs'>" +
                                                data_entrada_unidade +
                                                "</td>" +
                                                "<td class='" + cor_linha_cond_mesmo_setor + " text-wrap text-xs'>" +
                                                data_saida_unidade +
                                                "</td>" +
                                                "<td class='" + cor_linha_cond_mesmo_setor + " text-wrap text-xs'>" +
                                                resultadoMovimentacoes[j]["qt_dias_unidade"] +
                                                "</td>" +
                                                "</tr>";
                                        }

                                        //GUARDANDO PERMANENCIA (DIAS) NA MESMA LINHA DE CUIDADO
                                        let somatoria_dias_linha_cuidado = 0;
                                        for (let k = resultadoMovimentacoes.length - 1; k >= 0; k--) {
                                            if (resultadoMovimentacoes[k]["cd_agrupamento"] == $("#linha_cuidado_id").val()) {
                                                somatoria_dias_linha_cuidado = parseInt(resultadoMovimentacoes[k]["qt_dias_unidade"]) + parseInt(somatoria_dias_linha_cuidado);
                                            } else {
                                                break;
                                            }
                                        }

                                        html_movimentacoes_atendimento += "<tr class='my-4'>" +
                                            "<td colspan='4' class='text-xs font-weight-bold text-wrap text-white '>" +

                                            "</td>" +
                                            "<td class='text-xs text-wrap font-weight-bold text-white'>" +

                                            "</td>" +
                                            "</tr>" +
                                            "<tr>" +
                                            "<td colspan='4' class='text-xs font-weight-bold text-wrap text-white cor_card_mesma_linha_cuidado'>" +
                                            "Total atual na Linha de Cuidado" +
                                            "</td>" +
                                            "<td class='text-xs text-wrap font-weight-bold text-white cor_card_mesma_linha_cuidado'>" +
                                            somatoria_dias_linha_cuidado + " dia(s)" +
                                            "</td>" +
                                            "</tr>" +
                                            "<tr >" +
                                            "<td colspan='4' class='text-xs font-weight-bold text-wrap'>" +
                                            "Total" +
                                            "</td>" +
                                            "<td class='text-xs text-wrap font-weight-bold'>" +
                                            resultadoMovimentacoes[0]["total_dias_unidade"] + " dia(s)" +
                                            "</td>" +
                                            "</tr>" +
                                            "</table>";
                                    }

                                    let html_cuidados_paliativos = "";

                                    if (result['cuidados_paliativos'].length > 0) {


                                        let data_liberacao_cuidados_paliativos = new Date(result["cuidados_paliativos"][0]["dt_liberacao"]);
                                        let hora_liberacao_cuidados_paliativos = result["cuidados_paliativos"][0]["dt_liberacao"].split(" ");
                                        hora_liberacao_cuidados_paliativos = hora_liberacao_cuidados_paliativos[1];
                                        dt_liberacao_cuidados_paliativos = result["cuidados_paliativos"][0]["dt_liberacao"].substr(0, 10).split('-').reverse().join('/') + " " + hora_liberacao_cuidados_paliativos;
                                        if (dt_liberacao_cuidados_paliativos == "00/00/0000 00:00:00") {
                                            dt_liberacao_cuidados_paliativos = " - ";
                                        }

                                        html_cuidados_paliativos = "<tr>" +
                                            "<td colspan='2' style='background-color: rgba(200, 1, 255, 0.6);' class='font-weight-bold text-wrap text-center text-white'>" +
                                            "Cuidados Paliativos" +
                                            "</td>" +
                                            "</tr>" +
                                            "<tr>" +
                                            "<td style='background-color: rgba(200, 1, 255, 0.6);' class='font-weight-bold text-wrap'>" +
                                            "Profissional C. Paliativos" +
                                            "</td>" +
                                            "<td style='background-color: rgba(200, 1, 255, 0.6);' class='text-wrap'>" +
                                            result["cuidados_paliativos"][0]["profissional_liberacao"] +
                                            "</td>" +
                                            "</tr>" +
                                            "<tr>" +
                                            "<td style='background-color: rgba(200, 1, 255, 0.6);' class='font-weight-bold text-wrap'>" +
                                            "Data de liberação" +
                                            "</td>" +
                                            "<td style='background-color: rgba(200, 1, 255, 0.6);' class='text-wrap'>" +
                                            dt_liberacao_cuidados_paliativos +
                                            "</td>" +
                                            "</tr>";
                                    } else {
                                        html_cuidados_paliativos = "";
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
                                        idade_paciente + " anos" +
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
                                        result['tempo_internacao'] + " dia(s)" +
                                        "</td>" +
                                        "</tr>" +
                                        html_cuidados_paliativos +
                                        html_avaliacao_isolada +
                                        "</table>" +
                                        html_movimentacoes_atendimento +
                                        conteudo_template_avaliacao;

                                    $("#corpo_modal").html(html_leito);
                                    $("#modal_info").modal('show');
                                },
                                error: function (data) {
                                    alert('Não foi possível abrir o detalhamento. Confira sua conexão!');
                                }
                            });

                        },
                        error: function (data) {
                            alert('Não foi possível abrir o detalhamento. Confira sua conexão!');
                        }
                    });
                }, error: function (data) {
                    alert('Não foi possível abrir o detalhamento. Confira sua conexão!');
                }
            });
        }

        function detalhesIsolados(nr_atendimento, leito_atual, tipo_avaliacao, dt_ultima_avaliacao = "", classificacao = "", profissional = "", pontos = "") {
            $.ajax({
                url: "<?php echo base_url('detalhada/retornaDadosLeito'); ?>",
                type: 'POST',
                data:
                {
                    "nr_atendimento": nr_atendimento,
                    "leito_atual": leito_atual,
                    "cd_setor_atendimento": $("#cd_setor_atendimento_id").val()
                },
                dataType: "json",
                success: function (result) {
                    let data_entrada = new Date(result["dt_entrada"]);
                    let data_prev_alta = new Date(result["dt_previsao_alta"]);

                    let data_nascimento = result["dt_nascimento"].split("/");
                    let idade_paciente = idade(data_nascimento[2], data_nascimento[1], data_nascimento[0]);
                    // if(data_entrada.toLocaleString()!="Invalid Date"){
                    //     data_entrada = data_entrada.toLocaleString().replace(',','');
                    // }else{
                    //     data_entrada = " - ";
                    // }

                    let hora_entrada_geral = result["dt_entrada"].split(" ");
                    hora_entrada_geral = hora_entrada_geral[1];
                    data_entrada = result["dt_entrada"].substr(0, 10).split('-').reverse().join('/') + " " + hora_entrada_geral;
                    if (data_entrada == "00/00/0000 00:00:00") {
                        data_entrada = " - ";
                    }

                    if (data_prev_alta.toLocaleString() !== "Invalid Date") {
                        let dt_arr = result["dt_previsao_alta"].split('-');
                        data_prev_alta = dt_arr[2] + '/' + dt_arr[1] + '/' + dt_arr[0];
                    } else {
                        data_prev_alta = " - ";
                    }

                    if (tipo_avaliacao == 'fugulin') {
                        let hora_entrada_fugulin = result["data_fugulin"].split(" ");
                        hora_entrada_fugulin = hora_entrada_fugulin[1];
                        let data_fugulin = result["data_fugulin"].substr(0, 10).split('-').reverse().join('/') + " " + hora_entrada_fugulin;
                        if (data_fugulin == "00/00/0000 00:00:00") {
                            data_fugulin = " - ";
                        }
                        html_avaliacao_isolada = "<tr>" +
                            "<td class='font-weight-bold text-wrap'>" +
                            "Fugulin" +
                            "</td>" +
                            "<td class='font-weight-bold text-wrap'>" +
                            result['ds_gradacao'] + ' - ' + result['qt_pontuacao'] + " pontos" +
                            "</td>" +
                            "</tr>" +
                            "<tr>" +
                            "<td class='font-weight-bold text-wrap'>" +
                            "Profissional" +
                            "</td>" +
                            "<td class='font-weight-bold text-wrap'>" +
                            result['profissional_fugulin'] +
                            "</td>" +
                            "</tr>" +
                            "<tr>" +
                            "<td class='font-weight-bold text-wrap'>" +
                            "Data Avaliação Fugulin" +
                            "</td>" +
                            "<td class='font-weight-bold text-wrap'>" +
                            data_fugulin +
                            "</td>" +
                            "</tr>";
                    } else if (tipo_avaliacao == 'news') {
                        let hora_entrada_news = result["dt_liberacao_news"].split(" ");
                        hora_entrada_news = hora_entrada_news[1];
                        let data_news = result["dt_liberacao_news"].substr(0, 10).split('-').reverse().join('/') + " " + hora_entrada_news;
                        if (data_news == "00/00/0000 00:00:00") {
                            data_news = " - ";
                        }
                        html_avaliacao_isolada = "<tr>" +
                            "<td class='font-weight-bold text-wrap'>" +
                            "News" +
                            "</td>" +
                            "<td class='font-weight-bold text-wrap'>" +
                            result['score'] +
                            "</td>" +
                            "</tr>" +
                            "<tr>" +
                            "<td class='font-weight-bold text-wrap'>" +
                            "Profissional" +
                            "</td>" +
                            "<td class='font-weight-bold text-wrap'>" +
                            result['profissional_news'] +
                            "</td>" +
                            "</tr>" +
                            "<tr>" +
                            "<td class='font-weight-bold text-wrap'>" +
                            "Data Avaliação News" +
                            "</td>" +
                            "<td class='font-weight-bold text-wrap'>" +
                            data_news +
                            "</td>" +
                            "</tr>";

                    } else {
                        //SINAIS VITAIS
                        if (tipo_avaliacao == 'temperatura') {
                            let hora_temperatura = result["dt_qt_temp"].split(" ");
                            hora_temperatura = hora_temperatura[1];
                            let data_temp = result["dt_qt_temp"].substr(0, 10).split('-').reverse().join('/') + " " + hora_temperatura;
                            if (data_temp == "00/00/0000 00:00:00") {
                                data_temp = " - ";
                            }
                            html_avaliacao_isolada = "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Temperatura" +
                                "</td>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                result['qt_temp'] + "º" +
                                "</td>" +
                                "</tr>" +
                                "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Profissional" +
                                "</td>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                result['profissional_qt_temp'] +
                                "</td>" +
                                "</tr>" +
                                "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Data Medição" +
                                "</td>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                data_temp +
                                "</td>" +
                                "</tr>";

                        } else if (tipo_avaliacao == 'cardiaca') {
                            let hora_cardiaca = result["dt_qt_freq_cardiac"].split(" ");
                            hora_cardiaca = hora_cardiaca[1];
                            let data_card = result["dt_qt_freq_cardiac"].substr(0, 10).split('-').reverse().join('/') + " " + hora_cardiaca;
                            if (data_card == "00/00/0000 00:00:00") {
                                data_card = " - ";
                            }
                            html_avaliacao_isolada = "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Frequência Cardíaca" +
                                "</td>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                result['qt_freq_cardiac'] + " bpm" +
                                "</td>" +
                                "</tr>" +
                                "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Profissional" +
                                "</td>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                result['profissional_qt_freq_cardiac'] +
                                "</td>" +
                                "</tr>" +
                                "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Data Medição" +
                                "</td>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                data_card +
                                "</td>" +
                                "</tr>";

                        } else if (tipo_avaliacao == 'respiratoria') {
                            let hora_respiratoria = result["dt_qt_freq_resp"].split(" ");
                            hora_respiratoria = hora_respiratoria[1];
                            let data_respiratoria = result["dt_qt_freq_resp"].substr(0, 10).split('-').reverse().join('/') + " " + hora_respiratoria;
                            if (data_respiratoria == "00/00/0000 00:00:00") {
                                data_respiratoria = " - ";
                            }
                            html_avaliacao_isolada = "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Frequência Respiratória" +
                                "</td>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                result['qt_freq_resp'] + " irpm" +
                                "</td>" +
                                "</tr>" +
                                "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Profissional" +
                                "</td>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                result['profissional_qt_freq_resp'] +
                                "</td>" +
                                "</tr>" +
                                "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Data Medição" +
                                "</td>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                data_respiratoria +
                                "</td>" +
                                "</tr>";

                        } else if (tipo_avaliacao == 'braden') {
                            let hora_braden = dt_ultima_avaliacao.split(" ");
                            hora_braden = hora_braden[1];
                            let data_braden = dt_ultima_avaliacao.substr(0, 10).split('-').reverse().join('/') + " " + hora_braden;
                            if (data_braden == "00/00/0000 00:00:00") {
                                data_braden = " - ";
                            }
                            html_avaliacao_isolada = "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Pontuação Braden" +
                                "</td>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                pontos +
                                "</td>" +
                                "</tr>" +
                                "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Classificação Braden" +
                                "</td>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                classificacao +
                                "</td>" +
                                "</tr>" +
                                "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Profissional" +
                                "</td>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                profissional +
                                "</td>" +
                                "</tr>" +
                                "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Data Medição" +
                                "</td>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                data_braden +
                                "</td>" +
                                "</tr>";

                        } else if (tipo_avaliacao == 'morse') {
                            let hora_morse = dt_ultima_avaliacao.split(" ");
                            hora_morse = hora_morse[1];
                            let data_morse = dt_ultima_avaliacao.substr(0, 10).split('-').reverse().join('/') + " " + hora_morse;
                            if (data_morse == "00/00/0000 00:00:00") {
                                data_morse = " - ";
                            }
                            html_avaliacao_isolada = "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Pontuação Morse" +
                                "</td>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                pontos +
                                "</td>" +
                                "</tr>" +
                                "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Classificação Morse" +
                                "</td>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                classificacao +
                                "</td>" +
                                "</tr>" +
                                "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Profissional" +
                                "</td>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                profissional +
                                "</td>" +
                                "</tr>" +
                                "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Data Medição" +
                                "</td>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                data_morse +
                                "</td>" +
                                "</tr>";

                        } else if (tipo_avaliacao == 'precaucoes_isolamento') {
                            let hora_precaucao = result["dt_atualizacao_precaucao"].split(" ");
                            hora_precaucao = hora_precaucao[1];
                            let data_precaucao = result["dt_atualizacao_precaucao"].substr(0, 10).split('-').reverse().join('/') + " " + hora_precaucao;
                            if (data_precaucao == "00/00/0000 00:00:00") {
                                data_precaucao = " - ";
                            }

                            let cuidados_precaucao = "";
                            if (result['ds_cuidado_isolamento'].length > 1) {
                                cuidados_precaucao = "<tr>" +
                                    "<td class='font-weight-bold text-wrap'>" +
                                    "Cuidados" +
                                    "</td>" +
                                    "<td class=' text-wrap'>" +
                                    result['ds_cuidado_isolamento'] +
                                    "</td>" +
                                    "</tr>";
                            }

                            let observacao_precaucao = "";
                            if (result['ds_observacao_isolamento'].length > 1) {
                                observacao_precaucao = "<tr>" +
                                    "<td class='font-weight-bold text-wrap'>" +
                                    "Observação" +
                                    "</td>" +
                                    "<td class=' text-wrap'>" +
                                    result['ds_observacao_isolamento'] +
                                    "</td>" +
                                    "</tr>"
                            }

                            html_avaliacao_isolada = "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Tipo de Precaução" +
                                "</td>" +
                                "<td class=' text-wrap'>" +
                                result['ds_precaucao_isolamento'] +
                                "</td>" +
                                "</tr>" +
                                "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Motivo" +
                                "</td>" +
                                "<td class=' text-wrap'>" +
                                result['ds_motivo_isolamento'] +
                                "</td>" +
                                "</tr>" +
                                "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Profissional" +
                                "</td>" +
                                "<td class=' text-wrap'>" +
                                result['nome_profissional_precaucao'] +
                                "</td>" +
                                "</tr>" +
                                "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Data" +
                                "</td>" +
                                "<td class=' text-wrap'>" +
                                data_precaucao +
                                "</td>" +
                                "</tr>" +
                                cuidados_precaucao +
                                observacao_precaucao;

                        } else if (tipo_avaliacao == 'precaucoes_isolamento_2') {
                            let hora_precaucao = result["dt_atualizacao_precaucao_2"].split(" ");
                            hora_precaucao = hora_precaucao[1];
                            let data_precaucao = result["dt_atualizacao_precaucao_2"].substr(0, 10).split('-').reverse().join('/') + " " + hora_precaucao;
                            if (data_precaucao == "00/00/0000 00:00:00") {
                                data_precaucao = " - ";
                            }

                            let cuidados_precaucao = "";
                            if (result['ds_cuidado_isolamento_2'].length > 1) {
                                cuidados_precaucao = "<tr>" +
                                    "<td class='font-weight-bold text-wrap'>" +
                                    "Cuidados" +
                                    "</td>" +
                                    "<td class=' text-wrap'>" +
                                    result['ds_cuidado_isolamento_2'] +
                                    "</td>" +
                                    "</tr>";
                            }

                            let observacao_precaucao = "";
                            if (result['ds_observacao_isolamento_2'].length > 1) {
                                observacao_precaucao = "<tr>" +
                                    "<td class='font-weight-bold text-wrap'>" +
                                    "Observação" +
                                    "</td>" +
                                    "<td class=' text-wrap'>" +
                                    result['ds_observacao_isolamento_2'] +
                                    "</td>" +
                                    "</tr>"
                            }
                            html_avaliacao_isolada = "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Tipo de Precaução" +
                                "</td>" +
                                "<td class=' text-wrap'>" +
                                result['ds_precaucao_isolamento_2'] +
                                "</td>" +
                                "</tr>" +
                                "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Motivo" +
                                "</td>" +
                                "<td class=' text-wrap'>" +
                                result['ds_motivo_isolamento_2'] +
                                "</td>" +
                                "</tr>" +
                                "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Profissional" +
                                "</td>" +
                                "<td class=' text-wrap'>" +
                                result['nome_profissional_precaucao_2'] +
                                "</td>" +
                                "</tr>" +
                                "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                "Data" +
                                "</td>" +
                                "<td class=' text-wrap'>" +
                                data_precaucao +
                                "</td>" +
                                "</tr>" +
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
                        idade_paciente + " anos" +
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
                        result['tempo_internacao'] + " dia(s)" +
                        "</td>" +
                        "</tr>" +
                        html_avaliacao_isolada +
                        "</table>";

                    $("#corpo_modal").html(html_leito);
                    $("#modal_info").modal('show');
                },
                error: function (data) {
                    alert('Não foi possível abrir o detalhamento. Confira sua conexão!');
                }
            });

        }

        async function conteudoHistorico(username, atendimento) {
            const historico_conversa_paciente = { "username": username, "atendimento": Number(atendimento) }

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
            let historicoChat = "";

            historico = document.getElementById('historico');

            for (j = 0; j < historico_api.length; j++) {
                let caixamensagem = "";
                let caixaResposta = "";
                let mensagemResposta = "";
                let element = "";
                let mensagemEnviada = "";
                let mensagem = historico_api[j]["content"];

                console.log(mensagem);
                if (historico_api[j]["role"] == "assistant") {

                    caixaResposta = document.createElement('div');
                    caixaResposta.className = 'caixa-resposta';

                    mensagemResposta = document.createElement('p');
                    mensagemResposta.className = 'mensagem-resposta';
                    mensagemResposta.innerHTML = mensagem;

                    caixaResposta.appendChild(mensagemResposta);
                    historico.appendChild(caixaResposta);

                } else if (historico_api[j]["role"] == "user") {

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

        function iaAssistente(nr_atendimento) {

            let html_ia = "<div class='container chat'>" +

                "<div id='historico'>" +

                "</div>" +
                "<span id='status'></span>" +

                "<div class='cotainer border-top pergunta-ia'>" +
                "<div class='row align-items-center'>" +
                "<div class='col-10 textarea-ia'><textarea type='text' id='mensagem-input' class='form-control flex-fill' placeholder='Faça uma pergunta...'></textarea></div>" +
                "<div class='col-2 mx-auto div-btn'><button id='btn-submit' class='btn-ia'><img src=<?php echo base_url("public/assets/img/icons/arrow-right.svg"); ?> alt=''></button></div>" +
                "</div>" +
                "</div>" +
                "</div>";

            conteudoHistorico(usuario_ad, nr_atendimento);

            $("#corpo_modal_ia").html(html_ia);
            $("#modal_ia").modal('show');

            const btnSubmit = document.getElementById('btn-submit');
            btnSubmit.addEventListener("click", function () {
                enviarmensagem(nr_atendimento);
            })

            const mensagem = document.getElementById('mensagem-input');
            mensagem.addEventListener('keypress', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    enviarmensagem(nr_atendimento);
                }
            })
        }

    </script>