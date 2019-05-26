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

    baseUrl: string = 'http://127.0.0.1:8000/'

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



    //    
    //    getCustomerById(id) {
    //        return this._http.get(this.baseUrl + "item/" + id).pipe(
    //            map((response: Response) => response.json()),
    //            catchError(this._errorHandler))
    //    }



    deleteUser(email): Observable<any> {


        var del = this._rest.deleteMethod('api/user', email);

        return del;

    };



    updateUser(customer, email): Observable<any> {
        var save = this._rest.putMethod('api/user/'+email, customer);
        return save;
    }
    
    
    saveUser(customer): Observable<any> {

        var save = this._rest.postMethod('users/create', customer);
        return save;

        //        var token = localStorage.getItem('token');
        //
        //        const httpOptions = {
        //            headers: new HttpHeaders({
        //                'Content-Type': 'application/json',
        //                'Authorization': 'Bearer ' + token
        //            })
        //        };
        //
        //        return this._http.post(this.baseUrl + 'users/create', customer, httpOptions).pipe(
        //            map((response: Response) => 
        //            {
        //                return response;
        //            
        //            }),
        //            catchError(error => {
        //                return _throw(error.error.error.message);
        //            })
        //
        //        )
    }


}

export interface User {
    email: string;
    password: string;
}
