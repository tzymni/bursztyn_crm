import {Component, OnInit} from '@angular/core';
import {NgbActiveModal, NgbModal} from '@ng-bootstrap/ng-bootstrap';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {ActivatedRoute} from '@angular/router';
import {ItemServiceService} from '../items-list/service/item-service.service';
//import {CottagesService} from '../cottages/service/cottages.service';
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
        //        private _userService: UsersService,
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



    save() {
        console.log("Zapis");
    }

    cancel() {
        this._router.navigate(['home']);
    }



    ngOnInit() {
    }

}
