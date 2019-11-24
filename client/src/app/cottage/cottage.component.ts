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
        private _cottagesService: CottagesService,
        private _router: Router,
        private notification: NotificationsService,
    ) {

        if (this._avRoute.snapshot.params["id"]) {
            this.id = (this._avRoute.snapshot.params["id"]);
        }

        this.cottageForm = this._fb.group({
            name: ['', [Validators.required,
            Validators.minLength(3),
            Validators.maxLength(30)]],
            color: ['', [Validators.required]],
            extra_info: ['', [Validators.required]],
            id: ['', []]
        })

    }


    save() {
    console.log("TES");

        if (!this.cottageForm.valid) {
            return;
        }

        if (this.id) {
            var method = this._cottagesService.updateCottage(this.cottageForm.value, this.id);
        }
        else {


            var method = this._cottagesService.saveCottage(this.cottageForm.value);





        }

        method
            .subscribe(response => {
                console.log("zas");
                this.notification.notifier.notify('success', 'Zapisano!');
                this.activeModal.close();
                this._router.navigate(['cottages'], {skipLocationChange: true});


            }, error => {
                this.notification.notifier.notify('error', error);
            })


    }

    cancel() {
        this._router.navigate(['home']);
    }



    ngOnInit() {
        if (this.id) {
            this.title = 'Edytuj domek ' + this.id;
            this._cottagesService.getCottage(this.id)
                .subscribe(resp => this.cottageForm.setValue(resp)
                    , error => this.errorMessage = error);
        }
    }

}
