<script setup>
import { h, onMounted, ref } from 'vue';

import AppMenuItem from './AppMenuItem.vue';
import { elements } from 'chart.js';

const getUserData = () => {
    return JSON.parse(localStorage.getItem("cgate_user"))
}



const acessRouters = ref(
    {
        "cgate1dotxfound": false,
        "cgate2dotxfound": false,
        "cgate1dotxfoundCargo": false,
        "cgate1dotxfoundTerminal": false,
        "cgate2dotxfoundCargo": false,
        "cgate2dotxfoundTerminal": false,
        "adminAcesse": false,
        "adminAcesseSuperAdmin": false
    }
)

const model = ref([]);
const userMenus = ref([{
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
        },
        {
            label: 'Empresas',
            icon: 'pi pi-building',
            to: '/company'
        },
        {
            label: 'Gates',
            icon: 'pi pi-window-maximize',
            to: '/gate'
        }
    ]
}])

const userMenu = () => {
    return ({
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
            },
            {
                label: 'Empresas',
                icon: 'pi pi-building',
                to: '/company'
            },
            {
                label: 'Gates',
                icon: 'pi pi-window-maximize',
                to: '/gate'
            }
        ]
    })
}

const homeMenu = () => {
    return ({
        label: 'Home',
        items: [{ label: 'Dashboard', icon: 'pi pi-fw pi-home', to: '/dashboard' }]
    })
}

const cgate1dot1TerminalMenu = () => {
    return (
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
                },
                {
                    label: 'Portão 11A',
                    icon: 'pi pi-window-maximize',
                    to: '/terminalone/11AIn'
                },

            ]
        }
    )
}

const cgate1dot1CargaGeralMenu = () => {
    return (
        {
            label: 'Carga Geral',
            icon: 'pi pi-truck',
            items: [
                {
                    label: 'Portão 1',
                    icon: 'pi pi-window-maximize',
                    to: '/cargaoneone/1Out'
                },
                {
                    label: 'Portão 2',
                    icon: 'pi pi-window-maximize',
                    to: '/cargaoneone/2Out'
                },
                {
                    label: 'Portão 3',
                    icon: 'pi pi-window-maximize',
                    to: '/cargaoneone/3In'
                },
                {
                    label: 'Portão 16',
                    icon: 'pi pi-window-maximize',
                    to: '/cargaoneone/16In'
                }
            ]
        }
    )
}

const cgate1dot2TerminalMenu = () => {
    return (
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
                },
                {
                    label: 'Portão 11A',
                    icon: 'pi pi-window-maximize',
                    to: '/terminalone/11AIn'
                },

            ]
        }
    )
}

const cgate1dot2CargaGeralMenu = () => {
    return (
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
        }
    )
}

const cgate2dot1TerminalMenu = () => {
    return (
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
                },
                {
                    label: 'Portão 11A',
                    icon: 'pi pi-window-maximize',
                    to: '/terminalone/11AIn'
                },

            ]
        }
    )
}

const cgate2dot1CargaGeralMenu = () => {
    return (
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
        }
    )
}


const viewsMenu = ref(getUserData().roles)

const cgateMenuPages1dot1 = (name) => {
    if (name == "terminal") {
        return [cgate1dot1TerminalMenu()]
    } else if (name == "carga") {
        return [cgate1dot1CargaGeralMenu()]
    }
    else {
        return ([
            cgate1dot1TerminalMenu(),
            cgate1dot1CargaGeralMenu()
        ])
    }

}

const cgateMenuPages1dot2 = (name) => {
    if (name == "terminal") {
        return [cgate1dot2TerminalMenu()]
    } else if (name == "carga") {
        return [cgate1dot2CargaGeralMenu()]
    }
    else {
        return ([

            cgate1dot2TerminalMenu(),
            cgate1dot2CargaGeralMenu()

        ])
    }

}

const cgateMenuPages2dot1 = (name) => {
    if (name == "terminal") {
        return [cgate2dot1TerminalMenu()]
    } else if (name == "carga") {
        return [cgate2dot1CargaGeralMenu()]
    }
    else {
        return ([

            cgate2dot1TerminalMenu(),
            cgate2dot1CargaGeralMenu()

        ])
    }

}
const gates1dot1 = (name) => {
    return {
        label: 'C-Gate 1.1',
        icon: 'pi pi-globe',
        items: cgateMenuPages1dot1(name)
    }
}

const gates1dot2 = (name) => {
    return {
        label: 'C-Gate 1.2',
        icon: 'pi pi-globe',
        items: cgateMenuPages1dot2(name)
    }
}
const cgateWitch1X = (gate, [name, gateespecific]) => {
    if (gateespecific == "" && gate != "") {
        if (gate == "C-Gate 1.2") {
            return [gates1dot2(name)]
        } else if (gate == "C-Gate 1.1") {
            return [gates1dot1(name)]
        }
        else {
            return [gates1dot1(name), gates1dot2(name)]
        }
    } else if (gateespecific != "" && gate == "") {
        if (gateespecific == "C-Gate 1.2") {
            return [gates1dot1(""), gates1dot2(name)]
        } else if (gateespecific == "C-Gate 1.1") {
            return [gates1dot1(name), gates1dot2("")]
        }
        else {
            return [gates1dot1(name), gates1dot2(name)]
        }
    } else {
        if (gate == "C-Gate 1.2") {
            return [gates1dot2(name)]
        } else if (gate == "C-Gate 1.1") {
            return [gates1dot1(name)]
        }
        else {
            return [gates1dot1(name), gates1dot2(name)]
        }
    }
}

const funVerify = () => {
    if (acessRouters.value.cgate1dotxfoundCargo && !acessRouters.value.cgate1dotxfoundTerminal) {
        return "carga"
    } else if (!acessRouters.value.cgate1dotxfoundCargo && acessRouters.value.cgate1dotxfoundTerminal) {
        return "terminal"
    }
    return ""
}

const funVerifyv2 = () => {
    if (acessRouters.value.cgate2dotxfoundCargo && acessRouters.value.cgate2dotxfoundTerminal) {
        return ""
    } else if (acessRouters.value.cgate2dotxfoundCargo && !acessRouters.value.cgate2dotxfoundTerminal) {
        return "carga"
    }
    return "terminal"
}
const cgateMenuPages = (menuCgateX) => {

    menuCgateX.forEach(element => {
        if (element.name.split('-').length > 1) {
            element.name.split('-').forEach(e => {
                if (e == "CGate1x") {
                    acessRouters.value.cgate1dotxfound = true
                    if (element.name.split('-')[1] == "General Cargo") {
                        acessRouters.value.cgate1dotxfoundCargo = true
                    } else {
                        acessRouters.value.cgate1dotxfoundTerminal = true
                    }
                } else if (e == "CGate2x") {
                    acessRouters.value.cgate2dotxfound = true
                    if (element.name.split('-')[1] == "General Cargo") {
                        acessRouters.value.cgate2dotxfoundCargo = true
                    } else {
                        acessRouters.value.cgate2dotxfoundTerminal = true
                    }
                }
            })
        } else {
            if (element.name == "Admin") {
                acessRouters.value.adminAcesse = true
            } else if (element.name == "Super Admin") {
                acessRouters.value.adminAcesse = true
                acessRouters.value.adminAcesseSuperAdmin = true
            }
        }
    });

    if (acessRouters.value.adminAcesse) {

        return (
            [{
                label: 'C-Gate 1.x',
                icon: 'pi pi-globe',
                items: cgateWitch1X("", ["", ""])
            },
            {

                label: 'C-Gate 2.x',
                icon: 'pi pi-globe',
                items: cgateMenuPages2dot1("")
            }]
        )
    } else {


        if (acessRouters.value.cgate1dotxfound && acessRouters.value.cgate2dotxfound) {
            return (
                [{
                    label: 'C-Gate 1.x',
                    icon: 'pi pi-globe',
                    // items: cgateWitch1X("", [acessRouters.value.cgate1dotxfoundCargo ? "carga" : "terminal", ""])
                    items: cgateWitch1X("", [funVerify(), ""])

                },
                {

                    label: 'C-Gate 2.x',
                    icon: 'pi pi-globe',
                    items: cgateMenuPages2dot1(
                        funVerifyv2()
                    )
                }]
            )
        } else if (acessRouters.value.cgate1dotxfound && !acessRouters.value.cgate2dotxfound) {
            return (
                [{
                    label: 'C-Gate 1.x',
                    icon: 'pi pi-globe',
                    items: cgateWitch1X("", [funVerify(), ""])
                }]
            )
        } else if (!acessRouters.value.cgate1dotxfound && acessRouters.value.cgate2dotxfound) {
            return (
                [{

                    label: 'C-Gate 2.x',
                    icon: 'pi pi-globe',
                    items: cgateMenuPages2dot1(
                        funVerifyv2()
                    )
                }]
            )
        } else {
            return (
                null
            )
        }
    }
}

const baseMenu = ref(
    {
        home: homeMenu(),
        cgate: {
            label: 'Paginas',
            icon: 'pi pi-fw pi-briefcase',
            items: [
                // cgateMenuPages(viewsMenu.value)
            ]
        },



        // users: {
        //     permissions: {

        //     },
        //     user: {

        //     },
        //     empresas: {

        //     },
        //     gates: {

        //     }
        // }
        // users: userMenu()
    }







)





const menusVisiveis = ["terminal", "carga"]


const buildMenu = () => {

    const menuBase = ref([])
    const cgatePages = ref()

    for (let items in baseMenu.value) {
        if (items == "users") {
            // model.value = baseMenu.value[items]
            menuBase.value.push(baseMenu.value[items])
        } else if (items == "home") {
            menuBase.value.push(baseMenu.value[items])
        } else if (items == "cgate") {
            cgatePages.value = baseMenu.value[items]
        }

    }
    cgatePages.value['items'] = cgateMenuPages(viewsMenu.value)
    
    menuBase.value.push(cgatePages.value)
    if (acessRouters.value.adminAcesseSuperAdmin) {
        menuBase.value.push(userMenu())
    }
    

    model.value = menuBase.value


    // {
    //     label: 'Paginas',//Pages
    //     icon: 'pi pi-fw pi-briefcase',
    //     // to: '/pages',
    //     items: [
    // {
    //     label: 'C-Gate 1.x',
    //     icon: 'pi pi-globe',
    //     items: [

    //         //C-gate 1.1
    //         {
    //             label: 'C-Gate 1.1',
    //             icon: 'pi pi-globe',
    //             items: [
    //                 {
    //                     label: 'Terminal',
    //                     icon: 'pi pi-fw pi-list',
    //                     items: [
    //                         {
    //                             label: 'Portão 4',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/terminaloneone/4'
    //                         },
    //                         {
    //                             label: 'Portão 5',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/terminaloneone/5'
    //                         },
    //                         {
    //                             label: 'Portão 6',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/terminaloneone/6'
    //                         },
    //                         {
    //                             label: 'Portão 8A',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/terminaloneone/8A'
    //                         }, {
    //                             label: 'Portão 11A',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/terminaloneone/11AIn'
    //                         }
    //                     ]
    //                 },
    //                 {
    //                     label: 'Carga Geral',
    //                     icon: 'pi pi-truck',
    //                     items: [
    //                         {
    //                             label: 'Portão 1',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/cargaoneone/1Out'
    //                         },
    //                         {
    //                             label: 'Portão 2',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/cargaoneone/2Out'
    //                         },
    //                         {
    //                             label: 'Portão 3',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/cargaoneone/3In'
    //                         },
    //                         {
    //                             label: 'Portão 16',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/cargaoneone/16In'
    //                         }
    //                     ]
    //                 },

    //             ]
    //         },

    //         //C-gate 1.2

    //         {
    //             label: 'C-Gate 1.2',
    //             icon: 'pi pi-globe',
    //             items: [
    //                 {
    //                     label: 'Terminal',
    //                     icon: 'pi pi-fw pi-list',
    //                     items: [
    //                         {
    //                             label: 'Portão 4',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/terminalone/4'
    //                         },
    //                         {
    //                             label: 'Portão 5',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/terminalone/5'
    //                         },
    //                         {
    //                             label: 'Portão 6',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/terminalone/6'
    //                         },
    //                         {
    //                             label: 'Portão 8A',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/terminalone/8A'
    //                         },
    //                         {
    //                             label: 'Portão 11A',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/terminalone/11AIn'
    //                         },

    //                     ]
    //                 },
    //                 {
    //                     label: 'Carga Geral',
    //                     icon: 'pi pi-truck',
    //                     items: [
    //                         {
    //                             label: 'Portão 1',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/cargaone/1Out'
    //                         },
    //                         {
    //                             label: 'Portão 2',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/cargaone/2Out'
    //                         },
    //                         {
    //                             label: 'Portão 3',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/cargaone/3In'
    //                         },
    //                         {
    //                             label: 'Portão 16',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/carga-geral/16In'
    //                         }
    //                     ]
    //                 },

    //             ]
    //         },

    //     ]
    // },
    //         {

    //             label: 'C-Gate 2.x',
    //             icon: 'pi pi-globe',
    //             items: [
    //                 {
    //                     label: 'Terminal',
    //                     icon: 'pi pi-fw pi-list',
    //                     items: [
    //                         {
    //                             label: 'Portão 4',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/terminal-contetores/4'
    //                         },
    //                         {
    //                             label: 'Portão 5',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/terminal-contetores/5'
    //                         },
    //                         {
    //                             label: 'Portão 6',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/terminal-contetores/6'
    //                         },
    //                         {
    //                             label: 'Portão 8A',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/terminal-contetores/8A'
    //                         }, {
    //                             label: 'Portão 11A - Entrada',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/terminal-contetores/11AIn'
    //                         },
    //                         {
    //                             label: 'Portão 11A - Saida',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/terminal-contetores/11AOut'
    //                         }
    //                     ]
    //                 },
    //                 {
    //                     label: 'Carga Geral',
    //                     icon: 'pi pi-truck',
    //                     items: [
    //                         {
    //                             label: 'Portão 1',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/carga-geral/1Out'
    //                         },
    //                         {
    //                             label: 'Portão 2',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/carga-geral/2Out'
    //                         },
    //                         {
    //                             label: 'Portão 3',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/carga-geral/3In'
    //                         },
    //                         {
    //                             label: 'Portão 16',
    //                             icon: 'pi pi-window-maximize',
    //                             to: '/carga-geral/16In'
    //                         }
    //                     ]
    //                 },
    //             ]
    //         },
    // {
    //     label: 'Usuários',
    //     icon: 'pi pi-fw pi-users',
    //     items: [
    //         {
    //             label: "Permissões",
    //             icon: 'pi pi-key',
    //             to: '/rolespermissions'
    //         },
    //         {
    //             label: 'User',
    //             icon: 'pi pi-user',
    //             to: '/user'
    //         },
    //         {
    //             label: 'Empresas',
    //             icon: 'pi pi-building',
    //             to: '/company'
    //         },
    //         {
    //             label: 'Gates',
    //             icon: 'pi pi-window-maximize',
    //             to: '/gate'
    //         }
    //     ]
    // },
    //     ]

    // },


}

onMounted(() => {
    buildMenu()
})
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

<!-- <script setup>
import { ref, onMounted } from 'vue';
import AppMenuItem from './AppMenuItem.vue';

// Define o tipo de usuário atual (isto pode vir da autenticação, por exemplo)
const userRole = ref('admin'); // pode ser 'admin', 'operador', 'visitante', etc.

// Menu reativo
const model = ref([]);

// Função que gera o menu com base no tipo de utilizador
function buildMenu() {
    const baseMenu = [
        {
            label: 'Home',
            items: [{ label: 'Dashboard', icon: 'pi pi-fw pi-home', to: '/dashboard' }]
        }
    ];

    const paginasMenu = {
        label: 'Paginas',
        icon: 'pi pi-fw pi-briefcase',
        items: []
    };

    const cGate11Items = [];

    // Se o utilizador puder ver o Terminal
    if (['admin', 'operador'].includes(userRole.value)) {
        cGate11Items.push({
            label: 'Terminal',
            icon: 'pi pi-fw pi-list',
            items: [
                { label: 'Portão 4', icon: 'pi pi-window-maximize', to: '/terminaloneone/4' },
                { label: 'Portão 5', icon: 'pi pi-window-maximize', to: '/terminaloneone/5' },
                { label: 'Portão 6', icon: 'pi pi-window-maximize', to: '/terminaloneone/6' },
                { label: 'Portão 8A', icon: 'pi pi-window-maximize', to: '/terminaloneone/8A' },
                { label: 'Portão 11A', icon: 'pi pi-window-maximize', to: '/terminaloneone/11AIn' }
            ]
        });
    }

    // Se o utilizador puder ver "Carga"
    if (['admin'].includes(userRole.value)) {
        cGate11Items.push({
            label: 'Carga',
            icon: 'pi pi-fw pi-truck',
            items: [
                { label: 'Expedição', icon: 'pi pi-send', to: '/carga/expedicao' },
                { label: 'Recepção', icon: 'pi pi-download', to: '/carga/recepcao' }
            ]
        });
    }

    // Apenas adiciona o menu C-Gate 1.1 se tiver sub-itens
    if (cGate11Items.length > 0) {
        paginasMenu.items.push({
            label: 'C-Gate 1.1',
            icon: 'pi pi-globe',
            items: cGate11Items
        });
    }

    // Adiciona o menu "Paginas" se tiver conteúdos
    if (paginasMenu.items.length > 0) {
        baseMenu.push(paginasMenu);
    }

    model.value = baseMenu;
}

// Gera o menu ao iniciar
onMounted(() => {
    buildMenu();
});
</script>


<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in model" :key="item">
            <app-menu-item v-if="!item.separator" :item="item" :index="i"></app-menu-item>
            <li v-if="item.separator" class="menu-separator"></li>
        </template>
    </ul>
</template> -->
