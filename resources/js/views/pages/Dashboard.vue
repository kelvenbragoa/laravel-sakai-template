<script setup>
import BestSellingWidget from '@/components/dashboard/BestSellingWidget.vue';
import NotificationsWidget from '@/components/dashboard/NotificationsWidget.vue';

import RevenueStreamWidget from '@/components/dashboard/RevenueStreamWidget.vue';
import StatsWidget from '@/components/dashboard/StatsWidget.vue';
import { onMounted } from 'vue';

// const getUserData = ()=>{
//     return JSON.parse(localStorage.getItem("cgate_user"))
// }

// console.log(getUserData())   
</script>



<template>
    <div class="grid grid-cols-12 gap-8">
        <StatsWidget />
        <div class="w-full col-span-12">
            <RevenueStreamWidget />
            <div class="m-10"></div>
            <NotificationsWidget />
        </div>
    </div>
</template>
