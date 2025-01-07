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
            Gate selecionado: <strong>{{gateId}}</strong>
          </h2>
          <IconField>
            <InputIcon>
              <i class="pi pi-search" />
            </InputIcon>
            <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
          </IconField>
        </div>
      </template>
      <template #empty> Nenhum dado encontrado. </template>
      <template #loading> Carregando os dados. Por favor, aguarde. </template>
      <!-- <Column field="appointment_nbr" header="Appointment Number" style="min-width: 12rem" /> -->
       <Column field="driver_name" header="Driver Name" style="min-width: 12rem" />
      <Column field="truck_license_plate_number" header="Truck License Plate" style="min-width: 12rem" />
      <Column field="transaction_gate" header="Gate" style="min-width: 12rem"></Column>
      <Column field="created_at" header="Created At" style="min-width: 12rem">
        <template #body="{ data }">
          {{ formatDate(data.created_at) }}
        </template>
      </Column>
      <Column field="status" header="Status" style="min-width: 12rem">
        <template #body="{ data }">
          <Tag :value="data.status" :severity="getSeverity(data.status)" />
        </template>
        <template #filter="{ filterModel, filterCallback }">
          <Select v-model="filterModel.value" @change="filterCallback()" :options="statuses" placeholder="Status" style="min-width: 12rem" :showClear="true">
            <template #option="slotProps">
              <Tag :value="slotProps.option" :severity="getSeverity(slotProps.option)" />
            </template>
          </Select>
        </template>
      </Column>
      <Column header="Detalhes" style="min-width: 10rem">
        <template #body="{ data }">
          <Button class="btnEstiliza" label="PDF" icon="pi pi-file-pdf" @click="generatePDF(data)" style=" border: 0px" />
        </template>
      </Column>
      
    </DataTable>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { FilterMatchMode } from '@primevue/core/api';
import { getCarga, getTransactions } from '@/api'; 
import { useRoute } from 'vue-router';
import { jsPDF } from "jspdf";


const route = useRoute();
const userId = route.params.id;
// console.log(userId)
let gateId = userId;
if(Number(gateId.indexOf("Out")) > -1){
    gateId = gateId.replace("Out", "")
    gateId = `Gate ${gateId}`
}else{
    gateId = gateId.replace("In", "")
    gateId = `Gate ${gateId}`
}
  
const data = ref([]);
const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  cargo_type: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  document_number: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  driver_name: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  truck_license_plate_number: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  status: { value: null, matchMode: FilterMatchMode.EQUALS },
});
const statuses = ref(['Pending', 'Done', 'Started', 'Cancelled']); 
const loading = ref(true);


const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
  const date = new Date(dateString);
  return date.toLocaleDateString('pt-BR', options);
};

const generatePDF = (rowData) => {

  
  const doc = new jsPDF();
  const larguraPagina = doc.internal.pageSize.width;
  const alturaPagina = doc.internal.pageSize.height;

  
  doc.setFontSize(10);
  doc.text(`Detalhes da transação: ${rowData.id}`, 20, 13);
  doc.addImage("/images/logo.png" , 'JPEG', larguraPagina-60, 7, 40, 10);
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
      // doc.addImage(rowData.trailer_1_internal_cargo_photo , 'JPEG', 20, y, 180, 160);

      const data = new Date()
      // const hoje = data.getDate()+"/"+(data.getMonth()+1)+"/"+data.getUTCFullYear()+" - "+data.getHours+"h:"+data.getMinutes+"min:"+data.getSeconds+"s"
      const hoje = data.getDate()+"/"+(data.getMonth()+1)+"/"+data.getUTCFullYear()

      doc.setLineWidth(0.1);
      doc.line(20, alturaPagina-20, 190, alturaPagina-20);

      doc.text("Processado por: CGate", 20, alturaPagina-10)

      doc.text(String(hoje), larguraPagina/2, alturaPagina-10)

      doc.text("1/1", larguraPagina-25  , alturaPagina-10)

      doc.save(`doc_${rowData.id}_${rowData.driver_name}_detalhes.pdf`);
  // console.log(rowData)
};


onMounted(async () => {
  try {
    const result = await getTransactions(1, 10, "", null); 
    console.log("Resposta da API:", result);
    if (result && result.data && Array.isArray(result.data)) {
      data.value = result.data; 
      // console.log(data.value)
      for(let key in data.value){
        if(data.value[key].transaction_gate == gateId){
          console.log("Encontrado")
        }
        
      }
      
    } else {
      console.log("Estrutura inesperada", result);
      data.value = []; 
    }
  } catch (error) {
    console.error("Erro ao buscar transações:", error);
  } finally {
    loading.value = false;
  }
});


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
