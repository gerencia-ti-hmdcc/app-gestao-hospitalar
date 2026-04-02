<?php
$variavel_controle_margem_tv = 4;
if ($link_pagina == 'dashboard') {
    if ($tipo_perfil == 'P') {
        $variavel_controle_margem_tv = 2;
        $usuario_logado = $this->session->get("usuario_logado");
        if (isset($usuario_logado["painel_variavel_controle"])) {
            $usuario_logado["painel_variavel_controle"] = $usuario_logado["painel_variavel_controle"];
        } else {
            $usuario_logado["painel_variavel_controle"] = $setor_ultimo_painel;
        }
        ?>
        <meta http-equiv="refresh" content="180" />
        <?php
    }
}

function iniciais($str){

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
   
?>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div class="row">

    <!-- <div class="col-lg-12 mb-<?php echo $variavel_controle_margem_tv; ?>">
        <a class='btn bg-gradient-hmdcc text-white mb-4' href='../detalhada'><i class="fas fa-arrow-left me-2"></i>
            Voltar</a>
        <div class="card glass-card z-index-2">
            <div class="card-header pb-0 bg-transparent">
                <div
                    class="row justify-center lead text-hmdcc-green active breadcrumb-item font-weight-bolder text-uppercase">
                    <i class="ni ni-building text-lg me-2"></i> Setores de atendimento
                </div>
                <div class="text-sm flex row justify-center text-secondary font-weight-bold opacity-7">
                    <?php echo $linha_cuidado["DS_LINHA_CUIDADO"]; ?>
                </div>
            </div>
            <div class="card-body p-3">
            </div>
        </div>
    </div> -->
    
    <div class="secaoBusca">
        <h1>Campo de Busca</h1>

        <div class="busca">
            <div class="campo">
                <label for="">NOME</label>
                <input id="inputNome" type="text" placeholder="Digite o nome do paciente">
            </div>
            <div class="campo">
                <label for="">LEITO</label>
                <input id="inputLeito" type="text" placeholder="Digite o número do Leito">
            </div>
            <div class="campo campoAtendimento">
                <label for="" name="numAtendimento">ATENDIMENTO</label>
                <input id="inputAtendimento" type="text" placeholder="Digite o número do Atendimento">
            </div>

            <button id="btn" type="button" onclick="Buscar()">Buscar</button>
        </div>
    </div>

    <div class="resultadoBusca" id="resultadoBusca">

        <i onclick="fechaResultadoBusca()" class="material-icons">close</i>

        <p id="semResultado">Nenhum resultado encontrado</p>

        <?php
        
            for ($i=0; $i < count($detalhes_leito); $i++) { 

                if ($detalhes_leito[$i]['ds_nome_paciente'] == "")
                    $nome2 = $nome = "Indefinido"; 

                else{
                    $nome2 = $nome = $detalhes_leito[$i]['ds_nome_paciente'];
                    $nome = iniciais($nome);
                }

                $leito = explode(" ", $detalhes_leito[$i]["ds_leito_atual"]);

                $aux = $leito[0];
                $aux2 = $leito[1];

                if($aux2 == "")
                    $aux2 = 0;

                echo '<div id="'. $nome2 . '" class="item glass-card" onclick="abrirLeitos(' . $detalhes_leito[$i]["cd_agrupamento"] . ',' . $detalhes_leito[$i]["cd_setor_atendimento"] . ',' . '\'' . $aux . '\'' . ',' . '\'' . $aux2 . '\'' . ',' . $detalhes_leito[$i]["nr_atendimento"] . ')">
                
                        <div class="campoNome">
                            <p>' . $nome .'</p>
                        </div>
                        <div class="campoAtendimentoNum">
                            <p>' . $detalhes_leito[$i]["nr_atendimento"] . '</p>
                        </div>
                        <hr>
                        <div class="campoLeito">
                            <p>' . $detalhes_leito[$i]["ds_leito_atual"] . '</p>
                        </div>
                        <div class="compoSetor">
                            <p>' . $detalhes_leito[$i]["ds_setor_atendimento"] . '</p>
                        </div>
                    </div>';
            }
        ?>
    </div>

    <?php

    echo '<div class="flex flex-wrap col-12 pt-4">';
    for ($i = 0; $i < count($setores); $i++) {
        echo '<div class="w-full md:w-1/2 mb-2 px-2">
                    <div class="card glass-card cursor-pointer w-full h-100 hover-scale" onclick="abrirSetorAtendimento(' . $setores[$i]["CD_SETOR_ATENDIMENTO"] . ')">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <div class="numbers">
                                        <h5 class="font-weight-bolder mb-0 text-dark">
                                            ' . $setores[$i]["DS_SETOR_ATENDIMENTO"] . '
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-3 text-end">
                                    <div class="icon icon-shape bg-gradient-hmdcc shadow text-center border-radius-md">
                                        <i class="ni ni-building text-lg opacity-10 text-white" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
    }
    echo '</div><input id="cd_agrupamento" name="cd_agrupamento" type="hidden" value="' . $_GET["l"] . '"/>';
    ?>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content glass-card">
            <div class="modal-header">
                <h5 class="modal-title text-hmdcc-green" id="exampleModalLabel">Informações</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-dark" id="corpo_modal" name="corpo_modal">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-hmdcc text-white" data-bs-dismiss="modal">OK</button>
                <!-- <button type="button" class="btn bg-gradient-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>

<script>

    function abrirSetorAtendimento(cd_setor_atendimento) {
        let cd_agrupamento = $("#cd_agrupamento").val();

        window.location.href = "leitos?l=" + cd_agrupamento + "&s=" + cd_setor_atendimento;
    }

    const currentPageUrl = window.location.href;
    let aux = currentPageUrl.split("detalhada")[0]
     
    aux += 'public/assets/css/style_detalhada.css'

    const head = document.querySelector("head")
    var link = document.createElement('link');

    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = aux;

    head.appendChild(link)

    function abrirLeitos(cd_agrupamento, cd_setor_atendimento, aux, aux2, nr_atendimento) {
        
        let ds_leito_atual = aux

        if (aux2 != 0) {
            ds_leito_atual = aux + " " + aux2
        }

        window.location.href = "leitos?l=" + cd_agrupamento + "&s=" + cd_setor_atendimento + "&lei=" + ds_leito_atual + "&a=" + nr_atendimento; 
    
        console.log(cd_agrupamento+ " " + cd_setor_atendimento+ " " + ds_leito_atual + " " + nr_atendimento)
    }

    let inputNome = document.getElementById("inputNome");
    let inputLeito = document.getElementById("inputLeito")
    let inputAtendimento = document.getElementById("inputAtendimento");

    function Buscar() {
        
        inputNome = document.getElementById("inputNome").value;
        inputLeito = document.getElementById("inputLeito").value
        inputAtendimento = document.getElementById("inputAtendimento").value;

        const resultadoBusca = document.getElementById("resultadoBusca")
        const items = document.getElementsByClassName("item")
        const semResultado = document.getElementById("semResultado")
  
        let aux2 = "";
        let aux3 = ""
        let qtExibido = 0   
        let qtExibido2 = 0   
        let qtExibido3 = 0

        resultadoBusca.style.display = "flex"
        aux2 = inputNome.toLowerCase().trim().split(" ")

        aux3 = aux2[0].charAt(0)

        // aux2.forEach(element => {
            
        //   //  if(element != 'da' && element != 'dos' && element != "do" && element != "de")
        //         inputNome += element.charAt(0)
        // });

        aux3 = aux3.toLowerCase()

        for (let i = 0; i < items.length; i++) {
 
            if ((items[i].id.toLocaleLowerCase().toLowerCase().trim().startsWith(inputNome.toLowerCase().trim()))
                && (items[i].children[1].innerText.includes(inputAtendimento)) && (items[i].children[3].innerText.toLowerCase().includes(inputLeito.toLowerCase()))) {
                
                items[i].style.display = "grid";
                qtExibido++;

            }else{

                items[i].style.display = "none";
            }
        }

        for (let i = 0; i < items.length; i++) {
            
            if (qtExibido == 0) {

                if ((items[i].id.toLocaleLowerCase().toLowerCase().trim().includes(inputNome.toLowerCase().trim())) && 
                (items[i].children[1].innerText.includes(inputAtendimento)) && (items[i].children[3].innerText.toLowerCase().includes(inputLeito.toLowerCase()))){

                    let aux4 = items[i].id.trim().split(" ")

                    aux4.forEach(element => {
                        
                        if (element.toLowerCase().trim().startsWith(inputNome.toLowerCase())) {

                            items[i].style.display = "grid";
                            qtExibido2++;
                        }
                    });

                }else{

                    items[i].style.display = "none";
                }
            }
        }

        for (let i = 0; i < items.length; i++) {

            let qtNomes = 0 
            let aux4 = inputNome.toLowerCase().trim().split(" ")
            
            if (qtExibido == 0 && qtExibido2 == 0) {

                let aux5 = items[i].id.toLowerCase().trim().split(" ")

                aux4 = aux4.filter(Boolean)

                aux5.forEach(element => {

                    aux4.forEach(element2 => {
                        
                        if (element.toLowerCase().trim() == element2.toLowerCase().trim()) {

                            qtNomes++
                        }
                    });
                });

                if (aux4.length == qtNomes) {

                    items[i].style.display = "grid"

                    qtExibido3++
                }
            }     
        }


        if (qtExibido == 0 && qtExibido2 == 0 &&  qtExibido3 == 0) {
            semResultado.style.display = "block"

        }else{
            semResultado.style.display = "none"
        }
    }

    function fechaResultadoBusca(){
        resultadoBusca.style.display = "none"
    }

    const resultadoBusca = document.getElementById("resultadoBusca")
    const btn = document.getElementById("btn")

    document.addEventListener("click", function(event){

        if (!resultadoBusca.contains(event.target) && !btn.contains(event.target))
            fechaResultadoBusca() 
        
    })

    inputNome.addEventListener("keyup", (e) =>{

        if (e.code === "Enter") {
            Buscar()
        }
    })

    inputAtendimento.addEventListener("keyup", (e) =>{

        if (e.code === "Enter") {
            Buscar()
        }
    })

    inputLeito.addEventListener("keyup", (e) =>{

        if (e.code === "Enter") {
            Buscar()
        }
    })
</script>

