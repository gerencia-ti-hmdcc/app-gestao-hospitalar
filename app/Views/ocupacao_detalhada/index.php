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
<link rel="stylesheet" type="text/css" href="css/style_detalhada.css">

<div class="row">
    <!-- <div class="col-lg-12 mb-<?php echo $variavel_controle_margem_tv; ?>">
        <div class="card glass-card z-index-2">
            <div class="card-header pb-0 bg-transparent">
                <div
                    class="row justify-center lead text-hmdcc-green active breadcrumb-item font-weight-bolder text-uppercase">
                    <i class="ni ni-building text-lg me-2"></i> Linhas de cuidado
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
                    $nome2 = $nome ="Indefinido"; 

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

        // echo '<pre>';
        // print_r($detalhes_leito);
        // echo '</pre>';
    
    echo '<div class="flex flex-wrap col-12 mb-auto pt-4">';
    for ($i = 0; $i < count($linhas_de_cuidado); $i++) {

        echo '<div class="w-full md:w-1/2 lg:w-1/3 xl:w-1/4 mb-2 px-2">
                    <div class="card glass-card cursor-pointer w-full h-100 hover-scale" onclick="abrirLinhaDeCuidado(' . $linhas_de_cuidado[$i]["CD_CLASSIF_SETOR"] . ')">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <div class="numbers">
                                        <h5 class="font-weight-bolder mb-0 text-dark">
                                            ' . $linhas_de_cuidado[$i]["DS_LINHA_CUIDADO"] . '
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-3 text-end">
                                    <div class="icon icon-shape bg-gradient-hmdcc shadow text-center border-radius-md">
                                        <i class="ni ni-ambulance text-lg opacity-10 text-white" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
    }
    echo '</div>';
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

    function abrirLinhaDeCuidado(cd_agrupamento) {
    
        window.location.href = "detalhada/setores?l=" + cd_agrupamento;
    }

    function abrirLeitos(cd_agrupamento, cd_setor_atendimento, aux, aux2, nr_atendimento) {

        let ds_leito_atual = aux

        console.log(cd_agrupamento+ " " + cd_setor_atendimento+ " " + aux + " " + aux2 + " " + nr_atendimento)

        if (aux2 != 0) {
            ds_leito_atual = aux + " " + aux2
        }

        console.log(cd_agrupamento+ " " + cd_setor_atendimento+ " " + ds_leito_atual + " " + nr_atendimento)
        
        window.location.href = "detalhada/leitos?l=" + cd_agrupamento + "&s=" + cd_setor_atendimento + "&lei=" + ds_leito_atual + "&a=" + nr_atendimento;  
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

        let aux = "";  
        let aux2 = ""
        let qtExibido = 0   
        let qtExibido2 = 0   
        let qtExibido3 = 0

        resultadoBusca.style.display = "flex"
        aux = inputNome.toLowerCase().trim().split(" ")

        aux2 = aux[0].charAt(0)
        // aux.forEach(element => {
            
        //   //  if(element != 'da' && element != 'dos' && element != "do" && element != "de")
        //         aux2 += element.charAt(0)
        // });
        
        aux2 = aux2.toLowerCase()

        for (let i = 0; i < items.length; i++) {

            if ((items[i].id.toLocaleLowerCase().toLowerCase().trim().startsWith(inputNome.toLowerCase().trim()))
                && (items[i].children[1].innerText.includes(inputAtendimento)) && (items[i].children[3].innerText.toLowerCase().includes(inputLeito.toLowerCase())) ) {

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

                    let aux3 = items[i].id.trim().split(" ")

                    aux3.forEach(element => {
                        
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

                let aux3 = items[i].id.toLowerCase().trim().split(" ")

                aux4 = aux4.filter(Boolean)

                aux3.forEach(element => {

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