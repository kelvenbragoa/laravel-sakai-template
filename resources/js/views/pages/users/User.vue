<script setup>
import { CustomerService } from "@/service/CustomerService";
import { ProductService } from "@/service/ProductService";
import { FilterMatchMode, FilterOperator } from "@primevue/core/api";
import { onBeforeMount, onMounted, reactive, ref, watch } from "vue";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import { baseUrls } from "../../../api/index";
import { useRouter } from "vue-router";
import axios from "axios";
import { backLog, checkAccess, dataUser } from "../../../utils/accesRoute";
import { elements } from "chart.js";
import { computed } from "vue";


checkAccess()
let dialogoUserVisble = ref(false);
let dialogUserUpdateVisible = ref(false);
let dialogRoleUpdateVisible = ref(false);
const applications = ref([])
const rolesName = ref([]);
let verificarA = ref([]);
const empresas = ref([]);
const empresa = ref([]);
const empresaL = ref([]);
const empresaName = ref([]);
const empresaMap = {};
const dadoSearch = ref("")
const treeApplications = ref([
])

const aplicationsLabelsAll = ref([]);
const aplicationsLabelsAll2 = ref([]);



const aplicationsLabels = ref([])

const treeGates = ref([
])

const numberL = []

for (let i = 0; i < 10; i++) {
  numberL[i] = i
}
const applicationsAcesso = ref([
  {
    name: 'acesso1'
  },
  {
    name: 'acesso2'
  },
  {
    name: 'acesso3'
  },
  {
    name: 'acesso4'
  }
])

const dialogUserDetails = ref(false)
const userDetails = ref()

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

const permissionsItems = [];

const loading = ref(false);

const formDataSave = reactive({
  name: "",
  user: "",
  email: "",
  company_id: "0",
  password: "",
  roles: [],
  applications: [],
  gate: ""
});




const countries = ref([
  { name: "Moçambique", code: "MZ" },
  { name: "Brasil", code: "BR" },
  { name: "Portugal", code: "PT" },
]);

const gateIdArray = ref([])

// Definindo a pré-seleção (exemplo: Moçambique)
const selectedCountry = ref(countries.value[0]);
const roleSelected = ref([]);

const gateSelected = ref([])
const gatePreSelecteds = ref([])
const ative_selected = ref({ name: "Inativo", code: "0" });

const getToken = () => {
  return localStorage.getItem("access_token");
};

const getUserDataEspecific = async (id) => {

  const token = getToken();
  if (!token) {
    backLog()
    return;
  }
  try {
    loading.value = true;
    const response = await axios.get(`${baseUrls.userList}/${id}`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    loading.value = false;

    return response.data.data
  } catch (e) {
    return e
  }

}

const getApplicationsUserEspecific = () => {
  return JSON.parse(localStorage.getItem("cgate_applicationsPermissions"))
}


const aplicationsAcesso = (id) => {

  let result = []
  for (let aplicationsField in applications.value) {
    if (Number(id) == Number(applications.value[aplicationsField].id)) {
      for (let i in applications.value[aplicationsField].application_permissions) {
        result.push({
          value: `${id}#${applications.value[aplicationsField].application_permissions[i].permission.id}`,
          label: applications.value[aplicationsField].application_permissions[i].permission.name
        })
      }

      aplicationsLabelsAll.value.push(result)

      return result
    }
  }

  return ({ label: '', value: '' })
}




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

    applications.value = response.data.data.data.map((element) => {

      return {
        ...element,
        name: element.name + " " + element.version,
      };
    });



    applications.value.forEach((element) => {

      treeApplications.value.push(
        aplicationsLabels.value.push(
          {
            label: String(element.name),
            items: aplicationsAcesso(element.id)
          }
        )
      )
    })
  } catch (error) {
    console.error("Erro ao carregar dados:", error);
  } finally {
    loading.value = false;
  }
};

const buscarUsuarios = async (page = 1) => {
  loading.value = true
  const token = getToken();

  if (!token) {
    backLog()
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
    (error);
  }
};




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


const statusItems = ref([
  { name: "Ativo", code: "1" },
  { name: "Inativo", code: "0" },
]);

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

function mapApplications(applications) {
  return applications.map(item => {
    const [application_id, application_permission_id] = item.split('#').map(Number);
    return {
      application_id,
      application_permission_id
    };
  });
}


const salvarDadosShow = async () => {
  // let arrayRols = []
  // formDataSave.roles.forEach((elements)=>{
  //   arrayRols.push(elements['name'])
  //   // (elements['name'])
  // })

  const resultadoAplications = mapApplications(formDataSave.applications);

  let idsApplications = []
  let idsGates = []
  let applicationsName = []
  // for (let i in formDataSave.applications) {

  //   numberL.forEach(elements => {
  //     if (elements == i) {
  //       applicationsName.push(returnPermissionsApplications(i))
  //     }
  //   })
  // }
  // returnPermissionsApplications(2)

  // formDataSave.applications.forEach(elements=>{
  // })

  // formDataSave.applications.forEach((element) => {
  //   idsApplications.push({ application_id: element['id'] })
  // })

  formDataSave.gate.forEach((element) => {
    idsGates.push({
      "gate_id": element['id']
    })
  })
  let dadosAddL = {
    user_full_name: formDataSave.name,
    user_name: formDataSave.user,
    email: formDataSave.email,
    company_id: String(formDataSave.company_id.id),
    password: formDataSave.password,
    roles: formDataSave.roles,
    gates: idsGates,
    applications: resultadoAplications

  };

  const token = getToken();

  if (!token) {
    backLog()
    return;
  } else {
    // verificadorDeCampos(dadosAddL);
    // if (errorL.value === "") {
    //   dialogoUserVisble.value = false;
    //   loading.value = true;

    //   try {
    //     const response = await axios.post(baseUrls.userList, dadosAddL, {
    //       headers: {
    //         Authorization: `Bearer ${token}`,
    //       },
    //     });

    //     toast.add({
    //       severity: "success",
    //       summary: "Confirmação",
    //       detail: `Usuário criado com sucesso!`,
    //       life: 3000,
    //     });
    //     // fetchUsers();
    //     buscarUsuarios();
    //     loading.value = false;

    //     formDataSave.name = "";
    //     formDataSave.user = "";
    //     formDataSave.email = "";
    //     formDataSave.password = "";
    //     formDataSave.company_id = "0";
    //     formDataSave.roles = [];
    //     formDataSave.applications = [];
    //     formDataSave.gate = []
    //   } catch (error) {
    //     console.error(
    //       "Erro ao adicionar usuário:",
    //       error.response?.data || error
    //     );
    //     toast.add({
    //       severity: "error",
    //       summary: "Erro",
    //       detail: error.response?.data?.message || "Erro ao criar o usuário.",
    //       life: 3000,
    //     });
    //     loading.value = false;
    //   }
    // }
  }
};

const buscarEmpresas = async () => {
  const token = getToken();

  if (!token) {
    backLog()
    return;
  }
  try {
    const response = await axios.get(baseUrls.empresaAdd, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });


    empresas.value = response.data.data.data;
    empresaL.value = response.data.data.data
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

  const resultadoAplications = mapApplications(dadosAtualizar.applications);



  const dadoAtualizacao = {
    // id: dadosAtualizar.id,
    user_full_name: dadosAtualizar.user_full_name,
    user_name: dadosAtualizar.user_name,
    email: dadosAtualizar.email,
    is_active: Number(ative_selected.value.code),
    gates: gateIdArray.value,
    roles: roleSelected.value,
    applications: resultadoAplications,
    company_id: dadosAtualizar.company_id.id,
  };

  if (dadosAtualizar.password != "") {
    dadoAtualizacao.password = dadosAtualizar.password
  } else {
    if (dadoAtualizacao.password !== undefined) {
      delete teste["password"];
    }
  }

  const token = getToken();
  if (!token) {
    backLog()
    return;
  } else {
    verificadorDeCampos(dadoAtualizacao);
    if (errorL.value === "") {
      dialogUserUpdateVisible.value = false;
      loading.value = true;
      try {
        await axios.post(
          `${baseUrls.userList}/${dadosAtualizar.id}/update`,
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


function closeConfirmatio2n() {
  displayConfirmation.value = false;
}



async function apaga() {
  displayConfirmation.value = false;
  loading.value = true;
  const token = getToken();
  if (!token) {
    backLog()
    return;
  }
  console.log(`Token: ${token}`)
  try {
    const response = await axios.post(
      `${baseUrls.userList}/${dadosUserDelete.value.id}/delete`,
      {},
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


const returnAplications = (id) => {
  const app = applications.value.find(a => a.id === Number(id));
  return app ? app.name : String(id);
}



const returnCompany = (id) => {
  for (let i = 0; i < empresaName.value.length; i++) {
    if (i === (Number(id) - 1)) {
      return empresaName.value[i]
    }
  }
  return id

}

const returnGates = (id) => {

  for (let i = 0; i < gates.value.length; i++) {
    if (Number(id) === i) {
      return gates.value[i].name
    }
  }
  return id
}

const returnAcessos = (id) => {

  for (let i = 0; i < rolesItems.value.length; i++) {
    if (Number(id) === rolesItems.value[i].id) {
      return rolesItems.value[i].name
    }
  }
  return id
}

const returnPermissionsApplications = (id) => {
  for (let i = 0; i < applicationsAcesso.value.length; i++) {
    if (Number(id) === i) {
      return applicationsAcesso.value[i].name
    }
  }
  return id
}

const returnAplicationsIds = (dados) => {
  let result = []
  for (let i in dados) {
    for (let c in dados[i].user_application_permissions) {
      result.push(`${dados[i].application_id}#${dados[i].user_application_permissions[c].application_permission_id}`)
    }
  }
  return result
}

// returnAplicationsIds(getApplicationsUserEspecific().applications)

const dadosAtualizar = reactive({
  id: null,
  user_full_name: "",
  user_name: "",
  is_active: Number(ative_selected.value.code),
  email: "",
  applications: "",
  company_id: "",
  roles: roleSelected.value,
  gate: gates.value,
  password: ""
});
const atualizarDados = async (dados) => {
  const result = await getUserDataEspecific(dados.id)
  dadosAtualizar.applications = returnAplicationsIds(result.applications)
  dadosAtualizar.company_id = { id: Number(dados.company_id), name: returnCompany(dados.company_id) }
  gateSelected.value = []
  gateIdArray.value = []
  gatePreSelecteds.value = []


  dialogUserUpdateVisible.value = true;
  dadosAtualizar.id = dados.id;
  dadosAtualizar.user_name = dados.user_name
  // // dadosAtualizar.roles = [{name: dados.roles[0].name}];
  let arrayRols = []
  dados.roles.forEach((elements) => {
    arrayRols.push({ name: elements['name'] })
  })

  roleSelected.value = arrayRols

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

const buscarGates = async () => {
  const token = getToken();
  if (!token) {
    backLog()
    return;
  }
  try {
    const response = await axios.get(baseUrls.gate, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    gates.value = response.data.data.data;

    gates.value.forEach((element) => {

      treeGates.value.push(
        {
          key: String(element.gate_id),
          label: element.name,
          children: {
            key: `0`,
            label: ""
          }
        }
      )
    })

    // treeGates

  } catch (error) {
    console.error("Erro ao carregar dados:", error);
  } finally {
    loading.value = false;
  }
};

const detailsUser = (data) => {
  dialogUserDetails.value = true
  userDetails.value = data
}

const gatesApplacations = [
  {
    name: "Cgate 2.0 Terminal",
    gates: ["4", "5", "6", "8A", "11A"]
  },
  {
    name: "Cgate 1.2",
    gates: ["4", "5", "6", "8A", "11A"]
  },

  {
    name: "Cgate 2.0 Cargo",
    gates: ["1", "3", "16"]
  }
]

const perfis = [
  {
    nome: "Tally 8A",
    gate: ["8A"],
    permissao: ["Tally In"],
    papel: "Tally"
  },
  {
    nome: "Security 8A",
    gate: ["8A"],
    permissao: ["Security Check In"],
    papel: "Security"
  },
  {
    nome: "General Cargo Security",
    gate: ["1", "3"],
    permissao: ["Security Check In", "Security Check Out"],
    papel: "Security"
  },
  {
    nome: "Tally 11A",
    gate: ["11A"],
    permissao: ["Tally In", "Tally Out"],
    papel: "Tally"
  },
  {
    nome: "Security 11A",
    gate: ["11A"],
    permissao: ["Security Check In", "Security Check Out"],
    papel: "Security"
  },
  {
    nome: "Security 8A CG1.2",
    gate: ["8A"],
    permissao: ["Security Check In"],
    papel: "Security"
  },
  {
    nome: "Security 4,5,6 CG1.2",
    gate: ["4", "5", "6"],
    permissao: ["Security Check Out"],
    papel: "Security"
  },
  {
    nome: "Precheck",
    gate: ["none"],
    permissao: ["Check Appointment"],
    papel: "Tally"
  },
  {
    nome: "Admin",
    gate: ["all"],
    permissao: ["all"],
    papel: "Admin"
  },
  {
    nome: "Manager Tc",
    gate: ["4", "5", "6", "8A", "11A"],
    // gate: ["Cgate 2.0 Terminal", "Cgate 1.2"],
    permissao: ["all"],
    papel: "Manager"
  },
  {
    nome: "Manager Cargo",
    gate: ["4", "5", "6", "8A", "11A"],
    // gate: ["Cgate 2.0 Cargo"],
    permissao: ["all"],
    papel: "Manager"
  }
];



const allRoleNames = () => {
  aplicationsLabelsAll.value.forEach(
    (e) => {
      for (let i in e) {
        aplicationsLabelsAll2.value.push(e[i]['value'])
        // console.log(e)
      }

    }
  )
}


const returGatesForName = (gateName) => {
  console.log(gateName)
  if (gateName == "all") {
    formDataSave.gate = gates.value
  } else {
    gates.value.forEach((gate) => {
      if (gate.name.includes(gateName)) {
        formDataSave.gate.push(gate)
        // console.log("Yes")
        // console.log(gate.name)
      }

    })
  }
}

const returApplicationsForName = (aplicationName) => {
  if (aplicationName == "all") {
    formDataSave.applications = aplicationsLabelsAll2.value
  }else{
    // console.log("Aplications diferentes")
    // console.log(aplicationName)

    aplicationName.forEach((e)=>{
      console.log(e)
    })
  }
}

const preenchimentoRoles = (name) => {
  perfis.forEach((elements) => {
    if (name.toLocaleUpperCase() == elements.papel.toLocaleUpperCase()) {
      // console.log(`Yes: ${elements.papel}`)
      returGatesForName(elements.gate)
      returApplicationsForName(elements.permissao)

    }
  })
}


// const rolePrePreenchido = (dado) => {
// for (let item in dado) {
//   preenchimentoRoles(dado[item].name)
// }
// }
const preAllRoleName = ref(true)

const preechimentoField = (dado) => {
  console.log(dado)
  // rolePrePreenchido(dados)
  for (let item in dado) {
    preenchimentoRoles(dado[item].name)

  }
}


watch(
  () => formDataSave.roles,
  (newValue) => {
    formDataSave.gate = []
    formDataSave.applications = []
    preechimentoField(newValue)


    if (preAllRoleName.value) {
      allRoleNames()
      preAllRoleName.value = false
    }
  },
  { deep: true }
);

onMounted(() => {
  //   fetchUsers();
  fetchRoles();
  fetchPermissions();
  buscarUsuarios();
  buscarEmpresas();
  buscarGates();
  buscarApplication();

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

    <!-- <div class="p-4">
      <TreeSelect v-model="selectedKey" :options="treeNodes" placeholder="Selecione um item" selectionMode="single"
        :metaKeySelection="false" class="w-full md:w-30rem mb-3" />

      <div>
        <strong>Selecionado:</strong>
        <pre>{{ selectedKey }}</pre>
      </div>
    </div> -->
    <!-- <MultiSelect 
      v-model="valoresSelecionados"
      :options="opcoes"
      optionLabel="label"
      optionValue="value"
      optionGroupLabel="label"
      optionGroupChildren="items"
      placeholder="Selecione frutas ou doces"
      display="chip"
      :filter="true"
    /> -->

    <!-- <span>{{ valoresSelecionados }}</span> -->

    <!-- <div v-if="Object.keys(selecionadosPorCategoria).length" class="mt-4">
      <h3>Selecionados:</h3>
      <div v-for="(itens, categoria) in selecionadosPorCategoria" :key="categoria" class="mb-2">
        <strong>{{ categoria }}:</strong>
        <ul>
          <li v-for="item in itens" :key="item">{{ item }}</li>
        </ul>
      </div>
    </div> -->


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
            <Button class="btnEstiliza" label="" icon="pi  pi-eye"
              style="border: 0px; background-color: transparent; color: #1558b0" @click="detailsUser(data)" />

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
        <!-- Usuario -->
        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="name">User</label>
            <InputText id="name" v-model="formDataSave.user" required autofocus class="camposTextos" />
          </div>
        </div>

        <!-- Nome completo -->
        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="name">Nome completo</label>
            <InputText id="name" v-model="formDataSave.name" required autofocus class="camposTextos" />
          </div>
        </div>
      </div>

      <div class="camposAgrupadosFormulario my-5">
        <!-- Email -->

        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="email">Email</label>
            <InputText id="email" v-model="formDataSave.email" required autofocus class="camposTextos" />
          </div>
        </div>
        <!-- Acesso -->
        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="acesso">Acesso</label>
            <MultiSelect v-model="formDataSave.roles" :options="rolesName" optionLabel="name" placeholder="Papel"
              display="chip" class="w-full" />

          </div>

        </div>
      </div>

      <!-- <TreeSelect v-model="selectedKey" :options="treeGates" placeholder="Selecione um item"
        selectionMode="single" :metaKeySelection="false" class="w-full md:w-30rem mb-3" /> -->

      <div class="camposAgrupadosFormulario my-5">
        <!-- Aplications -->

        <div class="formUserAdd aplicationsCHip">

          <div class="field formUserAddI">
            <label for="acesso">Aplicações</label>
            <!-- <TreeSelect v-model="formDataSave.applications" :options="treeApplications" placeholder="applications"
              selectionMode="checkbox" :metaKeySelection="false" class="w-full md:w-30rem mb-3" /> -->
            <!-- <MultiSelect v-model="formDataSave.applications" :options="applications" optionLabel="name"
              placeholder="Aplicações" display="chip" class="w-full" /> -->



            <MultiSelect v-model="formDataSave.applications" :options="aplicationsLabels" optionLabel="label"
              optionValue="value" optionGroupLabel="label" optionGroupChildren="items" placeholder="Aplicações"
              display="chip" class="w-full" />

          </div>

        </div>
        <!-- Gates -->
        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="gates">Gates</label>
            <MultiSelect v-model="formDataSave.gate" :options="gates" optionLabel="name" placeholder="Portões"
              display="chip" class="w-full" />

            <!-- <TreeSelect v-model="formDataSave.gate" :options="treeGates" placeholder="Portões" selectionMode="checkbox"
              :metaKeySelection="false" class="w-full md:w-30rem mb-3" /> -->

          </div>

        </div>
      </div>

      <div class="formUserAdd">

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

        <!--User-->

        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="name">User</label>
            <InputText id="name" v-model="dadosAtualizar.user_name" required autofocus class="camposTextos" />
          </div>
        </div>


      </div>

      <div class="camposAgrupadosFormulario my-5">
        <!-- Email -->
        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="email">Email</label>
            <InputText id="email" v-model="dadosAtualizar.email" required autofocus class="camposTextos" />
          </div>
        </div>


        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="acesso">Aplicações</label>
            <MultiSelect v-model="dadosAtualizar.applications" :options="aplicationsLabels" optionLabel="label"
              optionValue="value" optionGroupLabel="label" optionGroupChildren="items" placeholder="Aplicações"
              display="chip" class="w-full" />

          </div>

        </div>
      </div>


      <div class="camposAgrupadosFormulario my-5">
        <!-- GAte -->

        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="gates">Portão</label>
            <MultiSelect v-model="gateSelected" :options="gates" optionLabel="name" placeholder="Portões" display="chip"
              class="w-full" />

          </div>

        </div>

        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="acesso">Acesso</label>
            <MultiSelect v-model="roleSelected" :options="rolesName" optionLabel="name" placeholder="Papel"
              display="chip" class="w-full" />

          </div>

        </div>
      </div>




      <div class="camposAgrupadosFormulario my-5">
        <!-- Empresa -->
        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="email">Empresa</label>
            <Select id="empresa" v-model="dadosAtualizar.company_id" :options="empresa" optionLabel="name"
              placeholder="Empresas" class="w-full"></Select>
          </div>
        </div>


        <!--status-->
        <div class="formUserAdd">
          <div class="field formUserAddI">
            <label for="acesso">Status</label>
            <Select id="status" v-model="ative_selected" :options="statusItems" optionLabel="name" placeholder="Status"
              class="w-full"></Select>
          </div>
        </div>
      </div>

      <!-- Senha -->
      <div class="formUserAdd" style="margin-top: 15px; width: 100%">
        <div class="field formUserAddI" style="width: 100%; border: 0px solid black">
          <label for="senha" class="my-5">Senha</label>
          <Password id="senha" v-model="dadosAtualizar.password" placeholder="Senha" :toggleMask="true"
            class="mb-4 inputsCaixas camposTextos" fluid :feedback="false"></Password>
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

    <Dialog header="Detalhes do user" v-model:visible="dialogUserDetails" :closable="true" :modal="true"
      :draggable="false" :resizable="false" style="width: 50vw; min-height: 5vh" :footer="productDialogFooterForm">

      <!-- <hr /> -->
      <div class="containersDetails">
        <div class="cardUserDetails">

          <div class="section">
            <div class="titleCardUsers"> <i class="pi pi-user"></i>
              <div class="titleCardUser">Informações do Usuário</div>
            </div>
            <div class="dateDetails"><span class="label">ID:</span><span class="value">{{ userDetails.id }}</span></div>
            <div class="dateDetails"><span class="label">Nome:</span><span class="value">{{ userDetails.user_full_name
                }}</span></div>
            <div class="dateDetails"><span class="label">Email:</span><span class="value">{{ userDetails.email }}</span>
            </div>
            <div class="dateDetails"><span class="label">Ativo:</span><span class="value">{{ userDetails.is_active == 0
              ?
              'Inativo' : 'Ativo' }}</span></div>
          </div>

          <div class="section">
            <div class="titleCardUsers"> <i class="pi pi-box"></i>
              <div class="titleCardUser">Aplicações</div>
            </div>
            <div class="dateDetails">
              <ul class="aplicationUserEspecific">
                <li v-for="(app, index) in userDetails.applications" :key="app.id">
                  <span class="label">Aplicação {{ index + 1 }}: </span>
                  <span class="value">{{ returnAplications(app.application_id) }}</span>
                  <!-- <span class="value">{{ app.application_id }}</span> -->
                </li>
              </ul>
            </div>
          </div>

          <div class="section">
            <div class="titleCardUsers"> <i class="pi pi-box"></i>
              <div class="titleCardUser">Empresa</div>
            </div>
            <div class="dateDetails">
              <span class="label">Nome:</span><span class="value">{{ returnCompany(userDetails.company_id) }}</span>
              <!-- <span class="label">Id:</span><span class="value">{{ userDetails.company_id }}</span> -->

              <!-- <ul>
                <li v-for="(app, index) in userDetails.application" :key="app.id">
                  <span class="label">Aplicação {{ index+1 }}: </span>
                  <span class="value">{{ returnAplications(app.application_id) }}</span>
                </li>
              </ul> -->
            </div>

          </div>

          <div class="section">
            <div class="titleCardUsers"> <i class="pi pi-sign-in"></i>
              <div class="titleCardUser">Gates</div>
            </div>
            <ul class="gatesCircle">
              <li v-for="(app) in userDetails.gate" :key="app.id">
                <div class="chip dateDetails"><span class="value">{{ returnGates(app.gate_id) }}</span></div>


              </li>
            </ul>

            <!-- <div class="chip dateDetails">Gate ID: 1</div> -->
          </div>

          <div class="section">
            <div class="titleCardUsers"> <i class="pi pi-shield"></i>
              <div class="titleCardUser">Acesso</div>
            </div>
            <ul class="gatesCircle">
              <li v-for="(app) in userDetails.roles" :key="app.id">
                <div class="chip dateDetails"><span class="value">{{ returnAcessos(app.id) }}</span></div>


              </li>
            </ul>
            <!-- <div class="chip dateDetails">Manager</div> -->
          </div>
          <hr>
          <br>
          <div class="flex">
            <button class="p-button p-component cores" @click="dialogUserDetails = false">
              Sair
            </button>
          </div>
        </div>
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

.cardUserDetails {
  background-color: white;
  border-radius: 10px;
  padding: 20px;
  width: 99%;
  // margin: 0 auto;
  margin: 20px 0px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.cardUserDetails h2 {
  margin-top: 0;
  color: #2c3e50;
}

.section {
  margin-bottom: 20px;
}

.section div {
  // margin: 15px 0px;
}

.dateDetails {
  margin: 15px 0px;
}

.section div:first-child {
  // border: 1px solid red;
  margin: 0px !important;
}

.label {
  font-weight: bold;
  color: #555;
  font-size: 1.2rem;
}

.value {
  margin-left: 10px;
  color: #333;
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

.titleCardUsers {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  // margin: 10px 0px;
  // border: 1px solid black;
}

.section span:last-child {
  font-size: 1.1rem;
}

.titleCardUsers .titleCardUser {
  margin-left: 10px;
  font-size: 1.3rem;
  font-weight: 600;
  color: #1558b0;
}

.titleCardUsers i {
  display: block;
  color: #1558b0;
}

.containersDetails {
  display: flex;
  justify-content: center;
}

.gatesCircle {
  display: flex;
  margin-top: 10px;

}

.gatesCircle .value {
  color: #00796b;
}

.gatesCircle li {
  margin-right: 10px;
}

.aplicationUserEspecific li {
  margin-top: 10px;
}
</style>
