<template>
    <div>
        <form @submit.prevent="handleSubmit">

            <div class="form-group">
                <label for="date_from">Date from</label>
                <input type="date" v-model="date_from" name="date_from"
                       class="form-control"
                       :class="{ 'is-invalid': submitted && !date_from }"/>
                <div v-show="submitted && !date_from" class="invalid-feedback">Date from is required</div>
            </div>
            <div class="form-group">
                <label for="date_to">Date to</label>
                <input type="date" v-model="date_to" name="date_from"
                       class="form-control"
                       :class="{ 'is-invalid': submitted && !date_to }"/>
                <div v-show="submitted && !date_to" class="invalid-feedback">Date to is required</div>
            </div>
            <div class="form-group">
                <label for="cottage_id">Cottage</label>
                <b-form-select v-model="cottage_id" :options="options"
                               class="form-control"
                               :class="{ 'is-invalid': submitted && !cottage_id }"/>
                <div v-show="submitted && !cottage_id" class="invalid-feedback">Cottage is required</div>
            </div>
            <div class="form-group">
                <label for="guest_first_name">Guest first name</label>
                <input type="text" v-model="guest_first_name" name="guest_first_name"
                       class="form-control"
                       :class="{ 'is-invalid': submitted && !guest_first_name }"/>
                <div v-show="submitted && !guest_first_name" class="invalid-feedback">Guest first name is required</div>
            </div>
            <div class="form-group">
                <label for="guest_last_name">Guest last name</label>
                <input type="text" v-model="guest_last_name" name="guest_last_name"
                       class="form-control"
                       :class="{ 'is-invalid': submitted && !guest_last_name }"/>
                <div v-show="submitted && !guest_last_name" class="invalid-feedback">Guest last name is required</div>
            </div>
            <div class="form-group">
                <label for="guest_phone_number">Guest phone number</label>
                <input type="text" v-model="guest_phone_number" name="guest_first_name"
                       class="form-control"
                       :class="{ 'is-invalid': submitted && !guest_phone_number }"/>
                <div v-show="submitted && !guest_phone_number" class="invalid-feedback">Guest phone number is required
                </div>
            </div>
            <div class="form-group">
                <label for="guests_number">Number of guests</label>
                <input type="number" min="1" value="1" max="10" v-model="guests_number" name="guests_number"
                       class="form-control"
                       :class="{ 'is-invalid': submitted && !guests_number }"/>
            </div>
            <div class="form-group">
                <label for="advance_payment">Advance payment?</label>
                <input type="checkbox" v-model="advance_payment" name="advance_payment"
                       class="form-control"
                       :class="{ 'is-invalid': submitted && !advance_payment }"/>
            </div>
            <div class="form-group">
                <label for="extra_info">Extra info</label>
                <textarea v-model="extra_info" name="extra_info"
                          class="form-control"></textarea>
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
    import {reservationService} from "../_services/reservation.service";

    export default {
        name: "ReservationForm",
        data() {
            return {
                // childMessage: '',
                id: null,
                date_from: "",
                date_to: "",
                cottage_id: null,
                guest_first_name: "",
                guest_last_name: "",
                guest_phone_number: "",
                guests_number: 1,
                submitted: false,
                extra_info: null,
                advance_payment: false,
                returnUrl: "",
                errorNotify: "",
                loading: false,
                selected: null,
                options: []
            };
        },
        mounted() {
            if (typeof this.editId != 'undefined' && this.editId != null) {
                this.getCottageById(this.editId);
            }

            if (typeof this.clickedStartDate != 'undefined' && this.clickedStartDate != null) {

                this.date_from = this.formatClickedDate();
            }
            this.getCottages();
        },
        props: {
            editId: Number,
            clickedStartDate: String,
        },
        methods: {

            formatClickedDate() {
                let changedDate = this.clickedStartDate.replace(/\./g, '-');
                var parts = changedDate.split('-');
                let newDate = parts[2] + '-' + parts[1] + '-' + ('0' + parts[0]).slice(-2);
                return newDate;
            },

            handleSubmit() {
                this.submitted = true;
                let id = this.id;
                const {date_from, date_to, cottage_id, guest_first_name, guest_last_name, guest_phone_number, guests_number, extra_info, advance_payment} = this;

                // stop here if form is invalid
                if (!(date_from && date_to && cottage_id) && !id) {
                    return;
                }

                const data = {
                    date_from,
                    date_to,
                    cottage_id,
                    guest_first_name,
                    guest_last_name,
                    guest_phone_number,
                    guests_number,
                    extra_info,
                    advance_payment,
                    id
                };

                var self = this;

                reservationService.saveReservation(data).then(function () {
                        self.$bvModal.hide("cottage-form-modal");
                    }
                )
                    .catch(function (error) {
                        if (error) {
                            self.errorNotify = error;
                        }
                    });
            },
            getCottages() {
                var self = this;
                cottageService.getCottages().then(function (response) {
                        let list = [];
                        response.map(function (value) {
                            list.push({value: value.id, text: value.name})
                        });
                        self.options = list;
                    }
                ).catch(function (error) {
                    if (error) {
                        self.errorNotify = error;
                        self.loading = false;
                    }
                });

            },

        }
    }
</script>


