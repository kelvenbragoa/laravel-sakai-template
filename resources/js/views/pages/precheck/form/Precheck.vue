<script setup>
import FloatingConfigurator from "@/components/FloatingConfigurator.vue";
import { ref } from "vue";
import { useRouter } from "vue-router";
import { baseUrls } from "../../../../api/index";
import * as XLSX from 'xlsx';

const containers = ref([]);
const updateContainerNumber = ref()
const idDocs =  ref()
const router = useRouter();

const token = ref("");
const precheckData = ref({
  containerNumber: ""
});

const dialogGateUpdate = ref(false)


const loading = ref(false);
const errorL = ref(" ");

const precheck = async () => {
  errorL.value = " "
  emptyField()
  if (errorL.value == " ") {
  }

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
  updateContainerNumber.value = dados.containerNumber
}

const atualizar = () => {
  containers.value[idDocs.value].containerNumber = updateContainerNumber.value
  dialogGateUpdate.value = false
}
const deleteContainerData = (dados)=>{
  idDocs.value = (dados.id - 1)
  deleteContainer(idDocs.value)
}
function deleteContainer(dadosId) {
  let id = (dadosId - 0)
  containers.value = containers.value.filter(container => container.id != id);

}

</script>

<template>
  <FloatingConfigurator />



  <div v-if="loading" class="loader-overlay">
    <div class="louderL">
      <ProgressSpinner />

    </div>
  </div>
  <div v-else style="background-color: #f9f9f9; border: 0px solid black"
    class="dark:bg-surface-950 flex items-center justify-center min-h-screen min-w-[100vw] overflow-hidden">
    <FloatingConfigurator />

    <div class="containerPrecheck">

      <div style="background-color: #f9f9f9; border: 0px solid black">
        <div class="flex flex-col items-center justify-center">
          <div style="border-radius: 56px; padding: 0.3rem" class="blue-gradient">
            <div class="w-full bg-surface-0 dark:bg-surface-900 py-20 px-8 sm:px-20" style="
              border-radius: 53px;
              border: 0px solid red;
              background-color: #ffffff;
            ">
              <div class="text-center mb-8">
                <!-- <img src="@/images/login.png" alt="Descrição da imagem" class="my-image" /> -->
                <!-- <img :src="require('@/assets/images/login.png')" alt="Descrição da imagem" /> -->
                <div class="flex items-center justify-center w-full">
                  <!-- <Image src="@/assets/images/logo.png" alt="Image" width="200" /> -->
                  <!-- <img
                  src="http://[::1]:5173/resources/js/assets/images/logo.png"
                  alt="logo"
                  style="width: 200px"
                /> -->
                  <div class="imageLogoLogin"></div>
                </div>
                <div class="m-20"></div>
                <div class="text-surface-900 dark:text-surface-0 text-3xl font-medium mb-4">
                  Bem vindo ao pre-check
                </div>
                <span class="text-muted-color font-medium">Preencha o campo</span>
              </div>

              <div class="erroMessage">
                {{ errorL }}
              </div>

              <div>
                <label for="email1" class="block text-surface-900 dark:text-surface-0 text-xl font-medium mb-2"></label>
                <InputText id="email1" type="text" placeholder="Número de container"
                  class="w-full md:w-[30rem] mb-8 inputsCaixas" v-model="precheckData.containerNumber" />

                <label for="excelFile" class="excelLabel">
                  <i class="pi pi-file-excel"></i>
                  <span>Carregar excel</span></label>
                <input type="file" accept=".xlsx, .xls" @change="handleFileUpload" id="excelFile" class="excelArqui" />




                <Button label="Pre check" class="w-full facebook-button hover" @click="precheck"></Button>
                <Button label="Login" class="butoonCheck" @click="goToPrecheck"></Button>
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
              Tabela de container numbers (Excel)
            </div>
            <DataTable :value="containers" class="tabelaContainerNumber" paginator :rows="6">
              <Column field="containerNumber" header="Número do Contêiner" />
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
      <InputText id="name" v-model="updateContainerNumber" required autofocus class="w-full my-4" />
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
  background: linear-gradient(180deg, #1558b0 10%, rgba(33, 150, 243, 0) 30%);
}

.inputsCaixas {
  height: 50px !important;
  outline: none !important;
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

  /* width: 100%; */
}

.tabelaContainerNumber {
  margin-left: 20px;
  width: 400px;
  border-radius: 40px !important;
}
</style>