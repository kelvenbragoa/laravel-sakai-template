<script setup>
import { CustomerService } from '@/service/CustomerService';
import { ProductService } from '@/service/ProductService';
import { FilterMatchMode, FilterOperator } from '@primevue/core/api';
import { onBeforeMount, onMounted, reactive, ref } from 'vue';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

let dialogoUserVisble = ref(false)
let dialogUserUpdateVisible = ref(false)
let verificarA = ref([])



const toast = useToast()

const usersL = ref([])
const fetchUsers = async () => {
  try {
    const response = await fetch("/api/users");
    const data = await response.json();
    // permissions.value = Array.isArray(data) ? data : []; 
    usersL.value = data.data.data
    console.log(usersL.value)
    // verificarA.value.push = usersL.value.


    // console.log(permissions)
    for(let items in usersL.value){
        // verificarA.value.push = pe
    //   console.log(usersL.value[items]["is_active"])
    verificarA.value.push(usersL.value[items]["is_active"]==1?true:false)
    }

    console.log("---------------------------------------------------")
    console.log(verificarA.value)
    // console.log(permissions.value)
  } catch (error) {
    console.error("Erro ao buscar Users:", error);
  }
};


const salvarDadosShow = () => {
     let dadoUserAdd = {
        nome: nomeL.value,
        apelido: apelidoL.value,
        email: emailL.value,
        senha: senhaL.value,
        gates: gateItem.value.name,
        sexos: sexoItem.value.name,
        acessos: acessoItem.value.name
    }
    dialogoUserVisble.value = false
    console.log(`Dialog visible: ${dialogoUserVisble}`)
    console.log(dadoUserAdd)
    // dialogVisible = false
    toast.add({ severity: 'success', summary: 'Confirmação', detail: 'Usuario salvo', life: 3000 });
    //toast.add({ severity: 'Confirmação', summary: 'Info', detail: 'Salvo', life: 3000 });
};

const atualizarDadosShow = () => {
     let dadoUserAdd = {
        nome: nomeL.value,
        apelido: apelidoL.value,
        email: emailL.value,
        senha: senhaL.value,
        gates: gateItem.value.name,
        sexos: sexoItem.value.name,
        acessos: acessoItem.value.name
    }
    dialogUserUpdateVisible.value = false
    // dialogVisible = false
    toast.add({ severity: 'success', summary: 'Confirmação', detail: 'Usuario atualizado', life: 3000 });
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







onMounted(()=>{
    fetchUsers();
})
</script>

<template>
    <div class="card">

        <div class="font-semibold text-xl mb-4">Users</div>
         <!-- <div class="card flex justify-center">
            <Toast />
            <Button label="Show" @click="show()" />
        </div> -->

        <!-- :paginator="true"
            :rows="10"
            dataKey="id"
            :rowHover="true"
            v-model:filters="filters1"
            filterDisplay="menu"
            :loading="loading1"
            :filters="filters1"
            :globalFilterFields="['name', 'country.name', 'representative.name', 'balance', 'status']"
            showGridlines -->

        <DataTable
            :value="usersL"
            :paginator="true"
            :rows="10"
        >
            <template #header>
                <div class="flex justify-between">
                    <IconField class="searchText">
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="value" placeholder="Pesquisar" />
                    </IconField>
                    <div class="btnsL">
                        <Button label="Novo" icon="pi pi-plus" class="cores" @click="dialogoUserVisble = true"/>
                    </div>
                    
                </div>
            </template>
            <template #empty> No customers found. </template>
            <template #loading> Loading customers data. Please wait. </template>
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
                        <Button class="btnEstiliza" label="" icon="pi pi-refresh" style=" border: 0px; background-color: transparent; color: #1558b0" @click="dialogUserUpdateVisible = true" />
                        <div>
                            
                            <Button label="" class="btnEstilizaDel" icon="pi pi-trash" severity="danger" style="padding: 5px 0px;background-color: transparent; color: #ff0000; border: 0px" @click="openConfirmation" />
                            
                        </div>
                    </div>
                </template>
               
            </Column>
            <Column field="is_active" header="Ativo" dataType="boolean" bodyClass="text-center" style="min-width: 8rem">
                <template #body="{data}" >

                   <div v-if="data.is_active==1">
                        <i class="pi pi-times-circle text-red-500"></i>

                   </div>

                   <div v-else>
                    <i class="pi pi-check-circle text-green-500 "></i>
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
                <Button label="Sim" icon="pi pi-check" @click="closeConfirmation" severity="danger" outlined autofocus />
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
                v-model="nomeL"
                required
                autofocus
                class="camposTextos"
            />
            </div>
        </div>

        <!-- Apelido -->
        <div class="formUserAdd">
            <div class="field formUserAddI">
            <label for="apelido">Apelido</label>
            <InputText
                id="apelido"
                v-model="apelidoL"
                required
                autofocus
                class="camposTextos"
            />
            </div>
        </div>
        </div>

        <!-- Email -->
        <div class="formUserAdd">
        <div class="field formUserAddI">
            <label for="email">Email</label>
            <InputText
            id="email"
            v-model="emailL"
            required
            autofocus
            class="camposTextos"
            />
        </div>
        </div>
        <!-- Portão -->
        <div class="formUserAdd">
            <label for="portao" class="labelDrop">Portão</label>
            <Select id="portao" v-model="gateItem" :options="gate" optionLabel="name" placeholder="Selecione o Portão" class="w-full"></Select>
        </div>

            <div class="camposAgrupadosFormulario">
            <!-- Sexo -->
            <div class="formUserAdd">
                <label for="sexo" class="labelDrop">Sexo</label>
                <Select id="sexo" v-model="sexoItem" :options="sexo" optionLabel="name" placeholder="Selecione o sexo" class="w-full"></Select>
            
            </div>

            <!-- Acesso -->
            <div class="formUserAdd">
                <label for="acesso" class="labelDrop">Acesso</label>
                <Select id="sexo" v-model="acessoItem" :options="acesso" optionLabel="name" placeholder="S. Nivel de acesso" class="w-full"></Select>
            </div>
            </div>

            <!-- Senha -->
            <div class="formUserAdd" style="margin-top: 15px; width: 100%">
            <div class="field formUserAddI" style="width: 100%; border: 0px solid black">
                <label for="senha" class="my-5">Senha</label>
                <Password id="senha" v-model="senhaL" placeholder="Senha" :toggleMask="true" class="mb-4 inputsCaixas camposTextos" fluid :feedback="false"></Password>
                
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
                v-model="nomeL"
                required
                autofocus
                class="camposTextos"
            />
            </div>
        </div>

        <!-- Apelido -->
        <div class="formUserAdd">
            <div class="field formUserAddI">
            <label for="apelido">Apelido</label>
            <InputText
                id="apelido"
                v-model="apelidoL"
                required
                autofocus
                class="camposTextos"
            />
            </div>
        </div>
        </div>

        <!-- Email -->
        <div class="formUserAdd">
        <div class="field formUserAddI">
            <label for="email">Email</label>
            <InputText
            id="email"
            v-model="emailL"
            required
            autofocus
            class="camposTextos"
            />
        </div>
        </div>
        <div class="formUserAdd">
            <label for="portao" class="labelDrop">Portão</label>
            <Select id="portao" v-model="gateItem" :options="gate" optionLabel="name" placeholder="Selecione o Portão" class="w-full"></Select>
        </div>

            <div class="camposAgrupadosFormulario">
            <!-- Sexo -->
            <div class="formUserAdd">
                <label for="sexo" class="labelDrop">Sexo</label>
                <Select id="sexo" v-model="sexoItem" :options="sexo" optionLabel="name" placeholder="Selecione o sexo" class="w-full"></Select>
            
            </div>

            <!-- Acesso -->
            <div class="formUserAdd">
                <label for="acesso" class="labelDrop">Acesso</label>
                <Select id="sexo" v-model="acessoItem" :options="acesso" optionLabel="name" placeholder="S. Nivel de acesso" class="w-full"></Select>
            </div>
            </div>

            <!-- Senha -->
            <div class="formUserAdd" style="margin-top: 15px; width: 100%">
            <div class="field formUserAddI" style="width: 100%; border: 0px solid black">
                <label for="senha" class="my-5">Senha</label>
                <!-- <Password v-model="value" class="btnPass w-full" placeholder="Senha" style="width: 100%; border: 1px solid red; outline: 0px;"/> -->
                <Password id="senha" v-model="senhaL" placeholder="Senha" :toggleMask="true" class="mb-4 inputsCaixas camposTextos" fluid :feedback="false"></Password>
                
            </div>
            </div>

            <hr class="my-5">
             <div class="flex">
                <button class="p-button p-component cores" @click="atualizarDadosShow">Atualizar</button>
                <button class="p-button p-component p-button-secondary mx-2" @click="dialogUserUpdateVisible = false">Cancelar</button>
                <!-- <button @click="sayHello">Clique Aqui</button> -->
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


</style>


