<template>
  <div class="card">
    <DataTable :value="cargaData" removableSort tableStyle="min-width: 50rem">
      <Column field="id" header="ID" sortable style="width: 10%"></Column>
      <Column field="cargo_type" header="Tipo de Carga" sortable style="width: 20%"></Column>
      <Column field="truck_license_plate_number" header="Placa do Caminhão" sortable style="width: 20%"></Column>
      <Column field="driver_name" header="Nome do Motorista" sortable style="width: 25%"></Column>
      <Column field="status" header="Status" sortable style="width: 15%"></Column>
      <Column field="created_at" header="Data de Criação" sortable style="width: 20%"></Column>
    </DataTable>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getCarga } from '@/api'; 

const cargaData = ref([]); 

onMounted(() => {
  fetchData(); 
});

const fetchData = async () => {
  try {
    const data = await getCarga(1, 10, "", null); 
    cargaData.value = data || []; 
  } catch (error) {
    console.error("Erro ao buscar as transações:", error);
  }
};
</script>

<style scoped>
.card {
  padding: 2rem;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
</style>


<template>
    <div class="card">
        <DataTable :value="products" removableSort tableStyle="min-width: 50rem">
            <Column field="code" header="Code" sortable style="width: 25%"></Column>
            <Column field="name" header="Name" sortable style="width: 25%"></Column>
            <Column field="category" header="Category" sortable style="width: 25%"></Column>
            <Column field="quantity" header="Quantity" sortable style="width: 25%"></Column>
        </DataTable>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { ProductService } from '@/service/ProductService';

onMounted(() => {
    ProductService.getProductsMini().then((data) => (products.value = data));
});

const products = ref();

</script>
