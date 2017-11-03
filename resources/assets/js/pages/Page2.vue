<template>
    <div class="container">
        <div class="row">
            暂未开通在线分析
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
            axios.get('/home?ID=12345')
                .then(response => {
                //console.log(response.data)

            });
        }
    }
</script>
