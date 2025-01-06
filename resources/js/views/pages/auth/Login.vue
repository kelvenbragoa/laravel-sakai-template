<script setup>
import FloatingConfigurator from '@/components/FloatingConfigurator.vue';
import { ref } from 'vue';
// import imgLogo from "@assets/images/logo.png"

const email = ref('');
const password = ref('');
const checked = ref(false);
</script>

<template>
    <FloatingConfigurator />
    <div class="bg-surface-50 dark:bg-surface-950 flex items-center justify-center min-h-screen min-w-[100vw] overflow-hidden">
        
        <div class="flex flex-col items-center justify-center">
            
            <div style="border-radius: 56px; padding: 0.3rem;" class="blue-gradient">
                <div class="w-full bg-surface-0 dark:bg-surface-900 py-20 px-8 sm:px-20" style="border-radius: 53px">
                    <div class="text-center mb-8">
                        <!-- <img src="@/images/login.png" alt="Descrição da imagem" class="my-image" /> -->
                        <!-- <img :src="require('@/assets/images/login.png')" alt="Descrição da imagem" /> -->
                        <div class="flex items-center justify-center w-full">
                            <Image src="/images/logo.png" alt="Image" width="200" />
                        </div>
                        <div class="m-20">

                        </div>
                        <div class="text-surface-900 dark:text-surface-0 text-3xl font-medium mb-4">Bem vindo de volta!</div>
                        <span class="text-muted-color font-medium">Preencha os campos</span>
                    </div>

                    <div>
                        <label for="email1" class="block text-surface-900 dark:text-surface-0 text-xl font-medium mb-2">Email</label>
                        <InputText id="email1" type="text" placeholder="Endereço eletrónico" class="w-full md:w-[30rem] mb-8 inputsCaixas" v-model="email" />

                        <label for="password1" class="block text-surface-900 dark:text-surface-0 font-medium text-xl mb-2">Senha</label>
                        <Password id="password1" v-model="password" placeholder="Senha" :toggleMask="true" class="mb-4 inputsCaixas" fluid :feedback="false"></Password>

                        <div class="flex items-center justify-between mt-2 mb-8 gap-8">
                            <div class="flex items-center">
                                <Checkbox v-model="checked" id="rememberme1" binary class="mr-2"></Checkbox>
                                <label for="rememberme1">Lembrar</label>
                            </div>
                            <span class="font-medium no-underline ml-2 text-right cursor-pointer corPrimaria">Esqueceste a senha?</span>
                        </div>
                        <Button label="Entrar" class="w-full facebook-button hover" as="router-link" to="/dashboards"></Button>
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

.facebook-button:hover{
    background-color: #1877F2!important;
    border: 1px solid #1877F2!important;
}

.corPrimaria{
    color: #1558b0;
}

.blue-gradient {
  background: linear-gradient(180deg, #1558b0 10%, rgba(33, 150, 243, 0) 30%);
}

.inputsCaixas{
    height: 50px!important;
    outline: none!important;
    
    
}

.inputsCaixas:focus{
 
    outline: none!important;
    border: 1px solid #1558b0!important;
    
}
</style>
