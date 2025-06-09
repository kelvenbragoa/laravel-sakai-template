<template>

    <template v-if="loading">
        <div class="loader-overlay">
            <div class="louderL">
                <ProgressSpinner />
            </div>
        </div>
    </template>


    <!-- <div class="df" style="display: flex; flex-direction: column; width: 100%!important; border: 1px solid black;"><h1>mcids</h1></div> -->
    <div v-else>
        <div class="filterHeaderDashboard">
            <div class="calendaryFilte">
                <!-- <Calendar v-model="anoSelecionado" view="year" dateFormat="yy" placeholder="Escolha o ano"
                    class="mb-3" showOnFocus="false" /> -->
                <Calendar v-model="anoSelecionado" view="year" dateFormat="yy" showIcon :showOnFocus="false" placeholder="Escolha o ano" inputId="buttondisplay" />
               

                <!-- <div v-for="item in itensFiltrados" :key="item.id" class="p-3 mb-2 border-round shadow-1 bg-white">
                    <strong>{{ item.nome }}</strong><br />
                    Ano: {{ item.ano }}
                </div> -->
            </div>

        </div>
        <div style="width: calc(100vw - 380px); border: 0px solid red; display: flex; justify-content: space-between;">
            <div class="col-span-12 lg:col-span-6 xl:col-span-3" style="width: calc((100vw/3) - 140px);">

                <div class="card mb-0">
                    <div class="flex justify-between mb-4">
                        <div>
                            <span class="block text-muted-color font-medium mb-4">Total de Exceções</span>
                            <div class="text-surface-900 dark:text-surface-0 font-medium text-xl">
                                {{ variableDash.cards.total_exception }}</div>
                        </div>
                        <div class="flex items-center justify-center bg-blue-100 dark:bg-blue-400/10 rounded-border"
                            style="width: 2.5rem; height: 2.5rem">
                            <i class="pi pi-info-circle text-blue-500 text-xl"></i>
                        </div>
                    </div>
                    <!-- <span className="text-blue-500 font-medium">24 novos </span>
            <span className="text-500">na ult. sem.</span> -->
                </div>
            </div>
            <div class="col-span-12 lg:col-span-6 xl:col-span-3" style="width: calc((100vw/3) - 140px);">
                <div class="card mb-0">
                    <div class="flex justify-between mb-4">
                        <div>
                            <span className="block text-500 font-medium mb-3">Total Exceções Terminal</span>
                            <div className="text-900 font-medium text-xl">
                                {{ variableDash.cards.total_exception_container_terminal }}</div>
                        </div>
                        <div class="flex items-center justify-center bg-orange-100 dark:bg-orange-400/10 rounded-border"
                            style="width: 2.5rem; height: 2.5rem">
                            <i className="pi pi-info-circle text-orange-500 text-xl" />
                        </div>
                    </div>
                    <!-- <span className="text-blue-500 font-medium">5 </span>
                    <span className="text-500">alcançados</span> -->
                </div>
            </div>
            <div class="col-span-12 lg:col-span-6 xl:col-span-3" style="width: calc((100vw/3) - 140px);">
                <div class="card mb-0">
                    <div class="flex justify-between mb-4">
                        <div>
                            <span className="block text-500 font-medium mb-3">Total Exceções Carga Geral</span>
                            <div className="text-900 font-medium text-xl">
                                {{ variableDash.cards.total_exception_general_cargo }}</div>
                        </div>
                        <div class="flex items-center justify-center bg-cyan-100 dark:bg-cyan-400/10 rounded-border"
                            style="width: 2.5rem; height: 2.5rem">
                            <i className="pi pi-window-maximize text-cyan-500 text-xl" />
                        </div>
                    </div>
                    <!-- <span className="text-blue-500 font-medium">5 </span>
            <span className="text-500">novos</span> -->
                </div>
            </div>
        </div>
        <div class="graphics">
            <Fluid class="grid grid-cols-12 gap-8">
                <!-- <div class="col-span-12 xl:col-span-6">
                    <div class="card">
                        <div class="font-semibold text-xl mb-4"> Fluxo de Entrada e Saída</div>
                        <Chart type="line" :data="lineData" :options="lineOptions"></Chart>
                    </div>
                </div> -->

                <div class="col-span-12 xl:col-span-6">
                    <div class="card">
                        <div class="font-semibold text-xl mb-4">Transações</div>
                        <Chart type="bar" :data="transactionGraph" :options="transactionGraphOptions"></Chart>
                    </div>
                </div>

                <div class="col-span-12 xl:col-span-6">
                    <div class="card">
                        <div class="font-semibold text-xl mb-4">Fluxo de Exceções</div>
                        <Chart type="bar" :data="exptionsGraph" :options="barOptions"></Chart>
                    </div>
                </div>


                <!-- <div class="col-span-12 xl:col-span-6">
                    <div class="card flex flex-col items-center">
                        <div class="font-semibold text-xl mb-4">Tally IN / OUT Percentual</div>
                        <Chart type="pie" :data="pieData" :options="pieOptions"></Chart>
                    </div>
                </div>
                <div class="col-span-12 xl:col-span-6">
                    <div class="card flex flex-col items-center">
                        <div class="font-semibold text-xl mb-4">Distribuição de Exceções/div>
                            <Chart type="doughnut" :data="pieData2" :options="pieOptions2"></Chart>
                        </div>
                    </div>
                </div> -->
            </Fluid>
        </div>
    </div>
</template>


<script setup>
import { baseUrls, getDashboard } from '../../api';
import { ref, computed, watch } from 'vue';
import { variableDash } from '../../utils/variableDashboard';

const loading = ref(false);
const exptionsGraph = ref(null);
const transactionGraph = ref(null);
const transactionGraphOptions = ref(null);
const barOptions = ref(null);

const documentStyle = getComputedStyle(document.documentElement);
const textColor = documentStyle.getPropertyValue('--text-color');
const corAzul = '#1558b0'
const corAzul2 = '#1877F2'
const textColorSecondary = documentStyle.getPropertyValue('--text-color-secondary');
const surfaceBorder = documentStyle.getPropertyValue('--surface-border');

const getToken = () => {
    return localStorage.getItem("access_token");
};
const anoAtual = new Date().getFullYear()
const anoSelecionado = ref(new Date(anoAtual, 0))
console.log(anoSelecionado.value)
transactionGraphOptions.value = {
    plugins: {
        legend: {
            labels: {
                fontColor: "#1558b0"
            }
        }
    },
    scales: {
        x: {
            ticks: {
                color: textColorSecondary
            },
            grid: {
                color: surfaceBorder,
                drawBorder: false
            }
        },
        y: {
            ticks: {
                color: textColorSecondary
            },
            grid: {
                color: surfaceBorder,
                drawBorder: false
            }
        }
    }
};


barOptions.value = {
    plugins: {
        legend: {
            labels: {
                fontColor: "#FF0000"
            }
        }
    },
    scales: {
        x: {
            ticks: {
                color: textColorSecondary,
                font: {
                    weight: 500
                }
            },
            grid: {
                display: false,
                drawBorder: false
            }
        },
        y: {
            ticks: {
                color: textColorSecondary
            },
            grid: {
                color: surfaceBorder,
                drawBorder: false
            }
        }
    }
};



// console.log(await getDashboard(2024, getToken()))

const buscarDashboard = async (year = "2025") => {
    loading.value = true

    const token = getToken();
    if (!token) {
        backLog()
        return;
    }
    try {
        const response = await axios.get(baseUrls.dashboardCgate, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
            params: {
                year: year
            }

        });
        variableDash.value.cards = response.data.result.totals
        variableDash.value.graphTransaction = response.data.result.chart_transaction
        variableDash.value.graphExceptionsTerminal = response.data.result.chart_exception_cargo_terminal
        exptionsGraph.value = variableDash.value.graphExceptionsTerminal
        transactionGraph.value = response.data.result.chart_transaction

        // console.log("Yes")
        // console.log(response.data.result)

        // console.log("Exceptions dataset")
        // exptionsGraph.value.datasets[0].push(['teste'])

        // console.log(exptionsGraph.value.datasets[0])


    } catch (error) {
        console.error("Erro ao carregar dados:", error);
    } finally {
        loading.value = false;
    }
};

buscarDashboard()

// const itensFiltrados = computed(() => {
//     console.log("Yes")
// })


function yearSelected(novoAno) {
  const ano = new Date(novoAno).getFullYear()
  buscarDashboard(ano)

}


watch(anoSelecionado, (novoValor) => {
  if (novoValor) {
    yearSelected(novoValor)
  }
})
</script>

<style scoped>
.filterHeaderDashboard {
    width: calc((100vw - 380px));
    background-color: white;
    margin-bottom: 10px;
    padding: 15px 20px;
    border-radius: 4px;
    display: flex;
    align-content: center;
}

.calendaryFilte {
    border: 0px solid black;
    padding: 0px;
}

.graphics {
    width: calc((100vw - 380px));
    margin-top: 20px;
}

/* Altera a cor do ícone do calendário */
#buttondisplay+.p-inputtext+button .pi-calendar {
    color: red;
    /* Substitua por qualquer cor desejada */
}



</style>
