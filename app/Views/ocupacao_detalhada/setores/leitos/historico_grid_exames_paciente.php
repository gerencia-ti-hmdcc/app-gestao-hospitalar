<div class="row">
    <?php
    echo "<div class='col-lg-12'>
                    <div class='flex'>
                        <div class='col-6'>
                            <!-- <a class='btn btn-primary' href='../detalhada/leitos?l=" . $_GET['l'] . "&s=" . $_GET["s"] . "'>Voltar</a> -->
                        </div>
                        <div class='col-6 text-end'>

                        </div>
                    </div>";
    if ($_GET["l"] != 4 && $_GET["s"] != 145) {
        $botao_verdes_vermelhos = "<div class='w-full px-2 botoes-ocupacao-detalhada'>
                                            <a class='btn text-xs btn-primary flex rounded text-white' href='avaliacoesVerdeVermelho?a=" . $_GET["a"] . "&l=" . $_GET["l"] . "&s=" . $_GET["s"] . "'>Histórico</a>
                                        </div>";
    } else {
        $botao_verdes_vermelhos = "";
    }

    echo "</div>
                <div class='mt-3 flex flex-wrap'>
                    " . $botao_verdes_vermelhos . "
                    <div class='w-full px-2 botoes-ocupacao-detalhada'>
                        <a class='btn text-xs hmdcc-color-2 flex rounded text-white' href='historicoInterconsultasPaciente?a=" . $_GET["a"] . "&l=" . $_GET["l"] . "&s=" . $_GET["s"] . "'>Últimas interconsultas</a>
                    </div>
                    <div class='w-full px-2 botoes-ocupacao-detalhada'>
                        <a class='btn text-xs hmdcc-color-3 flex rounded text-white' href='historicoExamesLabPaciente?a=" . $_GET["a"] . "&l=" . $_GET["l"] . "&s=" . $_GET["s"] . "'>Exames laboratoriais</a>
                    </div>
                    <div class='w-full px-2 botoes-ocupacao-detalhada'>
                        <a class='btn text-xs hmdcc-color-1 flex rounded text-white' href='historicoEvolucoesPaciente?a=" . $_GET["a"] . "&l=" . $_GET["l"] . "&s=" . $_GET["s"] . "'>Últimas evoluções</a>
                    </div>
                    <div class='w-full px-2 botoes-ocupacao-detalhada'>
                        <a class='btn text-xs hmdcc-color-5 flex rounded text-white' href='historicoExamesImagemPaciente?a=" . $_GET["a"] . "&l=" . $_GET["l"] . "&s=" . $_GET["s"] . "'>Imagens e laudos</a>
                    </div>
                </div>
                </div>";
    /*.date('d/m/Y H:i:s', strtotime($ultima_atualizacao["ultima_atualizacao"])).*/
    ?>

    <?php
    echo "<div class='card z-index-2 w-full mb-3'><div class='w-full card-body' style='padding-top:0px !important; padding-bottom:0px !important;' id='info_principal' name='info_principal'></div></div>";
    echo $html_grid_exames_paciente;

    echo "<input type='hidden' id='cd_setor_atendimento_id' name='cd_setor_atendimento_id' value='" . $_GET["s"] . "'/>";
    echo "<input type='hidden' id='linha_cuidado_id' name='linha_cuidado_id' value='" . $_GET["l"] . "'/>";
    echo "<input type='hidden' id='nr_atendimento_id' name='nr_atendimento_id' value='" . $_GET["a"] . "'/>";
    ?>
</div>

<!-- Modal Info (existente) -->
<div class="modal fade w-full" id="modal_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Informações do leito</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="corpo_modal" name="corpo_modal">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Gráfico de Exame (duplo-clique) -->
<div class="modal fade" id="modal_grafico_exame" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header"
                style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border:none;">
                <div>
                    <h5 class="modal-title text-white mb-0" id="grafico_exame_titulo"
                        style="font-weight:700; font-size:15px;">
                        Histórico do Exame
                    </h5>
                    <small class="text-white-50" id="grafico_exame_referencia" style="font-size:11px;"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    style="background: none !important; border: none; width: auto; height: auto; padding: 0; font-size: 2rem; color: #fff !important; opacity: 0.85; line-height: 1; margin: -2.5rem 0.5rem -0.5rem auto;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background:#f8fafc; padding: 24px;">
                <div style="position: relative; height: 320px;">
                    <canvas id="canvas_grafico_exame"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


<script defer>
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

    // ─── Gráfico de histórico (duplo-clique nas células de resultado) ─────────
    let _chartExameInstance = null;

    // Converte valor no formato BR (ex: "1.234,5") para float
    function parseBRFloat(v) {
        if (v === null || v === undefined || v === '-' || v === '') return null;
        var s = String(v).trim();
        // Remove pontos de milhar e converte vírgula decimal
        if (s.indexOf(',') !== -1) {
            s = s.replace(/\./g, '').replace(',', '.');
        } else {
            // ponto seguido de 3 dígitos = milhar, remove
            s = s.replace(/\.(\d{3})(?!\d)/g, '$1');
        }
        var f = parseFloat(s);
        return isNaN(f) ? null : f;
    }

    // Extrai min/max de uma string de referência (ex: "4.500 a 11.000")
    function parseReferencia(ref) {
        if (!ref) return null;
        var m = ref.match(/(-?[\d.,]+)\s*[-–a]\s*(-?[\d.,]+)/i);
        if (m) return { min: parseBRFloat(m[1]), max: parseBRFloat(m[2]) };
        m = ref.match(/(?:até|ate|<=?)\s*(-?[\d.,]+)/i);
        if (m) return { min: null, max: parseBRFloat(m[1]) };
        m = ref.match(/(?:>=?|acima\s+de)\s*(-?[\d.,]+)/i);
        if (m) return { min: parseBRFloat(m[1]), max: null };
        return null;
    }

    document.addEventListener('dblclick', function (e) {
        var td = e.target.closest('td.cell-resultado');
        if (!td) return;

        var nomeExame = td.dataset.exame || 'Exame';
        var referencia = td.dataset.referencia || '';

        // Coleta todos os .cell-resultado da mesma linha (TR)
        var row = td.closest('tr');
        var allCells = Array.from(row.querySelectorAll('td.cell-resultado'));

        // Ordena da esquerda (mais antigo) para a direita (mais recente) conforme cellIndex
        allCells.sort(function (a, b) { return a.cellIndex - b.cellIndex; });

        var labels = [];
        var values = [];
        allCells.forEach(function (cell) {
            var val = parseBRFloat(cell.dataset.valorBruto);
            if (val !== null) {
                labels.push(cell.dataset.label || '');
                values.push(val);
            }
        });

        if (values.length === 0) return;

        // Atualiza cabeçalho do modal
        document.getElementById('grafico_exame_titulo').textContent = nomeExame;
        document.getElementById('grafico_exame_referencia').textContent = referencia ? 'Referência: ' + referencia : '';

        // Destrói instância anterior
        if (_chartExameInstance) { _chartExameInstance.destroy(); _chartExameInstance = null; }

        var ctx = document.getElementById('canvas_grafico_exame').getContext('2d');

        // Datasets base
        var datasets = [{
            label: nomeExame,
            data: values,
            borderColor: '#00AB97',
            backgroundColor: 'rgba(0,171,151,0.12)',
            borderWidth: 2.5,
            pointRadius: 5,
            pointHoverRadius: 7,
            pointBackgroundColor: values.map(function (v) {
                return '#00AB97';
            }),
            tension: 0.35,
            fill: true,
        }];

        // Linhas de referência (pontilhadas)
        var refParsed = parseReferencia(referencia);
        if (refParsed) {
            if (refParsed.min !== null) {
                datasets.push({
                    label: 'Ref. mín (' + refParsed.min + ')',
                    data: Array(values.length).fill(refParsed.min),
                    borderColor: 'rgba(234,179,8,0.7)',
                    borderWidth: 1.5,
                    borderDash: [6, 4],
                    pointRadius: 0,
                    fill: false,
                });
            }
            if (refParsed.max !== null) {
                datasets.push({
                    label: 'Ref. máx (' + refParsed.max + ')',
                    data: Array(values.length).fill(refParsed.max),
                    borderColor: 'rgba(239,68,68,0.7)',
                    borderWidth: 1.5,
                    borderDash: [6, 4],
                    pointRadius: 0,
                    fill: false,
                });
            }
        }

        _chartExameInstance = new Chart(ctx, {
            type: 'line',
            data: { labels: labels, datasets: datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        display: true,
                        labels: { color: '#334155', font: { size: 12 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                return ' ' + ctx.dataset.label + ': ' + ctx.formattedValue;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#64748b', font: { size: 11 }, maxRotation: 45 },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    y: {
                        ticks: { color: '#64748b', font: { size: 11 } },
                        grid: { color: 'rgba(0,0,0,0.07)' }
                    }
                }
            }
        });

        // Abre o modal
        var modal = new bootstrap.Modal(document.getElementById('modal_grafico_exame'));
        modal.show();
    });
</script>