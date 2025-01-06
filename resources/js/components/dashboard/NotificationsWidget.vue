<script setup>
import { ref } from 'vue';

const menu = ref(null);

const items = ref([
    { label: 'Add New', icon: 'pi pi-fw pi-plus' },
    { label: 'Remove', icon: 'pi pi-fw pi-trash' }
]);
</script>

<template>
    <div class="card">
        <div className="card">
                    <div className="flex align-items-center justify-content-between mb-4">
                        <h5>Notificações</h5>
                    </div>

                    <span className="block text-600 font-medium mb-3">HOJE</span>
                    <ul className="p-0 mx-0 mt-0 mb-4 list-none">
                        <li className="flex align-items-center py-2 border-bottom-1 surface-border">
                            <div className="w-3rem h-3rem flex align-items-center justify-content-center bg-blue-100 border-circle mr-3 flex-shrink-0">
                                <i className="pi pi-fw pi-heart text-xl text-blue-500" />
                            </div>
                            <span className="text-900 line-height-3">
                                Francisca João
                                <span className="text-700">
                                    
                                    Aderiu a plataforma 
                                    
                                </span>
                            </span>
                        </li>
                        <li className="flex align-items-center py-2">
                            <div className="w-3rem h-3rem flex align-items-center justify-content-center bg-orange-100 border-circle mr-3 flex-shrink-0">
                                <i className="pi pi-fw pi-globe text-xl text-orange-500" />
                            </div>
                            <span className="text-700 line-height-3">
                                Nova carga registrada
                            </span>
                        </li>
                    </ul>

                    <span className="block text-600 font-medium mb-3">Anteriores</span>
                    <ul className="p-0 m-0 list-none">
                        <li className="flex align-items-center py-2 border-bottom-1 surface-border">
                            <div className="w-3rem h-3rem flex align-items-center justify-content-center bg-blue-100 border-circle mr-3 flex-shrink-0">
                                <i className="pi pi-fw pi-calendar text-xl text-blue-500" />
                            </div>
                            <span className="text-900 line-height-3">
                                Live marcada para 07 de Janeiro de 2025
                            </span>
                        </li>
                       
                    </ul>
                </div>
    </div>
</template>
