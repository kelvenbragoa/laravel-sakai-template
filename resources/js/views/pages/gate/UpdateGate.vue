<template>
    <div class="card">
        <h1 style="font-weight: 600; font-size: 1.3rem;">
            Atualização do portão: {{ gateIdParam }}
        </h1>
        <div v-if="isLoading" class="loader-overlay">
            <div class="louderL">
                <ProgressSpinner />
            </div>
        </div>
        <div class="form">
            <div class="formUserAdd">
                <div class="field formUserAddI">
                    <label for="name">Nome</label>
                    <InputText id="name" v-model="gateName" required autofocus class="camposTextos"
                        placeholder="Nome do portão" />
                </div>
            </div>

            <div class="formUserAdd descL">
                <div class="field formUserAddI">
                    <label for="name">Descrição</label>
                    <Textarea placeholder="Descrição" v-model="description" />
                </div>
            </div>

            <div class="permissionsGates">

                <div class="permissionItems">
                    <h2>Inserção e Escaneamento</h2>
                    <div class="permissionsCheck">
                        <div class="permissionCheckItem" v-for="item in categoria1" :key="item.id">
                            <Checkbox v-model="item.checked" binary :inputId="`checkitem-${item.id}`" />
                            <label :for="`checkitem-${item.id}`">{{ item.name }}</label>
                        </div>
                    </div>
                </div>

                <div class="permissionItems">
                    <h2>Verificações e Validações</h2>
                    <div class="permissionsCheck">
                        <div class="permissionCheckItem" v-for="item in categoria2" :key="item.id">
                            <Checkbox v-model="item.checked" binary :inputId="`checkitem-${item.id}`" />
                            <label :for="`checkitem-${item.id}`">{{ item.name }}</label>
                        </div>
                    </div>
                </div>

                <div class="permissionItems">
                    <h2>Opções de plataforma de comparação de dados</h2>
                    <div class="permissionsCheck">
                        <div class="permissionCheckItem" v-for="item in categoria3" :key="item.id">
                            <Checkbox v-model="item.checked" binary :inputId="`checkitem-${item.id}`" />
                            <label :for="`checkitem-${item.id}`">{{ item.name }}</label>
                        </div>
                    </div>
                </div>

                <div class="permissionItems">
                    <h2>Opções de segurança</h2>
                    <div class="permissionsCheck">
                        <div class="permissionCheckItem" v-for="item in categoria4" :key="item.id">
                            <Checkbox v-model="item.checked" binary :inputId="`checkitem-${item.id}`" />
                            <label :for="`checkitem-${item.id}`">{{ item.name }}</label>
                        </div>
                    </div>
                </div>

            </div>
            <div class="btns">
                <Button @click="gotoList" severity="secondary" label="Cancel" icon="pi pi-times-circle" />
                <Button @click="onFormSubmit" severity="secondary" label="Atualizar" icon="pi pi-pencil" />
            </div>


        </div>

    </div>

</template>
<script setup>

import { onBeforeMount, onMounted, reactive, ref, watch } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useToast } from "primevue";
import axios from "axios";
import { baseUrls } from "../../../api";
const toast = useToast()
const router = useRouter()
const routePath = useRoute()
const gateIdParam = routePath.params.id;
const gateName = ref('')
const description = ref('')
const gatesPermissions = ref([])
const gateEspecific = ref([])
const gateAll = ref([])
const categoria1 = ref([]) // => Inserção e Escaneamento
const categoria2 = ref([]) // => Verificações e Validações
const categoria3 = ref([]) // => Opções de plataforma de comparação de dados
const categoria4 = ref([]) // => Opções de segurança
const selectedPermissions = ref({})
const isLoading = ref(true)


const checked = ref(false);

// const onFormSubmit = ({ valid }) => {
//     if (valid) {
//         toast.add({ severity: 'success', summary: 'Form is submitted.', life: 3000 });
//     }
// };

const gotoList = () => {
    router.push("/gate")
}


const getToken = () => {
    return localStorage.getItem("access_token");
};

const buscarGate = async()=>{
    isLoading.value = true
    const token = getToken();
    if (!token) {
        backLog()
        return;
    } else {
        try {
            const response = await axios.get(baseUrls.gate, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            })
            gateAll.value = response.data.data.data
            
            gateEspecific.value = gateAll.value.find(gate => gate.id === Number(gateIdParam));
            gateName.value = gateEspecific.value.name
            isLoading.value = false
        } catch (e) {
            isLoading.value = false
            toast.add({ severity: 'error', summary: `Erro ao buscar as permissões ${e}`, life: 3000 });
        }
    }
}

const buscarGatePermissions = async () => {
    isLoading.value = true
    const token = getToken();
    if (!token) {
        backLog()
        return;
    } else {
        try {
            const response = await axios.get(baseUrls.gatePermissions, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            })

            gatesPermissions.value = response.data.data
            categorizarPermissoes(gatesPermissions.value)
            isLoading.value = false
        } catch (e) {
            isLoading.value = false
            toast.add({ severity: 'error', summary: `Erro ao buscar as permissões ${e}`, life: 3000 });
        }
    }
}

const categorizarPermissoes = (dados) => {
    const permissoesSelecionadas = gateEspecific.value?.permissions?.map(p => Number(p.gate_permission_id)) || []

    const mapChecked = (arr) => arr.map(p => ({
        ...p,
        checked: permissoesSelecionadas.includes(p.id)
    }))

    categoria1.value = mapChecked(dados.filter(p =>
        [
            'Inserir e Escanear os Contentores',
            'Inserir e Escanear as Cartas de condução',
            'Inserir e Escanear as matrículas do camião',
            'Inserir e Escanear as matrículas dos atrelados',
            'Inserir e Escanear os tipos de carga',
            'Inserir e Escanear os números de selos',
            'Inserir e Escanear as Pesagens'
        ].includes(p.name)
    ))

    categoria2.value = mapChecked(dados.filter(p =>
        [
            'Verificar e Validar Contentor',
            'Verificar e Validar Carta de condução',
            'Verificar e Validar matrícula do camião',
            'Verificar e Validar matrícula dos atrelados',
            'Verificar e Validar o tipo de carga',
            'Verificar e Validar os números de selos',
            'Verificar e Validar Pesagens'
        ].includes(p.name)
    ))

    categoria3.value = mapChecked(dados.filter(p =>
        [
            'N4',
            'CDMS',
            'Neuralabs'
        ].includes(p.name)
    ))

    categoria4.value = mapChecked(dados.filter(p =>
        [
            'Verificação de segurança',
            'Verificação de Appointment',
            'Pre-Check',
            'After check',
            'Excepções',
            'Imprimir o TID',
            'Imprimir a nota de entrega'
        ].includes(p.name)
    ))
}

const onFormSubmit = async () => {


    const selecionados = [
        ...categoria1.value,
        ...categoria2.value,
        ...categoria3.value,
        ...categoria4.value
    ].filter(p => p.checked)
        .map(p => ({ gate_permission_id: p.id }))
    let gateSave = {
        'name': gateName.value,
        'description': description.value,
        'permissions': selecionados
    }
    const token = getToken();
    if (!token) {
        backLog()
        return;
    } else {
        if (selecionados.length === 0 || gateName.value == '') {
            toast.add({ severity: 'warn', summary: 'Selecione pelo menos uma permissão ou preencha o campo Nome.', life: 3000 })
            return
        } else {
            isLoading.value = true
            try {
                const response = await axios.put(`${baseUrls.gate}/${gateIdParam}`, gateSave, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                });
                toast.add({ severity: 'success', summary: `Gate ${gateName.value} atualizado com sucesso`, life: 3000 });

                isLoading.value = false
                gateName.value = ''
                description.value = ''
                const resetChecks = (categoria) => {
                    categoria.value.forEach(p => p.checked = false)
                }

                resetChecks(categoria1)
                resetChecks(categoria2)
                resetChecks(categoria3)
                resetChecks(categoria4)
            } catch (e) {
                toast.add({ severity: 'error', summary: `Erro ao buscar as permissões ${e}`, life: 3000 });
                isLoading.value = false
            }
        }
    }

    // toast.add({ severity: 'success', summary: 'Permissões salvas!', detail: JSON.stringify(selecionados), life: 5000 })
}

onMounted(() => {
    buscarGate()
    buscarGatePermissions()
})
</script>


<style scoped lang="scss">
.formUserAdd label {
    margin-bottom: 5px;
    font-weight: 600;
}

.descL {
    margin-top: 15px;
}

.form {
    border: 0px solid black;
    margin-top: 10px;
    min-height: 20px;
}

.form input:focus {
    border: 1px solid #1558b0 !important;
}

.form textarea:focus {
    border: 1px solid #1558b0 !important;
}

.formUserAddI {
    display: flex;
    flex-direction: column;
}

.formUserAddI Textarea {
    max-height: 300px;
    min-height: 300px;
    max-width: 100%;
    min-width: 100%;
}

.btns {
    padding: 20px 0px;
    margin-top: 20px;
    border-top: 1px solid #ccc;
}

.btns Button {
    width: 100px;
    background-color: #dc3545;
    border: 1px solid #dc3545;
    color: #fff;

}

.btns Button:last-child {
    background-color: #1558b0;
    border: 1px solid #1558b0;

    margin-left: 10px;
}

.btns Button:last-child:hover {
    background-color: #0c4b9d88 !important;
    border: 1px solid #0c4b9d88 !important;
    color: #fff;
}

.permissionItems h2 {
    padding: 20px 0px;
    border-bottom: 1px solid #ccc;
    font-size: 1rem;
    font-weight: 600;
}

.permissionsCheck {
    display: flex;
    flex-wrap: wrap;
    padding: 20px 0px;
}

.permissionsCheck .permissionCheckItem {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    margin-right: 20px;
    margin-top: 15px;
}

.permissionsCheck .permissionCheckItem:last-child {
    margin-left: 0px;
}

.permissionsCheck .permissionCheckItem label {
    margin-left: 10px;
    cursor: pointer;
}

.permissionsCheck .permissionCheckItem label:hover {
    color: #1558b0;
}
</style>
