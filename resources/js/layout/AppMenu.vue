<script setup>
import { ref } from 'vue';

import AppMenuItem from './AppMenuItem.vue';

const model = ref([
    {
        label: 'Home',
        items: [{ label: 'Dashboard', icon: 'pi pi-fw pi-home', to: '/dashboard' }]
    },
    // {
    //     label: 'UI Components',
    //     items: [
    //         { label: 'Form Layout', icon: 'pi pi-fw pi-id-card', to: '/uikit/formlayout' },
    //         { label: 'Input', icon: 'pi pi-fw pi-check-square', to: '/uikit/input' },
    //         { label: 'Button', icon: 'pi pi-fw pi-mobile', to: '/uikit/button', class: 'rotated-icon' },
    //         { label: 'Table', icon: 'pi pi-fw pi-table', to: '/uikit/table' },
    //         { label: 'List', icon: 'pi pi-fw pi-list', to: '/uikit/list' },
    //         { label: 'Tree', icon: 'pi pi-fw pi-share-alt', to: '/uikit/tree' },
    //         { label: 'Panel', icon: 'pi pi-fw pi-tablet', to: '/uikit/panel' },
    //         { label: 'Overlay', icon: 'pi pi-fw pi-clone', to: '/uikit/overlay' },
    //         { label: 'Media', icon: 'pi pi-fw pi-image', to: '/uikit/media' },
    //         { label: 'Menu', icon: 'pi pi-fw pi-bars', to: '/uikit/menu' },
    //         { label: 'Message', icon: 'pi pi-fw pi-comment', to: '/uikit/message' },
    //         { label: 'File', icon: 'pi pi-fw pi-file', to: '/uikit/file' },
    //         { label: 'Chart', icon: 'pi pi-fw pi-chart-bar', to: '/uikit/charts' },
    //         { label: 'Timeline', icon: 'pi pi-fw pi-calendar', to: '/uikit/timeline' },
    //         { label: 'Misc', icon: 'pi pi-fw pi-circle', to: '/uikit/misc' }
    //     ]
    // },
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
                icon: 'pi pi-users',
                items: [
                    {
                        label: "Permissões",
                        icon: 'pi pi-key',
                        to: '/permissions'
                    },
                    {
                        label: "User",
                        icon: 'pi pi-user',
                        to: '/user  '
                    }
                ]
            }

                // { label: 'Terminal', icon: 'pi pi-fw pi-list', to: '/terminal-contetores' },
                // { label: 'Cargo', icon: 'pi pi-truck', to: '/carga-geral' },  
            ]
            
        },
    // {
    //     label: 'Hierarchy',
    //     items: [
    //         {
    //             label: 'Submenu 1',
    //             icon: 'pi pi-fw pi-bookmark',
    //             items: [
    //                 {
    //                     label: 'Submenu 1.1',
    //                     icon: 'pi pi-fw pi-bookmark',
    //                     items: [
    //                         { label: 'Submenu 1.1.1', icon: 'pi pi-fw pi-bookmark' },
    //                         { label: 'Submenu 1.1.2', icon: 'pi pi-fw pi-bookmark' },
    //                         { label: 'Submenu 1.1.3', icon: 'pi pi-fw pi-bookmark' }
    //                     ]
    //                 },
    //                 {
    //                     label: 'Submenu 1.2',
    //                     icon: 'pi pi-fw pi-bookmark',
    //                     items: [{ label: 'Submenu 1.2.1', icon: 'pi pi-fw pi-bookmark' }]
    //                 }
    //             ]
    //         },
    //         {
    //             label: 'Submenu 2',
    //             icon: 'pi pi-fw pi-bookmark',
    //             items: [
    //                 {
    //                     label: 'Submenu 2.1',
    //                     icon: 'pi pi-fw pi-bookmark',
    //                     items: [
    //                         { label: 'Submenu 2.1.1', icon: 'pi pi-fw pi-bookmark' },
    //                         { label: 'Submenu 2.1.2', icon: 'pi pi-fw pi-bookmark' }
    //                     ]
    //                 },
    //                 {
    //                     label: 'Submenu 2.2',
    //                     icon: 'pi pi-fw pi-bookmark',
    //                     items: [{ label: 'Submenu 2.2.1', icon: 'pi pi-fw pi-bookmark' }]
    //                 }
    //             ]
    //         }
    //     ]
    // },
    // {
    //     label: 'Get Started',
    //     items: [
    //         {
    //             label: 'Documentation',
    //             icon: 'pi pi-fw pi-book',
    //             to: '/documentation'
    //         },
    //         {
    //             label: 'View Source',
    //             icon: 'pi pi-fw pi-github',
    //             url: 'https://github.com/primefaces/sakai-vue',
    //             target: '_blank'
    //         }
    //     ]
    // }
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
