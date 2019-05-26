import {Component, OnInit, ViewChild} from '@angular/core';
import {Router} from '@angular/router';
import {UsersService} from './service/users.service';
import {NgbActiveModal, NgbModal, NgbModalOptions} from '@ng-bootstrap/ng-bootstrap';
import {UserComponent} from '../user/user.component';
import { NgxTuiCalendarComponent } from 'ngx-tui-calendar';
@Component({
    selector: 'app-users',
    templateUrl: './users.component.html',
})


export class UsersComponent implements OnInit {
@ViewChild('exampleCalendar') exampleCalendar: NgxTuiCalendarComponent;
    constructor(private _usersService: UsersService,
        private _router: Router,
        private modalService: NgbModal

    ) {}
    users: Array<any> = [];
    errorMessage: any;
  id : any;
    getUsers() {
        this._usersService.getUsers().subscribe(
            data => this.users = data,
            error => this.errorMessage = error
        )
    }





    add() {
        
        
        
        this.modalService.open(UserComponent).result.then((data) => {
            this.refreshData();
        });
    }
    
    
    edit(email) {

        console.log(email);
        
        
        const modal =this.modalService.open(UserComponent, { size: 'lg' } ) ;
modal.componentInstance.id = email;
        
        modal.result.then((data) => {
            this.refreshData();
        });
        
    }

    delete(email) {

        this._usersService.deleteUser(email).subscribe(
            data => {
                this.refreshData();
                
            },
            error => this.errorMessage = error

        )
    }

    refreshData() {
        this.getUsers();
    }


    ngOnInit() {
        this.getUsers();
    }

}
