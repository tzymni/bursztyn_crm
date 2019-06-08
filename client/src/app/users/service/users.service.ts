import {Injectable} from '@angular/core';
import {Observable} from 'rxjs';
import {catchError, map} from 'rxjs/operators';
import {throwError as _throw} from 'rxjs';
import {HttpClient, HttpErrorResponse, HttpHeaders} from '@angular/common/http';
import {RestService} from '../../_rest/rest.service';

@Injectable({
    providedIn: 'root'
})




export class UsersService {

    private extractData(res: Response) {
        let body = res;
        return body || {};
    }

    constructor(private _http: HttpClient,
        private _rest: RestService
    ) {}

    getUsers(): Observable<any> {
        var get = this._rest.getMethod('api/users', null);
        return get;
    }

    getUser(email): Observable<any> {
        var get = this._rest.getMethod('api/user', email);
        return get;
    }



    errorHandler(error: HttpErrorResponse) {
        return Observable.throw(error.message || "Server Error");
    }


    deleteUser(email): Observable<any> {
        var del = this._rest.deleteMethod('api/user', email);
        return del;
    };



    updateUser(customer, email): Observable<any> {
        var save = this._rest.putMethod('api/user/' + email, customer);
        return save;
    }


    saveUser(customer): Observable<any> {

        var save = this._rest.postMethod('users/create', customer);
        return save;

    }


}

export interface User {
    email: string;
    password: string;
}
