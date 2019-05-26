import {Component, OnInit} from '@angular/core';
import {ItemServiceService} from './service/item-service.service';
import {Router} from '@angular/router';
import {NotificationsService} from '../_notifications/notifications.service';

@Component({
    selector: 'app-items-list',
    templateUrl: './items-list.component.html',
    styleUrls: ['./items-list.component.css']
})
export class ItemsListComponent implements OnInit {

    constructor(private _itemService: ItemServiceService,
                 private _router: Router
                 
                 ) {}

    customers: Array<any> = [];
    errorMessage: any;

    add() {
        this._router.navigate(['customers/add']);
    }
    edit(id) {
        this._router.navigate(['customers/edit/' + id])
    }

    getCustomers() {
        this._itemService.getCustomers().subscribe(
            data => this.customers = data,
            error => this.errorMessage = error
        )
    }
    // call this function in ngOnInit to call on page loading
    ngOnInit() {
        this.getCustomers()
    }

}
