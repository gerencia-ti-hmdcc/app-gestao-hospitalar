<div class="row">
    <?php
        //echo $calendario1;
        // echo $calendario->display(date('2022-01-01')); 
        // echo $calendario->display(date('2022-02-01')); 
        // echo '<a href="admissoes/meses" class="azul-hospital botao-inverso-hospital btn btn-rounded">Outros meses</a>';
        $calendario->stylesheetMonths();
        if($ano_calendario==0){
          $ano_calendario = date("Y");
        }
        $n_formatado = 0;
        echo '<a href="anos" class="azul-hospital botao-inverso-hospital btn btn-rounded">Outros anos</a>';
        echo("<div class='flex w-full row'>");
        for($i=1;$i<=12;$i++){
          echo("<div class='md:col-3 mb-2'>");
          if($i<10){
            $n_formatado = "0".$i;
          }else{
            $n_formatado = $i;
          }
          echo("<div class='text-center justify-center text-xs flex flex-nowrap row'>
                  <div class='col-2 rounded amarelo-hospital p-2 m-1'><a class='text-white' href='".$diretorio_raiz."admissoes/admissoes_por_linha?m=".$n_formatado."&s=10&a=".$ano_calendario."'>CLM</a></div>
                  <div class='col-2 rounded amarelo-hospital p-2 m-1'><a class='text-white' href='".$diretorio_raiz."admissoes/admissoes_por_linha?m=".$n_formatado."&s=9&a=".$ano_calendario."'>AVC</a></div>
                  <div class='col-2 rounded amarelo-hospital p-2 m-1'><a class='text-white' href='".$diretorio_raiz."admissoes/admissoes_por_linha?m=".$n_formatado."&s=1&a=".$ano_calendario."'>CTI</a></div>
                  <div class='col-2 rounded amarelo-hospital p-2 m-1'><a class='text-white' href='".$diretorio_raiz."admissoes/admissoes_por_linha?m=".$n_formatado."&s=11&a=".$ano_calendario."'>CIR</a></div>
                  <div class='col-2 rounded amarelo-hospital p-2 m-1'><a class='text-white' href='".$diretorio_raiz."admissoes/admissoes_por_linha?m=".$n_formatado."&s=13&a=".$ano_calendario."'>HD</a></div>
                </div>");
          echo("<a href='".$diretorio_raiz."admissoes?m=".$n_formatado."&a=".$ano_calendario."'>");
          if(date('m')==$i && ($ano_calendario==date("Y"))){
            echo($calendario->draw(date($ano_calendario.'-'.$i.'-01')));
          }else{
            echo($calendario->draw(date($ano_calendario.'-'.$i.'-01'),'purple'));
          }
          echo("</a></div>");
        }
        echo("</div>");
    ?>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Informações</h5>
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

<script>
    function abrirModalInformacoes(modal){
        if(modal=='modal_grafico_geral'){
            $("#corpo_modal").html("Percentual geral das linhas de cuidado e Clínica Cirúrgica.<br /><small>Clique nos cards abaixo para visualizar o quantitativo das linhas de cuidado por setor.</small>");
            $("#modal_info").modal('show');
        }else if(modal=='modal_tabela_setor'){
            $("#corpo_modal").html('<b>Linha de cuidado</b> - Linha de cuidado ao qual o setor faz parte.<br />'+
                                    '<b>Setor</b> - Setor referente à linha de cuidado do hospital.<br />'+
                                    '<b>Ocupação</b> - Percentual de ocupação de determinado setor.<br />'+
                                    '<b>Total de leitos</b> - Numero de unidades cadastradas no setor desconsiderando os leitos virtuais.<br />'+
                                    '<b>Leitos ocupados</b> - Unidades ocupadas desconsiderando as unidades em higienização.<br />'+
                                    '<b>Leitos livres</b> - Total de unidades disponíveis desconsiderando as reservadas, em higienização e manutenção.<br />'+
                                    '<b>Leitos reservados</b> - Leitos que já estão reservados para um atendimento futuro.<br />'+
                                    '<b>Leitos em isolamento</b> - Quantidade de leitos em isolamento sem pacientes (precaução).<br />'+
                                    '<b>Higienização</b> - Leitos que estão em higienização ou aguardando higienização.<br />'+
                                    '<b>Manutenção</b> - Leitos que estão em manutenção e/ou interditados.<br />'+
                                    '<b>Alta</b> - Leitos em processo de alta.<br />'+
                                    '<b>Indisponíveis</b> - Leitos isolados e em manutenção.<br />'+
                                    '<b>Reserv./ Higien./ Alta</b> - Leitos reservados, em higienização ou aguardando higienização e em processo de alta.');
            $("#modal_info").modal('show');
        }
    }
</script>