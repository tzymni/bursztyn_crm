import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {ActivatedRoute} from '@angular/router';
import {ItemServiceService} from '../items-list/service/item-service.service';
import { Router } from '@angular/router';


@Component({
  templateUrl: './item.component.html',
  styleUrls: ['./item.component.css']
})
export class ItemComponent implements OnInit {
    customerForm: FormGroup;
    title: string = "Add";
    id: number = 0;
    errorMessage: any;
    
    
    constructor(private _fb: FormBuilder,
        private _avRoute: ActivatedRoute,
        private _customerService: ItemServiceService,
        private _router: Router
    ) {
        // Check if id is passed and read it
        if (this._avRoute.snapshot.params["id"]) {
            this.id = parseInt(this._avRoute.snapshot.params["id"]);
        }


        this.customerForm = this._fb.group({
            id: 0,
            name: ['', [Validators.required,
            Validators.minLength(3),
            Validators.maxLength(30)]],
            amount: ['', [Validators.required]],
        })
    }

    get name() {return this.customerForm.get('name');}
    // similarly for address and phone
    get amount() {return this.customerForm.get('amount');}


cancel() {
    this._router.navigate(['home']);
}

    
        save() {
//        this.submitted = true;
        if (!this.customerForm.valid) {
            return;
        }

        this._customerService.saveCustomer(this.customerForm.value)
            .subscribe(custId => {
                //alert('Saved Successfully!')
                this._router.navigate(['customers', {id: custId}]);
            }, error => this.errorMessage = error)
    }
    
    
    
    // we will use it to load data for update
    ngOnInit() {
        if (this.id > 0) {
            this.title = 'Edit';
            this._customerService.getCustomerById(this.id)
                .subscribe(resp => this.customerForm.setValue(resp)
                    , error => this.errorMessage = error);
        }
    }
}
