import {Injectable} from '@angular/core';
import {Observable} from 'rxjs';
import {catchError, map} from 'rxjs/operators';
import {throwError as _throw} from 'rxjs';
import {HttpClient, HttpErrorResponse, HttpHeaders} from '@angular/common/http';
import {RestService} from '../../_rest/rest.service';

@Injectable({
    providedIn: 'root'
})

export class CottagesService {

    constructor(private _http: HttpClient,
        private _rest: RestService
    ) {}

    getCottages(): Observable<any> {
        var get = this._rest.getMethod('api/users', null);
        return get;
    }

    getCottage(email): Observable<any> {
        var get = this._rest.getMethod('api/user', email);
        return get;
    }



    errorHandler(error: HttpErrorResponse) {
        return Observable.throw(error.message || "Server Error");
    }


    deleteCottage(email): Observable<any> {
        var del = this._rest.deleteMethod('api/user', email);
        return del;
    };



    updateCottage(customer, email): Observable<any> {
        var save = this._rest.putMethod('api/user/' + email, customer);
        return save;
    }


    saveCottage(cottage): Observable<any> {

        var save = this._rest.postMethod('cottage/new', cottage);
        return save;

    }
}
