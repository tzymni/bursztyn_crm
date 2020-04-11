<template>
    <div class="cottages">
        <h1>{{ msg }}</h1>
        
    </div>
</template>

<script>
    export default {
        name: 'Test',
        data () {
            return {
                msg: 'Cottages'
            }
        }
    }
</script>

<style scoped>

    ul {
        list-style-type: none;
        padding: 0;
    }

    li {
        display: inline-block;
        margin: 0 10px;
    }

    a {
        color: #42b983;
    }
</style>