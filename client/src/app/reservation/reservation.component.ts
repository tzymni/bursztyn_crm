import {Component, OnInit} from '@angular/core';
import {NgbActiveModal, NgbModal} from '@ng-bootstrap/ng-bootstrap';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {ActivatedRoute} from '@angular/router';
import {CalendarService} from '../calendar/service/calendar.service';
import {Router} from '@angular/router';
import {NotificationsService} from '../_notifications/notifications.service';
import {CottagesService} from '../cottages/service/cottages.service';


@Component({
    selector: 'app-reservation',
    templateUrl: './reservation.component.html',
})
export class ReservationComponent implements OnInit {

    reservationForm: FormGroup;
    title: string = "Dodaj rezerwacje";
    id: number = 0;
    errorMessage: any;
    cottages: Array<any> = [];
    
    constructor(
        public activeModal: NgbActiveModal,
        private _fb: FormBuilder,
        private _avRoute: ActivatedRoute,
        private _calendarService: CalendarService,
        private _router: Router,
        private notification: NotificationsService,
                private _cottagesService: CottagesService,

    ) {
        this.reservationForm = this._fb.group({
            tourist_name: ['', [Validators.required,
            Validators.minLength(3),
            Validators.maxLength(40)]],
            date_from: ['', [Validators.required]],
            date_to: ['', [Validators.required]],
            tourist_num: ['', [Validators.required]],
            cottage_id: ['', [Validators.required]],
            id: ['', []]
        })


    }
    
        getCottages() {
        
        
        this._cottagesService.getCottages().subscribe(
            data => this.cottages = data,
            error => this.errorMessage = error
        )
        
    }
    
        save() {

console.log(this.reservationForm);
        if (!this.reservationForm.valid) {
            return;
        }





    }
    

    ngOnInit() {
        
        this.getCottages();
    }
    
    
    cancel() {
        this._router.navigate(['home']);
    }
}
