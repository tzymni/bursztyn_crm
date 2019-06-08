import {Component, OnInit, ViewChild} from '@angular/core';
import {Router} from '@angular/router';
//import {UsersService} from './service/users.service';
import {NgbActiveModal, NgbModal, NgbModalOptions} from '@ng-bootstrap/ng-bootstrap';
import {CottageComponent} from '../cottage/cottage.component';
import { NgxTuiCalendarComponent } from 'ngx-tui-calendar';
import {UserComponent} from '../user/user.component';
@Component({
  selector: 'app-cottages',
  templateUrl: './cottages.component.html',
  styleUrls: ['./cottages.component.css']
})
export class CottagesComponent implements OnInit {

  constructor(
          private _router: Router,
        private modalService: NgbModal
  ) { }

  ngOnInit() {
  }


    add() {
        this.modalService.open(CottageComponent).result.then((data) => {
            this.refreshData();
        });
    }
    
    
        refreshData() {
        console.log("blb");
    }

}
