<script setup>
import FloatingConfigurator from "@/components/FloatingConfigurator.vue";
import { ref } from "vue";
import { useRouter } from "vue-router";
import { baseUrls } from "../../../../api/index";
import * as XLSX from 'xlsx';
import { useToast } from "primevue/usetoast";
import { nextTick } from 'vue';
import { jsPDF } from "jspdf";
import html2canvas from 'html2canvas';

const emptyField2 = ref("Vazio")

const containers = ref([]);
const updateContainerNumber = ref(null)
const idDocs = ref()
const router = useRouter();
const toast = useToast();

const isActive = ref(true)
const getToken = () => {
  return localStorage.getItem("access_token");
};

const token = ref("");
const precheckData = ref({
  containerNumber: null
});


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



const dialogGateUpdate = ref(false)

const dialogRoleUpdateVisible = ref(false);

const loading = ref(false);
const errorL = ref(" ");

const precheckList = async () => {
  errorL.value = " "
  emptyField()
  if (errorL.value == " ") {

    const tokens = getToken();
    if (!tokens) {
      backLog()
      return;
    } else {
      loading.value = true
      try {
        const response = await axios.get(`${baseUrls.precheckList}/${precheckData.value.containerNumber}`, {
          headers: {
            Authorization: `Bearer ${tokens}`,
          },
        });

        detailsOpen(response.data.data)
        loading.value = false

      } catch (e) {
        toast.add({ severity: 'error', summary: `Erro ao buscar apointment ${e}`, life: 3000 });
        loading.value = false
      }
    }

  }

};

const precheck = async () => {
  errorL.value = " "
  emptyField()
  if (errorL.value == " ") {
    
    let dados = {
      appointment_number: precheckData.value.containerNumber
    }
    const tokens = getToken();
    if (!tokens) {
      backLog()
      return;
    } else {
      loading.value = true
      try {
        const response = await axios.post(`${baseUrls.precheckCheckappointment}`, dados, {
          headers: {
            Authorization: `Bearer ${tokens}`,
          },
        });
        toast.add({ severity: 'success', summary: `Precheck feito`, life: 3000 });
        loading.value = false
        dialogRoleUpdateVisible.value = false
        precheckData.value.containerNumber = null

      } catch (e) {
        toast.add({ severity: 'error', summary: `Erro ao buscar as permissões ${e}`, life: 3000 });
        loading.value = false
      }
    }

  }

};

const detailsOpen = (data) => {
  dialogRoleUpdateVisible.value = true;
  dadosRelatorio.value = data

};
const goToPrecheck = () => {
  router.push("/")
}
const checked = ref(false);


const emptyField = () => {
  for (var element in precheckData.value) {
    if (precheckData.value[element] == "") {
      errorL.value = "Campo vazio"
    }
  }

}


function handleFileUpload(event) {
  const file = event.target.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = (e) => {
    const data = new Uint8Array(e.target.result);
    const workbook = XLSX.read(data, { type: 'array' });

    const sheetName = workbook.SheetNames[0];
    const worksheet = workbook.Sheets[sheetName];

    const jsonData = XLSX.utils.sheet_to_json(worksheet);

    const dataWithId = jsonData.map((item, index) => ({
      id: index + 1,
      ...item
    }));

    containers.value = dataWithId;
  };

  reader.readAsArrayBuffer(file);
  event.target.value = "";
}



const atualizarDados = (dados) => {
  dialogGateUpdate.value = true
  idDocs.value = (dados.id - 1)

  updateContainerNumber.value = Number(dados.appointment_number)
}

const atualizar = () => {
  containers.value[idDocs.value].appointment_number = String(updateContainerNumber.value)
  dialogGateUpdate.value = false
}
const deleteContainerData = (dados) => {
  idDocs.value = (dados.id - 1)
  deleteContainer(idDocs.value)
}
function deleteContainer(dadosId) {
  let id = (dadosId - 0)
  containers.value = containers.value.filter(container => container.id != id);

}

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
  // pdf.addPage();

  // tabela
  // const imagensCanvas = await html2canvas(imagensElement, {
  //   useCORS: true,
  //   allowTaint: true,
  //   scale: 2
  // });
  // const imagensImgData = imagensCanvas.toDataURL("image/jpeg", 1.0);
  // let imagensHeight = (imagensCanvas.height * (pdfWidth - 2 * margin)) / imagensCanvas.width;

  // pdf.addImage(imagensImgData, "JPEG", margin, margin, pdfWidth - 2 * margin, imagensHeight);

  pdf.save("transacoes_relatorio.pdf");

  loading.value = false
};


</script>

<template>
  <div v-if="loading" class="loader-overlay">
    <div class="louderL">
      <ProgressSpinner />

    </div>
  </div>
  <div v-else style="background-color: #fff; border: 0px solid black; height: 100vh;"
    class="dark:bg-surface-950 flex items-center justify-center  overflow-auto containerPrecheckC">


    <div class="containerPrecheck">

      <div style="background-color: #fff; border: 0px solid black">
        <div class="flex flex-col items-center justify-center">
          <div style="border-radius: 56px; padding: 0.3rem" class="blue-gradient">
            <div class="w-full bg-surface-0 dark:bg-surface-900 py-20 px-8 sm:px-20" style="
              border-radius: 53px;
              border: 0px solid red;
              background-color: #ffffff;
            ">
              <div class="text-center mb-8">

                <!-- <div class="flex items-center justify-center w-full">
                  <div class="imageLogoLogin"></div>
                </div> -->
                <div class="m-20"></div>
                <!-- <div class="text-surface-900 dark:text-surface-0 text-3xl font-medium mb-4">
                  Bem vindo ao pre-check
                </div> -->
                <!-- <span class="text-muted-color font-medium">Preencha o campo</span> -->
              </div>

              <div class="erroMessage">
                {{ errorL }}
              </div>

              <div>
                <label for="appointment"
                  class="block text-surface-900 dark:text-surface-0 text-xl font-medium mb-2"></label>
                <InputNumber v-model="precheckData.containerNumber" inputId="appointment" :useGrouping="false"
                  class="inputsCaixas" placeholder="Inserir Appointment Number" />
                <!-- <InputNumber v-model="precheckData.containerNumber" inputId="numberOnly" :useGrouping="false" :min="0"
                  required autofocus class="w-full inputsCaixas" placeholder="Appointment Number" /> -->

                <!-- <label for="excelFile" class="excelLabel">
                  <i class="pi pi-file-excel"></i>
                  <span>Carregar excel</span></label> -->
                <!-- <input type="file" accept=".xlsx, .xls" @change="handleFileUpload" id="excelFile" class="excelArqui" /> -->




                <Button label="Check" class="w-full facebook-button hover " style="width: 500px; display: block;"
                  @click="precheckList"></Button>
                <!-- as="router-link" -->
                <!-- to="/dashboard" -->
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="flex flex-col items-center justify-center mx-20" v-if="containers.length">
        <div style="border-radius: 56px; padding: 0.3rem" class="blue-gradient">
          <div class="w-full bg-surface-0 dark:bg-surface-900 py-20 px-8 sm:px-20" style="
              border-radius: 53px;
              border: 0px solid red;
              background-color: #ffffff;
            ">
            <div class="text-surface-900 dark:text-surface-0 text-3xl font-medium mb-4"
              style="text-align: center; font-size: 1.4rem; font-weight: 600;">
              Appointment Numbers (Excel)
            </div>
            <DataTable :value="containers" class="tabelaContainerNumber" paginator :rows="6">
              <Column field="appointment_number" header="Appointment Number" />
              <Column header="Ações" :showFilterMatchModes="false" style="min-width: 12rem">
                <template #body="{ data }">
                  <div style="display: flex; gap: 0px">
                    <Button class="btnEstiliza" label="" icon="pi pi-refresh" @click="generatePDF(data)" style="
                border: 0px;
                background-color: transparent;
                color: #1558b0;
                display: none;
              " />
                    <Button class="btnEstiliza" label="" icon="pi  pi-pencil"
                      style="border: 0px; background-color: transparent; color: #1558b0"
                      @click="atualizarDados(data)" />
                    <div>
                      <Button label="" class="btnEstilizaDel" icon="pi pi-trash" severity="danger" style="
                  padding: 5px 0px;
                  background-color: transparent;
                  color: #ff0000;
                  border: 0px;
                " @click="deleteContainer(data.id)" />
                    </div>
                  </div>
                </template>
              </Column>
            </DataTable>
          </div>
        </div>
      </div>
    </div>
  </div>
  <Dialog header="Atualizar Gate" v-model:visible="dialogGateUpdate" :closable="true" :modal="true" :draggable="false"
    :resizable="false" style="width: 30vw; min-height: 5vh" :footer="productDialogFooterForm">
    <!-- optionLabel="name" -->
    <div class="msgErrorField">
      <p>
        {{ fieldIsEmpty }}
      </p>
    </div>
    <hr />
    <!-- <Select id="permis" v-model="permissions" :options="permissionsItems"  placeholder="S. Nivel de acesso" class="w-full" style="margin-top: 15px;"></Select> -->

    <div class="formUserAdd">

      <label for="gatename" style="display: block; margin-top: 10px;">Contêiner</label>
      <InputNumber v-model="updateContainerNumber" inputId="numberOnly" :useGrouping="false" :min="0" required autofocus
        class="w-full my-4" />


      <!-- v-model="formDataSave.name" -->

    </div>
    <div class="flex">
      <button class="p-button p-component cores" @click="atualizar">
        Atualizar
      </button>
      <button class="p-button p-component p-button-secondary mx-2" @click="dialogGateUpdate = false">
        Cancelar
      </button>
    </div>
  </Dialog>

  
  <Dialog header="Detalhes" v-model:visible="dialogRoleUpdateVisible" :closable="true" :modal="true" :draggable="false"
    :resizable="false" style="width:  250mm; min-height: 90vh" :footer="productDialogFooterForm">
    <div class="containerDetailsDialog">
      <div id="pdf-content">
        <div class="detalhesLogo">
          <div class="detalhesName">
            <span>
              Detalhes precheck:
            </span>
            <span>
              <span>{{ dadosRelatorio.number == null ? emptyField2 : dadosRelatorio.number }}</span>
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
            <span>Number</span>
            <span>{{ dadosRelatorio.number == null ? emptyField2 : dadosRelatorio.number }}</span>
          </div>

          <div class="lineRow">
            <span>Nome do motorista</span>
            <span>{{ dadosRelatorio.driver_name == null ? emptyField2 : dadosRelatorio.driver_name }}</span>
          </div>
          <div class="lineRow">
            <span>Num. carta</span>
            <span>{{ dadosRelatorio.driver_license_number == null ? emptyField2 : dadosRelatorio.driver_license_number }}</span>
          </div>

          <div class="lineRow">
            <span>placa do caminhão</span>
            <span>{{ dadosRelatorio.truck_license_number == null ? emptyField2 : dadosRelatorio.truck_license_number }}</span>
          </div>

          <div class="lineRow">
            <span>transportadora</span>
            <span>{{ dadosRelatorio.trucking_company == null ? emptyField2 : dadosRelatorio.trucking_company }}</span>
          </div>
          <div class="lineRow">
            <span>QR code</span>
            <span>{{ dadosRelatorio.appointment_qr_code == null ? emptyField2 : dadosRelatorio.appointment_qr_code }}</span>
          </div>
          
          <div class="lineRow">
            <span>Criado por </span>
            <span>{{ dadosRelatorio.created_by == null ? emptyField2 : dadosRelatorio.created_by }}</span>
          </div>

          <div class="lineRow">
            <span>Atualizado por </span>
            <span>{{ dadosRelatorio.updated_by == null ? emptyField : dadosRelatorio.updated_by }}</span>
          </div>

          
          <div class="lineRow">
            <span>Categoria</span>
            <span>{{ dadosRelatorio.category == null ? emptyField2 : dadosRelatorio.category }}</span>
          </div>
          <div class="lineRow">
            <span>Agent</span>
            <span>{{ dadosRelatorio.agent == null ? emptyField2 : dadosRelatorio.agent }}</span>
          </div>

          <div class="lineRow">
            <span>Num. da empresa do agente</span>
            <span>{{ dadosRelatorio.checklist == null ? emptyField2 : dadosRelatorio.checklist }}</span>
          </div>
          <div class="lineRow">
            <span>Time slot</span>
            <span>{{ dadosRelatorio.appointment_time_slot == null ? emptyField2 : dadosRelatorio.appointment_time_slot }}</span>
          </div>
          <div class="lineRow">
            <span>Pin number </span>
            <span>{{ dadosRelatorio.appointment_pin_number == null ? emptyField2 : dadosRelatorio.appointment_pin_number }}</span>
          </div>
          <div class="lineRow">
            <span>appointment data</span>
            <span>{{ dadosRelatorio.appointment_date == null ? emptyField2 : dadosRelatorio.appointment_date
              }}</span>
          </div>
          <div class="lineRow">
            <span>Num. de embarque da conta</span>
            <span>{{ dadosRelatorio.bill_lading_number == "" ? emptyField2 : dadosRelatorio.bill_lading_number }}</span>
          </div>
          <div class="lineRow">
            <span>Num. do container</span>
            <span>{{ dadosRelatorio.container_number ==
              null ? emptyField2 : dadosRelatorio.container_number }}</span>
          </div>
          <div class="lineRow">
            <span>Tipo contêiner</span>
            <span>{{ dadosRelatorio.container_type ==
              null ? emptyField2 : dadosRelatorio.container_type }}</span>
          </div>
          <div class="lineRow">
            <span>hold status</span>
            <span>{{ dadosRelatorio.hold_status == null ? emptyField2 : dadosRelatorio.hold_status
              }}</span>
          </div>
          <div class="lineRow">
            <span>Status</span>
            <span>{{ dadosRelatorio.status == null ? emptyField2 : dadosRelatorio.status
              }}</span>
          </div>
          <!-- <div class="lineRow">
            <span>Vessel visit</span>
            <span>{{ dadosRelatorio.vessel_visit ==
              null ? emptyField2 : dadosRelatorio.vessel_visit }}</span>
          </div> -->
          

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
              <span>1/1</span>
            </div>
          </div>

        </div>


      </div>
    </div>
    <div class="flex" style="display: flex; align-items: center; justify-content: space-between;">
      <div class="btnsCheckL">
        <button class="p-button p-component cores" @click="generatePDFCanva(dadosRelatorio)">
        Pdf
      </button>
      <button class="p-button p-component p-button-secondary mx-2" @click="dialogRoleUpdateVisible = false">
        SAIR
      </button>
      </div>
     
      <button class="p-button p-component cores" @click="precheck">
        Check Apointment
      </button>
      
    </div>
  </Dialog>
</template>

<style scoped>
.pi-eye {
  transform: scale(1.6);
  margin-right: 1rem;
}

.pi-eye-slash {
  transform: scale(1.6);
  margin-right: 1rem;
}

.facebook-button {
  background-color: #1558b0;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  cursor: pointer;
}

.facebook-button:hover {
  background-color: #1877f2 !important;
  border: 1px solid #1877f2 !important;
}

.corPrimaria {
  color: #1558b0;
}

.blue-gradient {
  /* background: linear-gradient(180deg, #1558b0 10%, rgba(33, 150, 243, 0) 30%); */
}

.inputsCaixas {
  height: 50px !important;
  outline: none !important;
  width: 500px !important;
  margin-bottom: 20px;
}

.inputsCaixas:focus {
  outline: none !important;
  border: 1px solid #1558b0 !important;
}

body {
  background-color: #ffffff !important;
}

.louderL {
  width: 100%;
  height: 100%;
  position: fixed;
  top: 0px;
  left: 0px;
  background-color: rgba(0, 0, 0, 0.05);
  z-index: 999999;
  display: flex;
  align-items: center;
  justify-content: center;
}

.butoonCheck {
  margin: 10px 0px;
  width: 100%;
  color: #222;
  background-color: transparent;
  border: none;
  font-size: 1rem;
  font-weight: 700 !important;
}

.butoonCheck:hover {
  background-color: transparent !important;
  color: #1558b0 !important;
  border: none !important;
}

.excelArqui {
  display: none;
}

.excelLabel {
  display: flex;
  align-items: center;
  margin-bottom: 40px;
  cursor: pointer;
  border: 1px solid #ddd;
  height: 50px;
  text-indent: 10px;
  border-radius: 8px;
}

.excelLabel i {
  font-size: 18px;
  color: #777 !important;

  display: block !important;
  ;
  margin-left: 5px;
}

.excelLabel span {
  color: #777 !important;
}

.excelLabel:hover i,
.excelLabel:hover span {
  color: #1558b0 !important;
}

.excelLabel:hover {
  border: 1px solid #1558b0;
}

.containerPrecheck {
  display: flex !important;
  ;
  justify-items: center !important;
  ;
  border: 0px solid red !important;
  align-items: flex-start;
  padding: 40px;

  /* width: 100%; */
}

.tabelaContainerNumber {
  margin-left: 20px;
  width: 400px;
  border-radius: 40px !important;
}

.containerPrecheckC {
  border: 0px solid red !important;
  border-radius: 15px;
  width: 100%;
  display: flex;
  align-content: center;
  justify-self: center;
}
</style>