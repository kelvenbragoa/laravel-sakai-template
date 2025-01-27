<script setup>
import { CustomerService } from '@/service/CustomerService';
import { ProductService } from '@/service/ProductService';
import { FilterMatchMode, FilterOperator } from '@primevue/core/api';
import { onBeforeMount, onMounted, reactive, ref } from 'vue';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

let dialogoUserVisble = ref(false)
let dialogUserUpdateVisible = ref(false)
let dialogRoleUpdateVisible = ref(false)
const rolesName = ref([])
let verificarA = ref([])
const checked = ref(true);
const checked2 = ref([false])
const dadosUserDelete = ref(
    {
        name: "",
        id: null
    }
)

const filtroDados = ref("")

const roles = ref()
const rolesItems = ref([])
const permissions = ref([])

const permissionsItems = ([])

const loading = ref(false);


const formDataSave =  reactive(
    {
        name: "",
        email: "",
        password: "",
        mobile: "",
        password: "",
        roles: [], 
    }
)


const fetchPermissions = async () =>{
    try{
        loading.value = true;
        const res = await fetch("/api/permissions")
        const dados = await res.json()
        // permissionsItems.value = dados.data.data
        console.log("-------------------------------------")
        console.log("Permissions")
       for(let k in dados.data.data){
        console.log("Key: "+dados.data.data[k].name)
        permissionsItems.push(dados.data.data[k].name)
        // permissionsItems.value.add(dados.data.data[k].name)
       }
        console.log(permissionsItems)
        loading.value = false;
    }catch(error){
        console.log(error)
    }
}

const fetchRoles = async ()=>{
    try{
        const response = await fetch("/api/roles");
        const data = await response.json();
        console.log("Roles")
        console.log(data.data.data[1].guard_name)
        console.log("---------------------------------------------")
        rolesItems.value = data.data.data.filter((roles)=>{
            // console.log(roles.guard_name.includes('web'))
           return roles.guard_name.includes('web')
            //  user.name.toLowerCase().includes(filtroDados.value.toLowerCase())
        })

        for(let items in rolesItems.value){
            // rolesName.value.push = 
            // console.log(rolesItems.value[items])
            rolesName.value.push({"name": rolesItems.value[items].name})
        }
        console.log("Roles Names")
        console.log(rolesName.value)
    }catch(erro){
        console.log(erro)
    }
}



const sexoItem = ref([
    { name: 'Masculino', code: 'M' },
    { name: 'Feminino', code: 'F' }
]);

const gateItem = ref([
    { name: 'Gate 4', code: '4' },
    { name: 'Gate 5', code: '5' },
    { name: 'Gate 6', code: '6' },
    { name: 'Gate 11A', code: '11A' },
    { name: 'Gate 8A', code: '8A' }
])

const acessoItem = ref([
    { name: 'Administrador', code: 'Admin' },
    { name: 'Manager', code: 'Manager' },
    { name: 'Operator', code: 'Operator' },
    { name: 'Super Admin', code: 'SuperAdmin' }

])

const acesso = ref()
const gate = ref()

const sexo = ref();

const toast = useToast()

const usersL = ref([])
const userFiltro = ref([])
const fetchUsers = async () => {
  try {
    const response = await fetch("/api/users");
    const data = await response.json();
    // permissions.value = Array.isArray(data) ? data : []; 
    usersL.value = data.data.data
    console.log(usersL.value)
    userFiltro.value = data.data.data
    // verificarA.value.push = usersL.value.


    // console.log(permissions)
    for(let items in usersL.value){
        // verificarA.value.push = pe
    //   console.log(usersL.value[items]["is_active"])
    verificarA.value.push(usersL.value[items]["is_active"]==1?true:false)
    }

    console.log("---------------------------------------------------")
    console.log(verificarA.value)
    console.log("Tamanho")
    console.log(verificarA.value.length)
    console.log(data.data.data.length)
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
        console.log(filtroDados.value);
        userFiltro.value = usersL.value.filter((user) =>
            user.name.toLowerCase().includes(filtroDados.value.toLowerCase()) ||
            user.email.toLowerCase().includes(filtroDados.value.toLocaleLowerCase())
        );
        loading.value = false;
        console.log(userFiltro.value)
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


const salvarDadosShow = async () => {
    let dadosAddL = {
        name: formDataSave.name,
        email: formDataSave.email,
        password: formDataSave.password,
        mobile: String(formDataSave.mobile),
        roles: [{name: formDataSave.roles.name}], 
    }
    dialogoUserVisble.value = false
    loading.value = true
    try {
    const response = await axios.post("/api/users", dadosAddL, {
      headers: {
        "Content-Type": "application/json",
      },
    });
    toast.add({ severity: 'success', summary: 'Confirmação', detail: `Usuário ${response.data.name} criado com sucesso!`, life: 3000 });
    fetchUsers();
    loading.value = false
  } catch (error) {
    console.error("Erro ao adicionar usuário:", error.response?.data || error);
    toast.add({ severity: 'error', summary: 'Erro', detail: error.response?.data?.message || "Erro ao criar o usuário.", life: 3000 });
    loading.value = false
  }
};

const atualizarDadosShow = async () => {
    console.log(dadosAtualizar)
    dialogUserUpdateVisible.value = false
    loading.value = true
    console.log(dadosAtualizar)
    const dadoAtualizacao = {
        id: dadosAtualizar.id,
        name: dadosAtualizar.name,
        email: dadosAtualizar.email,
        mobile: String(dadosAtualizar.mobile),
        roles: ["Manager"], 
    }
    try {
    await axios.put(`/api/users/${dadoAtualizacao.id}`, dadoAtualizacao);
    fetchUsers();
    loading.value = false

    toast.add({ severity: 'success', summary: 'Confirmação', detail: 'Usuario atualizado', life: 3000 });
    console.log("Adicionado")
    
  } catch (error) {
    fetchUsers();
    loading.value = false
    toast.add({
      severity: "error",
      summary: "Erro",
      detail: "Falha ao atualizar usuário.",
      life: 3000,
    });
    
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
    toast.add({severity: "success", summary: "Confirmação", detail:"Usuário eliminado", life: 3000})
}

async function apaga() {
    
    displayConfirmation.value = false;
    loading.value = true
    try {
        const response = await axios.delete(`api/users/${dadosUserDelete.value.id}`);
        console.log('Usuário deletado com sucesso', response.status);
         fetchUsers();
        loading.value = false
        toast.add({severity: "success", summary: "Confirmação", detail:`Usuário ${String(dadosUserDelete.value.name)} eliminado`, life: 3000})
    } catch (error) {
        loading.value = false
        toast.add({severity: "error", summary: "Confirmação", detail:`Erro ao deletar o usuário`, life: 3000})
        console.error('Erro ao deletar o usuário:', error.response.data);
    }
    
}

function apagaDados(dados){
    displayConfirmation.value = true;
    dadosUserDelete.value.name = dados.name
    dadosUserDelete.value.id = dados.id
    
    console.log(dadosUserDelete)
}
const salvarPermissions = async ()=>{
    console.log("Dados salvos")
    dialogRoleUpdateVisible.value = false
    console.log(permissions.value)
}


// dialogUserUpdateVisible = true

const dadosAtualizar = reactive({
    id: null,
    name: "",
    email: "",
    mobile: "",
    roles: []
})
const atualizarDados = (dados)=>{
    dialogUserUpdateVisible.value = true
    dadosAtualizar.id = dados.id
    dadosAtualizar.roles = ["Manager"]
    dadosAtualizar.email = dados.email
    dadosAtualizar.name = dados.name
    dadosAtualizar.mobile = dados.mobile
    
    console.log(dadosAtualizar)
}

const disableMk = ()=>{
    dialogUserUpdateVisible.value = false
    console.log("Cancelar")
}


onMounted(()=>{
    fetchUsers();
    fetchRoles();
    fetchPermissions();
})
</script>

<template>
    <div v-if="loading" class="loader-overlay">
        <div class="louderL">
            <ProgressSpinner />
        </div>
        
    </div>
    <div class="card">

        <div class="font-semibold text-xl mb-4">Users</div>
        <DataTable
            :value="userFiltro"
            :paginator="true"
            :rows="10"
        >
            <template #header>
                <div class="flex justify-between">
                    <IconField class="searchText">
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="filtroDados" @input="filtroChange" placeholder="Pesquisar" />
                    </IconField>
                    <div class="btnsL">
                        <Button label="Novo" icon="pi pi-plus" class="cores" @click="dialogoUserVisble = true"/>
                    </div>
                    
                </div>
            </template>
            <template #empty> Vazio. </template>
           

            <Column field="id" header="Id"  style="min-width: 10rem">
                
            </Column>
            <Column field="name" header="Nome" style="min-width: 12rem">

            </Column>
            
             <Column field="email" header="Email"  style="min-width: 10rem">
                
            </Column>
            
            <Column field="mobile" header="Telefone"  style="min-width: 10rem">
                
            </Column>

            
            <Column header="Ações" :showFilterMatchModes="false" style="min-width: 12rem">
                 <template #body="{ data }">
                    <div style="display: flex; gap: 0px">

                        <Button class="btnEstiliza" label="" icon="pi pi-refresh" @click="generatePDF(data)" style=" border: 0px; background-color: transparent; color: #1558b0; display: none" />
                        <Button class="btnEstiliza" label="" icon="pi  pi-pencil" style=" border: 0px; background-color: transparent; color: #1558b0" @click="atualizarDados(data)" />
                        <div>
                            <Button
                                label=""
                                icon="pi pi-key"
                                class="p-button-success btnPermission"
                                style="padding: 5px 0px;background-color: transparent; color: #000000; border: 0px"
                                @click="dialogRoleUpdateVisible = true"
                                />
                            
                            <Button label="" class="btnEstilizaDel" icon="pi pi-trash" severity="danger" style="padding: 5px 0px;background-color: transparent; color: #ff0000; border: 0px" @click="apagaDados(data)" />
                            
                        </div>
                    </div>
                </template>
               
            </Column>
            <Column field="is_active" header="Ativo" dataType="boolean" bodyClass="text-center" style="min-width: 8rem">
                <template #body="{data}" >
                    <!-- <div> {{verificarA[data.id]}} </div> -->

                   <div v-if="data.is_active==1" style="display: flex; align-items: center; justify-items: center; gap: 10px">
                        <i class="pi pi-times-circle text-red-500"></i>
                         <!-- <InputSwitch v-model="checked2" /> -->
                         <!-- <InputSwitch v-model="verificarA[data.id]" /> -->
                         <!-- {{data.is_active}} -->

                   </div>

                   <div v-if="data.is_active==0" style="display: flex; align-items: center; justify-items: center; gap: 10px">
                    <i class="pi pi-check-circle text-green-500 "></i>
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
        <hr />
        <div class="camposAgrupadosFormulario my-5">
        <!-- Nome -->
        <div class="formUserAdd">
            <div class="field formUserAddI">
            <label for="name">Nome</label>
            <InputText
                id="name"
                v-model="formDataSave.name"
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
            v-model="formDataSave.email"
            required
            autofocus
            class="camposTextos"
            />
        </div>
        </div>
        
        </div>

        <!-- Numero -->
        

        <div class="formUserAdd">
            <div class="field formUserAddI">
            <label for="apelido">Telefone</label>
            <!-- <InputText
                id="celular"
                v-model="apelidoL"
                required
                autofocus
                class="camposTextos"
            /> -->
            <!-- <InputNumber v-model="apelidoL" inputId="integeronly" fluid /> -->
            <InputNumber v-model="formDataSave.mobile" inputId="withoutgrouping" :useGrouping="false" fluid required />




            </div>
        </div>
        <!-- Portão -->
        <div class="formUserAdd">
            <label for="portao" class="labelDrop">Portão</label>
            <Select id="portao" v-model="gate" :options="gateItem" optionLabel="name" placeholder="Selecione o Portão" class="w-full"></Select>
        </div>

            <div class="camposAgrupadosFormulario">
            <!-- Sexo -->
            <div class="formUserAdd">
                <label for="sexo" class="labelDrop">Sexo</label>
                <Select id="sexo" v-model="sexo" :options="sexoItem" optionLabel="name" placeholder="Selecione o sexo" class="w-full"></Select>
            
            </div>

            <!-- Acesso -->
            <div class="formUserAdd">
                <label for="acesso" class="labelDrop">Acesso</label>
                <Select id="sexo" v-model="formDataSave.roles" :options="rolesName" optionLabel="name" placeholder="S. Nivel de acesso" class="w-full"></Select>
            </div>
            </div>

            <!-- Senha -->
            <div class="formUserAdd" style="margin-top: 15px; width: 100%">
            <div class="field formUserAddI" style="width: 100%; border: 0px solid black">
                <label for="senha" class="my-5">Senha</label>
                <Password id="senha" v-model="formDataSave.password" placeholder="Senha" :toggleMask="true" class="mb-4 inputsCaixas camposTextos" fluid :feedback="false"></Password>
                
            </div>
            </div>

            <hr class="my-5">
             <div class="flex">
                <button class="p-button p-component cores" @click="salvarDadosShow">Salvar</button>
                <button class="p-button p-component p-button-secondary mx-2" @click="dialogoUserVisble = false">Cancelar</button>
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

        <!-- Numero -->
        

        <div class="formUserAdd">
            <div class="field formUserAddI">
            <label for="apelido">Telefone</label>
            <InputNumber v-model="dadosAtualizar.mobile" inputId="withoutgrouping" :useGrouping="false" fluid />
            <!-- <InputNumber v-model="mobileUpdate" inputId="integeronly" fluid /> -->
            </div>
        </div>

            <hr class="my-5">
             <div class="flex">
                <button class="p-button p-component cores" @click="atualizarDadosShow">Atualizar</button>
                <button class="p-button p-component p-button-secondary mx-2" @click="disableMk">Cancelar</button>
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
                <hr>
                <!-- <Select id="permis" v-model="permissions" :options="permissionsItems"  placeholder="S. Nivel de acesso" class="w-full" style="margin-top: 15px;"></Select> -->

            <MultiSelect name="permissions" v-model="permissions" :options="permissionsItems" filter placeholder="Selecione permissões" :maxSelectedLabels="3" class="w-full md:w-80" style="margin-top: 15px; width: 100%" />
            <!-- <Message v-if="$form.city?.invalid" severity="error" size="small" variant="simple">{{ $form.city.error?.message }}</Message> -->
            <hr class="my-5">
             <div class="flex">
                <button class="p-button p-component cores" @click="salvarPermissions">Salvar</button>
                <button class="p-button p-component p-button-secondary mx-2" @click="dialogRoleUpdateVisible = false">Cancelar</button>
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
.cores{
    background-color: #1558b0;
    border: 1px solid #1558b0;
}

.cores:hover{
    background-color: #1558b0cf!important;
    border: 1px solid #1558b088!important;
}
.camposAgrupadosFormulario{
    display: flex;
    justify-content: space-between;
}
.camposAgrupadosFormulario .formUserAdd{
    width: calc((100% / 2) - 5px);
}

.camposTextos, .dropdownSexo{
    width: 100%;
    margin: 0px 0px;

}

.camposTextos:focus{
    border: #1558b0 1px solid!important;
}
.btnExports:last-child{
    color: #4271d4;
    background-color: #ffffff;
}

.labelDrop{
    margin: 15px 0px;
    display: block;
}

.btnPass{
    width: 100%!important;
    border: 0px!important;
    outline: 0px!important;
}
.p-inputtext{
    width: 100%!important;
}

.btnEstiliza:hover{
    color: #1558b0a4!important;
    background: #1558b033!important;
    transition: all .5s ease!important;
    
}

.btnEstilizaDel:hover{
    color: #ff0000a5!important;
    background: #ff000032!important;
    transition: all .5s ease!important;
    
}

.searchText:focus{
    border: #1558b0 1px solid!important;
}

.btnPermission:hover{
  background: #00000015!important;
  color: #555555!important;
  transition: all .3s ease;
  border-radius: 5px;
}

.louderL{
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


