<div class="row">
  <div class="col-12">
    <div class="card glass-card">
      <div class="card-body">
        <!-- Header -->
        <div class="d-flex align-items-center mb-4">
          <div class="me-2" style="width:4px; height:24px; background: var(--premium-gradient); border-radius:2px;">
          </div>
          <h6 class="mb-0 text-uppercase font-weight-bold text-petroleum"
            style="letter-spacing:0.5px; font-size:0.85rem;">Selecione o Ano</h6>
        </div>

        <!-- Year Cards Grid -->
        <div class="row">
          <?php for ($i = 2022; $i <= date("Y"); $i++): ?>
            <div class="col-sm-6 col-md-4 col-lg-3 mb-3">
              <a href="<?= $diretorio_raiz ?>admissoes/meses?a=<?= $i ?>" style="text-decoration:none;">
                <?php if (date('Y') == $i): ?>
                  <div class="list-card" style="background: var(--premium-gradient); border:none;">
                    <div class="list-card-body"
                      style="padding:24px; display:flex; justify-content:center; align-items:center;">
                      <div class="text-center">
                        <span style="font-size:2rem; font-weight:800; color:white; line-height:1;"><?= $i ?></span>
                        <div
                          style="font-size:0.7rem; color:rgba(255,255,255,0.8); text-transform:uppercase; letter-spacing:1px; margin-top:4px;">
                          Ano Atual</div>
                      </div>
                    </div>
                  </div>
                <?php else: ?>
                  <div class="list-card">
                    <div class="list-card-strip strip-purple"></div>
                    <div class="list-card-body"
                      style="padding:24px; display:flex; justify-content:center; align-items:center;">
                      <div class="text-center">
                        <span
                          style="font-size:2rem; font-weight:800; color:var(--color-petroleum-blue); line-height:1;"><?= $i ?></span>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>
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