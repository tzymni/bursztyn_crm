<template>
    <div class="cottages">
        <h1>{{ msg }}</h1>
        <div class="container"></div>
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
.cottages {
    width: 100%;
    height: 100%;
    min-height:95vh;
    max-width: 50vw;
}
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