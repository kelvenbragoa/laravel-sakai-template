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
const errorL = ref();
const dadosUserDelete = ref({
  name: "",
  id: null,
});

const filtroDados = ref("");

const roles = ref();
const rolesItems = ref([]);
const permissions = ref([]);

const gates = ref([]);
const gateFiltros = ref([]);

const permissionsItems = [];

const loading = ref(false);

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

// Definindo a pré-seleção (exemplo: Moçambique)
const selectedCountry = ref(countries.value[0]);
const roleSelected = ref({ name: "" });
const gateSelected = ref();
const ative_selected = ref({ name: "Inativo", code: "0" });

const getToken = () => {
  return localStorage.getItem("access_token");
};

const buscarUsuarios = async () => {
  const token = getToken();
  console.log(`Token: ${token}`);
  if (!token) {
    alert("Token de autenticação não encontrado. Por favor, faça login.");
    return;
  }
  try {
    const response = await axios.get(baseUrls.userList, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });

    console.log("Response user");
    console.log(response.data.data.data);

    // const data = await response.json();
    // console.log("Reponse users")

    usersL.value = response.data.data.data;
    // console.log(usersL.value);
    userFiltro.value = response.data.data.data;
    // // verificarA.value.push = usersL.value.

    // // console.log(permissions)
    // for (let items in usersL.value) {
    //   // verificarA.value.push = pe
    //   //   console.log(usersL.value[items]["is_active"])
    //   verificarA.value.push(
    //     usersL.value[items]["is_active"] == 1 ? true : false
    //   );
    // }

    // console.log("---------------------------------------------------");
    // console.log(verificarA.value);
    // console.log("Tamanho");
    // console.log(verificarA.value.length);
    // console.log(data.data.data.length);
    // exportToExcel()
  } catch (error) {
    console.error("Erro ao carregar dados:", error);
  } finally {
    loading.value = false;
  }
};

const fetchPermissions = async () => {
  try {
    loading.value = true;
    const res = await fetch("/api/permissions");
    const dados = await res.json();
    // permissionsItems.value = dados.data.data
    console.log("-------------------------------------");
    console.log("Permissions");
    for (let k in dados.data.data) {
      console.log("Key: " + dados.data.data[k].name);
      permissionsItems.push(dados.data.data[k].name);
      // permissionsItems.value.add(dados.data.data[k].name)
    }
    console.log(permissionsItems);
    loading.value = false;
  } catch (error) {
    console.log(error);
  }
};

const fetchRoles = async () => {
  try {
    const response = await fetch("/api/roles");
    const data = await response.json();
    console.log("Roles");
    console.log(data.data.data[1].guard_name);
    console.log("---------------------------------------------");
    rolesItems.value = data.data.data.filter((roles) => {
      // console.log(roles.guard_name.includes('web'))
      return roles.guard_name.includes("web");
      //  user.name.toLowerCase().includes(filtroDados.value.toLowerCase())
    });

    for (let items in rolesItems.value) {
      rolesName.value.push({ name: rolesItems.value[items].name });
    }
    //console.log("Roles Names");
    // console.log(rolesName.value);
  } catch (erro) {
    console.log(erro);
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
    // permissions.value = Array.isArray(data) ? data : [];
    usersL.value = data.data.data;
    console.log(usersL.value);
    userFiltro.value = data.data.data;
    // verificarA.value.push = usersL.value.

    // console.log(permissions)
    for (let items in usersL.value) {
      // verificarA.value.push = pe
      //   console.log(usersL.value[items]["is_active"])
      verificarA.value.push(
        usersL.value[items]["is_active"] == 1 ? true : false
      );
    }

    console.log("---------------------------------------------------");
    console.log(verificarA.value);
    console.log("Tamanho");
    console.log(verificarA.value.length);
    console.log(data.data.data.length);
    // console.log(permissions.value)
  } catch (error) {
    console.error("Erro ao buscar Users:", error);
  }
};

const filtroChange = () => {
  loading.value = true;
  if (filtroDados.value.trim() === "") {
    console.log("Vazio");
    userFiltro.value = [...usersL.value];
    loading.value = false;
  } else {
    console.log("Preenchido");
    console.log(filtroDados.value.toLowerCase());
    // userFiltro.value = usersL.value.filter(
    //   (user) =>
    //     user.name.toLowerCase().includes(filtroDados.value.toLowerCase()) ||
    //     user.email.toLowerCase().includes(filtroDados.value.toLocaleLowerCase())
    // );
    userFiltro.value = usersL.value.filter(
      (user) =>
        user.user_name
          .toLowerCase()
          .includes(filtroDados.value.toLowerCase()) ||
        user.email.toLowerCase().includes(filtroDados.value.toLowerCase()) ||
        user.user_full_name
          .toLowerCase()
          .includes(filtroDados.value.toLowerCase()) ||
        user.is_active.toLowerCase().includes(filtroDados.value.toLowerCase())
    );
    console.log("USer filtro");
    console.log(userFiltro.value);
    loading.value = false;
    console.log(userFiltro.value);
  }
};

const addUser = async () => {
  // Definindo os dados do payload com informações do usuário e suas permissões
  const payload = {
    name: "Domingos Doe",
    email: "teste34sea@gmail.com",
    password: "12345678",
    mobile: "987654321",
    roles: [{ name: "Manager" }], // Atribuindo o ID da role ao invés de apenas o nome
  };

  try {
    // Realizando a requisição POST para criar o usuário
    const response = await axios.post("/api/users", payload, {
      headers: {
        "Content-Type": "application/json",
      },
    });

    // Exibindo uma notificação de sucesso se o usuário for criado com sucesso
    toast.add({
      severity: "success",
      summary: "Usuário Criado",
      detail: `Usuário ${response.data.name} criado com sucesso!`,
      life: 3000,
    });
  } catch (error) {
    // Exibindo erro se a criação do usuário falhar
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
  console.log(`Token: ${token}`);
  console.log("Usuario sendo criado");
  console.log(dadosAddL);
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
        console.log("resposta do user adicionado");
        // let nome = "null"
        // response.data.forEach(element => {
        //   nome = element.user_name
        //   console.log(element)
        // })
        // console.log(response.data.user_name);

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
  console.log(`Token: ${token}`);
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

    console.log("Response Empresas");

    empresas.value = response.data.data.data;
    // empresa.value = empresas.value.filter(
    //   (nome)=>
    //     nome.name

    // )

    // empresa.value = e
    // console.log("FIltro empresas")
    // console.log(empresa.value)
    // for (const item in empresas.value) {
    //   console.log(empresas[0])
    // }

    empresas.value.forEach((element, key) => {
      console.log(element.name);
      empresa.value.push({ id: element.id, name: element.name });
    });
    console.log(empresa.value);
  } catch (error) {
    console.error("Erro ao carregar dados:", error);
  } finally {
    loading.value = false;
  }
};

const atualizarDadosShow = async () => {
  console.log();

  console.log("Dados sendo atualizados");
  console.log(dadosAtualizar);

  const dadoAtualizacao = {
    id: dadosAtualizar.id,
    user_full_name: dadosAtualizar.user_full_name,
    email: dadosAtualizar.email,
    is_active: Number(ative_selected.value.code),
    gate_id: gateSelected.value.id,
    roles: [{ name: roleSelected.value.name }],
  };

  console.log("Dados atualizacao");
  console.log(dadoAtualizacao);
  console.log(roleSelected);
  console.log("-----------------------------");
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
        await axios.put(
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
        console.log("Adicionado");
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
    const response = await axios.delete(
      `${baseUrls.userList}/${dadosUserDelete.value.id}`,
      {
        headers: { Authorization: `Bearer ${token}` },
      }
    );
    console.log("Usuário deletado com sucesso", response.status);
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

  console.log(dadosUserDelete);
}
const salvarPermissions = async () => {
  console.log("Dados salvos");
  dialogRoleUpdateVisible.value = false;
  console.log(permissions.value);
};

// dialogUserUpdateVisible = true

// const dadosAtualizar = reactive({
//   id: null,
//   user_full_name: "",
//   is_active: 0,
//   email: "",
//   gate_id: 0,
//   roles: [{name: "Admin"}],
// });

const dadosAtualizar = reactive({
  id: null,
  user_full_name: "",
  is_active: Number(ative_selected.value.code),
  email: "",
  gate_id: gateSelected.value,
  roles: roleSelected.value,
});
const atualizarDados = (dados) => {
  dialogUserUpdateVisible.value = true;
  dadosAtualizar.id = dados.id;
  // dadosAtualizar.roles = [{name: dados.roles[0].name}];

  roleSelected.value.name = dados.roles[0].name;
  dadosAtualizar.roles = roleSelected.value;

  dadosAtualizar.email = dados.email;
  dadosAtualizar.user_full_name = dados.user_full_name;

  console.log(`RoleDados:`);
  console.log(dadosAtualizar.roles);
  // gateSelected
  console.log("---------------------------------------------");
  console.log(dados);
  console.log(`Ative user: ${dados.is_active}`);
  gates.value.forEach((gateUser) => {
    if (gateUser.id == dados.gate[0].gate_id) {
      gateSelected.value = gateUser;
    }
    console.log(gateUser.id);
  });

  if (dados.is_active == "0") {
    ative_selected.value.name = "Inativo";
    ative_selected.value.code = "0";
  } else {
    ative_selected.value.name = "Ativo";
    ative_selected.value.code = "1";
  }
  dadosAtualizar.gate_id = gateSelected.value;
  dadosAtualizar.is_active = Number(ative_selected.value.code);

  console.log("---------------------------------------------");

  userFiltro.value.forEach((element) => {
    // console.log(element.roles.value)

    if (dados.id == element.id) {
      console.log("User correct");

      element.roles.forEach((item) => {
        dadosAtualizar.roles = item.name;
        console.log(formDataSave.roles);
      });
    }
  });
};

const disableMk = () => {
  dialogUserUpdateVisible.value = false;
  console.log("Cancelar");
};

const verificadorDeCampos = (dado) => {
  console.log("Verificador de campos vazios");
  console.log(dado);
  errorL.value = "";
  for (const key in dado) {
    // if (key == "roles") {
    //   console.log(`role: ${dado[key][0].name}`);
    //   console.log(dado[key][0].name == undefined ? "Vazio" : "Preenchido");
    //   if (dado[key][0].name == undefined) {
    //     console.log("Role nao definido");
    //     errorL.value = `Preencha todos os dados`;
    //   }
    // }
    if (dado[key] == "" || dado[key] == undefined) {
      if (key != "is_active") {
        console.log(`Key: ${key}: sem dados`);
        errorL.value = `Preencha todos os dados`;
      }
    }
  }

  console.log("-------------------------------------------------");
};

const buscarGates = async () => {
  const token = getToken();
  console.log(`Token: ${token}`);
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

    console.log("Response Gate");
    gates.value = response.data.data;

    console.log(response.data.data);
  } catch (error) {
    console.error("Erro ao carregar dados:", error);
  } finally {
    loading.value = false;
  }
};

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
    <!-- <div>
      <h3>Selecione um país:</h3>
      <Dropdown
        v-model="selectedCountry"
        :options="countries"
        optionLabel="name"
        placeholder="Selecione um país"
      />
      <p>País selecionado: {{ selectedCountry.name }}</p>
    </div> -->
    <div class="font-semibold text-xl mb-4">Users</div>
    <DataTable :value="userFiltro" :paginator="true" :rows="10">
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
      <Column field="user_full_name" header="Nome" style="min-width: 12rem">
      </Column>

      <Column field="email" header="Email" style="min-width: 10rem"> </Column>

      <Column field="user_name" header="Username" style="min-width: 10rem">
      </Column>

      <Column field="company_id" header="Empresa" style="min-width: 10rem">
      </Column>

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
              <!-- <Button
                label=""
                icon="pi pi-key"
                class="p-button-success btnPermission"
                style="
                  padding: 5px 0px;
                  background-color: transparent;
                  color: #000000;
                  border: 0px;
                "
                @click="dialogRoleUpdateVisible = true"
              /> -->

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
      <Column
        field="is_active"
        header="Ativo"
        dataType="boolean"
        bodyClass="text-center"
        style="min-width: 8rem"
      >
        <template #body="{ data }">
          <!-- <div> {{verificarA[data.id]}} </div> -->

          <div
            v-if="data.is_active == 1"
            style="
              display: flex;
              align-items: center;
              justify-items: center;
              gap: 10px;
            "
          >
            <i class="pi pi-times-circle text-red-500"></i>
            <!-- <InputSwitch v-model="checked2" /> -->
            <!-- <InputSwitch v-model="verificarA[data.id]" /> -->
            <!-- {{data.is_active}} -->
          </div>

          <div
            v-if="data.is_active == 0"
            style="
              display: flex;
              align-items: center;
              justify-items: center;
              gap: 10px;
            "
          >
            <i class="pi pi-check-circle text-green-500"></i>
            <!-- <InputSwitch v-model="checked" /> -->
            <!-- <InputSwitch  v-model="verificarA[data.id]"/> -->
          </div>
        </template>
      </Column>

      <!-- <Column header="Permissões">
            <template #body="{data}">
                <Button class="btnEstiliza" label="" icon="pi pi-refresh" @click="generatePDF(data)" style=" border: 0px; background-color: transparent; color: #1558b0; display: none" />
                <div style="display: flex; gap: 10px">
                    <Button label="Criar" rounded />
                    <Button label="Apagar" severity="success" rounded />
                    <Button label="Atualizar" severity="info" rounded />
                </div>

            </template>
            </Column> -->
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
      header="Novo user"
      v-model:visible="dialogoUserVisble"
      :closable="true"
      :modal="true"
      :draggable="false"
      :resizable="false"
      style="width: 30vw; min-height: 60vh"
      :footer="productDialogFooterForm"
    >
      <div class="erroMessage">
        {{ errorL }}
      </div>
      <hr />
      <div class="camposAgrupadosFormulario my-5">
        <!-- Nome nome -->
        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="name">User</label>
            <InputText
              id="name"
              v-model="formDataSave.user"
              required
              autofocus
              class="camposTextos"
            />
          </div>
        </div>

        <!-- Email -->
        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="name">Nome completo</label>
            <InputText
              id="name"
              v-model="formDataSave.name"
              required
              autofocus
              class="camposTextos"
            />
          </div>
        </div>
      </div>

      <div class="camposAgrupadosFormulario my-5">
        <!-- Nome -->

        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="email">Email</label>
            <InputText
              id="email"
              v-model="formDataSave.email"
              required
              autofocus
              class="camposTextos"
            />
          </div>
        </div>
        <!-- Acesso -->
        <div class="formUserAdd">
          <label for="acesso">Acesso</label>
          <Select
            id="acesso"
            v-model="formDataSave.roles"
            :options="rolesName"
            optionLabel="name"
            placeholder="S. Nivel de acesso"
            class="w-full"
          ></Select>
        </div>
      </div>

      <!-- Numero -->

      <!-- Portão -->
      <!-- <div class="formUserAdd">
        <label for="portao" class="labelDrop">Portão</label>
        <Select
          id="portao"
          v-model="gate"
          :options="gateItem"
          optionLabel="name"
          placeholder="Selecione o Portão"
          class="w-full"
        ></Select>
      </div> -->

      <div class="formUserAdd">
        <label for="empresa">Empresa</label>
        <Select
          id="empresa"
          v-model="formDataSave.company_id"
          :options="empresa"
          optionLabel="name"
          placeholder="Empresas"
          class="w-full"
        ></Select>
      </div>

      <!-- Senha -->
      <div class="formUserAdd" style="margin-top: 15px; width: 100%">
        <div
          class="field formUserAddI"
          style="width: 100%; border: 0px solid black"
        >
          <label for="senha" class="my-5">Senha</label>
          <Password
            id="senha"
            v-model="formDataSave.password"
            placeholder="Senha"
            :toggleMask="true"
            class="mb-4 inputsCaixas camposTextos"
            fluid
            :feedback="false"
          ></Password>
        </div>
      </div>

      <hr class="my-5" />
      <div class="flex">
        <button class="p-button p-component cores" @click="salvarDadosShow">
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
      header="Atualizar user"
      v-model:visible="dialogUserUpdateVisible"
      :closable="true"
      :modal="true"
      :draggable="false"
      :resizable="false"
      style="width: 30vw; min-height: 20vh"
      :footer="productDialogFooterForm"
    >
      <div class="erroMessage">
        {{ errorL }}
      </div>
      <hr />
      <div class="camposAgrupadosFormulario my-5">
        <!-- Nome -->
        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="name">Nome</label>
            <InputText
              id="name"
              v-model="dadosAtualizar.user_full_name"
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
        <!-- GAte -->

        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="acesso">Portão</label>
            <Select
              id="portao"
              v-model="gateSelected"
              :options="gates"
              optionLabel="name"
              placeholder="Portão"
              class="w-full"
            ></Select>
          </div>
        </div>

        <!-- Acesso -->
        <!-- roleSelected -->
        <!-- <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="acesso">Acesso</label>
            <Select
              id="acesso"
              v-model="dadosAtualizar.roles"
              :options="rolesName"
              optionLabel="name"
              placeholder="S. Nivel de acesso"
              class="w-full"
            ></Select>
          </div>
        </div> -->
        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="acesso">Acesso</label>
            <Select
              id="acesso"
              v-model="roleSelected"
              :options="rolesName"
              optionLabel="name"
              placeholder="S. Nivel de acesso"
              class="w-full"
            ></Select>
          </div>
        </div>
      </div>
      <div class="formUserAdd">
        <div class="field formUserAddI">
          <label for="acesso">Status</label>
          <Select
            id="status"
            v-model="ative_selected"
            :options="statusItems"
            optionLabel="name"
            placeholder="Status"
            class="w-full"
          ></Select>
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
</style>


