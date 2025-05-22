<script setup>
import { h, onMounted, ref } from 'vue';

import AppMenuItem from './AppMenuItem.vue';
import { elements } from 'chart.js';
import { useRouter } from 'vue-router';
import { acessRouters, backLog, dataUser } from '../utils/accesRoute';
import axios from 'axios';
import { baseUrls } from '../api';


const applicationsPermissions = ref()
const getUserData = () => {
    if (JSON.parse(localStorage.getItem("cgate_user"))) {
        return JSON.parse(localStorage.getItem("cgate_user"))
    }
    return false
}

console.log("Login")
console.log(getUserData())


const getToken = () => {
    return localStorage.getItem("access_token");
}


const getUserDataEspecific = async (id) => {
    const token = getToken();
    if (!token) {
        backLog()
        return;
    }
    try {
        const response = await axios.get(`${baseUrls.userList}/${id}`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        })

        dataUser.value.applicationsPermissions = response.data.data
        localStorage.setItem("cgate_applicationsPermissions", JSON.stringify(response.data.data));
    } catch (e) {
        console.error(e)
        // return e
    }
}

getUserDataEspecific(getUserData().id)





const applications = ref()


if (!getUserData()) {
    backLog()
}


// const acessRouters = ref(
//     {
//         cgate1dotxfound: false,
//         cgate1dot0found: false,
//         cgate1dot1found: false,
//         cgate2dotxfound: false,
//         cgate1dotxfoundCargo: false,
//         cgate1dotxfoundTerminal: false,
//         cgate2dotxfoundCargo: false,
//         cgate2dotxfoundTerminal: false,
//         adminAcesse: false,
//         adminAcesseSuperAdmin: false,
//         precheck: false,
//     }
// )

const model = ref([]);

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
            },
            {
                label: 'Aplicações',
                icon: 'pi pi-mobile',
                to: '/applications'
            }
        ]
    })
}

const aplicationsMenu = () => {
    return ({
        label: 'Pre check',
        icon: 'pi pi-fw pi-check-square',
        items: [
            {
                label: "Pre check 1.0",
                icon: 'pi pi-check',
                to: '/precheck'
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
                    to: '/terminaloneone/4'
                },
                {
                    label: 'Portão 5',
                    icon: 'pi pi-window-maximize',
                    to: '/terminaloneone/5'
                },
                {
                    label: 'Portão 6',
                    icon: 'pi pi-window-maximize',
                    to: '/terminaloneone/6'
                },
                {
                    label: 'Portão 8A',
                    icon: 'pi pi-window-maximize',
                    to: '/terminaloneone/8A'
                },
                {
                    label: 'Portão 11A',
                    icon: 'pi pi-window-maximize',
                    to: '/terminaloneone/11AIn'
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
                    to: '/terminalonetwo/4'
                },
                {
                    label: 'Portão 5',
                    icon: 'pi pi-window-maximize',
                    to: '/terminalonetwo/5'
                },
                {
                    label: 'Portão 6',
                    icon: 'pi pi-window-maximize',
                    to: '/terminalonetwo/6'
                },
                {
                    label: 'Portão 8A',
                    icon: 'pi pi-window-maximize',
                    to: '/terminalonetwo/8A'
                },
                {
                    label: 'Portão 11A',
                    icon: 'pi pi-window-maximize',
                    to: '/terminalonetwo/11AIn'
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
                    to: '/cargaonetwo/1Out'
                },
                {
                    label: 'Portão 2',
                    icon: 'pi pi-window-maximize',
                    to: '/cargaonetwo/2Out'
                },
                {
                    label: 'Portão 3',
                    icon: 'pi pi-window-maximize',
                    to: '/cargaonetwo/3In'
                },
                {
                    label: 'Portão 16',
                    icon: 'pi pi-window-maximize',
                    to: '/cargaonetwo/16In'
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
                    to: '/terminalotwo/4'
                },
                {
                    label: 'Portão 5',
                    icon: 'pi pi-window-maximize',
                    to: '/terminalotwo/5'
                },
                {
                    label: 'Portão 6',
                    icon: 'pi pi-window-maximize',
                    to: '/terminalotwo/6'
                },
                {
                    label: 'Portão 8A',
                    icon: 'pi pi-window-maximize',
                    to: '/terminalotwo/8A'
                },
                {
                    label: 'Portão 11A',
                    icon: 'pi pi-window-maximize',
                    to: '/terminalotwo/11AIn'
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
                    to: '/cargaotwo/1'
                },
                // {
                //     label: 'Portão 2',
                //     icon: 'pi pi-window-maximize',
                //     to: '/cargaotwo/2Out'
                // },
                {
                    label: 'Portão 3',
                    icon: 'pi pi-window-maximize',
                    to: '/cargaotwo/3'
                },
                {
                    label: 'Portão 16',
                    icon: 'pi pi-window-maximize',
                    to: '/cargaotwo/16'
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
            // cgate1dot1CargaGeralMenu()
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
            // cgate1dot2CargaGeralMenu()

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

        if (gate == "c-gate 1.2") {

            return [gates1dot2(name)]
        } else if (gate == "c-gate 1.1") {

            return [gates1dot1(name)]
        }
        else {
            return [gates1dot1(name), gates1dot2(name)]
        }
    } else if (gateespecific != "" && gate == "") {
        if (gateespecific == "c-gate 1.2") {
            return [gates1dot1(""), gates1dot2(name)]
        } else if (gateespecific == "c-gate 1.1") {
            return [gates1dot1(name), gates1dot2("")]
        }
        else {
            return [gates1dot1(name), gates1dot2(name)]
        }
    } else {

        if (gate == "c-gate 1.2") {
            return [gates1dot2(name)]
        } else if (gate == "c-gate 1.1") {
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
    // if (acessRouters.value.cgate2dotxfoundCargo && acessRouters.value.cgate2dotxfoundTerminal) {
    //     return ""
    // } else if (acessRouters.value.cgate2dotxfoundCargo && !acessRouters.value.cgate2dotxfoundTerminal) {
    //     return "carga"
    // }
    return "terminal"
}

const roleVerify = (role) => {
    return role.some(e => e.name.toLowerCase() === "super admin");
}


const cgateMenuPages = async (roles, empresas) => {

    const token = getToken()
    if (!token) {
        return;
    } else {
        try {
            const response = await axios.get(baseUrls.applications, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            })
            applications.value = response.data.data.data.map((element) => {
                return {
                    ...element,
                    name: element.name + " " + element.version,
                };
            });
        } catch (e) {
            console.error(e)
        }
    }


    let applicationsView = []
    let cgate1dotxviewer = ""
    // applications.value.forEach(elements => {
    //     applicationsView.push(elements.name)
    // })

    empresas.forEach(element => {
        applications.value.forEach(e => {
            if (element['application_id'] == e['id']) {
                applicationsView.push(e['name'])
            }
        })
    });


    applicationsView.forEach(e => {

        if (e.toLowerCase().includes("c-gate")) {

            if (e.toLowerCase().includes("1")) {


                if (e.toLowerCase().indexOf("1") < e.toLowerCase().indexOf(".")) {
                    acessRouters.value.cgate1dotxfound = true
                    acessRouters.value.cgate1dotxfoundCargo = true
                    acessRouters.value.cgate1dotxfoundTerminal = true
                    cgate1dotxviewer += e.toLowerCase()
                    if (e.toLowerCase().lastIndexOf("1") > e.toLowerCase().indexOf(".")) {

                    } else if (e.toLowerCase().indexOf("2") > e.toLowerCase().indexOf(".")) {
                    }


                }
            } else if (e.toLowerCase().includes("2")) {

                if (e.toLowerCase().indexOf("2") < e.toLowerCase().indexOf(".")) {
                    acessRouters.value.cgate2dotxfound = true

                }
            }
        } else if (e.toLowerCase().includes("pre-check")) {
            acessRouters.value.precheck = true
        }
    })

    if (roleVerify(roles)) {

        acessRouters.value.adminAcesseSuperAdmin = true
        acessRouters.value.adminAcesse = true
        acessRouters.value.precheck = true
    }

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
            },
                // aplicationsMenu()
            ]
        )
    } else {


        if (acessRouters.value.cgate1dotxfound && acessRouters.value.cgate2dotxfound) {
            return (
                [{
                    label: 'C-Gate 1.x',
                    icon: 'pi pi-globe',
                    // items: cgateWitch1X("", [acessRouters.value.cgate1dotxfoundCargo ? "carga" : "terminal", ""])
                    items: cgateWitch1X(cgate1dotxviewer.trim(), [funVerify(), ""])

                },
                {

                    label: 'C-Gate 2.x',
                    icon: 'pi pi-globe',
                    items: cgateMenuPages2dot1(
                        // funVerifyv2()
                        ""
                    )
                },

                ]
            )
        } else if (acessRouters.value.cgate1dotxfound && !acessRouters.value.cgate2dotxfound) {
            return (
                [{
                    label: 'C-Gate 1.x',
                    icon: 'pi pi-globe',
                    items: cgateWitch1X(cgate1dotxviewer.trim(), [funVerify(), ""])
                }]
            )
        } else if (!acessRouters.value.cgate1dotxfound && acessRouters.value.cgate2dotxfound) {
            return (
                [{

                    label: 'C-Gate 2.x',
                    icon: 'pi pi-globe',
                    items: cgateMenuPages2dot1(
                        // funVerifyv2()
                        ""
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
            // label: 'Paginas',
            label: 'Aplicações',
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


const buildMenu = async () => {
    if (!getUserData()) {
        backLog()
    } else {

        const menuBase = ref([])
        const cgatePages = ref([])

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
        const result = await cgateMenuPages(viewsMenu.value, getUserData().applications);
        // const result = 0

        if (!result || (Array.isArray(result) && result.length === 0)) {

        } else {
            cgatePages.value['items'] = result
        }

        if (acessRouters.value.precheck) {
            if (cgatePages.value?.['items'] == null) {
                cgatePages.value['items'] = aplicationsMenu()
            } else {
                cgatePages.value['items'].push(aplicationsMenu())
            }

        }

        // acessRouters.value.adminAcesseSuperAdmin = true

        menuBase.value.push(cgatePages.value)
        if (acessRouters.value.adminAcesseSuperAdmin) {
            menuBase.value.push(userMenu())
        }

        model.value = menuBase.value

    }


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
    if (!getUserData()) {
        backLog()
    }
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
