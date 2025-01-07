<script setup>
import { CustomerService } from '@/service/CustomerService';
import { ProductService } from '@/service/ProductService';
import { FilterMatchMode, FilterOperator } from '@primevue/core/api';
import { onBeforeMount, reactive, ref } from 'vue';

const customers1 = ref(null);
const customers2 = ref(null);
const customers3 = ref(null);
const filters1 = ref(null);
const loading1 = ref(null);
const balanceFrozen = ref(false);
const products = ref(null);
const expandedRows = ref([]);
const statuses = reactive(['Security', 'Admin', 'Outros', 'Admin', 'Security', 'Outros']);
const representatives = reactive([
    { name: 'Amy Elsner', image: 'amyelsner.png' },
    { name: 'Anna Fali', image: 'annafali.png' },
    { name: 'Asiya Javayant', image: 'asiyajavayant.png' },
    { name: 'Bernardo Dominic', image: 'bernardodominic.png' },
    { name: 'Elwin Sharvill', image: 'elwinsharvill.png' },
    { name: 'Ioni Bowcher', image: 'ionibowcher.png' },
    { name: 'Ivan Magalhaes', image: 'ivanmagalhaes.png' },
    { name: 'Onyama Limba', image: 'onyamalimba.png' },
    { name: 'Stephen Shaw', image: 'stephenshaw.png' },
    { name: 'XuXue Feng', image: 'xuxuefeng.png' }
]);

function getOrderSeverity(order) {
    switch (order.status) {
        case 'DELIVERED':
            return 'success';

        case 'CANCELLED':
            return 'danger';

        case 'PENDING':
            return 'warn';

        case 'RETURNED':
            return 'info';

        default:
            return null;
    }
}

function getSeverity(status) {
    switch (status) {
        case 'Admin':
            return 'danger';

        case 'Outros':
            return 'success';

        case 'Security':
            return 'info';

        case 'negotiation':
            return 'warn';

        case 'renewal':
            return null;
    }
}

function getStockSeverity(product) {
    switch (product.inventoryStatus) {
        case 'INSTOCK':
            return 'success';

        case 'LOWSTOCK':
            return 'warn';

        case 'OUTOFSTOCK':
            return 'danger';

        default:
            return null;
    }
}

onBeforeMount(() => {
    ProductService.getProductsWithOrdersSmall().then((data) => (products.value = data));
    CustomerService.getCustomersLarge().then((data) => {
        customers1.value = data;
        loading1.value = false;
        customers1.value.forEach((customer) => (customer.date = new Date(customer.date)));
    });
    CustomerService.getCustomersLarge().then((data) => (customers2.value = data));
    CustomerService.getCustomersMedium().then((data) => (customers3.value = data));

    initFilters1();
});

function initFilters1() {
    filters1.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        name: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
        'country.name': { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
        representative: { value: null, matchMode: FilterMatchMode.IN },
        date: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.DATE_IS }] },
        balance: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] },
        status: { operator: FilterOperator.OR, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] },
        activity: { value: [0, 100], matchMode: FilterMatchMode.BETWEEN },
        verified: { value: null, matchMode: FilterMatchMode.EQUALS }
    };
}

function expandAll() {
    expandedRows.value = products.value.reduce((acc, p) => (acc[p.id] = true) && acc, {});
}

function collapseAll() {
    expandedRows.value = null;
}

function formatCurrency(value) {
    return value.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
}

function formatDate(value) {
    return value.toLocaleDateString('en-US', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

function calculateCustomerTotal(name) {
    let total = 0;
    if (customers3.value) {
        for (let customer of customers3.value) {
            if (customer.representative.name === name) {
                total++;
            }
        }
    }

    return total;
}
</script>

<template>
    <div class="card">
        <div class="font-semibold text-xl mb-4">Users</div>
        <DataTable
            :value="customers1"
            :paginator="true"
            :rows="10"
            dataKey="id"
            :rowHover="true"
            v-model:filters="filters1"
            filterDisplay="menu"
            :loading="loading1"
            :filters="filters1"
            :globalFilterFields="['name', 'country.name', 'representative.name', 'balance', 'status']"
            showGridlines
        >
            <template #header>
                <div class="flex justify-between">
                    
                    <!-- <Button type="button" icon="pi pi-filter-slash" label="Clear" outlined @click="clearFilter()" /> -->
                    <IconField>
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="filters1['global'].value" placeholder="Pesquisar" />
                    </IconField>
                    <div class="btnsL">
                        <Button label="Novo" icon="pi pi-plus" class="cores" @click="dialogVisible = true"/>
                    </div>
                    
                </div>
            </template>
            <template #empty> No customers found. </template>
            <template #loading> Loading customers data. Please wait. </template>
            <Column field="name" header="Nome" style="min-width: 12rem">
                <template #body="{ data }">
                    {{ data.name }}
                </template>
                <template #filter="{ filterModel }">
                    <InputText v-model="filterModel.value" type="text" placeholder="Search by name" />
                </template>
            </Column>
            <!-- <Column header="Country" filterField="country.name" style="min-width: 12rem">
                <template #body="{ data }">
                    <div class="flex items-center gap-2">
                        <img alt="flag" src="https://primefaces.org/cdn/primevue/images/flag/flag_placeholder.png" :class="`flag flag-${data.country.code}`" style="width: 24px" />
                        <span>{{ data.country.name }}</span>
                    </div>
                </template>
                <template #filter="{ filterModel }">
                    <InputText v-model="filterModel.value" type="text" placeholder="Search by country" />
                </template>
                <template #filterclear="{ filterCallback }">
                    <Button type="button" icon="pi pi-times" @click="filterCallback()" severity="secondary"></Button>
                </template>
                <template #filterapply="{ filterCallback }">
                    <Button type="button" icon="pi pi-check" @click="filterCallback()" severity="success"></Button>
                </template>
            </Column>
            <Column header="Agent" filterField="representative" :showFilterMatchModes="false" :filterMenuStyle="{ width: '14rem' }" style="min-width: 14rem">
                <template #body="{ data }">
                    <div class="flex items-center gap-2">
                        <img :alt="data.representative.name" :src="`https://primefaces.org/cdn/primevue/images/avatar/${data.representative.image}`" style="width: 32px" />
                        <span>{{ data.representative.name }}</span>
                    </div>
                </template>
                <template #filter="{ filterModel }">
                    <MultiSelect v-model="filterModel.value" :options="representatives" optionLabel="name" placeholder="Any">
                        <template #option="slotProps">
                            <div class="flex items-center gap-2">
                                <img :alt="slotProps.option.name" :src="`https://primefaces.org/cdn/primevue/images/avatar/${slotProps.option.image}`" style="width: 32px" />
                                <span>{{ slotProps.option.name }}</span>
                            </div>
                        </template>
                    </MultiSelect>
                </template>
            </Column> -->
            <Column header="Data  de registro" filterField="date" dataType="date" style="min-width: 10rem">
                <template #body="{ data }">
                    {{ formatDate(data.date) }}
                </template>
                <template #filter="{ filterModel }">
                    <DatePicker v-model="filterModel.value" dateFormat="mm/dd/yy" placeholder="mm/dd/yyyy" />
                </template>
            </Column>
            <!-- <Column header="Balance" filterField="balance" dataType="numeric" style="min-width: 10rem">
                <template #body="{ data }">
                    {{ formatCurrency(data.balance) }}
                </template>
                <template #filter="{ filterModel }">
                    <InputNumber v-model="filterModel.value" mode="currency" currency="USD" locale="en-US" />
                </template>
            </Column> -->
            <Column header="Nível de Acesso" field="status" :filterMenuStyle="{ width: '14rem' }" style="min-width: 12rem">
                <template #body="{ data }">
                    <Tag :value="data.status" :severity="getSeverity(data.status)" />
                </template>
                <template #filter="{ filterModel }">
                    
                    <Select v-model="filterModel.value" :options="statuses" placeholder="Select One" showClear>
                        <template #option="slotProps">
                            <Tag :value="slotProps.option" :severity="getSeverity(slotProps.option)" />
                        </template>
                    </Select>
                </template>
            </Column>
            <Column field="activity" header="Activity" :showFilterMatchModes="false" style="min-width: 12rem">
                <template #body="{ data }">
                    <ProgressBar :value="data.activity" :showValue="false" style="height: 6px"></ProgressBar>
                </template>
                <template #filter="{ filterModel }">
                    <Slider v-model="filterModel.value" range class="m-4"></Slider>
                    <div class="flex items-center justify-between px-2">
                        <span>{{ filterModel.value ? filterModel.value[0] : 0 }}</span>
                        <span>{{ filterModel.value ? filterModel.value[1] : 100 }}</span>
                    </div>
                </template>
            </Column>
            <Column field="verified" header="Verified" dataType="boolean" bodyClass="text-center" style="min-width: 8rem">
                <template #body="{ data }">
                    <i class="pi" :class="{ 'pi-check-circle text-green-500 ': data.verified, 'pi-times-circle text-red-500': !data.verified }"></i>
                </template>
                <template #filter="{ filterModel }">
                    <label for="verified-filter" class="font-bold"> Verified </label>
                    <Checkbox v-model="filterModel.value" :indeterminate="filterModel.value === null" binary inputId="verified-filter" />
                </template>
            </Column>
        </DataTable>
    </div>

     <div class="p-fluid">
        <Dialog
            header="Novo user"
             v-model:visible="dialogVisible"
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
                v-model="nome"
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
                v-model="apelido"
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
            v-model="email"
            required
            autofocus
            class="camposTextos"
            />
        </div>
        </div>
        <!-- <label for="state">State</label>
        <Select id="state" v-model="dropdownItem" :options="dropdownItems" optionLabel="name" placeholder="Select One" class="w-full"></Select> -->

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
                <!-- <Password v-model="value" class="btnPass w-full" placeholder="Senha" style="width: 100%; border: 1px solid red; outline: 0px;"/> -->
                <Password id="senha" v-model="password" placeholder="Senha" :toggleMask="true" class="mb-4 inputsCaixas camposTextos" fluid :feedback="false"></Password>
                
            </div>
            </div>

            <hr class="my-5">
             <div class="flex">
                <button class="p-button p-component cores" @click="saveData()">Salvar</button>
                <button class="p-button p-component p-button-secondary mx-2" @click="dialogVisible = false">Cancelar</button>
            </div>
           
        </Dialog>
        
    </div>
    <!-- <Button 
      label="Novo Usuário" 
      class="p-button-raised p-button-success" 
      @click="openDialog" 
    />
    <Button label="Abrir Diálogo" icon="pi pi-external-link" @click="dialogVisible = true" /> -->
</template>

<script>

import { ref } from "vue";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import Password from "primevue/password";

const value = ref(null);
const dropdownItems = ref([
    { name: 'Option 1', code: 'Option 1' },
    { name: 'Option 2', code: 'Option 2' },
    { name: 'Option 3', code: 'Option 3' }
]);


const sexo = ref([
    { name: 'Masculino', code: 'M' },
    { name: 'Feminino', code: 'F' }
]);

const sexoItem = ref(null);

const acesso = ref([
    { name: 'Segurança', code: 'security' },
    { name: 'Outros', code: 'others' }
]);

const acessoItem = ref(null);

const gate = ref([
    { name: '4', code: 'quatro' },
    { name: '6', code: 'seis' },  
    { name: '11A', code: 'onzea' }
]);

const gateItem = ref(null);




export default {
  components: {
    Dialog,
    InputText,
    Dropdown,
    Password,
  },
  data() {
        return {
            dialogVisible: false,
        };
    },
    saveData(){
        console.log("Salvo")
    },
  setup() {
   


    return {
      displayFormulario,
      nome,
      apelido,
      email,
      portao,
      portaoS,
      sexoUser,
      sexo,
      acessoUser,
      acesso,
      senha,
      productDialogFooterForm,
      saveUser,
      cancel,
    };
  },
};
</script>


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


</style>


