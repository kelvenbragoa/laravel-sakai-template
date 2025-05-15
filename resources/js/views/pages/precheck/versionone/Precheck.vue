<script setup>

import { useRouter } from 'vue-router'
import { permissionsAcess } from '../../../../utils/accesRoute'

if (permissionsAcess().adminAcesseSuperAdmin == false) {
  if (permissionsAcess().precheck == false) {
    useRouter().push("/dashboard")
  } 
}

</script>

<template>
  <!-- <div v-if="loading" class="loader-overlay">
    <div class="louderL">
      <ProgressSpinner />
    </div>
  </div> -->
  <div class="card">
    <div class="font-semibold text-xl mb-4">Precheck</div>
    
  </div>

  
</template>



<style scoped lang="scss">
:deep(.p-datatable-frozen-tbody) {
  font-weight: bold;
}

:deep(.p-datatable-scrollable .p-frozen-column) {
  font-weight: bold;
}

.cores {
  background-color: #1558b0;
  border: 1px solid #1558b0;
}

.cores:hover {
  background-color: #1558b0cf !important;
  border: 1px solid #1558b088 !important;
}

.camposAgrupadosFormulario {
  display: flex;
  justify-content: space-between;
}

.camposAgrupadosFormulario .formUserAdd {
  width: calc((100% / 2) - 5px);
}

.camposTextos,
.dropdownSexo {
  width: 100%;
  margin: 0px 0px;
}

.camposTextos:focus {
  border: #1558b0 1px solid !important;
}

.btnExports:last-child {
  color: #4271d4;
  background-color: #ffffff;
}

.labelDrop {
  margin: 15px 0px;
  display: block;
}

.btnPass {
  width: 100% !important;
  border: 0px !important;
  outline: 0px !important;
}

.p-inputtext {
  width: 100% !important;
}

.btnEstiliza:hover {
  color: #1558b0a4 !important;
  background: #1558b033 !important;
  transition: all 0.5s ease !important;
}

.btnEstilizaDel:hover {
  color: #ff0000a5 !important;
  background: #ff000032 !important;
  transition: all 0.5s ease !important;
}

.msgErrorField {
  margin-bottom: 10px;
}

.msgErrorField p {
  color: #ff0000a5 !important;
  font-weight: 700;
}

.searchText:focus {
  border: #1558b0 1px solid !important;
}

.btnPermission:hover {
  background: #00000015 !important;
  color: #555555 !important;
  transition: all 0.3s ease;
  border-radius: 5px;
}

.louderL {
  width: 100%;
  height: 100%;
  position: fixed;
  top: 0px;
  left: 0px;
  background-color: rgba(0, 0, 0, 0.192);
  z-index: 999999;
  display: flex;
  align-items: center;
  justify-content: center;
}

.camposAgrupadosFormulario {
  border: 1px solid black;
}

.camposTextos {
  width: 100% !important;
}

.formUserAdd {
  margin: 20px 0px;
}

.gatename {
  display: block;
  margin-bottom: 10px;
}

.sprate {
  height: 20px;
}

.formUserAdd textarea {
  max-width: 100%;
  min-width: 100%;
  min-height: 150px;
  max-height: 150px;
}
</style>
