<div class="row">
  <div class="col-12">
    <div class="card glass-card">
      <div class="card-body">
        <?php
        $calendario->stylesheetMonths();
        if ($ano_calendario == 0) {
          $ano_calendario = date("Y");
        }
        $n_formatado = 0;
        ?>

        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between mb-4">
          <div class="d-flex align-items-center">
            <div class="me-2" style="width:4px; height:24px; background: var(--premium-gradient); border-radius:2px;">
            </div>
            <h6 class="mb-0 text-uppercase font-weight-bold text-petroleum"
              style="letter-spacing:0.5px; font-size:0.85rem;">
              Calendário <?= $ano_calendario ?>
            </h6>
          </div>
          <a href="anos" class="btn bg-gradient-hmdcc text-white btn-rounded btn-sm">
            <i class="ni ni-calendar-grid-61 me-1"></i> Outros anos
          </a>
        </div>

        <!-- Months Grid -->
        <div class="row">
          <?php for ($i = 1; $i <= 12; $i++):
            $n_formatado = ($i < 10) ? "0" . $i : $i;
            ?>
            <div class="col-md-4 col-lg-3 mb-3">
              <!-- Sector Shortcut Pills -->
              <div class="d-flex justify-content-center gap-1 mb-2 flex-wrap">
                <?php
                $setores = [
                  ['s' => 10, 'label' => 'CLM'],
                  ['s' => 9, 'label' => 'AVC'],
                  ['s' => 1, 'label' => 'CTI'],
                  ['s' => 11, 'label' => 'CIR'],
                  ['s' => 13, 'label' => 'HD'],
                ];
                foreach ($setores as $setor):
                  ?>
                  <a href="<?= $diretorio_raiz ?>admissoes/admissoes_por_linha?m=<?= $n_formatado ?>&s=<?= $setor['s'] ?>&a=<?= $ano_calendario ?>"
                    class="badge bg-gradient-hmdcc text-white"
                    style="font-size:0.65rem; padding:5px 8px; text-decoration:none; border-radius:6px; transition: all 0.2s ease;">
                    <?= $setor['label'] ?>
                  </a>
                <?php endforeach; ?>
              </div>
              <!-- Calendar -->
              <a href="<?= $diretorio_raiz ?>admissoes?m=<?= $n_formatado ?>&a=<?= $ano_calendario ?>"
                style="text-decoration:none;">
                <?php
                if (date('m') == $i && ($ano_calendario == date("Y"))) {
                  echo ($calendario->draw(date($ano_calendario . '-' . $i . '-01')));
                } else {
                  echo ($calendario->draw(date($ano_calendario . '-' . $i . '-01'), 'purple'));
                }
                ?>
              </a>
            </div>
          <?php endfor; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
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
  function abrirModalInformacoes(modal) {
    if (modal == 'modal_grafico_geral') {
      $("#corpo_modal").html("Percentual geral das linhas de cuidado e Clínica Cirúrgica.<br /><small>Clique nos cards abaixo para visualizar o quantitativo das linhas de cuidado por setor.</small>");
      $("#modal_info").modal('show');
    } else if (modal == 'modal_tabela_setor') {
      $("#corpo_modal").html('<b>Linha de cuidado</b> - Linha de cuidado ao qual o setor faz parte.<br />' +
        '<b>Setor</b> - Setor referente à linha de cuidado do hospital.<br />' +
        '<b>Ocupação</b> - Percentual de ocupação de determinado setor.<br />' +
        '<b>Total de leitos</b> - Numero de unidades cadastradas no setor desconsiderando os leitos virtuais.<br />' +
        '<b>Leitos ocupados</b> - Unidades ocupadas desconsiderando as unidades em higienização.<br />' +
        '<b>Leitos livres</b> - Total de unidades disponíveis desconsiderando as reservadas, em higienização e manutenção.<br />' +
        '<b>Leitos reservados</b> - Leitos que já estão reservados para um atendimento futuro.<br />' +
        '<b>Leitos em isolamento</b> - Quantidade de leitos em isolamento sem pacientes (precaução).<br />' +
        '<b>Higienização</b> - Leitos que estão em higienização ou aguardando higienização.<br />' +
        '<b>Manutenção</b> - Leitos que estão em manutenção e/ou interditados.<br />' +
        '<b>Alta</b> - Leitos em processo de alta.<br />' +
        '<b>Indisponíveis</b> - Leitos isolados e em manutenção.<br />' +
        '<b>Reserv./ Higien./ Alta</b> - Leitos reservados, em higienização ou aguardando higienização e em processo de alta.');
      $("#modal_info").modal('show');
    }
  }
</script>