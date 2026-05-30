/**
 * Dashboard & Page Logic
 * Extracted from template_padrao.php for modularity
 */

$(document).ready(function () {
  const config = window.HMDCC_CONFIG;
  if (!config) return;

  // --- DASHBOARD LOGIC ---
  if (config.currentPage === 'dashboard') {

    $("#tabela_detalhes").hide();

    $.ajax({
      url: config.urls.percentuaisGerais, // updated
      type: 'POST',
      dataType: "JSON",
      success: function (data) {
        var result = data;
        var htmlPercentual = "";
        var dataSets_grafico = [];
        var percent = 0.0;
        var livres_1 = 0;
        var temp_indisponiveis = 0;
        var outros_leitos = 0;
        var unidades_reservadas = 0;

        for (var i = 0; i < result.length; i++) {
          unidades_reservadas = parseInt(result[i].NR_UNIDADES_RESERVADAS);
          temp_indisponiveis = parseInt(result[i].QT_UNIDADE_MANUTENCAO) + parseInt(result[i].QT_UNIDADES_ISOLAMENTO);
          outros_leitos = parseInt(unidades_reservadas) + parseInt(result[i].NR_UNIDADES_HIGIENIZACAO) + parseInt(result[i].QT_UNIDADES_ALTA) + parseInt(result[i].NR_UNID_AGUARD_HIGIEN);

          if (parseInt(result[i].CD_CLASSIF_SETOR) == 129 || (parseInt(result[i].CD_CLASSIF_SETOR) == 145) || (parseInt(result[i].CD_CLASSIF_SETOR) == 83)) {
            percent = (((parseInt(result[i].NR_UNID_OCUP)) / parseInt(result[i].NR_UNIDADES_SETOR)) * 100.00).toFixed(2);
            livres_1 = parseInt(result[i].NR_UNIDADES_SETOR) - parseInt(result[i].NR_UNID_OCUP) - (temp_indisponiveis + outros_leitos);
            if (livres_1 < 0 && parseInt(result[i].CD_CLASSIF_SETOR) == 129) {
              unidades_reservadas = unidades_reservadas - (livres_1 * -1);
              livres_1 = 0;
              outros_leitos = parseInt(unidades_reservadas) + parseInt(result[i].NR_UNIDADES_HIGIENIZACAO) + parseInt(result[i].QT_UNIDADES_ALTA) + parseInt(result[i].NR_UNID_AGUARD_HIGIEN);
            }
          } else {
            percent = parseFloat(result[i].PR_OCUPACAO).toFixed(2);
            livres_1 = result[i].NR_UNIDADES_LIVRES;
          }

          // Layout constants based on profile
          let margem_baixo_acerto_tv = (config.userProfile === 'P') ? 2 : 4;
          let espaco_card_acerto_tv = (config.userProfile === 'P') ? 2 : 3;

          if (config.userProfile === 'P') {
            htmlPercentual += '<div name="cardGeral_' + result[i].CD_CLASSIF_SETOR.toString() + '" id="cardGeral_' + result[i].CD_CLASSIF_SETOR.toString() + '" class="cardGeral col-xl-4 col-sm-12 mb-xl-' + margem_baixo_acerto_tv.toString() + ' mb-' + margem_baixo_acerto_tv.toString() + '">' +
              '<div class="card cursor-pointer glass-card" onclick="abrirDivDetalhes(' + parseInt(result[i].CD_CLASSIF_SETOR) + ')">' +
              '<div class="card-body p-' + espaco_card_acerto_tv.toString() + '">' +
              '<div class="row">' +
              '<div class="numbers col-12">' +
              '<div style="float: left" class="col-9">' +
              '<p class="text-sm mb-0 text-capitalize font-weight-bold" id="titulo' + result[i].CD_CLASSIF_SETOR + '" name="titulo' + result[i].CD_CLASSIF_SETOR + '">' + result[i].DS_SETOR_ATENDIMENTO + '</p>' +
              '<h5 class="font-weight-bolder mb-0 text-hmdcc-green">' + percent + '%</h5>' +
              '</div>' +
              '<div style="float: left" class="col-3 text-end">' +
              '<div class="icon icon-shape bg-gradient-hmdcc shadow text-center border-radius-md">' +
              '<i class="ni ni-building text-lg opacity-10 text-white" aria-hidden="true"></i>' +
              '<input id="id_area_antigo" name="id_area_antigo" value="' + parseInt(result[i].CD_CLASSIF_SETOR) + '" type="hidden"/>' +
              '</div>' +
              '</div>' +
              '</div>' +
              '<div class="numbers col-12">' +
              '<table name="tabelaGeral_' + result[i].CD_CLASSIF_SETOR.toString() + '" id="tabelaGeral_' + result[i].CD_CLASSIF_SETOR.toString() + '" width="100%" class="text-sm">' +
              '<tr>' +
              '<td class="font-weight-bold text-hmdcc-green"><i class="fa fa-check text-success"></i> Livres</td>' +
              '<td class="font-weight-bold text-hmdcc-green">' + livres_1 + '</td>' +
              '<td class="font-weight-bold text-petroleum" style="padding-left:30px"><i class="fas fa-asterisk text-info"></i> Resv./ Hig./ Alta</td>' +
              '<td class="font-weight-bold text-petroleum" style="padding-left:30px">' + outros_leitos + '</td>' +
              '</tr>' +
              '<tr>' +
              '<td class="font-weight-bold" style="color: red"><i class="fas fa-ban text-danger"></i> Ocupados</td>' +
              '<td class="font-weight-bold" style="color: red">' + result[i].NR_UNID_OCUP + '</td>' +
              '<td class="font-weight-bold" style="color: #ffa500;padding-left:30px"><i class="fas fa-hourglass-half text-warning"></i> Indisponíveis</td>' +
              '<td class="font-weight-bold" style="color: #ffa500;padding-left:30px">' + temp_indisponiveis + '</td>' +
              '</tr>' +
              '<tr>' +
              '<td class="font-weight-bold"><i class="fa fa-hospital"></i> Total</td>' +
              '<td class="font-weight-bold">' + result[i].NR_UNIDADES_SETOR + '</td>' +
              '<td colspan="2" class="font-weight-bold"></td>' +
              '</tr>' +
              '</table>' +
              '</div>' +
              '</div>' +
              '</div>' +
              '</div>' +
              '</div>';
          } else {
            htmlPercentual += '<div class="col-xl-4 flex flex-wrap col-sm-12 mb-xl-' + margem_baixo_acerto_tv.toString() + ' mb-' + margem_baixo_acerto_tv.toString() + '">' +
              '<div class="card cursor-pointer flex flex-wrap w-full glass-card" onclick="abrirDivDetalhes(' + parseInt(result[i].CD_CLASSIF_SETOR) + ')">' +
              '<div class="card-body p-' + espaco_card_acerto_tv.toString() + '">' +
              '<div class="row">' +
              '<div class="col-9">' +
              '<div class="numbers">' +
              '<p class="text-sm mb-0 text-capitalize font-weight-bold" id="titulo' + result[i].CD_CLASSIF_SETOR + '" name="titulo' + result[i].CD_CLASSIF_SETOR + '">' + result[i].DS_SETOR_ATENDIMENTO + '</p>' +
              '<h5 class="font-weight-bolder mb-0 text-hmdcc-green">' + percent + '%</h5>' +
              '<div class="text-sm">' +
              '<i class="fa fa-hospital"></i>' +
              '<span class="font-weight-bold"> Total : ' + result[i].NR_UNIDADES_SETOR + '</span><br />' +
              '<i class="fa fa-check text-success"></i>' +
              '<span class="font-weight-bold text-hmdcc-green"> Livres : ' + livres_1 + '</span><br />' +
              '<i class="fas fa-ban text-danger"></i>' +
              '<span class="font-weight-bold" style="color: red"> Ocupados : ' + result[i].NR_UNID_OCUP + '</span><br />' +
              '<i class="fas fa-hourglass-half text-warning"></i>' +
              '<span class="font-weight-bold" style="color: #ffa500"> Indisponíveis : ' + temp_indisponiveis + '</span><br />' +
              '<i class="fas fa-asterisk text-info"></i>' +
              '<span class="font-weight-bold text-petroleum"> Reserv./ Higien./ Alta : ' + outros_leitos + '</span>' +
              '</div>' +
              '</div>' +
              '</div>' +
              '<div class="col-3 text-end">' +
              '<div class="icon icon-shape bg-gradient-hmdcc shadow text-center border-radius-md">' +
              '<i class="ni ni-building text-lg opacity-10 text-white" aria-hidden="true"></i>' +
              '<input id="id_area_antigo" name="id_area_antigo" value="' + parseInt(result[i].CD_CLASSIF_SETOR) + '" type="hidden"/>' +
              '</div>' +
              '</div>' +
              '</div>' +
              '</div>' +
              '</div>' +
              '</div>';
          }

          dataSets_grafico.push({
            label: result[i].DS_SETOR_ATENDIMENTO,
            tension: 0.4,
            borderWidth: 0,
            borderRadius: 4,
            borderSkipped: false,
            backgroundColor: gerar_cor(),
            data: [parseFloat(percent).toFixed(2)],
            maxBarThickness: 50
          });
        }

        var d = new Date(result[0].DT_ATUALIZACAO);
        var curr_day = d.getDate(result[0].DT_ATUALIZACAO);
        var curr_month = d.getMonth(result[0].DT_ATUALIZACAO);
        var curr_year = d.getFullYear(result[0].DT_ATUALIZACAO);
        var curr_hour = d.getHours(result[0].DT_ATUALIZACAO);
        var curr_min = d.getMinutes(result[0].DT_ATUALIZACAO);
        curr_month++; // In js, first month is 0, not 1
        var year_2d = curr_year.toString().substring(2, 4)

        if (isNaN(curr_day) || isNaN(curr_hour)) {
          var hora_iphone = result[0].DT_ATUALIZACAO.split(" ");
          hora_iphone = hora_iphone[1];
          $("#data_ult_att").html("<small>Atualizado em: " + result[0].DT_ATUALIZACAO.substr(0, 10).split('-').reverse().join('/') + " " + hora_iphone + "</small>");
        } else {
          $("#data_ult_att").html("<small>Atualizado em: " + String(curr_day).padStart(2, "0") + "/" + String(curr_month).padStart(2, "0") + "/" + year_2d + " " + String(curr_hour).padStart(2, "0") + ":" + String(curr_min).padStart(2, "0") + "</small>");
        }

        $("#divPercentualGeral").html(htmlPercentual);
        var ctx = document.getElementById("chart-bars").getContext("2d");

        new Chart(ctx, {
          type: "bar",
          data: {
            labels: ["Ocupação"],
            datasets: dataSets_grafico,
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: true,
                labels: {
                  color: '#344767' // Updated for better visibility on light bg
                }
              }
            },
            interaction: {
              intersect: false,
              mode: 'index',
            },
            scales: {
              y: {
                grid: {
                  drawBorder: true,
                  display: true,
                  drawOnChartArea: true,
                  drawTicks: true,
                },
                ticks: {
                  suggestedMin: 0,
                  suggestedMax: 100, // Changed from 500 to 100 for percentage
                  beginAtZero: true,
                  padding: 15,
                  font: {
                    size: 14,
                    family: "Open Sans",
                    style: 'normal',
                    lineHeight: 2
                  },
                  color: "#344767"
                },
              },
              x: {
                grid: {
                  drawBorder: false,
                  display: false,
                  drawOnChartArea: false,
                  drawTicks: false
                },
                ticks: {
                  display: false
                },
              },
            },
          },
        });
      },
      error: function (data) {
        console.error('Erro ao carregar dados do dashboard');
      }
    });

    // Rotator logic for 'P' profile
    if (config.userProfile === 'P') {
      $('#div_pagina_geral').addClass('semPaddingCima');
      setTimeout(function () {
        $.ajax({
          url: config.urls.retornaSetorLoopPainel,
          type: 'POST',
          data: { "atual": $("#painel_variavel_controle").val() },
          dataType: "json",
          success: function (data) {
            var proximo_att = data.PROXIMO;
            $.ajax({
              url: config.urls.atualizarVariavelPainelControleSessao,
              type: 'POST',
              data: { "proximo": proximo_att },
              dataType: "json",
              success: function (a) {
                $(".cardGeral").hide();
                $("#cardGeral_" + $("#painel_variavel_controle").val().toString()).removeClass("col-xl-4").addClass("col-xl-4 margemTV");
                $("#cardGeral_" + $("#painel_variavel_controle").val().toString()).show();
                abrirDivDetalhes($("#painel_variavel_controle").val());
              },
              error: function (data) {
                console.error('Não foi possível definir próximo detalhe!');
              }
            });
          },
          error: function (data) {
            console.error('Não foi possível retornar detalhe atual!');
          }
        });
      }, 90000);
    }

    // --- AVALIACOES VERDE/VERMELHO LOGIC ---
  } else if (config.currentPage === 'avaliacoesVerdeVermelho') {
    let html_leito = "";
    let nr_atendimento = $("#nr_atendimento_id").val();
    if (!nr_atendimento) return;

    $.ajax({
      url: config.urls.retornaTotaisAvaliacoesVerdeVermelho,
      type: 'POST',
      data: { "nr_atendimento": nr_atendimento },
      dataType: "json",
      success: function (totaisVerdeVemelho) {
        let html_totais_verde_vermelho = "";

        if (parseInt(totaisVerdeVemelho.total) > 0) {
          let regra3_verde = Math.round(totaisVerdeVemelho.porcentagem_verde) * 255 / 100;
          let regra3_vermelho = Math.round(totaisVerdeVemelho.porcentagem_vermelho) * 255 / 100;

          html_totais_verde_vermelho =
            "<tr><td colspan='2'></td></tr>" +
            "<tr>" +
            "<td class='font-weight-bold text-wrap text-xs'>Total de avaliações</td>" +
            "<td class='text-wrap text-end justify-content-end text-xs'>" + totaisVerdeVemelho.total + "</td>" +
            "</tr>" +
            "<tr>" +
            "<td class='font-weight-bold text-wrap text-xs'>Total verde</td>" +
            "<td class='text-wrap text-end justify-content-end text-xs'>" + totaisVerdeVemelho.total_verde + "</td>" +
            "</tr>" +
            "<tr>" +
            "<td class='font-weight-bold text-wrap text-xs'>% verde</td>" +
            "<td class='text-wrap text-end justify-content-end text-xs'>" + parseFloat(totaisVerdeVemelho.porcentagem_verde).toFixed(2) + "%</td>" +
            "</tr>" +
            "<tr>" +
            "<td class='font-weight-bold text-wrap text-xs'>Total vermelho</td>" +
            "<td class='text-wrap text-end justify-content-end text-xs'>" + totaisVerdeVemelho.total_vermelho + "</td>" +
            "</tr>" +
            "<tr>" +
            "<td class='font-weight-bold text-wrap text-xs'>% vermelho</td>" +
            "<td class='text-wrap text-end justify-content-end text-xs'>" + parseFloat(totaisVerdeVemelho.porcentagem_vermelho).toFixed(2) + "%</td>" +
            "</tr>" +
            "<tr>" +
            "<td colspan='2' class='text-wrap justify-content-center' style='text-align:-webkit-center'>" +
            "<div style='background-color: rgb(" + regra3_vermelho + " " + regra3_verde + " 0); border-radius:50%; width:50px;height:50px'></div>" +
            "</td>" +
            "</tr>";
        }

        $.ajax({
          url: config.urls.retornaDadosLeitoPorAtendimento,
          type: 'POST',
          data: { "nr_atendimento": nr_atendimento },
          dataType: "json",
          success: function (result) {
            $.ajax({
              url: config.urls.retornaMovimentacoesAtendimento,
              type: 'POST',
              data: { "nr_atendimento": nr_atendimento },
              dataType: "json",
              success: function (resultadoMovimentacoes) {
                let data_entrada = new Date(result["dt_entrada"]);
                let data_prev_alta = new Date(result["dt_previsao_alta"]);
                let dt_liberacao = new Date(result["dt_liberacao"]);

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

                let data_nascimento = result["dt_nascimento"].split("/");
                let idade_paciente = idade(data_nascimento[2], data_nascimento[1], data_nascimento[0]);

                /*INÍCIO PREENCHIMENTO TABELA DE AVALIAÇÃO VERDE/VERMELHO*/
                if (result["ds_verde_ou_vermelho"] && result["cd_agrupamento"] != 4) {
                  let cor = "";
                  if (result["ds_verde_ou_vermelho"].trim().toUpperCase() == "VERDE") {
                    cor = "#00ff00";
                  } else {
                    cor = "#ff0000";
                  }

                  if (result["ds_motivo_vermelho"] && result["ds_motivo_vermelho"].length > 0) {
                    motivo_vermelho =
                      "<tr>" +
                      "<td class='font-weight-bold text-wrap'>Motivo</td>" +
                      "<td class='text-wrap text-justify'>" + result["ds_motivo_vermelho"] + "</td>" +
                      "</tr>";
                  }

                  conteudo_template_avaliacao =
                    "<table class='table align-items-center justify-content-center' width='100%'>" +
                    html_totais_verde_vermelho +
                    "<tr>" +
                    "<td colspan='2' class='text-center text-uppercase font-weight-bold text-wrap'>Última Avaliação</td>" +
                    "</tr>" +
                    "<tr>" +
                    "<td class='font-weight-bold text-wrap'>Data avaliação</td>" +
                    "<td class='text-wrap text-justify'>" + dt_liberacao.toLocaleString().replace(',', '') + "</td>" +
                    "</tr>" +
                    "<tr>" +
                    "<td class='font-weight-bold text-wrap'>Profissional</td>" +
                    "<td class='text-wrap text-justify'>" + result["profissional_verde_vermelho"] + "</td>" +
                    "</tr>" +
                    "<tr>" +
                    "<td class='font-weight-bold text-wrap'>Condição</td>" +
                    "<td class='text-wrap'>" +
                    "<div class='w-full text-center' style='border-radius:4px; color:#fff; background-color:" + cor + "'>" +
                    result["ds_verde_ou_vermelho"].trim().toUpperCase() +
                    "</div>" +
                    "</td>" +
                    "</tr>" +
                    motivo_vermelho +
                    "<tr>" +
                    "<td class='font-weight-bold text-wrap'>Previsão de alta</td>" +
                    "<td class='text-wrap'>" + data_prev_alta + "</td>" +
                    "</tr>" +
                    "</table>";
                }
                /*FIM PREENCHIMENTO TABELA DE AVALIAÇÃO VERDE/VERMELHO*/

                /*INÍCIO PROCESSAMENTO MOVIMENTAÇÕES*/
                if (resultadoMovimentacoes.length > 0) {
                  html_movimentacoes_atendimento =
                    "<table class='table align-items-center justify-content-center' width='100%'>" +
                    "<tr><td class='text-center text-uppercase text-wrap font-weight-bold' colspan='5'>Movimentações</td></tr>" +
                    "<tr>" +
                    "<td class='text-xs font-weight-bold text-wrap'>Setor</td>" +
                    "<td class='text-xs font-weight-bold text-wrap'>Leito</td>" +
                    "<td class='text-xs text-wrap font-weight-bold'>Entrada</td>" +
                    "<td class='text-xs text-wrap font-weight-bold'>Saída</td>" +
                    "<td class='text-xs text-wrap font-weight-bold'>Dias</td>" +
                    "</tr>";

                  for (let j = 0; j < resultadoMovimentacoes.length; j++) {
                    let data_entrada_unidade = new Date(resultadoMovimentacoes[j]["dt_entrada_unidade"]);
                    let data_saida_unidade = new Date(resultadoMovimentacoes[j]["dt_saida_unidade"]);

                    let hora_entrada_u = resultadoMovimentacoes[j]["dt_entrada_unidade"].split(" ");
                    hora_entrada_u = hora_entrada_u[1];
                    data_entrada_unidade = resultadoMovimentacoes[j]["dt_entrada_unidade"].substr(0, 10).split('-').reverse().join('/') + " " + hora_entrada_u;
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

                    html_movimentacoes_atendimento +=
                      "<tr>" +
                      "<td class='" + cor_linha_cond_mesmo_setor + " font-weight-bold text-wrap text-xs'>" + resultadoMovimentacoes[j]["ds_setor_atendimento"] + "</td>" +
                      "<td class='" + cor_linha_cond_mesmo_setor + " text-wrap text-xs'>" + resultadoMovimentacoes[j]["leito"] + ' ' + resultadoMovimentacoes[j]["ds_complemento_leito"] + "</td>" +
                      "<td class='" + cor_linha_cond_mesmo_setor + " text-wrap text-xs'>" + data_entrada_unidade + "</td>" +
                      "<td class='" + cor_linha_cond_mesmo_setor + " text-wrap text-xs'>" + data_saida_unidade + "</td>" +
                      "<td class='" + cor_linha_cond_mesmo_setor + " text-wrap text-xs'>" + resultadoMovimentacoes[j]["qt_dias_unidade"] + "</td>" +
                      "</tr>";
                  }

                  // Permanência na mesma linha de cuidado
                  let somatoria_dias_linha_cuidado = 0;
                  for (let k = resultadoMovimentacoes.length - 1; k >= 0; k--) {
                    if (resultadoMovimentacoes[k]["cd_agrupamento"] == $("#linha_cuidado_id").val()) {
                      somatoria_dias_linha_cuidado = parseInt(resultadoMovimentacoes[k]["qt_dias_unidade"]) + parseInt(somatoria_dias_linha_cuidado);
                    } else {
                      break;
                    }
                  }

                  html_movimentacoes_atendimento +=
                    "<tr class='my-4'>" +
                    "<td colspan='4' class='text-xs font-weight-bold text-wrap text-white '></td>" +
                    "<td class='text-xs text-wrap font-weight-bold text-white'></td>" +
                    "</tr>" +
                    "<tr>" +
                    "<td colspan='4' class='text-xs font-weight-bold text-wrap text-white cor_card_mesma_linha_cuidado'>Total atual na Linha de Cuidado</td>" +
                    "<td class='text-xs text-wrap font-weight-bold text-white cor_card_mesma_linha_cuidado'>" + somatoria_dias_linha_cuidado + " dia(s)</td>" +
                    "</tr>" +
                    "<tr>" +
                    "<td colspan='4' class='text-xs font-weight-bold text-wrap'>Total</td>" +
                    "<td class='text-xs text-wrap font-weight-bold'>" + resultadoMovimentacoes[0]["total_dias_unidade"] + " dia(s)</td>" +
                    "</tr>" +
                    "</table>";
                }
                /*FIM PROCESSAMENTO MOVIMENTAÇÕES*/

                html_leito =
                  "<table class='table align-items-center justify-content-center' width='100%'>" +
                  "<tr><td class='font-weight-bold text-wrap'>Paciente</td><td class='text-wrap'>" + result['ds_nome_paciente'] + "</td></tr>" +
                  "<tr><td class='font-weight-bold text-wrap'>Idade</td><td class='text-wrap'>" + idade_paciente + " anos</td></tr>" +
                  "<tr><td class='font-weight-bold text-wrap'>Nº atendimento</td><td class='text-wrap'>" + result['nr_atendimento'] + "</td></tr>" +
                  "<tr><td class='font-weight-bold text-wrap'>Leito</td><td class='text-wrap'>" + result['ds_leito_atual'] + "</td></tr>" +
                  "<tr><td class='font-weight-bold text-wrap'>Data de entrada</td><td class='text-wrap'>" + data_entrada + "</td></tr>" +
                  "<tr><td class='font-weight-bold text-wrap'>Tempo de internação</td><td class='text-wrap'>" + result['tempo_internacao'] + " dia(s)</td></tr>" +
                  "</table>" +
                  "<div class='bg-gradient border-radius-lg pe-1'>" +
                  "<div class='chart'>" +
                  "<canvas name='grafico_quantidade_verdes_vermelhos' id='grafico_quantidade_verdes_vermelhos' class='chart-canvas' height='250'></canvas>" +
                  "</div>" +
                  "</div>" +
                  conteudo_template_avaliacao +
                  html_movimentacoes_atendimento;

                $("#info_principal").html(html_leito);

                // Chart generation for verde/vermelho
                let dataset_verde_vermelho = [];
                dataset_verde_vermelho.push({
                  label: "Verdes",
                  tension: 0.4, borderWidth: 0, borderRadius: 4, borderSkipped: false,
                  backgroundColor: "#00ff00",
                  data: [parseInt(totaisVerdeVemelho.total_verde)],
                  maxBarThickness: 50
                });
                dataset_verde_vermelho.push({
                  label: "Vermelhos",
                  tension: 0.4, borderWidth: 0, borderRadius: 4, borderSkipped: false,
                  backgroundColor: "#ff0000",
                  data: [parseInt(totaisVerdeVemelho.total_vermelho)],
                  maxBarThickness: 50
                });

                var ctx = document.getElementById("grafico_quantidade_verdes_vermelhos").getContext("2d");
                new Chart(ctx, {
                  type: "bar",
                  data: { labels: ["Verdes e Vermelhos"], datasets: dataset_verde_vermelho },
                  options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: true, labels: { color: '#000' } } },
                    interaction: { intersect: false, mode: 'index' },
                    scales: {
                      y: {
                        grid: { drawBorder: true, display: true, drawOnChartArea: true, drawTicks: true },
                        ticks: { suggestedMin: 0, suggestedMax: 500, beginAtZero: true, padding: 15, font: { size: 14, family: "Open Sans", style: 'normal', lineHeight: 2 }, color: "#000" }
                      },
                      x: {
                        grid: { drawBorder: true, display: true, drawOnChartArea: true, drawTicks: true },
                        ticks: { display: true }
                      }
                    }
                  }
                });
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
      },
      error: function (data) {
        alert('Não foi possível abrir o detalhamento. Confira sua conexão!');
      }
    });

    // --- HISTORICO PAGES ---
  } else if (['historicoEvolucoesPaciente', 'historicoInterconsultasPaciente', 'historicoExamesLabPaciente', 'historicoExamesImagemPaciente'].includes(config.currentPage)) {
    let nr_atendimento = $("#nr_atendimento_id").val();
    if (!nr_atendimento) return;

    $.ajax({
      url: config.urls.retornaDadosLeitoPorAtendimento,
      type: 'POST',
      data: { "nr_atendimento": nr_atendimento },
      dataType: "json",
      success: function (result) {
        $.ajax({
          url: config.urls.retornaMovimentacoesAtendimento,
          type: 'POST',
          data: { "nr_atendimento": nr_atendimento },
          dataType: "json",
          success: function (resultadoMovimentacoes) {
            let data_entrada = new Date(result["dt_entrada"]);
            let data_prev_alta = new Date(result["dt_previsao_alta"]);
            let dt_liberacao = new Date(result["dt_liberacao"]);

            let hora_entrada_geral = result["dt_entrada"].split(" ");
            hora_entrada_geral = hora_entrada_geral[1];
            data_entrada = result["dt_entrada"].substr(0, 10).split('-').reverse().join('/') + " " + hora_entrada_geral;
            if (data_entrada == "00/00/0000 00:00:00") {
              data_entrada = " - ";
            }

            let hora_liberacao_h = result["dt_liberacao"].split(" ");
            hora_liberacao_h = hora_liberacao_h[1];
            dt_liberacao = result["dt_liberacao"].substr(0, 10).split('-').reverse().join('/') + " " + hora_liberacao_h;
            if (dt_liberacao == "00/00/0000 00:00:00") {
              dt_liberacao = " - ";
            }

            if (data_prev_alta.toLocaleString() !== "Invalid Date") {
              let dt_arr = result["dt_previsao_alta"].split('-');
              data_prev_alta = dt_arr[2] + '/' + dt_arr[1] + '/' + dt_arr[0];
            } else {
              data_prev_alta = " - ";
            }

            let conteudo_template_avaliacao = "";
            let html_movimentacoes_atendimento = "";

            let data_nascimento = result["dt_nascimento"].split("/");
            let idade_paciente = idade(data_nascimento[2], data_nascimento[1], data_nascimento[0]);

            /*INÍCIO PROCESSAMENTO MOVIMENTAÇÕES*/
            if (resultadoMovimentacoes.length > 0) {
              html_movimentacoes_atendimento =
                "<table class='table align-items-center justify-content-center' width='100%'>" +
                "<tr><td class='text-center text-uppercase text-wrap font-weight-bold' colspan='5'>Movimentações</td></tr>" +
                "<tr>" +
                "<td class='text-xs font-weight-bold text-wrap'>Setor</td>" +
                "<td class='text-xs font-weight-bold text-wrap'>Leito</td>" +
                "<td class='text-xs text-wrap font-weight-bold'>Entrada</td>" +
                "<td class='text-xs text-wrap font-weight-bold'>Saída</td>" +
                "<td class='text-xs text-wrap font-weight-bold'>Dias</td>" +
                "</tr>";

              for (let j = 0; j < resultadoMovimentacoes.length; j++) {
                let data_entrada_unidade = new Date(resultadoMovimentacoes[j]["dt_entrada_unidade"]);
                let data_saida_unidade = new Date(resultadoMovimentacoes[j]["dt_saida_unidade"]);

                let hora_entrada_u = resultadoMovimentacoes[j]["dt_entrada_unidade"].split(" ");
                hora_entrada_u = hora_entrada_u[1];
                data_entrada_unidade = resultadoMovimentacoes[j]["dt_entrada_unidade"].substr(0, 10).split('-').reverse().join('/') + " " + hora_entrada_u;
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

                html_movimentacoes_atendimento +=
                  "<tr>" +
                  "<td class='" + cor_linha_cond_mesmo_setor + " font-weight-bold text-wrap text-xs'>" + resultadoMovimentacoes[j]["ds_setor_atendimento"] + "</td>" +
                  "<td class='" + cor_linha_cond_mesmo_setor + " text-wrap text-xs'>" + resultadoMovimentacoes[j]["leito"] + ' ' + resultadoMovimentacoes[j]["ds_complemento_leito"] + "</td>" +
                  "<td class='" + cor_linha_cond_mesmo_setor + " text-wrap text-xs'>" + data_entrada_unidade + "</td>" +
                  "<td class='" + cor_linha_cond_mesmo_setor + " text-wrap text-xs'>" + data_saida_unidade + "</td>" +
                  "<td class='" + cor_linha_cond_mesmo_setor + " text-wrap text-xs'>" + resultadoMovimentacoes[j]["qt_dias_unidade"] + "</td>" +
                  "</tr>";
              }

              // Permanência na mesma linha de cuidado
              let somatoria_dias_linha_cuidado = 0;
              for (let k = resultadoMovimentacoes.length - 1; k >= 0; k--) {
                if (resultadoMovimentacoes[k]["cd_agrupamento"] == $("#linha_cuidado_id").val()) {
                  somatoria_dias_linha_cuidado = parseInt(resultadoMovimentacoes[k]["qt_dias_unidade"]) + parseInt(somatoria_dias_linha_cuidado);
                } else {
                  break;
                }
              }

              html_movimentacoes_atendimento +=
                "<tr class='my-4'>" +
                "<td colspan='4' class='text-xs font-weight-bold text-wrap text-white '></td>" +
                "<td class='text-xs text-wrap font-weight-bold text-white'></td>" +
                "</tr>" +
                "<tr>" +
                "<td colspan='4' class='text-xs font-weight-bold text-wrap text-white cor_card_mesma_linha_cuidado'>Total atual na Linha de Cuidado</td>" +
                "<td class='text-xs text-wrap font-weight-bold text-white cor_card_mesma_linha_cuidado'>" + somatoria_dias_linha_cuidado + " dia(s)</td>" +
                "</tr>" +
                "<tr>" +
                "<td colspan='4' class='text-xs font-weight-bold text-wrap'>Total</td>" +
                "<td class='text-xs text-wrap font-weight-bold'>" + resultadoMovimentacoes[0]["total_dias_unidade"] + " dia(s)</td>" +
                "</tr>" +
                "</table>";
            }
            /*FIM PROCESSAMENTO MOVIMENTAÇÕES*/

            let html_leito =
              "<table class='table align-items-center justify-content-center' width='100%'>" +
              "<tr><td class='font-weight-bold text-wrap'>Paciente</td><td class='text-wrap'>" + result['ds_nome_paciente'] + "</td></tr>" +
              "<tr><td class='font-weight-bold text-wrap'>Idade</td><td class='text-wrap'>" + idade_paciente + " anos</td></tr>" +
              "<tr><td class='font-weight-bold text-wrap'>Nº atendimento</td><td class='text-wrap'>" + result['nr_atendimento'] + "</td></tr>" +
              "<tr><td class='font-weight-bold text-wrap'>Leito</td><td class='text-wrap'>" + result['ds_leito_atual'] + "</td></tr>" +
              "<tr><td class='font-weight-bold text-wrap'>Data de entrada</td><td class='text-wrap'>" + data_entrada + "</td></tr>" +
              "<tr><td class='font-weight-bold text-wrap'>Tempo de internação</td><td class='text-wrap'>" + result['tempo_internacao'] + " dia(s)</td></tr>" +
              "</table>" +
              conteudo_template_avaliacao +
              html_movimentacoes_atendimento;

            // Special handling for image exams page
            if (config.currentPage === 'historicoExamesImagemPaciente') {
              $.ajax({
                url: config.urls.retornaTabelaExames,
                type: 'POST',
                data: { "nr_prontuario": $("#nr_prontuario_id").val() },
                dataType: "json",
                success: function (resultadoTabelaExames) {
                  html_leito += resultadoTabelaExames;
                  $("#info_principal").html(html_leito);
                },
                error: function (data) {
                  alert('Não foi possível abrir o detalhamento. Confira sua conexão!');
                }
              });
            } else {
              $("#info_principal").html(html_leito);
            }
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

    // --- LEITOS PAGE ---
  } else if (config.currentPage === 'leitos') {
    if (config.userProfile === 'P') {
      $("#div_pagina_geral").removeClass('py-4');
      function recarregarComAncora() {
        window.location.reload(true);
      }
      setInterval(recarregarComAncora, 300000); // 5 min
      var target = $("#ultima_atualizacao_div");
      if (target.length) {
        $('html, body').animate({ scrollTop: target.offset().top }, 0);
      }
    }
  }
});

// Helper Functions
// Persistent state for non-repeating colors
let shuffledPalette = [];
let paletteIndex = 0;

function gerar_cor(opacidade = 1) {
  const basePalette = [
    '0, 171, 151',   // HMDCC Green
    '87, 191, 207',  // Sky Blue
    '247, 168, 14',  // Royal Yellow
    '239, 125, 26',  // Orangiu
    '149, 28, 127',  // Ype Purple
    '41, 41, 41',    // Classic Black
    '15, 70, 84',    // Petroleum Blue
    '208, 209, 209', // Light Grey
    // '62, 62, 62'     // Dark Grey
  ];

  // Initialize or reset if we've used all colors
  if (shuffledPalette.length === 0 || paletteIndex >= shuffledPalette.length) {
    shuffledPalette = [...basePalette].sort(() => Math.random() - 0.5);
    paletteIndex = 0;
  }

  const color = shuffledPalette[paletteIndex++];
  return `rgba(${color}, ${opacidade})`;
}

function idade(ano, mes, dia) {
  var hoje = new Date();
  var nascimento = new Date(ano, mes - 1, dia);
  var idade = hoje.getFullYear() - nascimento.getFullYear();
  var m = hoje.getMonth() - nascimento.getMonth();
  if (m < 0 || (m === 0 && hoje.getDate() < nascimento.getDate())) {
    idade--;
  }
  return idade;
}
