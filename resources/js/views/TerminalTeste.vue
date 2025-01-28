<template>
  <div>
    <!-- Cabeçalho -->
    <div class="flex justify-between align-center mb-4">
      <h2>Transações - Gate 11A</h2>
      <div class="search-field">
        <i class="pi pi-search search-icon"></i>
        <InputText 
          v-model="filters.global.value" 
          placeholder="Pesquise" 
          class="search-input"
        />
      </div>
    </div>

    <!-- DataTable -->
    <DataTable 
      :value="transactions" 
      
      :filters="filters" 
      :loading="loading" 
      :rows="10"
      :paginator="true" 
      :total-records="totalRecords"
      :first="((currentPage - 1) * rowsPerPage)"
      @page="onPageChange"
      :global-filter-fields="['transaction_gate', 'driver_name', 'truck_license_plate_number', 'status', 'type']"
      table-style="min-width: 60rem"
    >
    
      <Column field="transaction_gate" header="Gate" />
      <Column field="driver_name" header="Condutor" />
      <Column field="truck_license_plate_number" header="Placa do Caminhão" />
      <Column field="status" header="Status" />
      <Column field="type" header="Tipo" />
      <Column 
        field="created_at" 
        header="Criado em"
      >
        <template #body="{ data }">
          {{ formatDate(data.created_at) }}
        </template>
      </Column>
    </DataTable>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { DataTable, Column, InputText } from 'primevue';

// Referências reativas
const transactions = ref([]); // Dados das transações
const totalRecords = ref(0); // Total de registros para paginação
const loading = ref(false); // Estado de carregamento
const currentPage = ref(1); // Página atual
const rowsPerPage = ref(5); // Registros por página
const filters = ref({
  global: { value: '' }, // Filtro global
});

// Métodos
const fetchTransactions = async (page = 1) => {
  loading.value = true;
  try {
    const response = await axios.get(`http://20.87.9.35/api/v1/transacoes/lista`, {
      params: {
        page: page, // Página atual
      },
    });

    // Configuração dos dados
    const { data, current_page, total } = response.data.result;
    transactions.value = data;
    currentPage.value = current_page;
    totalRecords.value = total;
  } catch (error) {
    console.error('Erro ao carregar dados:', error);
  } finally {
    loading.value = false;
  }
};

// Troca de página
const onPageChange = (event) => {
  const newPage = event.page + 1; // PrimeVue usa index 0
  fetchTransactions(newPage);
};

// Formatar data
const formatDate = (date) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(date).toLocaleDateString(undefined, options);
};

// Montagem inicial
onMounted(() => {
  fetchTransactions(); // Carregar dados iniciais
});
</script>

<style scoped>
/* Estilo geral */
.flex {
  display: flex;
  align-items: center;
}
.mb-4 {
  margin-bottom: 1rem;
}

/* Campo de pesquisa */
.search-field {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.search-input {
  width: 300px;
}
.search-icon {
  font-size: 1.2rem;
}

/* DataTable */
.p-datatable {
  margin-top: 1rem;
}
</style>


