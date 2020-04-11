<template>
    <div class="cottages">
        <h1><font-awesome-icon icon="home"/> || {{ header }}</h1>
        
        <div class="container">

            <div>
                <b-button class="btn btn-info" id="show-modal" @click="showModal()">Add user</b-button>
                <b-modal @hide="setCottage()" id="cottage-form-modal" title="Cottage form" hide-footer>
                    <CottageForm :editId="$data.editId" v-on:childToParent="showModal"/>
                </b-modal>

            </div>

            <div class="table-wrap">
                <b-table id="Cottages"
                         :per-page="perPage"
                         :current-page="currentPage" striped small bordered class="table-cottages" :fields="fields"
                         :items="Cottages"
                         thead-class="thead-dark">
                    <template v-slot:head(id)="data">
                        <p class="hide">{{data.field.id}}</p>
                    </template>
                    <template v-slot:cell(id)="data">
                        <p class="hide">{{data.item.id}}</p>
                        <p class="text-center">
                            <font-awesome-icon class="icon" icon="user"/>
                        </p>
                    </template>
                    <template v-slot:head(is_active)="data">
                        <p class="hide">{{data.field.is_active}}</p>
                        <p>Operations</p>
                    </template>
                    <template v-slot:cell(is_active)="data">
                        <p class="hide">{{data.item.is_active}}</p>
                        <a @click="show(data.item.id)"  class="btn btn-danger">
                            <font-awesome-icon  icon="trash-alt"/>
                        </a>
                        <a @click="showModal(data.item.id)" class="btn btn-primary">
                            <font-awesome-icon icon="edit"/>
                        </a>
                    </template>
                </b-table>
                <b-pagination
                        v-model="currentPage"
                        :total-rows="rows"
                        :per-page="perPage"
                        aria-controls="Cottages"
                ></b-pagination>
            </div>
        </div>
            <b-modal id="delete-form"
                hide-footer 
                title="Removing cottage">
                <p>Are you sure you want to delete this cottage?</p>
                <div class="m-footer text-center">
                <b-button @click="deleteUser($data.editId)" class="btn btn-danger p-2 m-3">
                            Delete
                </b-button>
                <b-button @click="$bvModal.hide('delete-form')" class="btn p-2 m-3">
                            Cancel
                </b-button>
                    </div>
            </b-modal>


    </div>
</template>

<script>
    import CottageForm from "./CottageForm";
    import {cottageService} from "../_services/cottage.service";

    export default {
        name: "Cottages",
        components: {CottageForm},
        data: function () {
            return {
                header: "Cottages",
                cottages: [],
                perPage: 10,
                currentPage: 1,
                fields: [],
                editId: null
            }
        },
        mounted() {
            this.setCottage();

        },
        methods: {
            showModal(id = null) {
                this.editId = id;
                this.$bvModal.show("cottage-form-modal");
            },
            show (id) {
                this.editId = id;
                this.$bvModal.show('delete-form');
            },
            setCottages() {
                var self = this;
                cottageService.getCottages().then(function (response) {
                        self.Cottages = response;
                    }
                )
                    .catch(function (error) {
                        if (error) {
                            self.errorNotify = error;
                            self.loading = false;
                        }
                    });
            },
            deleteUser(id) {
                var self = this;
                cottageService.deleteUser(id).then(function () {
                       self.setCottages();
                    }
                )
                    .catch(function (error) {
                        if (error) {
                            self.errorNotify = error;
                            self.loading = false;
                        }
                    });

                this.$bvModal.hide('delete-form');
            }
        },
        computed: {
            rows() {
                return this.cottages.length
            },
        
        }
    }
    export {CottageForm}
</script>
<style scoped>
    .cottages {
        width: 100%;
        height: 100%;
        max-width: 50vw;
    }

    .table-wrap {
        margin-top: 1rem;
    }

    .table-cottages {
        width: 100%;
        max-width: 40vw;
        text-align: left;
    }

    .table-head-cottages {
        text-transform: uppercase;
    }

    .table-btn {
        width: 15px;
        height: 15px;
    }

    .hide {
        width: 0px;
        height: 0px;
        padding: 0;
        margin: 0;
        display: none;
    }

    .icon {
        width: 20px;
        height:  20px;
        padding: 0px;
        margin: 0 auto;
    }

</style>