<script setup>
import { useLayout } from '@/layout/composables/layout';
import AppConfigurator from './AppConfigurator.vue';
import { useRouter } from 'vue-router';
import { acessRouters } from '../utils/accesRoute';

const { toggleMenu, toggleDarkMode, isDarkTheme } = useLayout();
const router = useRouter()

// const getUserData = () => {
//     if (JSON.parse(localStorage.getItem("cgate_user"))) {
//         return JSON.parse(localStorage.getItem("cgate_user"))
//     }
//     return false
// }



function logoutfun() {
    localStorage.removeItem('access_token');
    localStorage.removeItem('cgate_user');

    const accessToken = localStorage.getItem('access_token');
    const user = localStorage.getItem('cgate_user');

    if (!accessToken && !user) {
        for (const key in acessRouters.value) {
            if (Object.prototype.hasOwnProperty.call(acessRouters.value, key)) {
                acessRouters.value[key] = false;
            }
        }
        router.push('/');
    } else {
    }
}

</script>

<template>
    <div class="layout-topbar">
        <div class="layout-topbar-logo-container">

            <router-link to="/" class="layout-topbar-logo">
                <div class="flex items-center justify-center">
                    <!-- <Image src="`${logo}`" alt="Image" width="120" /> -->
                    <!-- <img src="/cgate1x/logo.png" alt="logo" style="width: 120px"/> -->
                    <div class="imageLogoTopBar"></div>
                </div>

            </router-link>

            <!-- <button class="layout-menu-button layout-topbar-action mx-10" @click="toggleMenu">
                <i class="pi pi-bars"></i>
            </button> -->




        </div>

        <div class="layout-topbar-actions">
            <button class="layout-menu-button layout-topbar-action mx-10" @click="logoutfun">
                <i class="pi pi-power-off"></i>
            </button>
            <div class="layout-config-menu">
                <!-- <button type="button" class="layout-topbar-action" @click="toggleDarkMode">
                    <i :class="['pi', { 'pi-moon': isDarkTheme, 'pi-sun': !isDarkTheme }]"></i>
                </button> -->
                <!-- <div class="relative">
                    <button
                        v-styleclass="{ selector: '@next', enterFromClass: 'hidden', enterActiveClass: 'animate-scalein', leaveToClass: 'hidden', leaveActiveClass: 'animate-fadeout', hideOnOutsideClick: true }"
                        type="button"
                        class="layout-topbar-action layout-topbar-action-highlight"
                    >
                        <i class="pi pi-palette"></i>
                    </button>
                    <AppConfigurator />
                </div> -->
            </div>

            <!-- <button class="layout-topbar-menu-button layout-topbar-action"
                v-styleclass="{ selector: '@next', enterFromClass: 'hidden', enterActiveClass: 'animate-scalein', leaveToClass: 'hidden', leaveActiveClass: 'animate-fadeout', hideOnOutsideClick: true }">
                <i class="pi pi-ellipsis-v"></i>
            </button> -->


            <!-- <div class="layout-topbar-menu hidden lg:block">
                <div class="layout-topbar-menu-content">
                    <button type="button" class="layout-topbar-action">
                        <i class="pi pi-calendar"></i>
                        <span>Calendar</span>
                    </button>
                    <button type="button" class="layout-topbar-action">
                        <i class="pi pi-inbox"></i>
                        <span>Messages</span>
                    </button>
                    <button type="button" class="layout-topbar-action">
                        <i class="pi pi-user"></i>
                        <span>Profile</span>
                    </button>
                </div>
            </div> -->
        </div>
    </div>
</template>
