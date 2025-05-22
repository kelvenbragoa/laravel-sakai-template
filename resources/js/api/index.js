import axios from 'axios';


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
const baseURLCDMProd = "https://cdmapi.cornelder.co.mz/cgate1x/api"
// const baseURLCDMProd = "http://10.0.8.44:8010/api"
// const baseURLCDMProd = "http://127.0.0.1:8000/api"

const storageImg =  "https://cdmapi.cornelder.co.mz/cgate1x"
//cgate2 - terminal
// const baseURLCgate2 = "http://20.87.9.35/api/v1/transacoes"
const baseURLCgate2 = "https://cdmapi.cornelder.co.mz/cgate1x"
//cgate2 - carga
const baseURLCgate2Carga = "https://cdmapi.cornelder.co.mz"

  export const baseUrls = {
    auth: `${baseURLCDMProd}/login`,
    transacoes: `${baseURLCDMProd}/containertransaction`,
    transacoesCgate2dotzero: `${baseURLCgate2}/cgate2/transaction`,
    transacoesCgate2dotzeroCarga: `${baseURLCgate2Carga}/cgate/api/v1/c_gate/general_cargo/list_transactions`,
    userList: `${baseURLCDMProd}/users`,
    empresaAdd: `${baseURLCDMProd}/companies`,
    gate: `${baseURLCDMProd}/gates`,
    applications: `${baseURLCDMProd}/applications`,
    baseURl: baseURLCDMProd,
    storageUrl: storageImg
  };


