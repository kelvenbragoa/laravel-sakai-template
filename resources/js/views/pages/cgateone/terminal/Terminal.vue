<template>
  <div v-if="loading" class="loader-overlay">
    <div class="louderL">
      <ProgressSpinner />
    </div>
  </div>

  <div class="card">
    
    <!-- <DataTable :value="transactions" :filters="filters" :loading="loading" :rows="rowsPerPage" :paginator="true"
      :total-records="totalRecords" :first="(currentPage - 1) * rowsPerPage" @page="onPageChange" :global-filter-fields="[
        'transaction_gate',
        'driver_name',
        'truck_license_plate_number',
        'status',
        'type',
      ]" table-style="min-width: 60rem"> -->

    <DataTable :value="transactions" paginator :rows="rowsPerPage" :totalRecords="totalRecords" lazy :first="first"
      @page="onPageChange">
      <template #header>
        <div class="flex justify-between align-center">
          <h2>
            Gate selecionado: <strong>{{ gateId }}</strong>
          </h2>
          <div class="groupExel">
            <Button @click="exportToExcel">Excel</Button>
            <div class="calendaryFilter">
              <DatePicker v-model="startDate" fluid iconDisplay="input" showTime hourFormat="24"
                placeholder="Data de Início" />
              <span></span>
              <DatePicker v-model="endDate" fluid iconDisplay="input" class="dtPicker" showTime hourFormat="24"
                placeholder="Data de Fim" />
              <span></span>
              <Button class="dtFilter" icon="pi pi-filter" @click="filterDate" />
            </div>
            <IconField>
              <InputIcon>
                <i class="pi pi-search" />
              </InputIcon>
              <InputText v-model="filtroDados" placeholder="Pesquisar" />
              <!-- <InputText v-model="filtroChange" placeholder="Pesquisa" /> -->
            </IconField>
          </div>
        </div>
      </template>
      <template #empty> Nenhum dado encontrado. </template>
      <!-- <Column field="appointment_nbr" header="Appointment Number" style="min-width: 12rem" /> -->

      <div v-if="first_last_name">
        <Column field="first_last_name" header="Condutor" style="min-width: 12rem" />
      </div>
      <div v-else>
        <Column field="first_last_name_overwrite" header="Condutor" style="min-width: 12rem" />
      </div>
      <Column field="main_plate" header="Placa de caminhão" style="min-width: 12rem" />
      <Column field="gate" header="Gate" style="min-width: 12rem"></Column>
      <Column field="movement" header="Tipo de movimento" style="min-width: 12rem"></Column>
      <Column field="created_at" header="Criado" style="min-width: 12rem">
        <template #body="{ data }">
          {{ formatDate(data.created_at) }}
        </template>
      </Column>
      <Column field="created_by" header="Criado por" style="min-width: 12rem">
        <!-- <template #body="{ data }">
          <Tag :value="data.status" :severity="getSeverity(data.status)" />
        </template>
        <template #filter="{ filterModel, filterCallback }">
          <Select
            v-model="filterModel.value"
            @change="filterCallback()"
            :options="statuses"
            placeholder="Status"
            style="min-width: 12rem"
            :showClear="true"
            
          >
            <template #option="slotProps">
              <Tag
                :value="slotProps.option"
                :severity="getSeverity(slotProps.option)"
              />
            </template>
          </Select>
        </template> -->
      </Column>
      <Column header="Detalhes" style="min-width: 10rem">
        <template #body="{ data }">
          <!-- <Button class="btnEstiliza" label="PDF" icon="pi pi-file-pdf" @click="generatePDFCanva(data)"
            style="border: 0px" /> -->
          <Button class="btnEstiliza" label="VER" icon="pi pi-eye" @click="detailsOpen(data)" style="border: 0px" />
        </template>
      </Column>
    </DataTable>
  </div>



  <Dialog header="Detalhes" v-model:visible="dialogRoleUpdateVisible" :closable="true" :modal="true" :draggable="false"
    :resizable="false" style="width:  250mm; min-height: 90vh" :footer="productDialogFooterForm">
    <div class="containerDetailsDialog">
      <div id="pdf-content">
        <div class="detalhesLogo">
          <div class="detalhesName">
            <span>
              Detalhes da transação:
            </span>
            <span>
              <span>{{ dadosRelatorio.id == null ? emptyField : dadosRelatorio.id }}</span>
            </span>
          </div>
          <div class="logoCornelderRelatorio">
            <!-- <img src="/public/logo.png" alt=""> -->
            <div class="imageLogoPdf"></div>
          </div>
        </div>
        <div class="lineDiv"></div>
        <div class="columTable">
          <div class="lineRow">
            <span>Id</span>
            <span>{{ dadosRelatorio.id == null ? emptyField : dadosRelatorio.id }}</span>
          </div>
          <div class="lineRow">
            <span>gate</span>
            <span>{{ dadosRelatorio.gate == null ? emptyField : dadosRelatorio.gate }}</span>
          </div>
          <div class="lineRow">
            <span>Nome</span>
            <span>{{ dadosRelatorio.first_last_name == null ? emptyField : dadosRelatorio.first_last_name }}</span>
          </div>
          <div class="lineRow">
            <span>Criado por </span>
            <span>{{ dadosRelatorio.created_by == null ? emptyField : dadosRelatorio.created_by }}</span>
          </div>
          <div class="lineRow">
            <span>Tipo de movimento</span>
            <span>{{ dadosRelatorio.movement == null ? emptyField : dadosRelatorio.movement }}</span>
          </div>
          <div class="lineRow">
            <span>Verificação</span>
            <span>{{ dadosRelatorio.checklist == null ? emptyField : dadosRelatorio.checklist }}</span>
          </div>
          <div class="lineRow">
            <span>Quant. Containers</span>
            <span>{{ dadosRelatorio.containers == null ? emptyField : dadosRelatorio.containers }}</span>
          </div>
          <div class="lineRow">
            <span>Quant. trailers </span>
            <span>{{ dadosRelatorio.trailers == null ? emptyField : dadosRelatorio.trailers }}</span>
          </div>
          <div class="lineRow">
            <span>Número da carteira de motorista</span>
            <span>{{ dadosRelatorio.driver_license_number == null ? emptyField : dadosRelatorio.driver_license_number
              }}</span>
          </div>
          <div class="lineRow">
            <span>Placa</span>
            <span>{{ dadosRelatorio.main_plate == "" ? emptyField : dadosRelatorio.main_plate }}</span>
          </div>
          <div class="lineRow">
            <span>Atrelado 1</span>
            <span>{{ dadosRelatorio.trailer_1_license_plate_number ==
              null ? emptyField : dadosRelatorio.trailer_1_license_plate_number }}</span>
          </div>
          <div class="lineRow">
            <span>Atrelado 2</span>
            <span>{{ dadosRelatorio.trailer_2_license_plate_number ==
              null ? emptyField : dadosRelatorio.trailer_2_license_plate_number }}</span>
          </div>
          <div class="lineRow">
            <span>Num. Container</span>
            <span>{{ dadosRelatorio.container_number_1 == null ? emptyField : dadosRelatorio.container_number_1
              }}</span>
          </div>
          <div class="lineRow">
            <span>Num. Container 2</span>
            <span>{{ dadosRelatorio.container_number_2 == null ? emptyField : dadosRelatorio.container_number_2
              }}</span>
          </div>
          <div class="lineRow">
            <span>Num. Container 3</span>
            <span>{{ dadosRelatorio.container_number_3 == null ? emptyField : dadosRelatorio.container_number_3
              }}</span>
          </div>
          <div class="lineRow">
            <span>Selo do contêiner 2</span>
            <span>{{ dadosRelatorio.container_seal_number_2 ==
              null ? emptyField : dadosRelatorio.container_seal_number_2 }}</span>
          </div>
          <div class="lineRow">
            <span>Lista Verificação </span>
            <span>{{ dadosRelatorio.checklist_check == null ? emptyField : dadosRelatorio.checklist_check }}</span>
          </div>
          <div class="lineRow">
            <span>Nota de entrega verificação </span>
            <span>{{ dadosRelatorio.delivery_note_check == null ? emptyField : dadosRelatorio.delivery_note_check
              }}</span>
          </div>
          <div class="lineRow">
            <span>verificação de carteira de motorista </span>
            <span>{{ dadosRelatorio.driver_license_check == null ? emptyField : dadosRelatorio.driver_license_check
              }}</span>
          </div>

        </div>
        <div class="footerLd">
          <div class="lineDiv"></div>
          <div class="containerFooter">
            <div class="processadoPorCgate">
              <span>Processado por:</span>
              <span>
                C-gate
              </span>
            </div>
            <div class="processadoPorCgate">
              <span>{{ dataLk }}</span>
            </div>

            <div class="processadoPorCgate">
              <span>1/2</span>
            </div>
          </div>

        </div>
        <div class="imagensRelatorio">
          <div class="columTable">
            <div class="lineRow">
              <span>notes </span>
              <span>{{ dadosRelatorio.notes == null ? emptyField : dadosRelatorio.notes }}</span>
            </div>
            <div class="lineRow">
              <span>Atualização </span>
              <span>{{ dadosRelatorio.updated_by == null ? emptyField : dadosRelatorio.updated_by }}</span>
            </div>
            <div class="lineRow">
              <span>Atualizado em </span>
              <span>{{ dadosRelatorio.updated_at == null ? emptyField : dadosRelatorio.updated_at }}</span>
            </div>
            <div class="lineRow">
              <span>Criado em </span>
              <span>{{ dadosRelatorio.created_at == null ? emptyField : dadosRelatorio.created_at }}</span>
            </div>
          </div>
          <table>
            <thead>
              <th>
                Matrícula do caminhão
              </th>
              <th>
                Matrícula do atrelado 1
              </th>

            </thead>
            <tr>

              <td
                :style="{ backgroundImage: 'url(' + baseUrls.storageUrl + '' + dadosRelatorio.main_plate_cutout_photo + ')' }">
                <!-- <td style="background-image: url(/public/testeimgcgate.png);"> -->

                <div class="imgNone" v-if="dadosRelatorio.main_plate_cutout_photo == null">
                  <p>
                    Sem imagem
                  </p>
                </div>


                <div class="imgNone" v-else>

                </div>

              </td>
              <td
                :style="{ backgroundImage: `url(${baseUrls.storageUrl}${dadosRelatorio.trailer_1_license_plate_cutout_photo})` }">



                <div class="imgNone" v-if="dadosRelatorio.trailer_1_license_plate_cutout_photo == null">
                  <p>
                    Sem imagem
                  </p>
                </div>

                <div class="imgNone" v-else>

                </div>
              </td>

            </tr>
          </table>

          <table>
            <thead>
              <th>
                Fotografia do contêiner
              </th>

              <th>
                Fotografia da carta de condução
              </th>


            </thead>
            <tr>
              <td
                :style="{ backgroundImage: 'url(' + baseUrls.storageUrl + '' + dadosRelatorio.container_number_1_cutout_photo + ')' }">
                <div class="imgNone" v-if="dadosRelatorio.container_number_1_cutout_photo == null">
                  <p>
                    Sem imagem
                  </p>
                </div>

                <div class="imgNone" v-else>

                </div>
              </td>
              <td
                :style="{ backgroundImage: 'url(' + baseUrls.storageUrl + '' + dadosRelatorio.driver_license_cutout_photo + ')' }">
                <div class="imgNone" v-if="dadosRelatorio.driver_license_cutout_photo == null">
                  <p>
                    Sem imagem
                  </p>
                </div>

                <div class="imgNone" v-else>

                </div>
              </td>

            </tr>
          </table>

          <table>
            <thead>
              <th>
                Fotografia do contêiner 3
              </th>
              <th>
                Fotografia do contêiner 2
              </th>


            </thead>
            <tr>
              <td
                :style="{ backgroundImage: 'url(' + baseUrls.storageUrl + '' + dadosRelatorio.container_number_3_cutout_photo + ')' }">
                <div class="imgNone" v-if="dadosRelatorio.container_number_3_cutout_photo == null">
                  <p>
                    Sem imagem
                  </p>
                </div>

                <div class="imgNone" v-else>

                </div>
              </td>
              <td
                :style="{ backgroundImage: 'url(' + baseUrls.storageUrl + '' + dadosRelatorio.container_number_2_cutout_photo + ')' }">
                <div class="imgNone" v-if="dadosRelatorio.container_number_2_cutout_photo == null">
                  <p>
                    Sem imagem
                  </p>
                </div>

                <div class="imgNone" v-else>

                </div>
              </td>

            </tr>
          </table>

          <table>
            <thead>
              <th>
                Fotografia de selo 2
              </th>
              <th>
                Fotografia de selo 3
              </th>


            </thead>
            <tr>
              <td
                :style="{ backgroundImage: 'url(' + baseUrls.storageUrl + '' + dadosRelatorio.seal_2_cutout_photo + ')' }">
                <div class="imgNone" v-if="dadosRelatorio.seal_2_cutout_photo == null">
                  <p>
                    Sem imagem
                  </p>
                </div>

                <div class="imgNone" v-else>

                </div>
              </td>
              <td
                :style="{ backgroundImage: 'url(' + baseUrls.storageUrl + '' + dadosRelatorio.seal_3_cutout_photo + ')' }">
                <div class="imgNone" v-if="dadosRelatorio.seal_3_cutout_photo == null">
                  <p>
                    Sem imagem
                  </p>
                </div>

                <div class="imgNone" v-else>

                </div>
              </td>


            </tr>
          </table>
          <table>
            <thead>

              <th>
                Fotografia de selo 1
              </th>
              <th>
                Fotografia de atrelado 2
              </th>



            </thead>
            <tr>
              <td
                :style="{ backgroundImage: 'url(' + baseUrls.storageUrl + '' + dadosRelatorio.seal_1_cutout_photo + ')' }">
                <div class="imgNone" v-if="dadosRelatorio.seal_1_cutout_photo == null">
                  <p>
                    Sem imagem
                  </p>
                </div>

                <div class="imgNone" v-else>

                </div>
              </td>
              <td
                :style="{ backgroundImage: 'url(' + baseUrls.storageUrl + '' + dadosRelatorio.trailer_2_license_plate_cutout_photo + ')' }">
                <div class="imgNone" v-if="dadosRelatorio.trailer_2_license_plate_cutout_photo == null">
                  <p>
                    Sem imagem
                  </p>
                </div>

                <div class="imgNone" v-else>

                </div>
              </td>

            </tr>
          </table>
          <div class="footerLd">
            <div class="lineDiv"></div>
            <div class="containerFooter">
              <div class="processadoPorCgate">
                <span>Processado por:</span>
                <span>
                  C-gate
                </span>
              </div>
              <div class="processadoPorCgate">
                <span>{{ dataLk }}</span>
              </div>

              <div class="processadoPorCgate">
                <span>2/2</span>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
    <div class="flex">
      <button class="p-button p-component cores" @click="generatePDFCanva(dadosRelatorio)">
        Pdf
      </button>
      <button class="p-button p-component p-button-secondary mx-2" @click="dialogRoleUpdateVisible = false">
        SAIR
      </button>
    </div>
  </Dialog>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { FilterMatchMode } from "@primevue/core/api";
import { getCarga, getTransactions } from "@/api";
import { useRoute, useRouter } from "vue-router";
import { jsPDF } from "jspdf";
import json from "../../../../../../public/user.json";
import * as XLSX from "xlsx";
import { useToast } from "primevue/usetoast";
import { baseUrls } from "../../../../api";
import html2canvas from 'html2canvas';
import { nextTick } from 'vue';
import { backLog, permissionsAcess } from "../../../../utils/accesRoute";

if (permissionsAcess().adminAcesseSuperAdmin == false) {

  if (permissionsAcess().cgate1dotxfoundTerminal == false) {
    useRouter().push("/dashboard")
  }
}

const dialogRoleUpdateVisible = ref(false);

const dadoSearch = ref("");
const filtroDados = ref("");
const isActive = ref(true)
const userFiltro = ref([])
const dadosRelatorio = ref({
  id: null,
  gate: null,
  first_last_name: null,
  created_by: null,
  movement: null,
  checklist: null,
  containers: null,
  trailers: null,
  driver_license_number: null,
  main_plate: null,
  trailer_1_license_plate_number: null,
  trailer_2_license_plate_number: null,
  container_number_1: null,
  container_number_2: null,
  container_number_3: null,
  container_seal_number_2: null,
  checklist_check: null,
  delivery_note_check: null,
  driver_license_check: null,
  notes: null,
  updated_by: null,
  updated_at: null,
})



const toast = useToast();

const startDate = ref(null);
const dateError = ref(false);
const endDate = ref(null);

const totalRecords = ref(0);

const pageNumber = ref(1);

const users = ref([]);

const tabelaDados = ref({
  address: "Endereço",
  age: "Idade",
  email: "Email",
  estado: "Estado",
  gender: "Genero",
  id: "Id",
  monthlyFee: "Taxa mensal",
  name: "Nome",
  phone: "Telefone",
  tier: "Nível",
  time: "08:00",
});

const dataAtual = new Date();
const transactionsFilter = ref([])

const formatDates = (date) => {
  if (!date) return "";

  const d = new Date(date);
  const year = d.getFullYear();
  const month = String(d.getMonth() + 1).padStart(2, "0");
  const day = String(d.getDate()).padStart(2, "0");
  const hours = String(d.getHours()).padStart(2, "0");
  const minutes = String(d.getMinutes()).padStart(2, "0");
  const seconds = String(d.getSeconds()).padStart(2, "0");

  return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
};


const filterDate = async () => {
  const token = getToken();
  if (!token) {
    backLog()
    return;
  } else {
    if (!startDate.value || !endDate.value) {
      toast.add({
        severity: "error",
        summary: "Erro",
        detail: "Preencha os campos",
        life: 3000,
      });
      return;
    }


    const start = new Date(startDate.value);
    const end = new Date(endDate.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    end.setHours(0, 0, 0, 0);
    start.setHours(0, 0, 0, 0);

    if (end > today) {
      dateError.value = true;
      toast.add({
        severity: "error",
        summary: "Erro",
        detail: "A data não pode ser maior que a data de hoje.",
        life: 3000,
      });
      return;
    }

    if (start.getTime() === end.getTime()) {
      dateError.value = false;
    } else if (start > end) {
      dateError.value = true;
      toast.add({
        severity: "error",
        summary: "Erro",
        detail: "A data de início não pode ser maior que a data de fim.",
        life: 3000,
      });
      return;
    }

    const startFormatted = formatDates(startDate.value);
    const endFormatted = formatDates(endDate.value);

    try {
      const response = await axios.get(
        `${baseUrls.transacoes}`,

        {
          params: {
            startdatetime: startFormatted,
            enddatetime: endFormatted,
          },
          headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "application/json",
          },
        }
      );


      userFiltro.value = response.data.data;
      transactions.value = response.data.data.data
      transactionsFilter.value = transactions.value

    } catch (error) {
      console.error("Erro ao buscar dados:", error);
      toast.add({
        severity: "error",
        summary: "Erro",
        detail: "Não foi possível buscar os dados.",
        life: 3000,
      });
    }
  }
};

const tabelaDados2 = ref({
  appointment_nbr: "Nbr appointment",
  appointment_number: "Número appointment",
  comments: "Comentários",
  container_number_1: "Contêiner número 1",
  container_number_2: "Contêiner número 2",
  container_number_3: "Contêiner número 3",
  container_number_4: "Contêiner número 4",
  container_photo_1: "Foto do contêiner 1",
  container_photo_2: "Foto do contêiner 2",
  container_photo_3: "Foto do contêiner 3",
  container_photo_4: "Foto do contêiner 4",
  container_seal_1: "Selo do recipiente 1",
  container_seal_2: "Selo do recipiente 2",
  container_seal_3: "Selo do recipiente 3",
  container_seal_4: "Selo do recipiente 4",
  container_seal_photo_1: "Foto do selo do contêiner 1",
  container_seal_photo_2: "Foto do selo do contêiner 2",
  container_seal_photo_3: "Foto do selo do contêiner 3",
  container_seal_photo_4: "Foto do selo do contêiner 4",
  created_at: "Criado em",
  driver_license_number: "Número da carta de motorista",
  driver_license_photo: "Foto da carta",
  driver_name: "Condutor",
  external_ref_nbr: "referência externa nbr",
  id: "Id",
  imdg: "Imdg",
  logged_user: "Usuário",
  movement_type: "Tipo de movimento",
  status: "Estado",
  trailer_1_license_plate_number: "Número de matrícula do trailer 1",
  trailer_1_license_plate_photo: "Foto de matrícula do trailer 1",
  trailer_2_license_plate_number: "Número de matrícula do trailer 2",
  trailer_2_license_plate_photo: "Foto de matrícula do trailer 2",
  transaction_gate: "Portão",
  truck_license_plate_number: "Número da matrícula do caminhão",
  truck_license_plate_photo: "Foto da matrícula do caminhão",
  tv_key: "Tv key",
  type: "Tipot",
  updated_at: "Atualizado em",
});

const transactions = ref([]);
const totalRecords2 = ref(0);

const currentPage = ref(1);
const rowsPerPage = ref(50);
const filters2 = ref({
  global: { value: "" }, // Filtro global
});

const getToken = () => {
  return localStorage.getItem("access_token");
};
function removerGate(texto) {
  return texto.replace(/^Gate\s*/, '');
}

const buscarTransccoes = async (page = 1) => {
  loading.value = true

  const token = getToken();
  if (!token) {
    backLog()
    return;
  }
  try {
    const response = await axios.get(baseUrls.transacoes, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
      params: {
        gate: removerGate(gateId.value),
        page: page,
        query: dadoSearch.value
      }

    });
    transactions.value = response.data.data.data;
    transactionsFilter.value = transactions.value
    totalRecords.value = response.data.data.total
    // loading.value = true

  } catch (error) {
    console.error("Erro ao carregar dados fkdsjf,:", error);
  } finally {
    loading.value = false;
  }
};

const filtroChange = () => {
  loading.value = true;
  if (filtroDados.value.trim() === "") {
    // userFiltro.value = [...usersL.value];
    transactionsFilter.value = [...transactions.value]
    dadoSearch.value = filtroDados.value.toLowerCase()
    buscarTransccoes()
  } else {
    dadoSearch.value = filtroDados.value.toLowerCase()
    buscarTransccoes()
  }
};

const exportToExcel = () => {
  const worksheet = XLSX.utils.json_to_sheet(transactions.value);
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, "Transações");
  XLSX.writeFile(workbook, "transacoes.xlsx");
};


const onPageChange = (event) => {
  first.value = event.first
  const newPage = Math.floor(event.first / rowsPerPage.value) + 1
  buscarTransccoes(newPage)

}

const formatDate2 = (date) => {
  const options = { year: "numeric", month: "long", day: "numeric" };
  return new Date(date).toLocaleDateString(undefined, options);
};

const loadJson = async () => {
  try {
    const response = await fetch("/user.json");
    users.value = await response.json();
  } catch (error) {
    console.error("Erro ao carregar o JSON:", error);
  }
};

let timeoutId = null

watch(filtroDados, (value)=>{
  if (timeoutId) {
    clearTimeout(timeoutId)
  }

  timeoutId = setTimeout(() => {
    filtroChange()
   
  }, 500)
})

onMounted(() => {
  // fetchTransactions(currentPage.value);
  tratamentoDoId()
  buscarTransccoes();

});

const first = ref(0);

const route = useRoute();
const userId = route.params.id;
const gateId = ref(userId);

const tratamentoDoId = () => {
  if (Number(gateId.value.indexOf("Out")) > -1) {
    gateId.value = gateId.value.replace("Out", "");
    gateId.value = `Gate ${gateId.value}`;
  } else {
    gateId.value = gateId.value.replace("In", "");
    gateId.value = `Gate ${gateId.value}`;
  }
}

const data = ref([]);
const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  cargo_type: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  document_number: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  driver_name: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  truck_license_plate_number: {
    value: null,
    matchMode: FilterMatchMode.STARTS_WITH,
  },
  status: { value: null, matchMode: FilterMatchMode.EQUALS },
});
const statuses = ref(["Pending", "Done", "Started", "Cancelled"]);
const loading = ref(true);

const formatDate = (dateString) => {
  const options = {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  };
  const date = new Date(dateString);
  return date.toLocaleDateString("pt-BR", options);
};

const generatePDF = (rowData) => {
  const doc = new jsPDF();
  const larguraPagina = doc.internal.pageSize.width;
  const alturaPagina = doc.internal.pageSize.height;

  doc.setFontSize(10);
  doc.text(`Detalhes da transação: ${rowData.id}`, 20, 13);
  doc.addImage("/logo.png", "JPEG", larguraPagina - 60, 7, 40, 10);
  let y = 15;
  // Linha de separação
  doc.setLineWidth(0.1);
  doc.line(20, 20, 190, 20);
  y += 10;
  const l = false;

  // Função para converter hexadecimal em RGB
  function hexToRgb(hex) {
    var bigint = parseInt(hex.replace("#", ""), 16);
    var r = (bigint >> 16) & 255;
    var g = (bigint >> 8) & 255;
    var b = bigint & 255;
    return [r, g, b];
  }


  let color = hexToRgb("#f5f5f5");
  let color2 = hexToRgb("#ffffff");

  let corChange = false;
  // /images/logo.png
  doc.setFontSize(9);

  for (let key in rowData) {
    if (rowData[key] != null) {
      doc.setFont("helvetica", "normal");
      doc.setTextColor(0, 0, 0); // Preto
      if (!corChange) {
        doc.setFillColor(color2[0], color2[1], color2[2]);
        doc.rect(20, y, 80, 10, "F"); // Borda da célula

        doc.text(
          String(key).length > 30
            ? String(tabelaDados2.value[key]).substring(0, 20) + "..."
            : String(tabelaDados2.value[key]),
          25,
          y + 6
        );
        doc.setFillColor(color2[0], color2[1], color2[2]);
        doc.rect(100, y, larguraPagina / 2 - 15, 10, "F"); // Borda da célula
        doc.text(
          String(rowData[key]).length > 50
            ? String(rowData[key]).substring(0, 20) + "..."
            : String(rowData[key]),
          105,
          y + 6
        );
        corChange = true;
      } else {
        doc.setFillColor(color[0], color[1], color[2]);
        doc.rect(20, y, 80, 10, "F"); // Borda da célula
        doc.text(
          String(key).length > 30
            ? String(tabelaDados2.value[key]).substring(0, 20) + "..."
            : String(tabelaDados2.value[key]),
          25,
          y + 6
        );
        doc.setFillColor(color[0], color[1], color[2]);
        doc.rect(100, y, larguraPagina / 2 - 15, 10, "F"); // Borda da célula
        doc.text(
          String(rowData[key]).length > 50
            ? String(rowData[key]).substring(0, 20) + "..."
            : String(rowData[key]),
          105,
          y + 6
        );
        corChange = false;
      }

      y += 10;
    }
  }
  doc.setLineWidth(0.1);
  doc.line(20, y, 190, y);
  y += 10;
  doc.text("Imagens", 20, y);
  y += 10;
  const squareSize = 50; // Tamanho de cada quadrado (50x50)
  const startX = 20; // Posição X do primeiro quadrado
  const startY = y; // Posição Y de todos os quadrados
  const spacing = 10; // Espaçamento entre os quadrados

  // Desenhando os quadrados
  for (let i = 0; i < 3; i++) {
    const x = startX + i * (squareSize + spacing); // Calculando a posição X de cada quadrado
    doc.rect(x, startY, squareSize, squareSize); // Desenhando o quadrado
  }
  // doc.addImage(rowData.trailer_1_internal_cargo_photo , 'JPEG', 20, y, 180, 160);

  const data = new Date();
  // const hoje = data.getDate()+"/"+(data.getMonth()+1)+"/"+data.getUTCFullYear()+" - "+data.getHours+"h:"+data.getMinutes+"min:"+data.getSeconds+"s"
  const hoje =
    data.getDate() + "/" + (data.getMonth() + 1) + "/" + data.getUTCFullYear();

  doc.setLineWidth(0.1);
  doc.line(20, alturaPagina - 20, 190, alturaPagina - 20);

  doc.text("Processado por: CGate", 20, alturaPagina - 10);

  doc.text(String(hoje), larguraPagina / 2, alturaPagina - 10);

  doc.text("1/1", larguraPagina - 25, alturaPagina - 10);

  doc.save(`doc_${rowData.id}_${rowData.driver_name}_detalhes.pdf`);
};

const generatePDFCanva = async (rowData) => {
  loading.value = true
  dadosRelatorio.value = { ...rowData }

  await nextTick();
  isActive.value = false

  generatePDFs();


}

const generatePDFs = async () => {
  const pdf = new jsPDF("p", "mm", "a4");
  const pdfWidth = 210;
  const pdfHeight = 297;
  const margin = 10;
  const contentElement = document.getElementById("pdf-content");
  const imagensElement = document.querySelector(".imagensRelatorio");

  if (!contentElement || contentElement.style.display === "none") {
    return;
  }

  // corpo
  const contentCanvas = await html2canvas(contentElement, {
    useCORS: true,
    allowTaint: true,
    scale: 2
  });
  const contentImgData = contentCanvas.toDataURL("image/jpeg", 1.0);

  let contentHeight = (contentCanvas.height * (pdfWidth - 2 * margin)) / contentCanvas.width;
  pdf.addImage(contentImgData, "JPEG", margin, margin, pdfWidth - 2 * margin, contentHeight);

  //pagina 1
  pdf.addPage();

  // tabela
  const imagensCanvas = await html2canvas(imagensElement, {
    useCORS: true,
    allowTaint: true,
    scale: 2
  });
  const imagensImgData = imagensCanvas.toDataURL("image/jpeg", 1.0);
  let imagensHeight = (imagensCanvas.height * (pdfWidth - 2 * margin)) / imagensCanvas.width;

  pdf.addImage(imagensImgData, "JPEG", margin, margin, pdfWidth - 2 * margin, imagensHeight);

  pdf.save("transacoes_relatorio.pdf");

  loading.value = false
};

let dateToday = new Date()
const dataLk = ref(formatDate(String(dateToday)))
const emptyField = ref("Vazio")

const detailsOpen = (data) => {
  dialogRoleUpdateVisible.value = true;
  dadosRelatorio.value = data
};


watch(() => route.params.id, (newId) => {
  gateId.value = newId
  tratamentoDoId()
  buscarTransccoes()

})
const getSeverity = (status) => {
  switch (status) {
    case "Pending":
      return "warn";
    case "Completed":
      return "success";
    case "In Progress":
      return "info";
    case "Cancelled":
      return "danger";
    default:
      return null;
  }
};
</script>

<style scoped>
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

th,
td {
  padding: 8px;
  text-align: left;
}

th {
  background-color: #f2f2f2;
}

td {
  border-bottom: 1px solid #ddd;
}

p {
  font-size: 18px;
  color: #888;
}

.btnEstiliza {
  background-color: #5498f1;
}

.btnEstiliza:hover {
  background-color: #046df7 !important;
}
</style>
