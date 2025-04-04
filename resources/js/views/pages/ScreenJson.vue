<script>
import { ref, onMounted } from "vue";
import url from "../../../../public/user.json"

    const users = ref([]);

    const numberPage =  ref(0)
    
    const loadJson = async () => {
        try {
        const response = await fetch(`/data.json?page=${numberPage}`);

       users.value = await response.json();
        } catch (error) {
        console.error('Erro ao carregar o JSON:', error);
        }
    };
    onMounted(
        loadJson()
    )
</script>
