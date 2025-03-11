<template>
  <div>
    <h2>Lista de Posts (Paginação)</h2>

    <DataTable :value="posts" paginator :rows="rowsPerPage" :totalRecords="totalRecords" lazy :first="first"
      @page="onPageChange">
      <Column field="email" header="Email" style="min-width: 10rem" sortable>
      </Column>
    </DataTable>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const getToken = () => {
  return localStorage.getItem("access_token");
};
const posts = ref([])
const totalRecords = ref(0)
const first = ref(0)
const rowsPerPage = 10


const fetchPosts = async (page = 1) => {
  const token = getToken();
  console.log(`Token: ${page}`);
  if (!token) {
    alert("Token de autenticação não encontrado. Por favor, faça login.");
    return;
  }
  try {
    const response = await axios.get(`http://10.0.8.44:8010/api/users`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
      params: {
        page: page,

      }
    })

    posts.value = response.data.data.data

    totalRecords.value = response.data.data.total
  } catch (error) {
    console.error('Erro ao buscar posts:', error)
  }
}


const onPageChange = (event) => {
  first.value = event.first
  const newPage = Math.floor(event.first / rowsPerPage) + 1
  fetchPosts(newPage)
}


onMounted(() => {
  fetchPosts()
})
</script>

<style>
h2 {
  margin-bottom: 10px;
}
</style>