<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="row">
                    <div class="col-lg-6 col-7">
                        <h6>Log por período</h6>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center justify-content-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nome</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder"><?php echo $dados_usuario["NOME"];?></th>
                            </tr>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder"><?php echo $dados_usuario["STATUS"];?></th>
                            </tr>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Perfil</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder"><?php echo date('d/m/Y H:i:s', strtotime($dados_usuario["NOME_TIPO_PERFIL"]));?></th>
                            </tr>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Último Login</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder"><?php echo $dados_usuario["ULTIMO_LOGIN"];?></th>
                            </tr>
                            <tr>
                                <th colspan="2" class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">Período de pesquisas de logs</th>
                            </tr>
                            <tr>
                                <th colspan="2" class="text-uppercase text-secondary text-xxs font-weight-bolder">Mês inicial</th>
                            </tr>
                            <tr>
                                <th colspan="2" class="text-uppercase text-secondary text-xxs font-weight-bolder">
                                    <input required='true' class="form-control" type="month" id="data1_log" name='data1_log'/>
                                    <span class="text-xxs">*Se vazio, o valor assumido será 05/2024.</span>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="2" class="text-uppercase text-secondary text-xxs font-weight-bolder">Mês final</th>
                            </tr>
                            <tr>
                                <th colspan="2" class="text-uppercase text-secondary text-xxs font-weight-bolder">
                                    <input required='true' class="form-control" type="month" id='data2_log' name='data2_log'/>
                                    <span class="text-xxs">*Se vazio, o valor assumido será o mês atual.</span>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="2" class="text-uppercase text-secondary text-xxs font-weight-bolder text-end">
                                    <button class="mb-0 btn btn-primary" onclick="pesquisaLogsUsuarioPeriodo();"> Pesquisar</button>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="tabela_logs" name="tabela_logs">

                        </tbody>
                    </table>
                    <input name="id_usuario_log" id="id_usuario_log" type="hidden" value='<?php echo $dados_usuario["ID"];?>'/>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../js/jquery-3.1.0.js"></script>
<script src="../js/jquery.js"></script>

<script>
    function pesquisaLogsUsuarioPeriodo(){
        let data1       = $("#data1_log").val();
        let data2       = $("#data2_log").val();
        let id_usuario  = $("#id_usuario_log").val();
        let dataAtual   = new Date();

        if(data1.length<1){
            data1 = "2024-05-01";
        }else{
            data1 = data1+"-01"; 
        }

        if(data2.length<1){
            data2 = dataAtual.getFullYear()+"-"+(dataAtual.getMonth()+1)+"-31";
        }else{
            data2 = data2+"-31";
        }

        $.ajax({
            url : "<?php echo site_url('logs/retornaLogUsuarioPeriodo');?>",
            type : 'POST',
            dataType: "json",
            data: 
            {
                "id_usuario" : id_usuario,
                "data1":data1,
                "data2":data2
            },
            success : function(data){
                var result = data;
                if(result.length>0){
                    var html_tabela = "<tr>"+
                                        "<td>"+
                                            "Data"+
                                        "</td>"+
                                        "<td>"+
                                            "Tipo de Log"+
                                        "</td>"+
                                        "<td>"+
                                            "Link"+
                                        "</td>"+
                                        "<td>"+
                                            "Função"+
                                        "</td>"+
                                        "<td>"+
                                            "Nr atendimento"+
                                        "</td>"+
                                        "<td>"+
                                            "Par. adc."+
                                        "</td>"+
                                        "<td>"+
                                            "Info dispositivo"+
                                        "</td>"+
                                    "</tr>";

                    let data_acesso = "";
                    let hora_acesso = "";
                    let funcao      = "";
                    let parametro   = "";
                    let n_atend     = "";
                    for(var i = 0;i<result.length;i++){
                        data_acesso = new Date(result[i]["DATA_ACESSO"]);
                        hora_acesso = result[i]["DATA_ACESSO"].split(" ");
                        hora_acesso = hora_acesso[1];
                        data_acesso = result[i]["DATA_ACESSO"].substr(0, 10).split('-').reverse().join('/')+" "+hora_acesso;

                        if(result[i]["FUNCAO_ACESSO"]==null){
                            funcao = "-";
                        }else{
                            funcao = result[i]["FUNCAO_ACESSO"];
                        }

                        if(result[i]["PARAMETRO_ADMIN"]==null){
                            parametro = "-";
                        }else{
                            parametro = result[i]["PARAMETRO_ADMIN"];
                        }

                        if(result[i]["NR_ATENDIMENTO"]==null){
                            n_atend = "-";
                        }else{
                            n_atend = result[i]["NR_ATENDIMENTO"];
                        }
                        html_tabela += '<tr>'+
                                            '<td>'+
                                                data_acesso+
                                            '</td>'+
                                            '<td>'+
                                                result[i]["TIPO_LOG"]+
                                            '</td>'+
                                            '<td>'+
                                                result[i]["LINK_ACESSO"]+
                                            '</td>'+
                                            '<td>'+
                                                funcao+
                                            '</td>'+
                                            '<td>'+
                                                n_atend+
                                            '</td>'+
                                            '<td>'+
                                                parametro+
                                            '</td>'+
                                            '<td>'+
                                                result[i]["INFO_DISPOSITIVO"]+
                                            '</td>'+   
                                        '</tr>';
                    }
                }else{
                    var html_tabela = "<tr><td class='text-center' colspan='2'>Este usuário não tem logs para serem exibidos no período escolhido.</td></tr>";
                }

                $("#tabela_logs").html(html_tabela);
            },
            error : function(data){
                alert('erro');
            }
        });

    }

</script>