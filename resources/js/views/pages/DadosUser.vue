<template>
  <div>
    <h1>Dados</h1>
    
    <!-- Campo de entrada -->
    <input v-model="userInput" type="text" placeholder="Digite algo" />
    
    <!-- Botão para imprimir o valor do input -->
    <button @click="printInput">Imprimir</button>
    <button @click="showConfirm">Imprimir</button>

    <!-- Dialog de confirmação -->
    <ConfirmDialog />
     <button @click="saveData">Salvar</button>
  </div>

  <div class="card flex justify-center">
        <Toast />
        <Button label="Show" @click="show()" />
    </div>
     <div class="card flex justify-center">
        <Button label="Show" @click="visible = true" />
        <Dialog v-model:visible="visible" modal header="Edit Profile" :style="{ width: '25rem' }">
            <span class="text-surface-500 dark:text-surface-400 block mb-8">Update your information.</span>
            <div class="flex items-center gap-4 mb-4">
                <label for="username" class="font-semibold w-24">Username</label>
                <InputText id="username" class="flex-auto" autocomplete="off" />
            </div>
            <div class="flex items-center gap-4 mb-8">
                <label for="email" class="font-semibold w-24">Email</label>
                <InputText id="email" class="flex-auto" autocomplete="off" />
            </div>
            <div class="flex justify-end gap-2">
                <Button type="button" label="Cancel" severity="secondary" @click="visible = false"></Button>
                <Button type="button" label="Save" @click="visible = false"></Button>
            </div>
        </Dialog>
    </div>
  
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useConfirm } from 'primevue/useconfirm'; // Importando o hook
import ConfirmDialog from 'primevue/confirmdialog'; 
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

// Variável reativa para armazenar o valor do input
const userInput = ref('');

// Função chamada ao clicar no botão
const printInput = () => {
};

// Inicializando a função de confirmação
const confirm = useConfirm();

// Função que dispara o diálogo de confirmação
const showConfirm = () => {
  confirm.require({
    message: 'Você tem certeza que deseja continuar?',
    header: 'Confirmar',
    icon: 'pi pi-exclamation-triangle',
    accept: () => {
      // Ação quando o usuário clica em "Sim"
    },
    reject: () => {
      // Ação quando o usuário clica em "Não"

    }
  });

  


}

const toast = useToast();

// Função para simular o salvamento de dados e mostrar a mensagem de sucesso
const saveData = () => {

  toast.add({
    severity: 'success',  // Tipo da mensagem (pode ser success, info, warn, error)
    summary: 'Salvo!',    // Título da mensagem
    detail: 'Seus dados foram salvos com sucesso.', // Detalhes adicionais
    life: 3000             // Duração em milissegundos (3 segundos)
  });
};

// Requisição para obter dados
axios
  .get(
    "https://cdmapi.cornelder.co.mz/cgate/api/v1/c_gate/general_cargo/list_transactions"
  )
  .then((res) => {

  })
  .catch((error) => {
    console.log(error);
  });
  


const show = () => {
    toast.add({ severity: 'info', summary: 'Info', detail: 'Message Content', life: 3000 });
};





const visible = ref(false);




</script>
