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

const route = useRoute();
const userId = route.params.id;
let gateId = userId;
if(Number(gateId.indexOf("Out")) > -1){
  gateId = gateId.replace("Out", "")
  gateId = `Portão ${gateId} (Saida)`
}else{
  gateId = gateId.replace("In", "")
  gateId = `Portão ${gateId} (Entrada)`
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
const statuses = ref(['Pending', 'Completed', 'In Progress', 'Cancelled']); 
const loading = ref(true);


const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
  const date = new Date(dateString);
  return date.toLocaleDateString('pt-BR', options);
};


const dadosCarga = ref([])

const pageNumber = ref(100)
const dadosCargaGeral = async ()=>{
  try{
    const response = await fetch(`/data.json?page=${pageNumber}`)
    if(response.ok){
      dadosCarga.value = await response.json()
      data.value = dadosCarga.value.data
      loading.value = false;
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
};

onMounted(
  dadosCargaGeral()

);


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


