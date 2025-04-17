<script setup>
import { useToast } from "primevue";
import { onMounted, reactive, ref } from "vue";
import { baseUrls } from "../../../api/index"
import { checkAccess } from "../../../utils/accesRoute";

checkAccess()

const toast = useToast();
const filtroDados = ref("");

const getToken = () => {
  return localStorage.getItem("access_token");
};

const number = ref(0)
const gates = ref([])
const gateFiltros = ref([])



const loading =  ref(false)

const buscarGates = async () => {
  const token = getToken();
  if (!token) {
    alert('Token de autenticação não encontrado. Por favor, faça login.');
    return;
  }
  try {
    const response = await axios.get(
      baseUrls.gate, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    gates.value = response.data.data
    
  } catch (error) {
    console.error("Erro ao carregar dados:", error);
  } finally {
    loading.value = false;
  }
};



onMounted(() => {
    buscarGates();
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
    <div class="font-semibold text-xl mb-4">Gates</div>
    <DataTable :value="gates" :paginator="true" :rows="10">
      <template #header>
        <div class="flex justify-between">
          <IconField class="searchText">
            <InputIcon>
              <i class="pi pi-search" />
            </InputIcon>
            <InputText
              v-model="filtroDados"
              @input="filtroChange"
              placeholder="Pesquisar"
            />
          </IconField>
          <div class="btnsL">
            <Button
              label="Novo"
              icon="pi pi-plus"
              class="cores"
              @click="dialogoUserVisble = true"
            />
          </div>
        </div>
      </template>
      <template #empty> Vazio. </template>

      <Column field="id" header="Id" style="min-width: 10rem"> </Column>
      <Column field="name" header="Nome" style="min-width: 12rem"> </Column>

      <Column
        header="Ações"
        :showFilterMatchModes="false"
        style="min-width: 12rem"
      >
        <template #body="{ data }">
          <div style="display: flex; gap: 0px">
            <Button
              class="btnEstiliza"
              label=""
              icon="pi pi-refresh"
              @click="generatePDF(data)"
              style="
                border: 0px;
                background-color: transparent;
                color: #1558b0;
                display: none;
              "
            />
            <Button
              class="btnEstiliza"
              label=""
              icon="pi  pi-pencil"
              style="border: 0px; background-color: transparent; color: #1558b0"
              @click="atualizarDados(data)"
            />
            <div>

              <Button
                label=""
                class="btnEstilizaDel"
                icon="pi pi-trash"
                severity="danger"
                style="
                  padding: 5px 0px;
                  background-color: transparent;
                  color: #ff0000;
                  border: 0px;
                "
                @click="apagaDados(data)"
              />
            </div>
          </div>
        </template>
      </Column>
    </DataTable>
  </div>

  <div class="p-fluid">
    <Dialog
      header="Confirmação"
      v-model:visible="displayConfirmation"
      :style="{ width: '350px' }"
      :modal="true"
    >
      <div class="flex items-center justify-center">
        <i class="pi pi-exclamation-triangle mr-4" style="font-size: 2rem" />
        <span>Tens a certeza que queres eliminar?</span>
      </div>
      <template #footer>
        <Button
          label="Não"
          icon="pi pi-times"
          @click="closeConfirmatio2n"
          text
          severity="secondary"
        />
        <Button
          label="Sim"
          icon="pi pi-check"
          @click="apaga"
          severity="danger"
          outlined
          autofocus
        />
      </template>
    </Dialog>
    <Dialog
      header="Nova Empresa"
      v-model:visible="dialogoUserVisble"
      :closable="true"
      :modal="true"
      :draggable="false"
      :resizable="false"
      style="width: 30vw; min-height: 40vh"
      :footer="productDialogFooterForm"
    >
      <hr />
      <div class="camposAgrupadosFormulario my-5">
        <!-- Nome -->
        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="name">Nome</label>
            <InputText
              id="name"
              v-model="empresaAdd.name"
              required
              autofocus
              class="camposTextos"
            />
          </div>
        </div>

        <!-- Email -->
        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="name">Email</label>
            <InputText
              id="name"
              v-model="empresaAdd.email"
              required
              autofocus
              class="camposTextos"
              type="email"
            />
          </div>
        </div>
      </div>

      <div class="camposAgrupadosFormulario my-5">
        <!-- Nome -->

        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="email">Endereço</label>
            <InputText
              id="email"
              v-model="empresaAdd.address"
              required
              autofocus
              class="camposTextos"
            />
          </div>
        </div>
        <!-- COntato -->
        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="name">Contato</label>
            <InputMask
              id="basic"
              v-model="empresaAdd.mobile"
              mask="99-999-9999"
              placeholder="99-999-9999"
            />
          </div>
        </div>
      </div>

      <hr class="my-5" />
      <div class="flex">
        <button class="p-button p-component cores" @click="salvarEmpresa">
          Salvar
        </button>
        <button
          class="p-button p-component p-button-secondary mx-2"
          @click="dialogoUserVisble = false"
        >
          Cancelar
        </button>
      </div>
    </Dialog>

    <Dialog
      header="Atualizar Empresa"
      v-model:visible="dialogUserUpdateVisible"
      :closable="true"
      :modal="true"
      :draggable="false"
      :resizable="false"
      style="width: 30vw; min-height: 20vh"
      :footer="productDialogFooterForm"
    >
      <hr />
      <div class="camposAgrupadosFormulario my-5">
        <!-- Nome -->
        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="name">Nome</label>
            <InputText
              id="name"
              v-model="dadosAtualizar.name"
              required
              autofocus
              class="camposTextos"
            />
          </div>
        </div>

        <!-- Email -->
        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="email">Email</label>
            <InputText
              id="email"
              v-model="dadosAtualizar.email"
              required
              autofocus
              class="camposTextos"
            />
          </div>
        </div>
      </div>

      <div class="camposAgrupadosFormulario my-5">
        <!-- Numero -->

        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="apelido">Telefone</label>
            <InputNumber
              v-model="number"
              inputId="withoutgrouping"
              :useGrouping="false"
              fluid
            />
            <!-- <InputNumber v-model="mobileUpdate" inputId="integeronly" fluid /> -->
          </div>
        </div>

        <!-- Email -->
        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="acesso">Acesso</label>
            <Select
              id="sexo"
              v-model="empresaAdd.name"
              :options="rolesName"
              optionLabel="name"
              placeholder="S. Nivel de acesso"
              class="w-full"
            ></Select>
          </div>
        </div>
      </div>

      <hr class="my-5" />
      <div class="flex">
        <button class="p-button p-component cores" @click="atualizarDadosShow">
          Atualizar
        </button>
        <button
          class="p-button p-component p-button-secondary mx-2"
          @click="disableMk"
        >
          Cancelar
        </button>
        <!-- <button @click="sayHello">Clique Aqui</button> -->
      </div>
    </Dialog>

    <Dialog
      header="Adicionar permissions"
      v-model:visible="dialogRoleUpdateVisible"
      :closable="true"
      :modal="true"
      :draggable="false"
      :resizable="false"
      style="width: 30vw; min-height: 5vh"
      :footer="productDialogFooterForm"
    >
      <!-- optionLabel="name" -->
      <hr />
      <!-- <Select id="permis" v-model="permissions" :options="permissionsItems"  placeholder="S. Nivel de acesso" class="w-full" style="margin-top: 15px;"></Select> -->

      <MultiSelect
        name="permissions"
        v-model="permissions"
        :options="permissionsItems"
        filter
        placeholder="Selecione permissões"
        :maxSelectedLabels="3"
        class="w-full md:w-80"
        style="margin-top: 15px; width: 100%"
      />
      <!-- <Message v-if="$form.city?.invalid" severity="error" size="small" variant="simple">{{ $form.city.error?.message }}</Message> -->
      <hr class="my-5" />
      <div class="flex">
        <button class="p-button p-component cores" @click="salvarPermissions">
          Salvar
        </button>
        <button
          class="p-button p-component p-button-secondary mx-2"
          @click="dialogRoleUpdateVisible = false"
        >
          Cancelar
        </button>
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

.btnEstilizaDel:hover {
  color: #ff0000a5 !important;
  background: #ff000032 !important;
  transition: all 0.5s ease !important;
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
  position: absolute;
  top: 0px;
  left: 0px;
  background-color: rgba(0, 0, 0, 0.192);
  z-index: 999999;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>


