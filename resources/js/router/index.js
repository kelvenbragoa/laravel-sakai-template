import AppLayout from '@/layout/AppLayout.vue';
import { createRouter, createWebHistory } from 'vue-router';



const router = createRouter({
    history: createWebHistory('/cgate1x/'),
    routes: [
        {
            path: '/',
            name: 'Login',
            component: () => import('@/views/pages/auth/Login.vue')
        },
        {
            path: '/dashboard',
            component: AppLayout,
            children: [
                {
                    path: '/dashboard',
                    name: 'dashboard',
                    component: () => import('@/views/pages/Dashboard.vue')
                },


                //C-gate 1.2
                {
                    path: '/cargaonetwo/:id',
                    name: 'cargaone',
                    component: () => import('@/views/pages/cgateonetwo/carga/Carga.vue'),
                    props: true
                },
               
                {
                    path: '/terminalonetwo/:id',
                    name: 'terminalone',
                    component: () => import('@/views/pages/cgateonetwo/terminal/Terminal.vue'),
                    props: true
                    
                },

                //C-gate 1.1
                {
                    path: '/cargaoneone/:id',
                    name: 'cargaoneone',
                    component: () => import('@/views/pages/cgateone/carga/Carga.vue'),
                    props: true
                },
                {
                    path: '/terminaloneone/:id',
                    name: 'terminaloneone',
                    component: () => import('@/views/pages/cgateone/terminal/Terminal.vue'),
                    props: true
                },
                //------

                 //C-gate 2.0
                 {
                    path: '/cargaotwo/:id',
                    name: 'cargaotwo',
                    component: () => import('@/views/pages/cgatetwo/carga/Carga.vue')
                },
                {
                    path: '/terminalotwo/:id',
                    name: 'terminalotwo',
                    component: () => import('@/views/pages/cgatetwo/terminal/Terminal.vue'),
                    
                },
                //------
                //Precheck
                //v1
                {
                    path: '/precheck',
                    name: 'precheck',
                    component: () => import('@/views/pages/precheck/versionone/Precheck.vue')
                },
                //-------------------------------------
                {
                    path: '/uikit/formlayout',
                    name: 'formlayout',
                    component: () => import('@/views/uikit/FormLayout.vue')
                },
                {
                    path: '/uikit/input',
                    name: 'input',
                    component: () => import('@/views/uikit/InputDoc.vue')
                },
                {
                    path: '/uikit/button',
                    name: 'button',
                    component: () => import('@/views/uikit/ButtonDoc.vue')
                },
                {
                    path: '/uikit/table',
                    name: 'table',
                    component: () => import('@/views/uikit/TableDoc.vue')
                },
                {
                    path: '/uikit/list',
                    name: 'list',
                    component: () => import('@/views/uikit/ListDoc.vue')
                },
                {
                    path: '/uikit/tree',
                    name: 'tree',
                    component: () => import('@/views/uikit/TreeDoc.vue')
                },
                {
                    path: '/uikit/panel',
                    name: 'panel',
                    component: () => import('@/views/uikit/PanelsDoc.vue')
                },

                {
                    path: '/uikit/overlay',
                    name: 'overlay',
                    component: () => import('@/views/uikit/OverlayDoc.vue')
                },
                {
                    path: '/uikit/media',
                    name: 'media',
                    component: () => import('@/views/uikit/MediaDoc.vue')
                },
                {
                    path: '/uikit/message',
                    name: 'message',
                    component: () => import('@/views/uikit/MessagesDoc.vue')
                },
                {
                    path: '/uikit/file',
                    name: 'file',
                    component: () => import('@/views/uikit/FileDoc.vue')
                },
                {
                    path: '/uikit/menu',
                    name: 'menu',
                    component: () => import('@/views/uikit/MenuDoc.vue')
                },
                {
                    path: '/uikit/charts',
                    name: 'charts',
                    component: () => import('@/views/uikit/ChartDoc.vue')
                },
                {
                    path: '/uikit/misc',
                    name: 'misc',
                    component: () => import('@/views/uikit/MiscDoc.vue')
                },
                {
                    path: '/uikit/timeline',
                    name: 'timeline',
                    component: () => import('@/views/uikit/TimelineDoc.vue')
                },
                {
                    path: '/pages/empty',
                    name: 'empty',
                    component: () => import('@/views/pages/Empty.vue')
                },
                {
                    path: '/pages/crud',
                    name: 'crud',
                    component: () => import('@/views/pages/Crud.vue')
                },
                {
                    path: '/documentation',
                    name: 'documentation',
                    component: () => import('@/views/pages/Documentation.vue')
                },
                {
                    path: '/user',
                    name: 'user',
                    component: () => import('@/views/pages/users/User.vue')
                },
                {
                    path: '/users',
                    name: 'users',
                    component: () => import('@/views/pages/users/Users.vue')
                },
                {
                    path: "/rolespermissions",
                    name: "rolespermissions",
                    component: () => import('@/views/pages/permitionroles/Permition.vue')
                },
                {
                    path: "/company",
                    name: "company",
                    component: () => import('@/views/pages/company/Company.vue')
                },
                {
                    path: "/gate",
                    name: "gate",
                    component: () => import('@/views/pages/gate/Gate.vue')
                },
                {
                    path: "/applications",
                    name: "applications",
                    component: () => import('@/views/pages/application/Application.vue')
                }
            ]
        },
        {
            path: '/landing',
            name: 'landing',
            component: () => import('@/views/pages/Landing.vue')
        },
        {
            path: '/pages/notfound',
            name: 'notfound',
            component: () => import('@/views/pages/NotFound.vue')
        },

        {
            path: '/auth/login',
            name: 'login',
            component: () => import('@/views/pages/auth/Login.vue')
        },
        {
            path: '/auth/access',
            name: 'accessDenied',
            component: () => import('@/views/pages/auth/Access.vue')
        },
        {
            path: '/auth/error',
            name: 'error',
            component: () => import('@/views/pages/auth/Error.vue')
        },
        {
            path: '/pdf',
            name: 'pdf',
            component: () => import('@/views/pages/PdfTeste.vue')
        },
        {
            path: '/dadoteste',
            name: 'dadoteste',
            component: () => import('@/views/pages/DadosUser.vue')
        },
        {
            path: "/add",
            name: 'add',
            component: () => import('@/views/pages/AddData.vue')
        },
        {
            path: "/jsonread",
            name: 'jsonread',
            component: () => import('@/views/pages/ScreenJson.vue')
        }
    ]
});

export default router;
