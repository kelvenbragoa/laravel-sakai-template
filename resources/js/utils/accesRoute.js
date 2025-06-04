import { useRouter } from 'vue-router'
import { ref } from 'vue'
import axios from 'axios'
import { baseUrls } from '../api'

export const acessRouters = ref(
    {
        cgate1dotxfound: false,
        cgate1dot0found: false,
        cgate1dot1found: false,
        cgate2dotxfound: false,
        cgate1dotxfoundCargo: false,
        cgate1dotxfoundTerminal: false,
        cgate2dotxfoundExcecoes: false,
        cgate2dotxfoundCargo: false,
        cgate2dotxfoundTerminal: false,
        adminAcesse: false,
        adminAcesseSuperAdmin: false,
        precheck: false,
    }
)

export const dataUser = ref(
    {
        applicationsPermissions: []
    }
)



const getToken = () => {
    return localStorage.getItem("access_token");
  };

export const getUserData = () => {
    if (JSON.parse(localStorage.getItem("cgate_user"))) {
        return JSON.parse(localStorage.getItem("cgate_user"))
    }
    return false
}

export const verifyUser = () => {
    const user = getUserData()
    // if (user != false) {
    //     if (user && Array.isArray(user.roles)) {
    //         user.roles.forEach((e) => {
    //             if (e.name === "Super Admin") {
    //                 acessRouters.value.adminAcesseSuperAdmin = true
    //             }
    //         })
    //     }
    // }
}

export const verifyPermissions = () => {
    // const user = getUserData()
    // user.forEach(element => {
    //     if (element.name.split('-').length > 1) {
    //         element.name.split('-').forEach(e => {
    //             if (e == "CGate1x") {
    //                 acessRouters.value.cgate1dotxfound = true
    //                 if (element.name.split('-')[1] == "General Cargo") {
    //                     acessRouters.value.cgate1dotxfoundCargo = true
    //                 } else {
    //                     acessRouters.value.cgate1dotxfoundTerminal = true
    //                 }
    //             } else if (e == "CGate2x") {
    //                 acessRouters.value.cgate2dotxfound = true
    //                 if (element.name.split('-')[1] == "General Cargo") {
    //                     acessRouters.value.cgate2dotxfoundCargo = true
    //                 } else {
    //                     acessRouters.value.cgate2dotxfoundTerminal = true
    //                 }
    //             }
    //         })
    //     } else {
    //         if (element.name == "Admin") {
    //             acessRouters.value.adminAcesse = true
    //         } else if (element.name == "Super Admin") {
    //             acessRouters.value.adminAcesse = true
    //             acessRouters.value.adminAcesseSuperAdmin = true
    //         }
    //     }
    // });
}
export const checkAccess = () => {
    const router = useRouter()
    verifyUser()

    if (!acessRouters.value.adminAcesseSuperAdmin) {
        router.push("/dashboard")
    }
}


export const checkAccessPermissions = () => {
    verifyPermissions()


}


export const backLog = () =>{
    useRouter().push("/")
}

export const permissionsAcess = ()=>{
    return acessRouters.value
}