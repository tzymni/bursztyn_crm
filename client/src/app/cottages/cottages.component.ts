import {Component, OnInit, ViewChild} from '@angular/core';
import {Router} from '@angular/router';
//import {UsersService} from './service/users.service';
import {NgbActiveModal, NgbModal, NgbModalOptions} from '@ng-bootstrap/ng-bootstrap';
import {CottageComponent} from '../cottage/cottage.component';
import {NgxTuiCalendarComponent} from 'ngx-tui-calendar';
import {CottagesService} from '../cottages/service/cottages.service';

@Component({
    selector: 'app-cottages',
    templateUrl: './cottages.component.html',
    styleUrls: ['./cottages.component.css']
})
export class CottagesComponent implements OnInit {

    constructor(
        private _cottagesService: CottagesService,
        private _router: Router,
        private modalService: NgbModal
    ) {}
    cottages: Array<any> = [];
    errorMessage: any;
    id: any;
    
    
    getCottages() {
        
        
        this._cottagesService.getCottages().subscribe(
            data => this.cottages = data,
            error => this.errorMessage = error
        )
        
    }



    ngOnInit() {
        this.getCottages();
       
    }


    add() {
        this.modalService.open(CottageComponent).result.then((data) => {
            this.refreshData();
        });
    }

    edit(id) {
        const modal = this.modalService.open(CottageComponent, {size: 'lg'});
        modal.componentInstance.id = id;

        modal.result.then((data) => {
            this.refreshData();
        });

    }


    refreshData() {
        this.getCottages();
    }

}
