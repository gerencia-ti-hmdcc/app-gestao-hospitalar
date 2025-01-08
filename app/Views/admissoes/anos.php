<div class="row">
    <?php
        //echo $calendario1;
        // echo $calendario->display(date('2022-01-01')); 
        // echo $calendario->display(date('2022-02-01')); 
        // echo '<a href="admissoes/meses" class="azul-hospital botao-inverso-hospital btn btn-rounded">Outros meses</a>';
        // $calendario->stylesheetMonths();
        
        echo("<div class='flex w-full flex-wrap row'>");
        for($i=2022;$i<=date("Y");$i++){
            echo("<div class='flex col-sm-12 col-xl-4 flex-wrap mb-2'><a href='".$diretorio_raiz."admissoes/meses?a=$i'>");
            if(date('Y')==$i){
                echo('<button class="flex azul-hospital text-white p-6" style="width: 80%;border-width:0px;margin: 30px;border-radius:2.5%;">'.$i.'</button>');
            }else{
                echo('<button class="flex roxo-hospital text-white p-6" style="width: 80%;border-width:0px;margin: 30px;border-radius:2.5%;">'.$i.'</button>');
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