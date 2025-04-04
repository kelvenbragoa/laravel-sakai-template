<script setup>
import { CustomerService } from "@/service/CustomerService";
import { ProductService } from "@/service/ProductService";
import { FilterMatchMode, FilterOperator } from "@primevue/core/api";
import { onBeforeMount, onMounted, reactive, ref, watch } from "vue";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import { baseUrls } from "../../../api/index";

let dialogoUserVisble = ref(false);
let dialogUserUpdateVisible = ref(false);
let dialogRoleUpdateVisible = ref(false);
const rolesName = ref([]);
let verificarA = ref([]);
const checked = ref(true);
const checked2 = ref([false]);
const empresas = ref([]);
const empresa = ref([]);
const empresaName = ref([]);
const empresaMap = {};
const dadoSearch = ref("")

const errorL = ref();
const rowsPerPage = ref(50);
const totalRecords = ref(0);
const dadosUserDelete = ref({
  name: "",
  id: null,
});
const first = ref(0);
const pagesCurrent = ref(2);
const filtroDados = ref("");

const roles = ref();
const rolesItems = ref([]);
const permissions = ref([]);

const gates = ref([]);
const gateFiltros = ref([]);

const permissionsItems = [];

const loading = ref(false);

const breakLoopGate = ref(false)

const formDataSave = reactive({
  name: "",
  user: "",
  email: "",
  company_id: "0",
  password: "",
  roles: [],
});

const countries = ref([
  { name: "Moçambique", code: "MZ" },
  { name: "Brasil", code: "BR" },
  { name: "Portugal", code: "PT" },
]);

const gateIdArray = ref([])

// Definindo a pré-seleção (exemplo: Moçambique)
const selectedCountry = ref(countries.value[0]);
const roleSelected = ref({ name: "" });
// const gateSelected = ref({
//   id: 70,
//   user_id: "120",
//   gate_id: "1",
//   created_by: null,
//   updated_by: null,
//   created_at: "2025-03-04T07:01:43.383000Z",
//   updated_at: "2025-03-04T07:01:43.383000Z",
// });

const gateSelected = ref([])
const gatePreSelecteds = ref([])
const ative_selected = ref({ name: "Inativo", code: "0" });

const getToken = () => {
  return localStorage.getItem("access_token");
};

const buscarUsuarios = async (page = 1) => {
  const token = getToken();

  if (!token) {
    alert("Token de autenticação não encontrado. Por favor, faça login.");
    return;
  }
  try {
    const response = await axios.get(
      `${baseUrls.userList}`,
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
        params: {
          page: page,
          query: dadoSearch.value
        }
      }
    );

    usersL.value = response.data.data.data;

    userFiltro.value = response.data.data.data;

    totalRecords.value = response.data.data.total;

  } catch (error) {
    console.error("Erro ao carregar dados:", error);
  } finally {
    loading.value = false;
  }
};

const onPageChange = (event) => {
  first.value = event.first
  const newPage = Math.floor(event.first / rowsPerPage.value) + 1

  buscarUsuarios(newPage)
}

const fetchPermissions = async () => {
  try {
    loading.value = true;
    const res = await fetch("/api/permissions");
    const dados = await res.json();
    for (let k in dados.data.data) {
      permissionsItems.push(dados.data.data[k].name);
      // permissionsItems.value.add(dados.data.data[k].name)
    }

    loading.value = false;
  } catch (error) {
    console.log(error);
  }
};


import axios from "axios";

const fetchRoles = async () => {
  try {
    const token = getToken();

    if (!token) {
      console.error("Token de autenticação não encontrado.");
      return;
    }

    const response = await axios.get(`${baseUrls.baseURl}/roles`, {
      headers: {
        Authorization: `Bearer ${token}`,
        "Content-Type": "application/json",
      },
    });


    rolesItems.value = response.data.data.data.filter((roles) =>
      roles.guard_name.includes("web")
    );


    rolesName.value = rolesItems.value.map((role) => ({ name: role.name }));

  } catch (error) {
    console.error("Erro ao buscar roles:", error);
  }
};

// watch(
//   rolesName,
//   (newRoles) => {
//     if (newRoles.length > 0) {
//       dadosAtualizar.roles =
//         newRoles.find((role) => role.name === "Admin") || null;
//     }
//   },
//   { immediate: true }
// );

const sexoItem = ref([
  { name: "Masculino", code: "M" },
  { name: "Feminino", code: "F" },
]);

const gateItem = ref([
  { name: "Gate 4", code: 4 },
  { name: "Gate 5", code: 5 },
  { name: "Gate 6", code: 6 },
  { name: "Gate 11A", code: 11 },
  { name: "Gate 8A", code: 8 },
  { name: "Gate 3", code: 3 },
]);

const statusItems = ref([
  { name: "Ativo", code: "1" },
  { name: "Inativo", code: "0" },
]);

const acessoItem = ref([
  { name: "Administrador", code: "Admin" },
  { name: "Manager", code: "Manager" },
  { name: "Operator", code: "Operator" },
  { name: "Super Admin", code: "SuperAdmin" },
]);

const acesso = ref();
const gate = ref();

const sexo = ref();

const toast = useToast();

const usersL = ref([]);
const userFiltro = ref([]);
const fetchUsers = async () => {
  try {
    const response = await fetch("/api/users");
    const data = await response.json();
    usersL.value = data.data.data;
    userFiltro.value = data.data.data;
    for (let items in usersL.value) {
      verificarA.value.push(
        usersL.value[items]["is_active"] == 1 ? true : false
      );
    }
  } catch (error) {
    console.error("Erro ao buscar Users:", error);
  }
};

const filtroChange = () => {
  loading.value = true;
  if (filtroDados.value.trim() === "") {
    userFiltro.value = [...usersL.value];
    loading.value = false;
  } else {
    dadoSearch.value = filtroDados.value.toLowerCase()
    buscarUsuarios()
  }
};

const addUser = async () => {

  const payload = {
    name: "Domingos Doe",
    email: "teste34sea@gmail.com",
    password: "12345678",
    mobile: "987654321",
    roles: [{ name: "Manager" }],
  };

  try {

    const response = await axios.post("/api/users", payload, {
      headers: {
        "Content-Type": "application/json",
      },
    });


    toast.add({
      severity: "success",
      summary: "Usuário Criado",
      detail: `Usuário ${response.data.name} criado com sucesso!`,
      life: 3000,
    });
  } catch (error) {

    console.error("Erro ao adicionar usuário:", error.response?.data || error);

    toast.add({
      severity: "error",
      summary: "Erro",
      detail: error.response?.data?.message || "Erro ao criar o usuário.",
      life: 3000,
    });
  }
};

// {
//     "user_full_name":"userfullname",
//     "user_name":"usernamekelven2",
//     "email":"",
//     "password":"12345678",
//     "roles":[
//         {
//             "name":"Admin"
//         }
//     ]
// }

const salvarDadosShow = async () => {
  let dadosAddL = {
    user_full_name: formDataSave.name,
    user_name: formDataSave.user,
    email: formDataSave.email,
    company_id: String(formDataSave.company_id.id),
    password: formDataSave.password,
    roles: [{ name: formDataSave.roles.name }],
  };

  const token = getToken();

  if (!token) {
    alert("Token de autenticação não encontrado. Por favor, faça login.");
    return;
  } else {
    verificadorDeCampos(dadosAddL);
    if (errorL.value === "") {
      dialogoUserVisble.value = false;
      loading.value = true;

      try {
        const response = await axios.post(baseUrls.userList, dadosAddL, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        toast.add({
          severity: "success",
          summary: "Confirmação",
          detail: `Usuário criado com sucesso!`,
          life: 3000,
        });
        // fetchUsers();
        buscarUsuarios();
        loading.value = false;

        formDataSave.name = "";
        formDataSave.user = "";
        formDataSave.email = "";
        formDataSave.password = "";
        formDataSave.company_id = "0";
        formDataSave.roles = [];
      } catch (error) {
        console.error(
          "Erro ao adicionar usuário:",
          error.response?.data || error
        );
        toast.add({
          severity: "error",
          summary: "Erro",
          detail: error.response?.data?.message || "Erro ao criar o usuário.",
          life: 3000,
        });
        loading.value = false;
      }
    }
  }
};

const buscarEmpresas = async () => {
  const token = getToken();

  if (!token) {
    alert("Token de autenticação não encontrado. Por favor, faça login.");
    return;
  }
  try {
    const response = await axios.get(baseUrls.empresaAdd, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });


    empresas.value = response.data.data.data;

    empresas.value.forEach((element, key) => {

      empresa.value.push({ id: element.id, name: element.name });
    });

    empresas.value
      .sort((a, b) => a.id - b.id)
      .forEach((element) => {

        empresaName.value.push(element.name);
      });

    empresas.value.forEach((element) => {
      empresaMap[element.id] = element.name;
    });

  } catch (error) {
    console.error("Erro ao carregar dados:", error);
  } finally {
    loading.value = false;
  }
};

const atualizarDadosShow = async () => {
  gateIdArray.value = []
  gatePreSelecteds.value = []

  gateSelected.value.forEach(dados => {

    gateIdArray.value.push({ "gate_id": dados.id })
  })



  const dadoAtualizacao = {
    id: dadosAtualizar.id,
    user_full_name: dadosAtualizar.user_full_name,
    email: dadosAtualizar.email,
    is_active: Number(ative_selected.value.code),
    gates: gateIdArray.value,
    roles: [{ name: roleSelected.value.name }],
  };




  const token = getToken();
  if (!token) {
    alert("Token de autenticação não encontrado. Por favor, faça login.");
    return;
  } else {
    verificadorDeCampos(dadoAtualizacao);
    if (errorL.value === "") {
      dialogUserUpdateVisible.value = false;
      loading.value = true;
      try {
        await axios.post(
          `${baseUrls.userList}/${dadoAtualizacao.id}`,
          dadoAtualizacao,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );
        // fetchUsers();
        buscarUsuarios();
        loading.value = false;

        toast.add({
          severity: "success",
          summary: "Confirmação",
          detail: "Usuario atualizado",
          life: 3000,
        });

      } catch (error) {
        // fetchUsers();
        buscarUsuarios();
        loading.value = false;
        toast.add({
          severity: "error",
          summary: "Erro",
          detail: "Falha ao atualizar usuário.",
          life: 3000,
        });
        console.error(error);
      }

      dadosAtualizar.email = "";
      dadosAtualizar.gate_id = 0;
      dadosAtualizar.id = null;
      dadosAtualizar.roles = [];
      dadosAtualizar.user_full_name = "";
      dadosAtualizar.is_active = 0;
      roleSelected.value.name = "";
      gateSelected.value = []
    }
  }

  //toast.add({ severity: 'Confirmação', summary: 'Info', detail: 'Salvo', life: 3000 });
};

const displayConfirmation = ref(false);
function openConfirmation() {
  displayConfirmation.value = true;
}

function closeConfirmatio2n() {
  displayConfirmation.value = false;
}

function closeConfirmation() {
  displayConfirmation.value = false;
  toast.add({
    severity: "success",
    summary: "Confirmação",
    detail: "Usuário eliminado",
    life: 3000,
  });
}

async function apaga() {
  displayConfirmation.value = false;
  loading.value = true;
  const token = getToken();
  if (!token) {
    alert("Token de autenticação não encontrado. Por favor, faça login.");
    return;
  }
  try {
    const response = await axios.post(
      `${baseUrls.userList}/${dadosUserDelete.value.id}`,
      {
        headers: { Authorization: `Bearer ${token}` },
      }
    );

    // fetchUsers();
    buscarUsuarios();
    loading.value = false;
    toast.add({
      severity: "success",
      summary: "Confirmação",
      detail: `Usuário ${String(dadosUserDelete.value.name)} eliminado`,
      life: 3000,
    });
  } catch (error) {
    loading.value = false;
    toast.add({
      severity: "error",
      summary: "Confirmação",
      detail: `Erro ao deletar o usuário`,
      life: 3000,
    });
    console.error("Erro ao deletar o usuário:", error.response.data);
  }
}

function apagaDados(dados) {
  displayConfirmation.value = true;
  dadosUserDelete.value.name = dados.name;
  dadosUserDelete.value.id = dados.id;
}
const salvarPermissions = async () => {
  dialogRoleUpdateVisible.value = false;
};


const dadosAtualizar = reactive({
  id: null,
  user_full_name: "",
  is_active: Number(ative_selected.value.code),
  email: "",
  roles: roleSelected.value,
});
const atualizarDados = (dados) => {

  gateSelected.value = []
  gateIdArray.value = []
  gatePreSelecteds.value = []


  dialogUserUpdateVisible.value = true;
  dadosAtualizar.id = dados.id;
  // // dadosAtualizar.roles = [{name: dados.roles[0].name}];

  roleSelected.value.name = dados.roles[0].name;
  dadosAtualizar.roles = roleSelected.value;

  dadosAtualizar.email = dados.email;
  dadosAtualizar.user_full_name = dados.user_full_name;

  dados.gate.forEach(item => {
    gatePreSelecteds.value.push(item)
    // gateSelected.value.push(item)
  })
  gates.value.forEach((gateUser) => {

    gatePreSelecteds.value.forEach((element) => {

      if (gateUser.id == element.gate_id) {
        gateSelected.value.push(gateUser)
      }
    })

  })
  if (gateSelected.value == undefined) {
    gateSelected.value = [
      {
        id: 70,
        user_id: "120",
        gate_id: "1",
        created_by: null,
        updated_by: null,
        created_at: "2025-03-04T07:01:43.383000Z",
        updated_at: "2025-03-04T07:01:43.383000Z",
      }
    ]
  }

  if (dados.is_active == "0") {
    ative_selected.value.name = "Inativo";
    ative_selected.value.code = "0";
  } else {
    ative_selected.value.name = "Ativo";
    ative_selected.value.code = "1";
  }
  dadosAtualizar.gate_id = gateSelected.value;
  dadosAtualizar.is_active = Number(ative_selected.value.code);
  userFiltro.value.forEach((element) => {
    if (dados.id == element.id) {
      element.roles.forEach((item) => {
        dadosAtualizar.roles = item.name;
      });
    }
  });
};

const removeGatePreSelected = (idPre, idCurrent) => {
  if (idPre == idCurrent) {
    return false
  }

  return true
}


const disableMk = () => {
  dialogUserUpdateVisible.value = false;
  gateSelected.value = []
};

const verificadorDeCampos = (dado) => {
  errorL.value = "";
  for (const key in dado) {
    if (dado[key] == "" || dado[key] == undefined) {
      if (key != "is_active") {
        errorL.value = `Preencha todos os dados`;
      }
    }
  }
};



const categories = ref([
  { name: "Tecnologia", code: "T" },
  { name: "Saúde", code: "S" },
  { name: "Educação", code: "E" },
  { name: "Esportes", code: "SP" }
]);

const selectedCategories = ref([]);

const buscarGates = async () => {
  const token = getToken();
  if (!token) {
    alert("Token de autenticação não encontrado. Por favor, faça login.");
    return;
  }
  try {
    const response = await axios.get(baseUrls.gate, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    gates.value = response.data.data;
  } catch (error) {
    console.error("Erro ao carregar dados:", error);
  } finally {
    loading.value = false;
  }
};


// const nextPage = () => {
//   pagesCurrent.value++;
//   buscarUsuarios(pagesCurrent);
// };

onMounted(() => {
  //   fetchUsers();
  fetchRoles();
  fetchPermissions();
  buscarUsuarios();
  buscarEmpresas();
  buscarGates();
});
</script>

<template>
  <div v-if="loading" class="loader-overlay">
    <div class="louderL">
      <ProgressSpinner />
    </div>
  </div>
  <div class="card">
    <div class="font-semibold text-xl mb-4">Users</div>
    <DataTable :value="userFiltro" paginator :rows="rowsPerPage" :totalRecords="totalRecords" lazy :first="first"
      @page="onPageChange">

      <template #header>
        <div class="flex justify-between">
          <IconField class="searchText">
            <InputIcon>
              <i class="pi pi-search" />
            </InputIcon>
            <InputText v-model="filtroDados" @input="filtroChange" placeholder="Pesquisar" />
          </IconField>
          <div class="btnsL">
            <Button label="Novo" icon="pi pi-plus" class="cores" @click="dialogoUserVisble = true" />
          </div>
        </div>
      </template>
      <template #empty> Vazio. </template>

      <Column field="id" header="Id" style="min-width: 10rem" sortable>
      </Column>
      <Column field="user_full_name" header="Nome" style="min-width: 12rem" sortable>
      </Column>

      <Column field="email" header="Email" style="min-width: 10rem" sortable>
      </Column>

      <Column field="user_name" header="Username" style="min-width: 10rem" sortable>
      </Column>

      <Column field="company_id" header="Empresa" style="min-width: 10rem" sortable="">
        <template #body="{ data }">
          {{ empresaMap[data.company_id] || data.company_id }}
        </template>
      </Column>

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
              style="border: 0px; background-color: transparent; color: #1558b0" @click="atualizarDados(data)" />
            <div>
              <Button label="" class="btnEstilizaDel" icon="pi pi-trash" severity="danger" style="
                  padding: 5px 0px;
                  background-color: transparent;
                  color: #ff0000;
                  border: 0px;
                " @click="apagaDados(data)" />
            </div>
          </div>
        </template>
      </Column>
      <Column field="is_active" header="Ativo" dataType="boolean" bodyClass="text-center" style="min-width: 8rem"
        sortable>
        <template #body="{ data }">
          <div v-if="data.is_active == `0`" style="
              display: flex;
              align-items: center;
              justify-items: center;
              gap: 10px;
            ">
            <i class="pi pi-times-circle text-red-500"></i>
          </div>

          <div v-else style="
              display: flex;
              align-items: center;
              justify-items: center;
              gap: 10px;
            ">
            <i class="pi pi-check-circle text-green-500"></i>
          </div>
        </template>
      </Column>
    </DataTable>
  </div>

  <div class="p-fluid">
    <Dialog header="Confirmação" v-model:visible="displayConfirmation" :style="{ width: '350px' }" :modal="true">
      <div class="flex items-center justify-center">
        <i class="pi pi-exclamation-triangle mr-4" style="font-size: 2rem" />
        <span>Tens a certeza que queres eliminar?</span>
      </div>
      <template #footer>
        <Button label="Não" icon="pi pi-times" @click="closeConfirmatio2n" text severity="secondary" />
        <Button label="Sim" icon="pi pi-check" @click="apaga" severity="danger" outlined autofocus />
      </template>
    </Dialog>
    <Dialog header="Novo user" v-model:visible="dialogoUserVisble" :closable="true" :modal="true" :draggable="false"
      :resizable="false" style="width: 30vw; min-height: 60vh" :footer="productDialogFooterForm">
      <div class="erroMessage">
        {{ errorL }}
      </div>
      <hr />
      <div class="camposAgrupadosFormulario my-5">
        <!-- Nome nome -->
        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="name">User</label>
            <InputText id="name" v-model="formDataSave.user" required autofocus class="camposTextos" />
          </div>
        </div>

        <!-- Email -->
        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="name">Nome completo</label>
            <InputText id="name" v-model="formDataSave.name" required autofocus class="camposTextos" />
          </div>
        </div>
      </div>

      <div class="camposAgrupadosFormulario my-5">
        <!-- Nome -->

        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="email">Email</label>
            <InputText id="email" v-model="formDataSave.email" required autofocus class="camposTextos" />
          </div>
        </div>
        <!-- Acesso -->
        <div class="formUserAdd">
          <label for="acesso">Acesso</label>
          <Select id="acesso" v-model="formDataSave.roles" :options="rolesName" optionLabel="name"
            placeholder="S. Nivel de acesso" class="w-full"></Select>
        </div>
      </div>

      <div class="formUserAdd">
        <label for="empresa">Empresa</label>
        <Select id="empresa" v-model="formDataSave.company_id" :options="empresa" optionLabel="name"
          placeholder="Empresas" class="w-full"></Select>
      </div>

      <!-- Senha -->
      <div class="formUserAdd" style="margin-top: 15px; width: 100%">
        <div class="field formUserAddI" style="width: 100%; border: 0px solid black">
          <label for="senha" class="my-5">Senha</label>
          <Password id="senha" v-model="formDataSave.password" placeholder="Senha" :toggleMask="true"
            class="mb-4 inputsCaixas camposTextos" fluid :feedback="false"></Password>
        </div>
      </div>

      <hr class="my-5" />
      <div class="flex">
        <button class="p-button p-component cores" @click="salvarDadosShow">
          Salvar
        </button>
        <button class="p-button p-component p-button-secondary mx-2" @click="dialogoUserVisble = false">
          Cancelar
        </button>
      </div>
    </Dialog>

    <Dialog header="Atualizar user" v-model:visible="dialogUserUpdateVisible" :closable="true" :modal="true"
      :draggable="false" :resizable="false" style="width: 30vw; min-height: 20vh" :footer="productDialogFooterForm">
      <div class="erroMessage">
        {{ errorL }}
      </div>
      <hr />
      <div class="camposAgrupadosFormulario my-5">
        <!-- Nome -->
        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="name">Nome</label>
            <InputText id="name" v-model="dadosAtualizar.user_full_name" required autofocus class="camposTextos" />
          </div>
        </div>

        <!-- Email -->
        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="email">Email</label>
            <InputText id="email" v-model="dadosAtualizar.email" required autofocus class="camposTextos" />
          </div>
        </div>
      </div>

      <div class="camposAgrupadosFormulario my-5">
        <!-- GAte -->

        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="acesso">Portão</label>
            <MultiSelect v-model="gateSelected" :options="gates" optionLabel="name" placeholder="Portões" display="chip"
              class="w-full" />

          </div>

        </div>


        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="acesso">Acesso</label>
            <Select id="acesso" v-model="roleSelected" :options="rolesName" optionLabel="name"
              placeholder="S. Nivel de acesso" class="w-full"></Select>
          </div>
        </div>
      </div>
      <div class="formUserAdd">
        <div class="field formUserAddI">
          <label for="acesso">Status</label>
          <Select id="status" v-model="ative_selected" :options="statusItems" optionLabel="name" placeholder="Status"
            class="w-full"></Select>
        </div>
      </div>

      <hr class="my-5" />
      <div class="flex">
        <button class="p-button p-component cores" @click="atualizarDadosShow">
          Atualizar
        </button>
        <button class="p-button p-component p-button-secondary mx-2" @click="disableMk">
          Cancelar
        </button>
        <!-- <button @click="sayHello">Clique Aqui</button> -->
      </div>
    </Dialog>

    <Dialog header="Adicionar permissions" v-model:visible="dialogRoleUpdateVisible" :closable="true" :modal="true"
      :draggable="false" :resizable="false" style="width: 30vw; min-height: 5vh" :footer="productDialogFooterForm">
      <!-- optionLabel="name" -->
      <hr />
      <!-- <Select id="permis" v-model="permissions" :options="permissionsItems"  placeholder="S. Nivel de acesso" class="w-full" style="margin-top: 15px;"></Select> -->

      <MultiSelect name="permissions" v-model="permissions" :options="permissionsItems" filter
        placeholder="Selecione permissões" :maxSelectedLabels="3" class="w-full md:w-80"
        style="margin-top: 15px; width: 100%" />
      <!-- <Message v-if="$form.city?.invalid" severity="error" size="small" variant="simple">{{ $form.city.error?.message }}</Message> -->
      <hr class="my-5" />
      <div class="flex">
        <button class="p-button p-component cores" @click="salvarPermissions">
          Salvar
        </button>
        <button class="p-button p-component p-button-secondary mx-2" @click="dialogRoleUpdateVisible = false">
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
</style>
