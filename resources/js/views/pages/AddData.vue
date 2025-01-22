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
  // Definindo os dados do payload com informações do usuário e suas permissões
  const payload = {
    name: "Domingos Doe",
    email: "teste34sea@gmail.com",
    password: "12345678",
    mobile: "987654321",
    roles: [{ name: "Manager" }], // Atribuindo o ID da role ao invés de apenas o nome
  };

  try {
    // Realizando a requisição POST para criar o usuário
    const response = await axios.post("/api/users", payload, {
      headers: {
        "Content-Type": "application/json",
      },
    });

    // Exibindo uma notificação de sucesso se o usuário for criado com sucesso
    toast.add({
      severity: "success",
      summary: "Usuário Criado",
      detail: `Usuário ${response.data.name} criado com sucesso!`,
      life: 3000,
    });
  } catch (error) {
    // Exibindo erro se a criação do usuário falhar
    console.error("Erro ao adicionar usuário:", error.response?.data || error);

    toast.add({
      severity: "error",
      summary: "Erro",
      detail: error.response?.data?.message || "Erro ao criar o usuário.",
      life: 3000,
    });
  }
};

// const addUser = async () => {
//   // Dados de exemplo para o usuário
//   const user = {
//     name: 'João Silva',
//     email: 'joao.silyfdvadscddsas@example.com',
//     password: 'senha123',
//     mobile: '999999999',
//     roles: [{ name: 'Admin' }],
//   };

//   try {
//     const response = await axios.post('/api/users', user, {
//       headers: {
//         'Content-Type': 'application/json'
//       }
//     });

//     console.log('Usuário adicionado', response.data);
//     // Mensagem de sucesso (console.log para testar)
//     alert('Usuário adicionado com sucesso!');
//   } catch (error) {
//     console.error('Erro ao adicionar usuário:', error);
//     // Mensagem de erro (console.log para testar)
//     alert('Falha ao adicionar usuário.');
//   }
// };







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
