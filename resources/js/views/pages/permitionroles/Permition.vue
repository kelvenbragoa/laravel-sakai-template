
<script setup>
import { ref, onMounted } from "vue";
import { DataTable, Column, Button, Dialog, MultiSelect } from "primevue";

const roles = ref([]); 

const usersL = ref([])

const permissions = ref([]); 
const selectedRole = ref(null);
const selectedPermissions = ref([]);
const showPermissionDialog = ref(false);

const showAddDialog = ref(false);
const newRoleData = ref({ name: "" }); 


const fetchRoles = async () => {
  try {
    const response = await fetch("/api/roles");
    const data = await response.json();
    roles.value = data.data.data
  } catch (error) {
    console.error("Erro ao buscar roles:", error);
  }
};


const fetchPermissions = async () => {
  try {
    const response = await fetch("/api/permissions");
    const data = await response.json();
    permissions.value = data.data.data
  } catch (error) {
    console.error("Erro ao buscar permissions:", error);
  }
};

const fetchUsers = async () => {
  try {
    const response = await fetch("/api/users");
    const data = await response.json();
    usersL.value = data.data.data
  } catch (error) {
    console.error("Erro ao buscar permissions:", error);
  }
};



const showEditDialog = ref(false); // Controle de visibilidade do diálogo de edição
const editedRole = ref({}); // Dados do role sendo editado

// Abrir diálogo de edição com dados do role
const showEditRoleDialog = (role) => {
  editedRole.value = { ...role }; // Clonar os dados do role
  showEditDialog.value = true;
};

const managePermissions = async (role) => {
  selectedRole.value = role;
  try {
    const response = await fetch(`/api/roles/${role.id}/rolepermission`);
    const data = await response.json();

    selectedPermissions.value = Array.isArray(data) ? data : []; 
    showPermissionDialog.value = true;
  } catch (error) {
    console.error("Erro ao buscar permissões do role:", error);
  }
};


const savePermissions = async () => {
  try {
    await fetch(`/api/roles/${selectedRole.value.id}/rolepermission`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ permissions: selectedPermissions.value }),
    });
    showPermissionDialog.value = false;
    fetchRoles();
  } catch (error) {
    console.error("Erro ao salvar permissões:", error);
  }
};


const removeRole = async (roleId) => {
  try {
    await fetch(`/api/roles/${roleId}`, { method: "DELETE" });
    fetchRoles();
  } catch (error) {
    console.error("Erro ao remover role:", error);
  }
};

onMounted(() => {
  fetchRoles();
  fetchPermissions();
  fetchUsers();
});



// Salvar alterações do role
const saveEditedRole = async () => {
  try {
    await fetch(`/api/roles/${editedRole.value.id}`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(editedRole.value),
    });
    showEditDialog.value = false;
    fetchRoles(); // Recarregar a lista de roles
  } catch (error) {
    console.error("Erro ao salvar alterações do role:", error);
  }
};

// Função para adicionar uma nova role
const addRole = async () => {
  try {
    const response = await fetch("/api/roles", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(newRoleData.value),
    });

    

    if (response.ok) {
      const result = response.formData
      showAddDialog.value = false; 
      fetchRoles(); 
      newRoleData.value = { name: "" }; // Reseta os dados
    } else {
      console.error("Erro ao adicionar nova role.");
    }
  } catch (error) {
    console.error("Erro ao adicionar role:", error);
  }
};


</script>



<template>
  <div>

    <!-- Tabela de Roles -->
    <DataTable :value="permissions" :paginator="true" :rows="10">
      <template #header>
          <div class="flex justify-between">
              <IconField class="searchText">
                  <InputIcon>
                      <i class="pi pi-search" />
                  </InputIcon>
                  <InputText v-model="value" placeholder="Pesquisar" />
              </IconField>
              <div class="btnsL">
                  <Button label="Novo" icon="pi pi-plus" class="cores" @click="dialogoUserVisble = true"/>
              </div>
              
          </div>
        </template>
      <div>
        
        <Column field="id" header="Id: "/>
        <Column field="created_at" header="Criado em:" />

        <Column field="name" header="Permisões: "/>

        <Column header="Ações" style="display: flex; gap: 10px">
          <template #body="slotProps">
            <Button
            
              label=""
              icon="pi pi-pencil"
              class="p-button-warning btnEstiliza"
              style=" border: 0px; background-color: transparent; color: #1558b0"
              @click="showEditRoleDialog(slotProps.data)"
            />
            <!-- <Button
              label=""
              icon="pi pi-key"
              class="p-button-success"
              style="padding: 5px 0px;background-color: transparent; color: #000000; border: 0px"
              @click="managePermissions(slotProps.data)"
            /> -->
            <Button
              label=""
              icon="pi pi-trash"
              class="p-button-danger btnEstilizaDel"
              style="padding: 5px 0px;background-color: transparent; color: #ff0000; border: 0px"
              @click="removeRole(slotProps.data.id)"
            />
          </template>
        </Column>
      </div>
      
    </DataTable>

    <!-- <div class="roles">
      {{roles}}
    </div> -->
    <!-- <h1>Gestão de Roles e Permissions</h1> -->

    <!-- Botão para adicionar nova Role
    <Button
      label="Add"
      icon="pi pi-plus"
      class="p-button-success mb-3"
      @click="showAddDialog = true"
    /> -->

    <div style="height: 40px;"></div>

    <DataTable :value="roles" responsiveLayout="scroll" :paginator="true" :rows="10">
       <template #header>
        <div class="flex justify-between">
            <IconField class="searchText">
                <InputIcon>
                    <i class="pi pi-search" />
                </InputIcon>
                <InputText v-model="value" placeholder="Pesquisar" />
            </IconField>
            <div class="btnsL">
                <Button label="Novo" icon="pi pi-plus" class="cores" @click="dialogoUserVisble = true"/>
            </div>
            
        </div>
      </template>

      <Column field="id" header="ID" />
      <Column field="name" header="Role" />
      <Column header="Ações" style="display: flex; gap: 10px">
        <template #body="slotProps">
          <Button
            
              label=""
              icon="pi pi-pencil"
              class="p-button-warning btnEstiliza"
              style=" border: 0px; background-color: transparent; color: #1558b0"
              @click="showEditRoleDialog(slotProps.data)"
            />
            <Button
              label=""
              icon="pi pi-key"
              class="p-button-success btnPermission"
              style="padding: 5px 0px;background-color: transparent; color: #000000; border: 0px"
              @click="managePermissions(slotProps.data)"
            />
            <Button
              label=""
              icon="pi pi-trash"
              class="p-button-danger btnEstilizaDel"
              style="padding: 5px 0px;background-color: transparent; color: #ff0000; border: 0px"
              @click="removeRole(slotProps.data.id)"
            />
        </template>
      </Column>
    </DataTable>

    
    

    <!-- Dialog para Gerenciar Permissões -->
    <Dialog v-model:visible="showPermissionDialog" header="Gerenciar Permissões" :modal="true" breakpoints="{'960px': '75vw', '640px': '100vw'}" style="width: 50vw">
      <template #header>
        <h3>Permissões para {{ selectedRole?.name }}</h3>
      </template>
      <MultiSelect
        v-model="selectedPermissions"
        :options="permissions"
        optionLabel="name"
        placeholder="Selecione Permissões"
        class="w-full"
      />
      <template #footer>
        <Button label="Salvar" icon="pi pi-check" @click="savePermissions" />
        <Button label="Cancelar" icon="pi pi-times" class="p-button-secondary" @click="showPermissionDialog = false" />
      </template>
    </Dialog>

    <Dialog
      v-model:visible="showEditDialog"
      header="Editar Role"
      :modal="true"
      breakpoints="{'960px': '75vw', '640px': '100vw'}"
      style="width: 50vw"
    >
      <template #header>
        <h3>Editar Role</h3>
      </template>
      <div class="p-fluid">
        <div class="field">
          <label for="roleName">Nome do Role</label>
          <input
            id="roleName"
            type="text"
            v-model="editedRole.name"
            placeholder="Insira o nome do role"
            class="p-inputtext w-full"
          />
        </div>
        <!-- Adicione outros campos que deseja editar -->
      </div>
      <template #footer>
        <Button label="Salvar" icon="pi pi-check" @click="saveEditedRole" />
        <Button label="Cancelar" icon="pi pi-times" class="p-button-secondary" @click="showEditDialog = false" />
      </template>
    </Dialog>

    <!-- Dialog para Adicionar Nova Role -->
    <Dialog v-model:visible="showAddDialog" header="Adicionar Nova Role" :modal="true" style="width: 50vw">
      <template #header>
        <h3>Nova Role</h3>
      </template>

      <div>
        <label for="new-role-name">Nome da Role</label>
        <InputText
          id="new-role-name"
          v-model="newRoleData.name"
          class="w-full"
          placeholder="Digite o nome da nova role"
        />
      </div>

      <template #footer>
        <Button label="Salvar" icon="pi pi-check" @click="addRole" />
        <Button label="Cancelar" icon="pi pi-times" class="p-button-secondary" @click="showAddDialog = false" />
      </template>
    </Dialog>

  </div>
</template>


<style>
:deep(.p-datatable-frozen-tbody) {
    font-weight: bold;
}

:deep(.p-datatable-scrollable .p-frozen-column) {
    font-weight: bold;
}
.cores{
    background-color: #1558b0;
    border: 1px solid #1558b0;
}

.cores:hover{
    background-color: #1558b0cf!important;
    border: 1px solid #1558b088!important;
}
.camposAgrupadosFormulario{
    display: flex;
    justify-content: space-between;
}
.camposAgrupadosFormulario .formUserAdd{
    width: calc((100% / 2) - 5px);
}

.camposTextos, .dropdownSexo{
    width: 100%;
    margin: 0px 0px;

}

.camposTextos:focus{
    border: #1558b0 1px solid!important;
}
.btnExports:last-child{
    color: #4271d4;
    background-color: #ffffff;
}

.labelDrop{
    margin: 15px 0px;
    display: block;
}

.btnPass{
    width: 100%!important;
    border: 0px!important;
    outline: 0px!important;
}
.p-inputtext{
    width: 100%!important;
}

.btnEstiliza:hover{
    color: #1558b0a4!important;
    background: #1558b033!important;
    transition: all .5s ease!important;
    
}

.btnEstilizaDel:hover{
    color: #ff0000a5!important;
    background: #ff000032!important;
    transition: all .5s ease!important;
    
}

.searchText:focus{
    border: #1558b0 1px solid!important;
}
.btnPermission:hover{
  background: #00000015!important;
  color: #555555!important;
  transition: all .3s ease;
  border-radius: 5px;
}
</style>
