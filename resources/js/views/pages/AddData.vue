<template>
  <div class="card">
    <h2>Adicionar Usuário</h2>
    <form @submit.prevent="addUser">
      <div class="field">
        <label for="name">Nome</label>
        <InputText id="name" v-model="form.name" required />
      </div>

      <div class="field">
        <label for="email">Email</label>
        <InputText id="email" v-model="form.email" required type="email" />
      </div>

      <div class="field">
        <label for="password">Senha</label>
        <Password id="password" v-model="form.password" required toggleMask />
      </div>

      <div class="field">
        <label for="mobile">Telefone</label>
        <InputText id="mobile" v-model="form.mobile" />
      </div>

      <div class="field">
        <label for="roles">Funções</label>
        <MultiSelect
          id="roles"
          v-model="form.roles"
          :options="roles"
          optionLabel="name"
          placeholder="Selecione as funções"
          required
        />
      </div>
        <!-- <button class="p-button p-component cores" @click="salvarPermissions">Salvar</button> -->
      <Button label="Adicionar Usuário" type="submit" class="mt-2" />
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import  InputText  from "primevue/inputtext";
import  Password  from "primevue/password";
import  MultiSelect  from "primevue/multiselect";
import  Button  from "primevue/button";
import { useToast } from 'primevue/usetoast';
import axios from "axios";

const form = reactive({
  name: "",
  email: "",
  password: "",
  mobile: "",
  roles: [],
});

const roles = ref([{"name": "Admin", "value": "Admin"}]);
const toast = useToast();

// Fetch roles from API
const fetchRoles = async () => {
  try {
    const data = await axios.get("/api/roles");
    roles.value = data.data.map((role) => ({
      name: role.name,
      id: role.id,
    }));
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Erro",
      detail: "Não foi possível carregar as funções",
      life: 3000,
    });
  }
};
const addUser = async () => {
  try {
    const payload = {
      name: "John Doe",
      email: "domingosmartinho34sea@gmail.com",
      password: "12345678",
      mobile: "987654321",
      roles: [{ name: "Admin" }],
    };

    const response = await axios.post("/api/users", payload, {
      headers: {
        'Content-Type': 'application/json',
      }
    });

    toast.add({
      severity: "success",
      summary: "Sucesso",
      detail: "Usuário adicionado com sucesso!",
      life: 3000,
    });
  } catch (error) {
    console.error("Erro detalhado:", error.response?.data); // Log da resposta completa
    toast.add({
      severity: "error",
      summary: "Erro",
      detail: error.response?.data?.message || "Erro ao adicionar usuário",
      life: 3000,
    });
  }
};


onMounted(fetchRoles);
</script>

<style>
.card {
  max-width: 600px;
  margin: 2rem auto;
  padding: 1.5rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  background: white;
}
.field {
  margin-bottom: 1.5rem;
}
.field label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: bold;
}
</style>
