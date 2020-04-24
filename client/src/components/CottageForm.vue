<template>
    <div>
        <form @submit.prevent="handleSubmit">
            <div class="form-group">
                <label for="name">Name</label>
                <input placeholder="Enter cottages' name" type="text" v-model="name" name="name"
                       class="form-control sm"
                       :class="{ 'is-invalid': submitted && !name }"/>
                <div v-show="submitted && !name" class="invalid-feedback">Cottages' name is required</div>
            </div>
            <div class="form-group">
                <label for="max_guests_number">Capacity</label>
                <b-form-select v-model="max_guests_number" :options="options" size="sm" class="mt-3" :class="{ 'is-invalid': submitted && !max_guests_number }"></b-form-select>
            </div>
            <div class="form-group">
                <label htmlFor="color">Colour</label>
                <compact-picker v-model="color" :value="color" @input="updateValue" name="color" />
            </div>
            <div class="form-group">
                <label htmlFor="extra_info">Other Information</label>
                <b-form-textarea
                id="textarea"
                v-model="extra_info"
                placeholder="Enter something..."
                rows="3"
                max-rows="6"
                class="form-control"
                :class="{ 'is-invalid': submitted && !extra_info }"
                ></b-form-textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" :disabled="loading">Save and close</button>
                <img v-show="loading"
                     src="data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA=="/>
            </div>
            <div v-if="errorNotify" class="alert alert-danger">{{errorNotify}}</div>
        </form>
    </div>
</template>

<script>
    import {cottageService} from "../_services/cottage.service";
    import {Compact} from 'vue-color'
    export default {
        name: "cottageForm",
        components: {
            'compact-picker': Compact,
        },
        data() {
            return {
                childMessage: '',
                id: null,
                name: "",
                max_guests_number: null,
                color: "",
                extra_info: "",
                submitted: false,
                returnUrl: "",
                errorNotify: "",
                loading: false,
                options: [
                    { value: null, text: 'Please select an option' },
                    { value: 1, text: '1 person' },
                    { value: 2, text: '2 people' },
                    { value: 3, text: '3 people' },
                    { value: 4, text: '4 people' },
                    { value: 5, text: '5 person' },
                    { value: 6, text: '6 people' },
                    { value: 7, text: '7 people' },
                    { value: 8, text: '8 people' },
                    { value: 9, text: '9 people' },
                    { value: 10, text: '10 people' },
                ],
            };
        },
        mounted() {
            if (typeof this.editId != 'undefined' && this.editId != null) {
                this.getCottageById(this.editId);
            }
        },
        props: {
            editId: Number,
        },
        methods: {
            handleSubmit() {
                this.submitted = true;
                let id = this.id;
                const {name, max_guests_number, color, extra_info} = this;

                // stop here if form is invalid
                if (!(name && color) && !id) {
                    return;
                }

                const data = {name, max_guests_number, color, extra_info, id};

                var self = this;

                cottageService.saveCottage(data).then(function () {
                        self.$bvModal.hide("cottage-form-modal");
                    }
                )
                    .catch(function (error) {
                        if (error) {
                            self.errorNotify = error;
                        }
                    });
            },
            getCottageById(id) {

                var self = this;
                cottageService.getCottage(id).then(function (data) {

                        self.name = data.name;
                        self.max_guests_number = data.max_guests_number;
                        self.color = data.color;
                        self.extra_info = data.extra_info;
                        self.id = data.id;
                    }
                )
                    .catch(function (error) {
                        if (error) {
                            self.errorNotify = error;
                        }
                    });

            },
            updateValue(value) {
                this.color = value.hex
            }

        }
    }
</script>

<style scoped>

</style>