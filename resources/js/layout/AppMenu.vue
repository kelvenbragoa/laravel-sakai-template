<script setup>
import { ref } from 'vue';

import AppMenuItem from './AppMenuItem.vue';

const model = ref([
    {
        label: 'Home',
        items: [{ label: 'Dashboard', icon: 'pi pi-fw pi-home', to: '/dashboard' }]
    },
    {
            label: 'Paginas',//Pages
            icon: 'pi pi-fw pi-briefcase',
            // to: '/pages',
            items: [
                {
                    label: 'C-Gate 1.x',
                    icon: 'pi pi-globe',
                    items: [
                        {
                            label: 'Terminal',
                            icon: 'pi pi-fw pi-list',
                            items: [
                                {
                                    label: 'Portão 4',
                                    icon: 'pi pi-window-maximize',
                                    to: '/terminalone/4'
                                },
                                {
                                    label: 'Portão 5',
                                    icon: 'pi pi-window-maximize',
                                    to: '/terminalone/5'
                                },
                                {
                                    label: 'Portão 6',
                                    icon: 'pi pi-window-maximize',
                                    to: '/terminalone/6'
                                },
                                {
                                    label: 'Portão 8A',
                                    icon: 'pi pi-window-maximize',
                                    to: '/terminalone/8A'
                                }, {
                                    label: 'Portão 11A - Entrada',
                                    icon: 'pi pi-window-maximize',
                                    to: '/terminalone/11AIn'
                                },
                                {
                                    label: 'Portão 11A - Saida',
                                    icon: 'pi pi-window-maximize',
                                    to: '/terminalone/11AOut'
                                }   
                            ]
                        },
                        {
                            label: 'Carga Geral',
                            icon: 'pi pi-truck',
                            items: [
                                {
                                    label: 'Portão 1',
                                    icon: 'pi pi-window-maximize',
                                    to: '/cargaone/1Out'
                                },
                                {
                                    label: 'Portão 2',
                                    icon: 'pi pi-window-maximize',
                                    to: '/cargaone/2Out'
                                },
                                {
                                    label: 'Portão 3',
                                    icon: 'pi pi-window-maximize',
                                    to: '/cargaone/3In'
                                },
                                {
                                    label: 'Portão 16',
                                    icon: 'pi pi-window-maximize',
                                    to: '/carga-geral/16In'
                                }   
                            ]
                        },
                    ]
                },
                {
            
                    label: 'C-Gate 2.x',
                    icon: 'pi pi-globe',
                    items: [
                        {
                            label: 'Terminal',
                            icon: 'pi pi-fw pi-list',
                            items: [
                                {
                                    label: 'Portão 4',
                                    icon: 'pi pi-window-maximize',
                                    to: '/terminal-contetores/4'
                                },
                                {
                                    label: 'Portão 5',
                                    icon: 'pi pi-window-maximize',
                                    to: '/terminal-contetores/5'
                                },
                                {
                                    label: 'Portão 6',
                                    icon: 'pi pi-window-maximize',
                                    to: '/terminal-contetores/6'
                                },
                                {
                                    label: 'Portão 8A',
                                    icon: 'pi pi-window-maximize',
                                    to: '/terminal-contetores/8A'
                                }, {
                                    label: 'Portão 11A - Entrada',
                                    icon: 'pi pi-window-maximize',
                                    to: '/terminal-contetores/11AIn'
                                },
                                {
                                    label: 'Portão 11A - Saida',
                                    icon: 'pi pi-window-maximize',
                                    to: '/terminal-contetores/11AOut'
                                }   
                            ]
                        },
                        {
                            label: 'Carga Geral',
                            icon: 'pi pi-truck',
                            items: [
                                {
                                    label: 'Portão 1',
                                    icon: 'pi pi-window-maximize',
                                    to: '/carga-geral/1Out'
                                },
                                {
                                    label: 'Portão 2',
                                    icon: 'pi pi-window-maximize',
                                    to: '/carga-geral/2Out'
                                },
                                {
                                    label: 'Portão 3',
                                    icon: 'pi pi-window-maximize',
                                    to: '/carga-geral/3In'
                                },
                                {
                                    label: 'Portão 16',
                                    icon: 'pi pi-window-maximize',
                                    to: '/carga-geral/16In'
                                }  
                            ]
                        },
                    ]
                },
                {
                label: 'Usuários',
                icon: 'pi pi-fw pi-users',
                items: [
                   {
                        label: "Permissões",
                        icon: 'pi pi-key',
                        to: '/rolespermissions'
                    },
                    {
                        label: 'User',
                        icon: 'pi pi-user',
                        to: '/user'
                    }
                ]
            },
            ]
            
        },
        
]);
</script>

<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in model" :key="item">
            <app-menu-item v-if="!item.separator" :item="item" :index="i"></app-menu-item>
            <li v-if="item.separator" class="menu-separator"></li>
        </template>
    </ul>
</template>

<style lang="scss" scoped></style>