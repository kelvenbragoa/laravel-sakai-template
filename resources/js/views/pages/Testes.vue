<template>
  <div class="card">
    <DataTable
      v-model:filters="filters"
      :value="data"
      paginator
      :rows="10"
      dataKey="id"
      filterDisplay="row"
      :loading="loading"
      :globalFilterFields="['cargo_type', 'document_number', 'driver_name', 'truck_license_plate_number', 'status']"
    >
      <template #header>
        <div class="flex justify-between align-center">
          <h2>
            Gate selecionado: <strong>{{ gateId }}</strong>
          </h2>
          <IconField>
            <InputIcon>
              <i class="pi pi-search" />
            </InputIcon>
            <InputText v-model="filters['global'].value" placeholder="Pesquise" />
          </IconField>
        </div>
      </template>
      <template #empty> Nenhum dado encontrado. </template>
      <template #loading> Carregando os dados. Por favor, aguarde. </template>

      <Column field="cargo_type" header="Tipo" style="min-width: 12rem">
        <template #body="{ data }">
          {{ data.cargo_type }}
        </template>
        <template #filter="{ filterModel, filterCallback }">
          <InputText v-model="filterModel.value" type="text" @input="filterCallback()" placeholder="Filtro por Tipo" />
        </template>
      </Column>

      <Column field="document_number" header="Número de documento" style="min-width: 12rem">
        <template #body="{ data }">
          {{ data.document_number }}
        </template>
        <template #filter="{ filterModel, filterCallback }">
          <InputText v-model="filterModel.value" type="text" @input="filterCallback()" placeholder="Filtro por documento" />
        </template>
      </Column>

      <Column field="driver_name" header="Condutor" style="min-width: 12rem">
        <template #body="{ data }">
          {{ data.driver_name }}
        </template>
        <template #filter="{ filterModel, filterCallback }">
          <InputText v-model="filterModel.value" type="text" @input="filterCallback()" placeholder="Filtro por condutor" />
        </template>
      </Column>

      <Column field="truck_license_plate_number" header="Placa de caminhão" style="min-width: 12rem">
        <template #body="{ data }">
          {{ data.truck_license_plate_number }}
        </template>
        <template #filter="{ filterModel, filterCallback }">
          <InputText v-model="filterModel.value" type="text" @input="filterCallback()" placeholder="Search by Truck License Plate" />
        </template>
      </Column>

      <Column field="created_at" header="Criado em" style="min-width: 12rem">
        <template #body="{ data }">
          {{ formatDate(data.created_at) }}
        </template>
      </Column>

      <Column header="Detalhes" style="min-width: 10rem">
        <template #body="{ data }">
          <Button label="Ver PDF" icon="pi pi-file-pdf" @click="generatePDF(data)" />
        </template>
      </Column>
    </DataTable>
  </div>
</template>
<script src="bower_components/jspdf/dist/jspdf.min.js"></script>
<script src="bower_components/jspdf-autotable/dist/jspdf.plugin.autotable.js"></script>
<script setup>
import { ref, onMounted } from 'vue';
import { FilterMatchMode } from '@primevue/core/api';
import { getCarga } from '@/api';
import { useRoute } from 'vue-router';
import { jsPDF } from "jspdf";
import "jspdf-autotable";


const route = useRoute();
const userId = route.params.id;

let gateId = userId;
// if (Number(gateId.indexOf("Out")) > -1) {
//   gateId = gateId.replace("Out", "");
//   gateId = `Portão ${gateId} (Saida)`;
// } else {
//   gateId = gateId.replace("In", "");
//   gateId = `Portão ${gateId} (Entrada)`;
// }

const data = ref([]);
const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  cargo_type: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  document_number: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  driver_name: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  truck_license_plate_number: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
});
const loading = ref(true);

const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
  const date = new Date(dateString);
  return date.toLocaleDateString('pt-BR', options);
};

onMounted(async () => {
  try {
    const result = await getCarga(1, 10, "", null);
    if (result && result.data && Array.isArray(result.data)) {
      data.value = result.data;
    } else {
      data.value = [];
    }
  } catch (error) {
    console.error("Erro ao buscar transações:", error);
  } finally {
    loading.value = false;
  }
});

const generatePDF = () => {
  const doc = new jsPDF();

  // Dados para a tabela
  const tableData = [
    ["Nome", "Idade", "Cidade"],
    ["Maria", 30, "Lisboa"],
    ["João", 25, "Porto"]
  ];

  // Tamanho das células
  const cellHeight = 10;
  const cellWidth = 50;
  
  // Definir a posição inicial
  let x = 10;
  let y = 10;
  
  // Cabeçalhos da tabela
  tableData[0].forEach((header, index) => {
    doc.text(header, x + (index * cellWidth), y);
  });
  
  y += cellHeight; // Aumenta a posição para as linhas de dados

  // Linhas de dados
  for (let i = 1; i < tableData.length; i++) {
    tableData[i].forEach((cell, index) => {
      doc.text(cell, x + (index * cellWidth), y);
    });
    y += cellHeight;
  }

  // Salvar o PDF
  doc.save("tabela.pdf");
  
};


 
</script>

<style scoped>
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

th, td {
  padding: 8px;
  text-align: left;
}

th {
  background-color: #f2f2f2;
}

td {
  border-bottom: 1px solid #ddd;
}

p {
  font-size: 18px;
  color: #888;
}
</style>
