import {Component, OnInit} from '@angular/core';
import {NgbActiveModal, NgbModal} from '@ng-bootstrap/ng-bootstrap';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {ActivatedRoute} from '@angular/router';
import {UsersService} from '../users/service/users.service';
import {Router} from '@angular/router';
import {NotificationsService} from '../_notifications/notifications.service';
@Component({
    selector: 'app-user',
    templateUrl: './user.component.html',
    styleUrls: ['./user.component.css']
})
export class UserComponent {
    customerForm: FormGroup;
    title: string = "Dodaj użytkownika";
    id: number = 0;
    errorMessage: any;

    constructor(
        public activeModal: NgbActiveModal,
        private _fb: FormBuilder,
        private _avRoute: ActivatedRoute,
        private _userService: UsersService,
        private _router: Router,
        private notification: NotificationsService,
    ) {
        // Check if id is passed and read it
        if (this._avRoute.snapshot.params["id"]) {
            this.id = (this._avRoute.snapshot.params["id"]);
            console.log(this.id);
        }

        this.customerForm = this._fb.group({
            email: ['', [Validators.required,
            Validators.minLength(3),
            Validators.maxLength(30)]],
            password: ['', [Validators.required]],
            first_name: ['', [Validators.required]],
            last_name: ['', [Validators.required]],
        })


    }



    get email() {
        return this.customerForm.get('email');
    }
    // similarly for address and phone
    get password() {return this.customerForm.get('password');}


    cancel() {
        this._router.navigate(['home']);
    }


    save() {
        //        this.submitted = true;
        if (!this.customerForm.valid) {
            return;
        }

        if (this.id) {
            console.log('update');
            var method = this._userService.updateUser(this.customerForm.value, this.id);
        }
        else {
            console.log('cos nwoego');
            var method = this._userService.saveUser(this.customerForm.value);
        }

        method
            .subscribe(response => {
                this.notification.notifier.notify('success', 'Zapisano!');
                this.activeModal.close();
                this._router.navigate(['users'], {skipLocationChange: true});


            }, error => {
                this.notification.notifier.notify('error', error);
            })
    }



    // we will use it to load data for update
    ngOnInit() {
        if (this.id) {
            this.title = 'Edytuj użytkownika';
            this._userService.getUser(this.id)
                .subscribe(resp => this.customerForm.setValue(resp)
                    , error => this.errorMessage = error);
        }
    }

}
