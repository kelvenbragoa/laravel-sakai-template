<script setup>
import FloatingConfigurator from "@/components/FloatingConfigurator.vue";
import { ref } from "vue";
import { useRouter } from "vue-router";
import { baseUrls } from "../../../api/index";
const router = useRouter();

const token = ref("");
const dadosUserAuth = ref({
  user_name: "",
  password: "",
});



const loading = ref(false);
const errorL = ref();

const autenticar = async () => {
  if (dadosUserAuth.value.user_name === "") {
    errorL.value = `Preencha o campo de nome`;
  } else if (dadosUserAuth.value.password === "") {
    errorL.value = `Preencha o campo de senha`;
  } else if (
    dadosUserAuth.value.user_name === "" &&
    dadosUserAuth.value.password === ""
  ) {
    errorL.value = `Preencha os campos user e senha`;
  } else {
    loading.value = true;
    try {
      const response = await axios.post(baseUrls.auth, {
        user_name: dadosUserAuth.value.user_name,
        password: dadosUserAuth.value.password,
      });

      const { user, access_token } = response.data;

      localStorage.setItem("access_token", access_token);
      localStorage.setItem("cgate_user", JSON.stringify(user));
      localStorage.setItem("cgate_logged_click", true)

      router.push("/dashboard");
      loading.value = false;
    } catch (error) {
      console.error("Erro:", error);
      // alert("Erro");
      loading.value = false;
      if (error.status == 401) {
        errorL.value = `Credenciais invalidas ${error.status}`;
      } else {
        errorL.value = "Any Error";
      }
    }
  }
};

const goToPrecheck = ()=>{
  router.push("/precheck-form")
}
const checked = ref(false);
</script>

<template>
   <FloatingConfigurator />

  

    <div v-if="loading" class="loader-overlay">
      <div class="louderL">
        <ProgressSpinner />

      </div>
    </div>
    <div v-else>
      <FloatingConfigurator />

      <div class="dark:bg-surface-950 flex items-center justify-center min-h-screen min-w-[100vw] overflow-hidden"
        style="background-color: #f9f9f9; border: 0px solid black">
        <div class="flex flex-col items-center justify-center">
          <div style="border-radius: 56px; padding: 0.3rem" class="blue-gradient">
            <div class="w-full bg-surface-0 dark:bg-surface-900 py-20 px-8 sm:px-20" style="
              border-radius: 53px;
              border: 0px solid red;
              background-color: #ffffff;
            ">
              <div class="text-center mb-8">
                <!-- <img src="@/images/login.png" alt="Descrição da imagem" class="my-image" /> -->
                <!-- <img :src="require('@/assets/images/login.png')" alt="Descrição da imagem" /> -->
                <div class="flex items-center justify-center w-full">
                  <!-- <Image src="@/assets/images/logo.png" alt="Image" width="200" /> -->
                  <!-- <img
                  src="http://[::1]:5173/resources/js/assets/images/logo.png"
                  alt="logo"
                  style="width: 200px"
                /> -->
                  <div class="imageLogoLogin"></div>
                </div>
                <div class="m-20"></div>
                <div class="text-surface-900 dark:text-surface-0 text-3xl font-medium mb-4">
                  Bem vindo de volta!
                </div>
                <span class="text-muted-color font-medium">Preencha os campos</span>
              </div>

              <div class="erroMessage">
                {{ errorL }}
              </div>

              <div>
                <label for="email1"
                  class="block text-surface-900 dark:text-surface-0 text-xl font-medium mb-2">User</label>
                <InputText id="email1" type="text" placeholder="Usuário" class="w-full md:w-[30rem] mb-8 inputsCaixas"
                  v-model="dadosUserAuth.user_name" />

                <label for="password1"
                  class="block text-surface-900 dark:text-surface-0 font-medium text-xl mb-2">Senha</label>
                <Password id="password1" v-model="dadosUserAuth.password" placeholder="Senha" :toggleMask="true"
                  class="mb-4 inputsCaixas" fluid :feedback="false"></Password>

               
                <Button label="Entrar" class="w-full facebook-button hover" @click="autenticar"></Button>
                <Button label="Pre check" class="butoonCheck" @click="goToPrecheck"></Button>
                <!-- as="router-link" -->
                <!-- to="/dashboard" -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<style scoped>
.pi-eye {
  transform: scale(1.6);
  margin-right: 1rem;
}

.pi-eye-slash {
  transform: scale(1.6);
  margin-right: 1rem;
}

.facebook-button {
  background-color: #1558b0;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  cursor: pointer;
}

.facebook-button:hover {
  background-color: #1877f2 !important;
  border: 1px solid #1877f2 !important;
}

.corPrimaria {
  color: #1558b0;
}

.blue-gradient {
  background: linear-gradient(180deg, #1558b0 10%, rgba(33, 150, 243, 0) 30%);
}

.inputsCaixas {
  height: 50px !important;
  outline: none !important;
}

.inputsCaixas:focus {
  outline: none !important;
  border: 1px solid #1558b0 !important;
}

body {
  background-color: #ffffff !important;
}

.louderL {
  width: 100%;
  height: 100%;
  position: fixed;
  top: 0px;
  left: 0px;
  background-color: rgba(0, 0, 0, 0.05);
  z-index: 999999;
  display: flex;
  align-items: center;
  justify-content: center;
}

.butoonCheck{
  margin: 10px 0px;
  width: 100%;
  color: #222;
  background-color: transparent;
  border: none;
  font-size: 1rem;
  font-weight: 700!important;
}

.butoonCheck:hover{
  background-color: transparent!important;
  color: #1558b0!important;
  border: none!important;
}
</style>