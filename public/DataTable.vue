<template>
  <h1>cmjkdkj</h1>
  <!-- <DataTable
    :value="posts"
    paginator
    :rows="10"
    :totalRecords="totalRecords"
    lazy
    :first="first"
    @page="onPage"
  >
    <Column field="id" header="Id" style="min-width: 10rem" sortable />
  </DataTable> -->
  <div id="pdf-content">
      <div class="detalhesLogo">
        <div class="detalhesName">
          <span>
            Detalhes da transação:
          </span>
          <span>
            01
          </span>
        </div>
        <div class="logoCornelderRelatorio">
          <img src="/public/logo.png" alt="">
        </div>
      </div>
      <div class="lineDiv"></div>
      <div class="columTable">
        <div class="lineRow">
          <span>Id</span>
          <span>1</span>
        </div>
         <div class="lineRow">
          <span>gate</span>
          <span>4</span>
        </div>
        <div class="lineRow">
          <span>Nome</span>
          <span>Mateus Joao</span>
        </div>
         <div class="lineRow">
          <span>Criado em </span>
          <span>4</span>
        </div>
      </div>
      <div class="imagensRelatorio">
        <table>
          <thead>
            <th>
              imagem 1
            </th>
            <th>
              imagem 2
            </th>
            <th>
              imagem 3
            </th>
          </thead>
          <tr>
            <td>
            </td>
            <td></td>
            <td></td>
          </tr>
        </table>
      </div>
  </div>

    <button @click="generatePDF">Baixar PDF</button>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { jsPDF } from 'jspdf';
import html2canvas from 'html2canvas';

const posts = ref([]);
const totalRecords = ref(0);
const first = ref(0);


const fetchPosts = async (page = 0) => {
  try {
    const response = await axios.get(
      `https://jsonplaceholder.typicode.com/posts?_start=${page * 10}&_limit=10`
    );
    posts.value = response.data;
    totalRecords.value = 100; 
  } catch (error) {
    console.error('Error fetching posts:', error);
  }
};


const onPage = (event) => {
  first.value = event.first;
  fetchPosts(event.first / 10); 
};


onMounted(() => {
  fetchPosts(); 
});
const generatePDF = async () => {
  const element = document.getElementById("pdf-content");


  const canvas = await html2canvas(element, { scale: 3 }); 
  const imgData = canvas.toDataURL("image/jpeg", 1.0); 

  const pdfWidth = 210; 
  const pdfHeight = (canvas.height * pdfWidth) / canvas.width; 

  const doc = new jsPDF("p", "mm", "a4");
  doc.addImage(imgData, "JPEG", 10, 10, pdfWidth - 20, pdfHeight);
  doc.save("relatorio.pdf");
};
</script>

<style scoped>
#pdf-content {
    
  padding: 20px;
  border: 0px solid #ddd;
  background: white;
  font-size: 1.2rem;
}

#pdf-content .detalhesName span:first-child{
  font-weight: 700;
  
}
#pdf-content .lineRow{
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 20px;
  margin: 10px 0px;
}

#pdf-content .lineRow span:first-child{
  text-transform: uppercase;
  font-weight: 700;
}
#pdf-content .columTable .lineRow:nth-child(even){
  background: #f5f5f5;
}
#pdf-content table {
  width: 100%;
  border-collapse: collapse;
}
#pdf-content .imagensRelatorio table th{
  text-align: center;
}

#pdf-content th, td {
  padding: 10px;
  text-align: left;
  border: 1px solid #ddd;

}

#pdf-content td{
  height: 200px;
  position: relative;
  background-image: url("/public/cteste.jpg");
  background-position: center;
  background-size: cover;
  background-repeat: no-repeat;
}
#pdf-content td img{
  width: 100%;
}

#pdf-content button {
  padding: 10px 20px;
  background-color: #007bff;
  color: white;
  border: none;
  cursor: pointer;
}

#pdf-content .detalhesLogo{
  display: flex;
  justify-content: space-between;
}

#pdf-content .detalhesLogo .logoCornelderRelatorio img{
  width: 140px;
}
#pdf-content .lineDiv{
  border-bottom: 1px solid #ddd;
  padding: 15px 20px;
}
</style>
