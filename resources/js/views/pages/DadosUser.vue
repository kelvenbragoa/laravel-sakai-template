<template>
    <div>
        <h1>
            Dados
        </h1>
    </div>
</template>
<script setup>


 axios
            .get("https://cdmapi.cornelder.co.mz/cgate/api/v1/c_gate/general_cargo/list_transactions")
            .then((res) => {
                    console.log(res.data)
            })
            .catch((error) => {
                    console.log(error);
            });
</script>
