
<template>
    <div class="users">
        <h1> Lista użytkowników</h1>
        <br/>
        <div>
            <b-button v-b-modal.modal-1>Dodaj użytkownika</b-button>
            <b-modal id="modal-1" title="BootstrapVue">
                <p class="my-4">Dodawanie usera!</p>
            </b-modal>
        </div>
        <b-table hover :items="items"></b-table>
    </div>
</template>

<b-modal id="modal-1" title="BootstrapVue">
    <p class="my-4">Hello from modal!</p>
</b-modal>
<script>
    export default {
        name: "Users",
        data() {
            return {
                items: [
                    { first_name: 'Tomasz', last_name: 'Zymni', email: 'tomasz23fl@gmail.com' },
                    { first_name: 'Asia', last_name: 'Cegiełka', email: 'asia.cegielka@gmail.com' },

                ]
            }
        }
    }
</script>



<style scoped>

</style>