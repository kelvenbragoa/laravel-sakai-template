<template>
  <div class="card">
    
    
    
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { FilterMatchMode } from '@primevue/core/api';
import { getCarga } from '@/api'; 
import { useRoute } from 'vue-router';
import { jsPDF } from "jspdf";
import "jspdf-autotable";
import logo from '../../../../assets/images/logo.png'
console.log(`logo: ${logo}`)
// import * as autoTable from 'jspdf-autotable'
// import * as jsPDF from 'jspdf' 

const route = useRoute();
const userId = route.params.id;
// console.log(userId)
let gateId = userId;
if(Number(gateId.indexOf("Out")) > -1){
  console.log("tem saida")
  gateId = gateId.replace("Out", "")
  gateId = `Portão ${gateId} (Saida)`
}else{
  console.log("tem entrada")
  gateId = gateId.replace("In", "")
  gateId = `Portão ${gateId} (Entrada)`
}
console.log(gateId)

const data = ref([]);
const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  cargo_type: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  document_number: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  driver_name: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  truck_license_plate_number: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  status: { value: null, matchMode: FilterMatchMode.EQUALS },
});
const statuses = ref(['Pending', 'Completed', 'In Progress', 'Cancelled']); 
const loading = ref(true);


const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
  const date = new Date(dateString);
  return date.toLocaleDateString('pt-BR', options);
};


// async () => {
//   //  axios
//   //           .get("http://20.87.9.35/api/v1/transacoes/lista")
//   //           .then((res) => {
//   //                   console.log(res.data)
//   //           })
//   //           .catch((error) => {
//   //                   console.log(error);
//   //           });
//   try {
//     const result = await getCarga(1, 10, "", null); 
//     console.log("Resposta da API:", result);
//     if (result && result.data && Array.isArray(result.data)) {
//       data.value = result.data; 
//       for(let key in data.value){
//         if(data.value[key].transaction_gate == gateId){
//           console.log("Encontrado")
//         }
        
//       }
//     } else {
//       console.log("Estrutura inesperada", result);
//       data.value = []; 
//     }
//   } catch (error) {
//     console.error("Erro ao buscar transações:", error);
//   } finally {
//     loading.value = false;
//   }
// }

const dadosCarga = ref([])

const pageNumber = ref(100)
const dadosCargaGeral = async ()=>{
  try{
    const response = await fetch(`/data.json?page=${pageNumber}`)
    if(response.ok){
      dadosCarga.value = await response.json()
      data.value = dadosCarga.value.data
      console.log(data.value)
      loading.value = false;
      console.log(loading.value)
    }
  }catch(error){
    console.error(`Erro ao carregar o json: ${error}`)
  }finally {
    loading.value = false;
  }
}


const getSeverity = (status) => {
  switch (status) {
    case 'Pending':
      return 'warn';
    case 'Completed':
      return 'success';
    case 'In Progress':
      return 'info';
    case 'Cancelled':
      return 'danger';
    default:
      return null;
  }
};

const generatePDF = (rowData) => {

  
  const doc = new jsPDF();
  const larguraPagina = doc.internal.pageSize.width;
  const alturaPagina = doc.internal.pageSize.height;

  
  doc.setFontSize(10);
  doc.text(`Detalhes da Carga: ${rowData.document_number}`, 20, 13);
  doc.addImage("https://www.cornelder.co.mz/" , 'JPEG', larguraPagina-60, 7, 40, 10);
      let y = 15;
      // Linha de separação
      doc.setLineWidth(0.1);
      doc.line(20, 20, 190, 20);
      y += 10;

      // Função para converter hexadecimal em RGB
      function hexToRgb(hex) {
        var bigint = parseInt(hex.replace("#", ""), 16);
        var r = (bigint >> 16) & 255;
        var g = (bigint >> 8) & 255;
        var b = bigint & 255;
        return [r, g, b];
      }

      // Definindo a cor de fundo com hexadecimal
      let color = hexToRgb("#f5f5f5"); // Hexadecimal convertido para RGB
      let color2 = hexToRgb("#ffffff"); // Hexadecimal convertido para RGB
      
      
      let corChange = false
      // /images/logo.png
      doc.setFontSize(9);
      
      
      for (let key in rowData) {
        if(rowData[key]!=null){
          doc.setFont("helvetica", "normal");
          doc.setTextColor(0, 0, 0); // Preto
          if(!corChange){
            doc.setFillColor(color2[0], color2[1], color2[2]);
            doc.rect(20, y, 80, 10, "F"); // Borda da célula
            
            doc.text(String(key).length>30?String(key).substring(0, 20)+"...":String(key), 25, y + 6);
            doc.setFillColor(color2[0], color2[1], color2[2]);
            doc.rect(100, y, (larguraPagina/2)-15, 10, "F"); // Borda da célula
            doc.text(String(rowData[key]).length>50?String(rowData[key]).substring(0, 20)+"...":String(rowData[key]), 105, y + 6);
            corChange=true
          }else{
            doc.setFillColor(color[0], color[1], color[2]);
            doc.rect(20, y, 80, 10, "F"); // Borda da célula
            doc.text(String(key).length>30?String(key).substring(0, 20)+"...":String(key), 25, y + 6);
            doc.setFillColor(color[0], color[1], color[2]);
            doc.rect(100, y, (larguraPagina/2)-15, 10, "F"); // Borda da célula
            doc.text(String(rowData[key]).length>50?String(rowData[key]).substring(0, 20)+"...":String(rowData[key]), 105, y + 6);
            corChange=false
          }
          
          y += 10;


        }
      }
      doc.setLineWidth(0.1);
      doc.line(20, y, 190, y);
      y+=40
      doc.addImage(rowData.trailer_1_internal_cargo_photo , 'JPEG', 20, y, 180, 160);

      const data = new Date()
      // const hoje = data.getDate()+"/"+(data.getMonth()+1)+"/"+data.getUTCFullYear()+" - "+data.getHours+"h:"+data.getMinutes+"min:"+data.getSeconds+"s"
      const hoje = data.getDate()+"/"+(data.getMonth()+1)+"/"+data.getUTCFullYear()

      doc.setLineWidth(0.1);
      doc.line(20, alturaPagina-20, 190, alturaPagina-20);

      doc.text("Processado por: CGate", 20, alturaPagina-10)

      doc.text(String(hoje), larguraPagina/2, alturaPagina-10)

      doc.text("1/1", larguraPagina-25  , alturaPagina-10)

      doc.save(`doc_${rowData.driver_name}_${rowData.document_number_overwrite}_detalhes.pdf`);
  // console.log(rowData)
};

onMounted(
  dadosCargaGeral()

);


// <DataTable
//       v-model:filters="filters"
//       :value="data"
//       paginator
//       :rows="10"
//       dataKey="id"
//       filterDisplay="row"
//       :loading="loading"
//       :globalFilterFields="['cargo_type', 'document_number', 'driver_name', 'truck_license_plate_number', 'status']"
//     >
    
      // <template #header>
        
      //   <div class="flex justify-between align-center">
      //     <h2>
      //       Gate selecionado: <strong>{{gateId}}</strong>
      //     </h2>
      //     <IconField>
      //       <InputIcon>
      //         <i class="pi pi-search" />
      //       </InputIcon>
      //       <InputText v-model="filters['global'].value" placeholder="Pesquise" />
      //     </IconField>
      //   </div>
      // </template>
      // <template #empty> Nenhum dado encontrado. </template>
      // <template #loading> Carregando os dados. Por favor, aguarde. </template>

      // <Column field="cargo_type" header="Tipo" style="min-width: 12rem">
      
      //   <template #body="{ data }">
      //     {{ data.cargo_type }}
      //   </template>
      //   <template #filter="{ filterModel, filterCallback }">
      //     <InputText v-model="filterModel.value" type="text" @input="filterCallback()" placeholder="Filtro por Tipo" />
      //   </template>
      // </Column>

      // <Column field="document_number" header="Número de documento" style="min-width: 12rem">
      //   <template #body="{ data }">
      //     {{ data.document_number }}
      //   </template>
      //   <template #filter="{ filterModel, filterCallback }">
      //     <InputText v-model="filterModel.value" type="text" @input="filterCallback()" placeholder="Filtro por documento" />
      //   </template>
      // </Column>

      // <Column field="driver_name" header="Condutor" style="min-width: 12rem">
      //   <template #body="{ data }">
      //     {{ data.driver_name }}
      //   </template>
      //   <template #filter="{ filterModel, filterCallback }">
      //     <InputText v-model="filterModel.value" type="text" @input="filterCallback()" placeholder="Filtro por condutor" />
      //   </template>
      // </Column>

      // <Column field="truck_license_plate_number" header="Placa de caminhão" style="min-width: 12rem">
      //   <template #body="{ data }">
      //     {{ data.truck_license_plate_number }}
      //   </template>
      //   <template #filter="{ filterModel, filterCallback }">
      //     <InputText v-model="filterModel.value" type="text" @input="filterCallback()" placeholder="Filtro por placa" />
      //   </template>
      // </Column>

      // <!-- <Column field="status" header="Status" style="min-width: 12rem">
      //   <template #body="{ data }">
      //     <Tag :value="data.status" :severity="getSeverity(data.status)" />
      //   </template>
      //   <template #filter="{ filterModel, filterCallback }">
      //     <Select v-model="filterModel.value" @change="filterCallback()" :options="statuses" placeholder="Select Status" style="min-width: 12rem" :showClear="true">
      //       <template #option="slotProps">
      //         <Tag :value="slotProps.option" :severity="getSeverity(slotProps.option)" />
      //       </template>
      //     </Select>
      //   </template>
      // </Column> -->

      // <Column header="Detalhes" style="min-width: 10rem">
      //   <template #body="{ data }">
      //     <Button class="btnEstiliza" label="PDF" icon="pi pi-file-pdf" @click="generatePDF(data)" style=" border: 0px" />
      //   </template>
      // </Column>

      // <Column field="created_at" header="Created At" style="min-width: 12rem">
      //   <template #body="{ data }">
      //     {{ formatDate(data.created_at) }}
      //   </template>
      // </Column>

      //  <Column field="gate" header="Gate" style="min-width: 12rem">
        
      // </Column>
//     </DataTable>


// <template>
//   <div>
//     <h1>Movimentos de Carga</h1>

//     <p-data-table
//       :value="data"
//       :loading="loading"
//       :paginator="true"
//       :rows="rowsPerPage"
//       :total-records="totalRecords"
//       :lazy="true"
//       :first="first"
//       @page="onPage"
//     >
//       <template #header>
//         <h2>Lista de Cargas</h2>
//       </template>
//       <template #loading>
//         <i class="pi pi-spinner pi-spin" style="font-size: 2em"></i>
//         Carregando dados...
//       </template>

//       <p-column field="id" header="ID" />
//       <p-column field="gate" header="Portão" />
//       <p-column field="movement_type" header="Tipo de Movimento" />
//       <p-column field="document_number" header="Número do Documento" />
//       <p-column field="cargo_type" header="Tipo de Carga" />
//       <p-column field="driver_name_overwrite" header="Motorista" />
//       <p-column field="truck_license_plate_number_overwrite" header="Placa do Caminhão" />
//       <p-column
//         header="Ações"
//         body-style="text-align: center"
//       >
//         <template #body="slotProps">
//           <button class="p-button p-button-info" @click="detalhes(slotProps.data)">
//             Ver Detalhes
//           </button>
//         </template>
//       </p-column>
//     </p-data-table>
//   </div>
// </template>

// <script setup>
// import { ref, onMounted } from "vue";

// const data = ref([]); // Dados carregados
// const totalRecords = ref(0); // Número total de registros
// const first = ref(0); // Primeira página
// const rowsPerPage = ref(100); // Registros por página
// const pageNumber = ref(1); // Página atual (controla a API)
// const loading = ref(true); // Indicador de carregamento

// // Função para carregar os dados da API
// const loadData = async (page) => {
//   loading.value = true;
//   try {
//     const response = await fetch(`/data.json?page=${page}`);
//     if (response.ok) {
//       const result = await response.json();
//       data.value = result.data; // Atualiza os dados da tabela
//       totalRecords.value = result.meta.total || result.meta.last_page * rowsPerPage.value; // Atualiza os registros totais
//       console.log(totalRecords.value)
//     }
//   } catch (error) {
//     console.error("Erro ao carregar os dados:", error);
//   } finally {
//     loading.value = false;
//   }
// };

// // Evento disparado ao mudar a página
// const onPage = (event) => {
//   first.value = event.first;
//   pageNumber.value = Math.floor(first.value / rowsPerPage.value) + 1;
//   loadData(pageNumber.value); // Carrega a próxima página
// };

// // Exibir detalhes do item
// const detalhes = (item) => {
//   alert(`Detalhes do item:\n${JSON.stringify(item, null, 2)}`);
// };

// // Carrega os dados iniciais
// onMounted(() => {
//   loadData(pageNumber.value);
// });
// </script>

// <style>
// .p-data-table {
//   margin-top: 20px;
// }
// </style>

// <template>
//   <div class="card">
//     <DataTable
//       v-model:filters="filters"
//       :value="data"
//       paginator
//       :rows="10"
//       dataKey="id"
//       filterDisplay="row"
//       :loading="loading"
//       :globalFilterFields="['cargo_type', 'document_number', 'driver_name', 'truck_license_plate_number', 'status']"
//     >
//       <template #header>
//         <div class="flex justify-between align-center">
//           <h2>
//             Gate selecionado: <strong>{{gateId}}</strong>
//           </h2>
//           <IconField>
//             <InputIcon>
//               <i class="pi pi-search" />
//             </InputIcon>
//             <InputText v-model="filters['global'].value" placeholder="Pesquisa" />
//           </IconField>
//         </div>
//       </template>
//       <template #empty> Nenhum dado encontrado. </template>
//       <template #loading> Carregando os dados. Por favor, aguarde. </template>
//       <!-- <Column field="appointment_nbr" header="Appointment Number" style="min-width: 12rem" /> -->
//        <Column field="driver_name" header="Driver Name" style="min-width: 12rem" />
//       <Column field="truck_license_plate_number" header="Truck License Plate" style="min-width: 12rem" />
//       <Column field="transaction_gate" header="Gate" style="min-width: 12rem"></Column>
//       <Column field="created_at" header="Created At" style="min-width: 12rem">
//         <template #body="{ data }">
//           {{ formatDate(data.created_at) }}
//         </template>
//       </Column>
//       <Column field="status" header="Status" style="min-width: 12rem">
//         <template #body="{ data }">
//           <Tag :value="data.status" :severity="getSeverity(data.status)" />
//         </template>
//         <template #filter="{ filterModel, filterCallback }">
//           <Select v-model="filterModel.value" @change="filterCallback()" :options="statuses" placeholder="Status" style="min-width: 12rem" :showClear="true">
//             <template #option="slotProps">
//               <Tag :value="slotProps.option" :severity="getSeverity(slotProps.option)" />
//             </template>
//           </Select>
//         </template>
//       </Column>
//       <Column header="Detalhes" style="min-width: 10rem">
//         <template #body="{ data }">
//           <Button class="btnEstiliza" label="PDF" icon="pi pi-file-pdf" @click="generatePDF(data)" style=" border: 0px" />
//         </template>
//       </Column>
      
//     </DataTable>
//   </div>
// </template>

// <script setup>
// import { ref, onMounted } from 'vue';
// import { FilterMatchMode } from '@primevue/core/api';
// import { getCarga, getTransactions } from '@/api'; 
// import { useRoute } from 'vue-router';
// import { jsPDF } from "jspdf";
// import json from "../../../../../../public/user.json"


// const totalRecords = ref(0);
// const rowsPerPage = ref(10);
// const pageNumber = ref(1);



// const users = ref([]);

// const tabelaDados = ref({
//   address: "Endereço",
//   age: "Idade",
//   email: "Email",
//   estado: "Estado",
//   gender: "Genero",
//   id: "Id",
//   monthlyFee: "Taxa mensal",
//   name:"Nome",
//   phone: "Telefone",
//   tier: "Nível",
//   time:"08:00"
// })

// const tabelaDados2 = ref({
//   appointment_nbr:"Nbr appointment",
//   appointment_number:"Número appointment",
//   comments: "Comentários",
//   container_number_1: "Contêiner número 1",
//   container_number_2: "Contêiner número 2",
//   container_number_3: "Contêiner número 3",
//   container_number_4: "Contêiner número 4",
//   container_photo_1: "Foto do contêiner 1",
//   container_photo_2: "Foto do contêiner 2",
//   container_photo_3: "Foto do contêiner 3",
//   container_photo_4: "Foto do contêiner 4",
//   container_seal_1: "Selo do recipiente 1",
//   container_seal_2: "Selo do recipiente 2",
//   container_seal_3: "Selo do recipiente 3",
//   container_seal_4: "Selo do recipiente 4",
//   container_seal_photo_1: "Foto do selo do contêiner 1",
//   container_seal_photo_2:"Foto do selo do contêiner 2",
//   container_seal_photo_3: "Foto do selo do contêiner 3",
//   container_seal_photo_4: "Foto do selo do contêiner 4",
//   created_at: "Criado em",
//   driver_license_number:"Número da carta de motorista",
//   driver_license_photo: "Foto da carta",
//   driver_name: "Condutor",
//   external_ref_nbr: "referência externa nbr",
//   id: "Id",
//   imdg: "Imdg",
//   logged_user: "Usuário",
//   movement_type: "Tipo de movimento",
//   status: "Estado",
//   trailer_1_license_plate_number: "Número de matrícula do trailer 1",
//   trailer_1_license_plate_photo: "Foto de matrícula do trailer 1",
//   trailer_2_license_plate_number: "Número de matrícula do trailer 2",
//   trailer_2_license_plate_photo: "Foto de matrícula do trailer 2",
//   transaction_gate: "Portão",
//   truck_license_plate_number: "Número da matrícula do caminhão",
//   truck_license_plate_photo: "Foto da matrícula do caminhão",
//   tv_key: "Tv key",
//   type: "Tipot",
//   updated_at: "Atualizado em"
// })

//   const loadJson = async () => {
//   try {
//     const response = await fetch('/user.json'); 
//     users.value = await response.json();
//    // console.log(users.value)
//     // for(let userL in users.value.users){
//     //   console.log(users.value.users[userL])
//     // }
//   } catch (error) {
//     console.error('Erro ao carregar o JSON:', error);
//   }
// };

// onMounted(() => {
//   loadJson();
// });



// const route = useRoute();
// const userId = route.params.id;
// // console.log(userId)
// let gateId = userId;
// if(Number(gateId.indexOf("Out")) > -1){
//     gateId = gateId.replace("Out", "")
//     gateId = `Gate ${gateId}`
// }else{
//     gateId = gateId.replace("In", "")
//     gateId = `Gate ${gateId}`
// }
  
// const data = ref([]);
// const filters = ref({
//   global: { value: null, matchMode: FilterMatchMode.CONTAINS },
//   cargo_type: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
//   document_number: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
//   driver_name: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
//   truck_license_plate_number: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
//   status: { value: null, matchMode: FilterMatchMode.EQUALS },
// });
// const statuses = ref(['Pending', 'Done', 'Started', 'Cancelled']); 
// const loading = ref(true);


// const formatDate = (dateString) => {
//   const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
//   const date = new Date(dateString);
//   return date.toLocaleDateString('pt-BR', options);
// };

// const generatePDF = (rowData) => {

  
//   const doc = new jsPDF();
//   const larguraPagina = doc.internal.pageSize.width;
//   const alturaPagina = doc.internal.pageSize.height;

  
//   doc.setFontSize(10);
//   doc.text(`Detalhes da transação: ${rowData.id}`, 20, 13);
//   doc.addImage("/logo.png" , 'JPEG', larguraPagina-60, 7, 40, 10);
//       let y = 15;
//       // Linha de separação
//       doc.setLineWidth(0.1);
//       doc.line(20, 20, 190, 20);
//       y += 10;

//       // Função para converter hexadecimal em RGB
//       function hexToRgb(hex) {
//         var bigint = parseInt(hex.replace("#", ""), 16);
//         var r = (bigint >> 16) & 255;
//         var g = (bigint >> 8) & 255;
//         var b = bigint & 255;
//         return [r, g, b];
//       }

//       // Definindo a cor de fundo com hexadecimal
//       let color = hexToRgb("#f5f5f5"); // Hexadecimal convertido para RGB
//       let color2 = hexToRgb("#ffffff"); // Hexadecimal convertido para RGB
      
      
//       let corChange = false
//       // /images/logo.png
//       doc.setFontSize(9);
      
      
//       for (let key in rowData) {
//         if(rowData[key]!=null){
//           doc.setFont("helvetica", "normal");
//           doc.setTextColor(0, 0, 0); // Preto
//           if(!corChange){
//             doc.setFillColor(color2[0], color2[1], color2[2]);
//             doc.rect(20, y, 80, 10, "F"); // Borda da célula
            
//             doc.text(String(key).length>30?String(tabelaDados2.value[key]).substring(0, 20)+"...":String(tabelaDados2.value[key]), 25, y + 6);
//             doc.setFillColor(color2[0], color2[1], color2[2]);
//             doc.rect(100, y, (larguraPagina/2)-15, 10, "F"); // Borda da célula
//             doc.text(String(rowData[key]).length>50?String(rowData[key]).substring(0, 20)+"...":String(rowData[key]), 105, y + 6);
//             corChange=true
//           }else{
//             doc.setFillColor(color[0], color[1], color[2]);
//             doc.rect(20, y, 80, 10, "F"); // Borda da célula
//             doc.text(String(key).length>30?String(tabelaDados2.value[key]).substring(0, 20)+"...":String(tabelaDados2.value[key]), 25, y + 6);
//             doc.setFillColor(color[0], color[1], color[2]);
//             doc.rect(100, y, (larguraPagina/2)-15, 10, "F"); // Borda da célula
//             doc.text(String(rowData[key]).length>50?String(rowData[key]).substring(0, 20)+"...":String(rowData[key]), 105, y + 6);
//             corChange=false
//           }
          
//           y += 10;


//         }
//       }
//       doc.setLineWidth(0.1);
//       doc.line(20, y, 190, y);
//       y+=10
//       doc.text("Imagens", 20, y)
//       y+=10
//       const squareSize = 50; // Tamanho de cada quadrado (50x50)
//       const startX = 20; // Posição X do primeiro quadrado
//       const startY = y; // Posição Y de todos os quadrados
//       const spacing = 10; // Espaçamento entre os quadrados

//       // Desenhando os quadrados
//       for (let i = 0; i < 3; i++) {
//         const x = startX + i * (squareSize + spacing); // Calculando a posição X de cada quadrado
//         doc.rect(x, startY, squareSize, squareSize); // Desenhando o quadrado
//       }
//       // doc.addImage(rowData.trailer_1_internal_cargo_photo , 'JPEG', 20, y, 180, 160);

//       const data = new Date()
//       // const hoje = data.getDate()+"/"+(data.getMonth()+1)+"/"+data.getUTCFullYear()+" - "+data.getHours+"h:"+data.getMinutes+"min:"+data.getSeconds+"s"
//       const hoje = data.getDate()+"/"+(data.getMonth()+1)+"/"+data.getUTCFullYear()

//       doc.setLineWidth(0.1);
//       doc.line(20, alturaPagina-20, 190, alturaPagina-20);

//       doc.text("Processado por: CGate", 20, alturaPagina-10)

//       doc.text(String(hoje), larguraPagina/2, alturaPagina-10)

//       doc.text("1/1", larguraPagina-25  , alturaPagina-10)

//       doc.save(`doc_${rowData.id}_${rowData.driver_name}_detalhes.pdf`);
//   // console.log(rowData)
// };


// onMounted(async () => {
//   try {
//     const result = await getTransactions(1, 10, "", null); 
//     console.log("Resposta da API:", result);
//     if (result && result.data && Array.isArray(result.data)) {
//       data.value = result.data; 
//       // console.log(data.value)
//       for(let key in data.value){
//         if(data.value[key].transaction_gate == gateId){
//           console.log("Encontrado")
//         }
        
//       }
      
//     } else {
//       console.log("Estrutura inesperada", result);
//       data.value = []; 
//     }
//   } catch (error) {
//     console.error("Erro ao buscar transações:", error);
//   } finally {
//     loading.value = false;
//   }
// });


// const getSeverity = (status) => {
//   switch (status) {
//     case 'Pending':
//       return 'warn';
//     case 'Completed':
//       return 'success';
//     case 'In Progress':
//       return 'info';
//     case 'Cancelled':
//       return 'danger';
//     default:
//       return null;
//   }
// };
// </script>

// <style scoped>

// table {
//   width: 100%;
//   border-collapse: collapse;
//   margin-top: 20px;
// }

// th, td {
//   padding: 8px;
//   text-align: left;
// }

// th {
//   background-color: #f2f2f2;
// }

// td {
//   border-bottom: 1px solid #ddd;
// }


// p {
//   font-size: 18px;
//   color: #888;
// }

// .btnEstiliza{
//   background-color: #5498f1;
// }

// .btnEstiliza:hover{
//   background-color: #046df7!important;
// }
// </style>


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

.btnEstiliza{
  background-color: #5498f1;
}

.btnEstiliza:hover{
  background-color: #046df7!important;
}
</style>


