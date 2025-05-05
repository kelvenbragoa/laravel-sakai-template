<script setup>
import { ref, onMounted } from "vue";
import { jsPDF } from "jspdf";
import { useRoute, useRouter } from "vue-router";
import * as XLSX from "xlsx";
import { permissionsAcess } from "../../../../utils/accesRoute";

if (permissionsAcess().adminAcesseSuperAdmin == false) {
  if (permissionsAcess().cgate2dotxfound == false) {
    useRouter().push("/dashboard")
  }
}

const data = ref([]);
const totalRecords = ref(0);
const first = ref(0);
const rowsPerPage = ref(10);
const pageNumber = ref(1);
const loading = ref(false);
// const filters = ref({ global: { value: null } }); // Inicialize os filtros
const filters = ref("");

//  [
//     "Carga",
//     "Criado",
//     "Número do documento"
//   ]

const tabelaDados = ref({
  cargo_type: "Carga",
  created_at: "Criado",
  document_number: "Número do documento",
  document_number_cutout_photo: "Número do documento de recorte foto",
  document_number_overwrite: "Número do documento(sobrescrever)",
  driver_license_number: "Número da licença do motorista",
  driver_license_number_cutout_photo:
    "Foto de recorte de número de carteira de motorista",
  driver_license_number_overwrite:
    "Número da carteira de motorista sobrescrito",
  driver_name: "Condutor",
  driver_name_overwrite: "Nome do condutor subrescrito",
  gate: "Portão",
  id: "Id",
  movement_type: "Tipo de movimento",
  status: "Status",
  trailer_1_internal_cargo_photo: "Foto de carga interna do reboque 1",
  trailer_1_license_plate_number: "Número da placa do trailer 1",
  trailer_1_license_plate_number_cutout_photo:
    "Foto do recorte do número da placa do trailer 1",
  trailer_1_license_plate_number_overwrite:
    "Número da placa do trailer 1 sobrescrito",
  trailer_2_internal_cargo_photo: "Foto de carga interna do trailer 2",
  trailer_2_license_plate_number: "Número da placa do trailer 2",
  trailer_2_license_plate_number_cutout_photo:
    "Foto do recorte do número da placa do trailer 2",
  trailer_2_license_plate_number_overwrite: "Número da placa do trailer 2",
  trailers_quantity: "Quantidade de trailer",
  truck_license_plate_number: "Número da placa do caminhão",
  truck_license_plate_number_cutout_photo:
    "Foto de recorte de placa de caminhão",
  truck_license_plate_number_overwrite:
    "Número de placa de caminhão sobrescrito",
  updated_at: "Atualizado em",
  user_name: "Nome do usuário",
});


const route = useRoute();
const userId = route.params.id;
let gateId = userId;
if (Number(gateId.indexOf("Out")) > -1) {
  gateId = gateId.replace("Out", "");
  gateId = `Portão ${gateId} (Saida)`;
} else {
  gateId = gateId.replace("In", "");
  gateId = `Portão ${gateId} (Entrada)`;
}

// Função para carregar os dados
const dataFilter = ref();
const loadData = async (page) => {
  loading.value = true;
  try {
    const response = await fetch(`/data.json?page=${page}`);
    if (response.ok) {
      const result = await response.json();
      dataFilter.value = result.data;
      if (filters.value.trim() === "") {
        data.value = dataFilter.value;
      } else {
        data.value = dataFilter.value.filter(
          (dados) =>
            dados.status.toLowerCase().includes(filters.value.toLowerCase()) ||
            dados.user_name
              .toLowerCase()
              .includes(filters.value.toLowerCase()) ||
            dados.document_number_overwrite
              .toLowerCase()
              .includes(filters.value.toLowerCase())
        );
      }

      totalRecords.value =
        result.meta.total || result.meta.last_page * rowsPerPage.value;
    }
  } catch (error) {
    console.error("Erro ao carregar os dados:", error);
  } finally {
    loading.value = false;
  }
};

// Evento ao mudar a página
const onPage = (event) => {
  first.value = event.first;
  pageNumber.value = Math.floor(first.value / rowsPerPage.value) + 1;
  loadData(pageNumber.value);
};

const exportToExcel = () => {
  const worksheet = XLSX.utils.json_to_sheet(data.value);
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, "Transações");
  XLSX.writeFile(workbook, "transacoes.xlsx");
};

const generatePDF = (rowData) => {
  const doc = new jsPDF();
  const larguraPagina = doc.internal.pageSize.width;
  const alturaPagina = doc.internal.pageSize.height;

  doc.setFontSize(10);
  doc.text(`Detalhes da Carga: ${rowData.document_number}`, 20, 13);
  doc.addImage(
    "/images/pB4j1n0xhumeKjfagw3sZqOjHLqusQcRFo4k4mD4.jpg",
    "JPEG",
    larguraPagina - 60,
    7,
    40,
    10
  );
  //  doc.addImage("/logo.png" , 'JPEG', larguraPagina-60, 7, 40, 10);
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

  let corChange = false;
  // /images/logo.png
  doc.setFontSize(9);

  for (let key in rowData) {
    if (rowData[key] != null) {
      doc.setFont("helvetica", "normal");
      doc.setTextColor(0, 0, 0); // Preto
      if (!corChange) {
        doc.setFillColor(color2[0], color2[1], color2[2]);
        doc.rect(20, y, 80, 10, "F"); // Borda da célula

        doc.text(
          String(key).length > 30
            ? String(tabelaDados.value[key]).substring(0, 20) + "..."
            : String(tabelaDados.value[key]),
          25,
          y + 6
        );
        doc.setFillColor(color2[0], color2[1], color2[2]);
        doc.rect(100, y, larguraPagina / 2 - 15, 10, "F"); // Borda da célula
        doc.text(
          String(rowData[key]).length > 50
            ? String(rowData[key]).substring(0, 20) + "..."
            : String(rowData[key]),
          105,
          y + 6
        );
        corChange = true;
      } else {
        doc.setFillColor(color[0], color[1], color[2]);
        doc.rect(20, y, 80, 10, "F"); // Borda da célula
        doc.text(
          String(key).length > 30
            ? String(tabelaDados.value[key]).substring(0, 20) + "..."
            : String(tabelaDados.value[key]),
          25,
          y + 6
        );
        doc.setFillColor(color[0], color[1], color[2]);
        doc.rect(100, y, larguraPagina / 2 - 15, 10, "F"); // Borda da célula
        doc.text(
          String(rowData[key]).length > 50
            ? String(rowData[key]).substring(0, 20) + "..."
            : String(rowData[key]),
          105,
          y + 6
        );
        corChange = false;
      }

      y += 10;
    }
  }
  doc.setLineWidth(0.1);
  doc.line(20, y, 190, y);
  y += 40;
  doc.addImage(rowData.trailer_1_internal_cargo_photo, "JPEG", 20, y, 180, 160);

  const data = new Date();
  // const hoje = data.getDate()+"/"+(data.getMonth()+1)+"/"+data.getUTCFullYear()+" - "+data.getHours+"h:"+data.getMinutes+"min:"+data.getSeconds+"s"
  const hoje =
    data.getDate() + "/" + (data.getMonth() + 1) + "/" + data.getUTCFullYear();

  doc.setLineWidth(0.1);
  doc.line(20, alturaPagina - 20, 190, alturaPagina - 20);

  doc.text("Processado por: CGate", 20, alturaPagina - 10);

  doc.text(String(hoje), larguraPagina / 2, alturaPagina - 10);

  doc.text("1/1", larguraPagina - 25, alturaPagina - 10);

  doc.save(
    `doc_${rowData.driver_name}_${rowData.document_number_overwrite}_detalhes.pdf`
  );
};

// Carrega os dados inicialmente
onMounted(() => {
  loadData(pageNumber.value);
});
</script>
<template>
  <DataTable
    :value="data"
    paginator
    :rows="rowsPerPage"
    :first="first"
    :loading="loading"
    :totalRecords="totalRecords"
    dataKey="id"
    filterDisplay="row"
    @page="onPage"
  >
    <template #header>
      <div class="flex justify-between align-center py-5">
        <h2>
          Gate selecionado: <strong>{{ gateId }}</strong>
        </h2>
        <div class="groupExel">
          <Button
            @click="exportToExcel"
            label="Excel"
            icon="pi pi-file-excel"
          />
          <div class="calendaryFilter">
            <DatePicker
              v-model="startDate"
              fluid
              iconDisplay="input"
              showTime
              hourFormat="24"
              placeholder="Data de Início"
            />
            <span></span>
            <DatePicker
              v-model="endDate"
              fluid
              iconDisplay="input"
              class="dtPicker"
              showTime
              hourFormat="24"
              placeholder="Data de Fim"
            />
            <span></span>
            <Button class="dtFilter" icon="pi pi-filter" @click="filterDate" />
          </div>
          <IconField>
            <InputIcon>
              <i class="pi pi-search" />
            </InputIcon>
            <InputText
              v-model="filters"
              @input="loadData(pageNumber)"
              placeholder="Pesquise"
            />
          </IconField>
        </div>
      </div>
    </template>
    <template #empty> Nenhum dado encontrado. </template>
    <template #loading> Carregando os dados. Por favor, aguarde. </template>
    <Column field="cargo_type" header="Tipo" />
    <Column field="document_number" header="Número do documento" />
    <Column field="driver_name" header="Condutor" />
    <Column field="truck_license_plate_number" header="Placa de caminhão" />
    <Column header="Detalhes">
      <template #body="{ data }">
        <Button
          class="btnEstiliza"
          label="PDF"
          icon="pi pi-file-pdf"
          @click="generatePDF(data)"
        />
      </template>
    </Column>
  </DataTable>
</template>

<style scoped>
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

th,
td {
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

.btnEstiliza {
  background-color: #5498f1;
}

.btnEstiliza:hover {
  background-color: #046df7 !important;
}
</style>
