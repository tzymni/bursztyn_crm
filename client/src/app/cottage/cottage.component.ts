import {Component, OnInit} from '@angular/core';
import {NgbActiveModal, NgbModal} from '@ng-bootstrap/ng-bootstrap';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {ActivatedRoute} from '@angular/router';
import {CottagesService} from '../cottages/service/cottages.service';
import {Router} from '@angular/router';
import {NotificationsService} from '../_notifications/notifications.service';

@Component({
    selector: 'app-cottage',
    templateUrl: './cottage.component.html',
})
export class CottageComponent implements OnInit {

    cottageForm: FormGroup;
    title: string = "Dodaj domek";
    id: number = 0;
    errorMessage: any;



    constructor(
        public activeModal: NgbActiveModal,
        private _fb: FormBuilder,
        private _avRoute: ActivatedRoute,
        //        private _customerService: ItemServiceService,
        private _cottagesService: CottagesService,
        private _router: Router,
        private notification: NotificationsService,
    ) {

        if (this._avRoute.snapshot.params["id"]) {
            this.id = (this._avRoute.snapshot.params["id"]);
            console.log(this.id);
        }

        this.cottageForm = this._fb.group({
            name: ['', [Validators.required,
            Validators.minLength(3),
            Validators.maxLength(30)]],
            color: ['', [Validators.required]],
            extra_info: ['', [Validators.required]]
        })

    }


    test(event) {
        console.log(event);
    }

    save() {
        console.log("Zapis");
        console.log(this.cottageForm.value);


        if (!this.cottageForm.valid) {
            return;
        }

        if (this.id) {
            console.log('update');
            //            var method = this._userService.updateUser(this.customerForm.value, this.id);
        }
        else {
            console.log('cos nwoego');
            console.log(this.cottageForm.value);
            var method = this._cottagesService.saveCottage(this.cottageForm.value);

            method
                .subscribe(response => {
                    this.notification.notifier.notify('success', 'Zapisano!');
                    this.activeModal.close();
                    this._router.navigate(['users'], {skipLocationChange: true});


                }, error => {
                    this.notification.notifier.notify('error', error);
                })

        }


    }

    cancel() {
        this._router.navigate(['home']);
    }



    ngOnInit() {
    }

}
