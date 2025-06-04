import axios from 'axios';
import { Exception } from 'sass';


//cgate2.0
const baseURLTransacoes = 'http://20.87.9.35/api/v1/transacoes/lista';

const baseURLCarga = 'https://cdmapi.cornelder.co.mz/cgate/api/v1/c_gate/general_cargo/list_transactions';

const apiCarga = axios.create({
  baseURL: baseURLCarga,
  timeout: 10000, 
});

const apiTransacao = axios.create({
    baseURL: baseURLTransacoes,
    timeout: 10000, 
  });


export const getTransactions = async (page, limit, search, status) => {
  try {
    const response = await apiTransacao.get('', {
      params: {
        page: page,
        limit: limit,
        search: search,
        status: status,
      },
    });
    return response.data.result; 
  } catch (error) {
    console.error('Erro ao buscar transações:', error);
    throw error; 
  }
};

export const getCarga = async (page, limit, search, status) => {
    try {
      const response = await apiCarga.get('', {
        params: {
          page: page,
          limit: limit,
          search: search,
          status: status,
        },
      });
      return response.data.result; 
    } catch (error) {
      console.error('Erro ao buscar transações:', error);
      throw error; 
    }
  };
// const baseURLCDMProd = "https://cdmapi.cornelder.co.mz/cgate1x/api"
const baseURLCDMProd = "http://10.0.8.44:8010/api"
// const baseURLCDMProd = "https://c006-197-249-75-55.ngrok-free.app/api"

const storageImg =  "https://cdmapi.cornelder.co.mz/cgate1x"
//cgate2 - terminal
const baseURLCgate2 = "http://10.0.8.44:8010/api"
// const baseURLCgate2 = "https://cdmapi.cornelder.co.mz/cgate1x/api"
//cgate2 - carga
const baseURLCgate2Carga = "https://cdmapi.cornelder.co.mz"
//Excecoes
// const baseURLExcecoes = "https://cdmapi.cornelder.co.mz/cgate1x/api"

  export const baseUrls = {
    auth: `${baseURLCDMProd}/login`,
    transacoes: `${baseURLCDMProd}/containertransaction`,
    transacoesCgate2dotzero: `${baseURLCDMProd}/cgate2/transaction`,
    transacoesCgate2dotzeroCarga: `${baseURLCgate2Carga}/cgate/api/v1/c_gate/general_cargo/list_transactions`,
    userList: `${baseURLCDMProd}/users`,
    empresaAdd: `${baseURLCDMProd}/companies`,
    gate: `${baseURLCDMProd}/gates`,
    gatePermissions: `${baseURLCDMProd}/gatepermissions`,
    applications: `${baseURLCDMProd}/applications`,
    exceptionsList: `${baseURLCDMProd}/cgate2/excepcoes/lista`,
    exceptionsUpdate: `${baseURLCDMProd}/cgate2/excepcoes/actualizar`,
    precheckList: `${baseURLCDMProd}/cdms/precheck`,
    precheckCheckappointment: `${baseURLCDMProd}/cdms/checkappointment`,
    baseURl: baseURLCDMProd,
    storageUrl: storageImg
  };


