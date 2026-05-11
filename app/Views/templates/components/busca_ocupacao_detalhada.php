<?php

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

<div class="secaoBusca">

    <div class="busca">

        <div class="w-full">
            <span class="text-uppercase font-weight-bold text-white">Busca</span>
        </div>
        
            <div class="campo">
                <label for="">NOME</label>
                <input id="inputNome" type="text" placeholder="Digite o nome do paciente">
            </div>
            <div class="campo">
                <label for="">LEITO</label>
                <input id="inputLeito" type="text" placeholder="Digite o número do leito">
            </div>
            <div class="campo campoAtendimento">
                <label for="" name="numAtendimento">ATENDIMENTO</label>
                <input id="inputAtendimento" type="text" placeholder="Digite o número de atendimento">
            </div>
        
        <!-- <button id="btn" type="button" onclick="Buscar()">Buscar</button> -->
    </div>
</div>

<div class="resultadoBusca" id="resultadoBusca">

    <i onclick="fechaResultadoBusca()" class="material-icons">close</i>

    <p id="semResultado">Nenhum resultado encontrado</p>

    <?php
    
        for ($i=0; $i < count($detalhes_leito); $i++) { 

            if ($detalhes_leito[$i]['ds_nome_paciente'] == "")
                $nome2 = $nome = $detalhes_leito[$i]['ie_status_unidade']; 

            else{
                $nome2 = $nome = $detalhes_leito[$i]['ds_nome_paciente'];
                $nome = iniciais($nome);
            }

            $leito = explode(" ", $detalhes_leito[$i]["ds_leito_atual"]);

            $aux = $leito[0];
            $aux2 = $leito[1];

            $active = "active cursor-pointer";
            $inative = "inative";

            if($aux2 == "")
                $aux2 = 0;

            echo '<div id="'. $nome2 . '" class="item glass-card ' . ($detalhes_leito[$i]['ds_nome_paciente'] != "" ? $active : $inative) .'" . onclick="abrirLeitos(' . $detalhes_leito[$i]["cd_agrupamento"] . ',' . $detalhes_leito[$i]["cd_setor_atendimento"] . ',' . '\'' . $aux . '\'' . ',' . '\'' . $aux2 . '\'' . ',' . $detalhes_leito[$i]["nr_atendimento"] . ')">
            
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

<script>

    const currentPageUrl = window.location.href;
    let linkCss = currentPageUrl.split("detalhada")[0]
    
    linkCss += 'public/assets/css/style_detalhada_busca.css'

    const head = document.querySelector("head")
    var link = document.createElement('link')

    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = linkCss;

    head.appendChild(link)

    const resultadoBusca = document.getElementById("resultadoBusca")
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

        let vetorNome = "";  
        let primeiraLetra = ""
        let qtExibido = 0   
        let qtExibido2 = 0   
        let qtExibido3 = 0

        resultadoBusca.style.display = "flex"
        vetorNome = inputNome.toLowerCase().trim().split(" ")

        primeiraLetra = vetorNome[0].charAt(0)
        
        primeiraLetra = primeiraLetra.toLowerCase()

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

                    let vetorNomesBanco = items[i].id.trim().split(" ")

                    vetorNomesBanco.forEach(element => {
                        
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
            let vetorNomesInput = inputNome.toLowerCase().trim().split(" ")
            
            if (qtExibido == 0 && qtExibido2 == 0) {

                let vetorNomesBanco = items[i].id.toLowerCase().trim().split(" ")

                vetorNomesInput = vetorNomesInput.filter(Boolean)

                vetorNomesBanco.forEach(element => {

                    vetorNomesInput.forEach(element2 => {
                        
                    //verificar se os outros campos são iguais aqui também
                        if (element.toLowerCase().trim() == element2.toLowerCase().trim()) {

                            qtNomes++
                        }
                    });
                });

                if (vetorNomesInput.length == qtNomes && (items[i].children[1].innerText.includes(inputAtendimento)) && (items[i].children[3].innerText.toLowerCase().includes(inputLeito.toLowerCase()))) {

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

    inputNome.addEventListener("keyup", (e) =>{
        Buscar()
    })

    inputAtendimento.addEventListener("keyup", (e) =>{
        Buscar()
    })

    inputLeito.addEventListener("keyup", (e) =>{
        Buscar()
    })
</script>