<script setup>
import { useToast } from "primevue";
import { onMounted, reactive, ref } from "vue";
import { baseUrls } from "../../../api/index"
import { backLog, checkAccess } from "../../../utils/accesRoute";
import axios from "axios";
import { elements } from "chart.js";

checkAccess()

const toast = useToast();
const filtroDados = ref("");
const dialogGate = ref(false)
const dialogGateUpdate = ref(false)
const dialogApplicationDelete = ref(false)
const dialogDetalhes = ref(false)
const permissionsApplications = ref()

const formDataSave = reactive({
  name: "",
  version: "",
  description: ""
})

const idGate = ref(0)

const fieldIsEmpty = ref("")

const getToken = () => {
  return localStorage.getItem("access_token");
};

const number = ref(0)
const applications = ref([])
const gateFiltros = ref([])



const loading = ref(false)

const buscarApplication = async () => {
  loading.value = true;
  const token = getToken();
  if (!token) {
    backLog()
    return;
  }
  try {
    const response = await axios.get(
      baseUrls.applications, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    applications.value = response.data.data.data

    for (let aplicacaoField in applications.value) {
      // for(let permissionsField in applications.value[aplicacaoField]){
      //   console.log(permissionsField)
      // }
    }


  } catch (error) {
    console.error("Erro ao carregar dados:", error);
  } finally {
    loading.value = false;
  }
};


const saveApplications = async () => {
  loading.value = true;
  fieldIsEmpty.value = ""
  if (verifyEmpty(formDataSave)) {
    const token = getToken();
    if (!token) {
      return;
    }

    try {
      const response = await axios.post(baseUrls.applications, formDataSave, {
        headers: {
          Authorization: `Bearer ${token}`
        }
      })

      loading.value = false;
      dialogGate.value = false
      fieldVoid(formDataSave)
      buscarApplication()
    } catch (error) {
      fieldIsEmpty.value = "Erro ao adicionar gate"
      loading.value = false;
    }
  } else {
    fieldIsEmpty.value = "Campo vazio"
    loading.value = false;
  }

}

const getDataApplications = (data) => {

  fieldIsEmpty.value = ""
  dialogGateUpdate.value = true
  // formDataSave.name = data.name
  idGate.value = data.id
  for (let dados in formDataSave) {
    formDataSave[dados] = data[dados]
  }

}

const getDataApplicationsDelete = (data) => {
  fieldIsEmpty.value = ""
  dialogApplicationDelete.value = true
  idGate.value = data.id
}

const updateApplication = async () => {
  const token = getToken();
  if (!token) {
    return;
  }
  try {
    loading.value = true;
    const response = await axios.put(`${baseUrls.applications}/${idGate.value}`, formDataSave, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    dialogGateUpdate.value = false
    fieldVoid(formDataSave)
    loading.value = false;
    idGate.value = 0
    buscarApplication()
  } catch (error) {
    fieldIsEmpty.value = "Erro ao tentar atualizar"
    loading.value = false;
    console.error(`Erro ${error}`)
  }

}


const deleteApplication = async () => {
  loading.value = true;
  const token = getToken();
  if (!token) {
    return;
  }
  fieldIsEmpty.value = ""
  try {

    const response = await axios.delete(`${baseUrls.applications}/${idGate.value}`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    idGate.value = 0
    loading.value = false;
    dialogApplicationDelete.value = false
    buscarApplication()
  } catch (error) {
    loading.value = false;
    fieldIsEmpty.value = "Erro ao apagar o dado"
  }
}


const verifyEmpty = (object) => {
  for (let campos in object) {
    if (object[campos] == "") {
      return false
    }
  }

  return true
}

const fieldVoid = (data) => {
  for (let field in data) {
    formDataSave[field] = ""
  }
}

const detailsGates = (data) => {
  dialogDetalhes.value = true

  permissionsApplications.value = data.application_permissions
}




onMounted(() => {
  buscarApplication();
}
)
</script>

<template>
  <div v-if="loading" class="loader-overlay">
    <div class="louderL">
      <ProgressSpinner />
    </div>
  </div>
  <div class="card">
    <div class="font-semibold text-xl mb-4">Aplicações</div>
    <DataTable :value="applications" :paginator="true" :rows="10">
      <template #header>
        <div class="flex justify-between">
          <IconField class="searchText">
            <InputIcon>
              <i class="pi pi-search" />
            </InputIcon>
            <InputText v-model="filtroDados" @input="filtroChange" placeholder="Pesquisar" />
          </IconField>
          <!-- <div class="btnsL">
            <Button label="Novo" icon="pi pi-plus" class="cores" @click="dialogGate = true" />
          </div> -->
        </div>
      </template>
      <template #empty> Vazio. </template>
      

      <Column field="id" header="Id" style="min-width: 10rem"> </Column>
      <Column field="name" header="Nome" style="min-width: 12rem"> </Column>
      <Column field="created_by" header="Criado por" style="min-width: 12rem"> </Column>
      <Column field="version" header="Versão" style="min-width: 12rem"> </Column>

      <Column header="Ações" :showFilterMatchModes="false" style="min-width: 12rem">
        <template #body="{ data }">
          <div style="display: flex; gap: 0px">
            <!-- <Button class="btnEstiliza" label="" icon="pi pi-refresh" @click="generatePDF(data)" style="
                border: 0px;
                background-color: transparent;
                color: #1558b0;
                display: none;
              " /> -->

            <Button class="btnEstiliza" label="Detalhes" icon="pi  pi-eye"
              style="border: 0px; background-color: transparent; color: #1558b0" @click="detailsGates(data)" />
            <!-- <Button class="btnEstiliza" label="" icon="pi  pi-pencil"
              style="border: 0px; background-color: transparent; color: #1558b0" @click="getDataApplications(data)" /> -->
            <!-- <div>

              <Button label="" class="btnEstilizaDel" icon="pi pi-trash" severity="danger" style="
                  padding: 5px 0px;
                  background-color: transparent;
                  color: #ff0000;
                  border: 0px;
                " @click="getDataApplicationsDelete(data)" />
            </div> -->
          </div>
        </template>
      </Column>
    </DataTable>
  </div>

  <div class="p-fluid">
    <Dialog header="Confirmação" v-model:visible="dialogApplicationDelete" :style="{ width: '350px' }" :modal="true">
      <div class="msgErrorField">
        <p>
          {{ fieldIsEmpty }}
        </p>
      </div>
      <hr>

      <div class="sprate">

      </div>

      <div class="flex items-center justify-center">
        <i class="pi pi-exclamation-triangle mr-4" style="font-size: 2rem" />
        <span>Tens a certeza que queres eliminar?</span>
      </div>
      <template #footer>
        <Button label="Não" icon="pi pi-times" @click="dialogApplicationDelete = false" text severity="secondary" />
        <Button label="Sim" icon="pi pi-check" @click="deleteApplication" severity="danger" outlined autofocus />
      </template>
    </Dialog>

    <Dialog header="Atualizar Aplicações" v-model:visible="dialogGateUpdate" :closable="true" :modal="true"
      :draggable="false" :resizable="false" style="width: 30vw; min-height: 5vh" :footer="productDialogFooterForm">
      <!-- optionLabel="name" -->
      <div class="msgErrorField">
        <p>
          {{ fieldIsEmpty }}
        </p>
      </div>
      <hr />
      <!-- <Select id="permis" v-model="permissions" :options="permissionsItems"  placeholder="S. Nivel de acesso" class="w-full" style="margin-top: 15px;"></Select> -->

      <div class="formUserAdd">
        <label for="gatename">Nome</label>
        <InputText id="name" v-model="formDataSave.name" required autofocus class="w-full" />

      </div>

      <div class="formUserAdd">
        <label for="gatename">Versão</label>
        <InputText id="name" v-model="formDataSave.version" required autofocus class="w-full" />

      </div>

      <div class="formUserAdd">
        <label for="gatename">Descrição</label>
        <Textarea v-model="formDataSave.description" rows="5" cols="30" />
        <!-- <InputText id="name" v-model="formDataSave.name" required autofocus class="w-full" /> -->

      </div>
      <div class="flex">
        <button class="p-button p-component cores" @click="updateApplication">
          Atualizar
        </button>
        <button class="p-button p-component p-button-secondary mx-2" @click="dialogGateUpdate = false">
          Cancelar
        </button>
      </div>
    </Dialog>

    <Dialog header="Adicionar Aplicações" v-model:visible="dialogGate" :closable="true" :modal="true" :draggable="false"
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
        <label for="gatename">Nome</label>
        <InputText id="name" v-model="formDataSave.name" required autofocus class="w-full" />

      </div>

      <div class="formUserAdd">
        <label for="gatename">Versão</label>
        <InputText id="name" v-model="formDataSave.version" required autofocus class="w-full" />

      </div>

      <div class="formUserAdd">
        <label for="gatename">Descrição</label>
        <Textarea v-model="formDataSave.description" rows="5" cols="30" />
        <!-- <InputText id="name" v-model="formDataSave.name" required autofocus class="w-full" /> -->

      </div>


      <div class="flex">
        <button class="p-button p-component cores" @click="saveApplications">
          Salvar
        </button>
        <button class="p-button p-component p-button-secondary mx-2" @click="dialogGate = false">
          Cancelar
        </button>
      </div>
    </Dialog>

    <Dialog header="Detalhes Aplicações" v-model:visible="dialogDetalhes" :closable="true" :modal="true"
      :draggable="false" :resizable="false" style="width: 30vw; min-height: 5vh" :footer="productDialogFooterForm">
      <hr />
      <!-- <Select id="permis" v-model="permissions" :options="permissionsItems"  placeholder="S. Nivel de acesso" class="w-full" style="margin-top: 15px;"></Select> -->

      <div class="quantiUsers">
        <span>Quantidade de usuários:</span>

        <span>10</span>
      </div>
      <div class="titleCardUsers"> 
        <div class="titleCardUser">Permissões</div>
      </div>
      <div class="dateDetails">
        <ul class="aplicationUserEspecific">
          <li v-for="app in permissionsApplications" :key="app.id">
            <span class="value">{{ app.permission.name }}</span>
            <!-- <span class="value">{{ app.application_permission?.permission || 'Sem nome' }}</span> -->
          </li>
        </ul>

      </div>
      <div class="flex">
        <button class="p-button p-component cores" @click="dialogDetalhes = false">
          Ok
        </button>

        <!-- <button class="p-button p-component p-button-secondary mx-2" @click="dialogDetalhes = false">
          Cancelar
        </button> -->
      </div>
    </Dialog>
  </div>
</template>



<style scoped lang="scss">
:deep(.p-datatable-frozen-tbody) {
  font-weight: bold;
}

:deep(.p-datatable-scrollable .p-frozen-column) {
  font-weight: bold;
}

.cores {
  background-color: #1558b0;
  border: 1px solid #1558b0;
}

.cores:hover {
  background-color: #1558b0cf !important;
  border: 1px solid #1558b088 !important;
}

.camposAgrupadosFormulario {
  display: flex;
  justify-content: space-between;
}

.camposAgrupadosFormulario .formUserAdd {
  width: calc((100% / 2) - 5px);
}

.camposTextos,
.dropdownSexo {
  width: 100%;
  margin: 0px 0px;
}

.camposTextos:focus {
  border: #1558b0 1px solid !important;
}

.btnExports:last-child {
  color: #4271d4;
  background-color: #ffffff;
}

.labelDrop {
  margin: 15px 0px;
  display: block;
}

.btnPass {
  width: 100% !important;
  border: 0px !important;
  outline: 0px !important;
}

.p-inputtext {
  width: 100% !important;
}

.btnEstiliza:hover {
  color: #1558b0a4 !important;
  background: #1558b033 !important;
  transition: all 0.5s ease !important;
}

.btnEstiliza {
  background-color: rgba(21, 88, 176, 0.8117647059) !important;
  border: 1px solid rgba(21, 88, 176, 0.5333333333) !important;
  color: #fff !important;
}


.btnEstilizaDel:hover {
  color: #ff0000a5 !important;
  background: #ff000032 !important;
  transition: all 0.5s ease !important;
}

.msgErrorField {
  margin-bottom: 10px;
}

.msgErrorField p {
  color: #ff0000a5 !important;
  font-weight: 700;
}

.searchText:focus {
  border: #1558b0 1px solid !important;
}

.btnPermission:hover {
  background: #00000015 !important;
  color: #555555 !important;
  transition: all 0.3s ease;
  border-radius: 5px;
}

.louderL {
  width: 100%;
  height: 100%;
  position: fixed;
  top: 0px;
  left: 0px;
  background-color: rgba(0, 0, 0, 0.192);
  z-index: 999999;
  display: flex;
  align-items: center;
  justify-content: center;
}

.camposAgrupadosFormulario {
  border: 1px solid black;
}

.camposTextos {
  width: 100% !important;
}

.formUserAdd {
  margin: 20px 0px;
}

.gatename {
  display: block;
  margin-bottom: 10px;
}

.sprate {
  height: 20px;
}

.formUserAdd textarea {
  max-width: 100%;
  min-width: 100%;
  min-height: 150px;
  max-height: 150px;
}

.chip {
  display: inline-block;
  background-color: #e0f2f1;
  color: #00796b;
  padding: 5px 10px;
  border-radius: 15px;
  margin-right: 10px;
  margin-top: 10px;
}

.aplicationUserEspecific .value {
  color: #00796b;
}

.aplicationUserEspecific{
  display: flex;
  align-content: center;
  flex-wrap: wrap;
  justify-content: space-between;
  margin-bottom: 20px;

  margin-bottom: 10px;
  border-bottom: 1px solid #eee;
  padding: 10px 0px;
  
  
}

.aplicationUserEspecific li{
  background-color: #e0f2f1;
  // margin-left: 10px;
  color: #00796b;
  padding: 5px 10px;
  border-radius: 15px;
  margin-right: 10px;
  margin-top: 10px;
  min-width: calc((100% / 2) - 10px);
  max-width: 100%;
}

.aplicationUserEspecific li:nth-child(odd){
  background-color: #1558b01c;
  
}

.aplicationUserEspecific li:nth-child(odd) .value{
  color: #1558b0!important;
}
</style>
