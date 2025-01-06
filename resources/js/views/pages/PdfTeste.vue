<template>
  <div>
    <h1>Gerar PDF com jsPDF</h1>
    <button @click="generatePDF" class="btn-generate">Gerar PDF</button>
  </div>
</template>

<script>
import { jsPDF } from "jspdf";

export default {
  methods: {
    generatePDF() {
      const doc = new jsPDF();

      // Definindo as fontes
      doc.setFont("helvetica", "normal");
      
      // Título
      doc.setFontSize(18);
      doc.text("Relatório de Movimento de Carga", 20, 20);

      // Subtítulo
      doc.setFontSize(12);
      doc.text("Informações do Movimento:", 20, 30);

      // Dados
      const data = {
        cargo_type: "Crómio (Pó)",
        document_number: "RI-24-071511",
        driver_name: "Darlington Fenyere",
        gate: "Portão 3 (Entrada)",
        movement_type: "Camião Cheio",
        status: "Done",
        truck_license_plate_number: "ADS8765",
        trailer_1_license_plate_number: "ACZ9941",
        user_name: "tcg.security3@cornelder.co.mz"
      };

      let y = 40;
      for (let key in data) {
        // Título da chave
        doc.setFontSize(12);
        doc.text(`${key.replace("_", " ")}:`, 20, y);
        
        // Valor da chave
        doc.setFontSize(10);
        doc.text(data[key], 80, y);
        
        y += 10;
      }

      // Linha de separação
      doc.setLineWidth(0.5);
      doc.line(20, y, 190, y);
      y += 10;

      // Tabela estilizada manualmente
      doc.setFontSize(12);
      doc.text("Detalhes do Carga e Veículos:", 20, y);
      y += 10;

      // Cabeçalho da tabela
      doc.setFont("helvetica", "bold");
      doc.setTextColor(255, 255, 255); // Branco
      doc.setFillColor(41, 128, 185); // Azul
      doc.rect(20, y, 60, 10, "F"); // Fundo azul para o cabeçalho
      doc.text("Veículo", 25, y + 6);
      doc.rect(80, y, 60, 10, "F"); // Fundo azul para o cabeçalho
      doc.text("Placa", 85, y + 6);
      doc.rect(140, y, 60, 10, "F"); // Fundo azul para o cabeçalho
      doc.text("Tipo", 145, y + 6);
      y += 10;

      // Dados da tabela
      doc.setFont("helvetica", "normal");
      doc.setTextColor(0, 0, 0); // Preto
      doc.rect(20, y, 60, 10); // Borda da célula
      doc.text("Camião", 25, y + 6);
      doc.rect(80, y, 60, 10); // Borda da célula
      doc.text(data.truck_license_plate_number, 85, y + 6);
      doc.rect(140, y, 60, 10); // Borda da célula
      doc.text("Cheio", 145, y + 6);
      y += 10;

      doc.rect(20, y, 60, 10); // Borda da célula
      doc.text("Reboque 1", 25, y + 6);
      doc.rect(80, y, 60, 10); // Borda da célula
      doc.text(data.trailer_1_license_plate_number, 85, y + 6);
      doc.rect(140, y, 60, 10); // Borda da célula
      doc.text("Carregado", 145, y + 6);

      // Salvar o PDF
      doc.save("relatorio-movimento.pdf");
    }
  }
};
</script>

<style scoped>
h1 {
  text-align: center;
  color: #2c3e50;
}

button.btn-generate {
  background-color: #3498db;
  color: white;
  border: none;
  padding: 10px 20px;
  font-size: 16px;
  cursor: pointer;
  border-radius: 5px;
  transition: background-color 0.3s;
}

button.btn-generate:hover {
  background-color: #2980b9;
}
</style>
