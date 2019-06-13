import {Injectable} from '@angular/core';
import {Http, Response} from '@angular/http';
import {Observable} from 'rxjs';
import {catchError, map} from 'rxjs/operators';

import {throwError as _throw} from 'rxjs';

@Injectable()
export class ItemServiceService {

    baseUrl: string = 'http://127.0.0.1:8000/'

    constructor(private _http: Http) {}


    getCustomers() {
        // pack in "pipe()"
        return this._http.get(this.baseUrl + 'items'
        ).pipe(
            // eg. "map" without a dot before
            map(data => {
                return data.json();
            }),
            // "catchError" instead "catch"
            catchError(error => {
                return Observable.throw('Something went wrong ;)');
            })
        );
    }

    getCustomerById(id) {
        return this._http.get(this.baseUrl + "api/user/" + id).pipe(
            map((response: Response) => response.json()),
            catchError(this._errorHandler))
    }



    saveCustomer(customer) {
        return this._http.post(this.baseUrl + 'item/', customer).pipe(
            map((response: Response) => response.json()),
            catchError(this._errorHandler)
        
        )
    }

    _errorHandler(error: Response) {
        debugger;
        return _throw(error || "Internal server error");
    }
}
