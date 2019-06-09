import {Injectable} from '@angular/core';
import {Observable} from 'rxjs';
import {catchError, map} from 'rxjs/operators';
import {throwError as _throw} from 'rxjs';
import {HttpClient, HttpErrorResponse, HttpHeaders, HttpParams} from '@angular/common/http';

@Injectable({
    providedIn: 'root'
})
export class RestService {

    private headers;
    private baseUrl = 'http://127.0.0.1:8000/';

    constructor(private _http: HttpClient) {

        var token = sessionStorage.getItem('token');

        this.headers = new HttpHeaders({
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        })



    }


    getMethod(url: string, data) {


        var parameters = (typeof data == 'undefined' || data == null) ? '' : '/' + data;

        return this._http.get(this.baseUrl + url + parameters, {headers: this.headers}
        ).pipe(
            // eg. "map" without a dot before
            map(data => {
                return data;
            }),
            // "catchError" instead "catch"
            catchError(error => {
                return _throw('Blad');
            })
        );
    }



    postMethod(url: string, data) {



        return this._http.post(this.baseUrl + url, data, {headers: this.headers}).pipe(
            map((response: Response) => {
                return response;

            }),
            catchError(error => {
                return _throw(error.error.error.message);
            })

        )


    }

    putMethod(url: string, data) {


        return this._http.put(this.baseUrl + url, data, {headers: this.headers}).pipe(
            map((response: Response) => {
                return response;

            }),
            catchError(error => {
                return _throw(error.error.error.message);
            })

        )

    }


    deleteMethod(url: string, data) {


        let params = new HttpParams();
        params.set('email', data);

        return this._http.delete(this.baseUrl + url + '/' + data, {headers: this.headers}
        ).pipe(
            // eg. "map" without a dot before
            map(data => {
                return data;
            }),
            // "catchError" instead "catch"
            catchError(error => {
                return _throw('Blad');
            })
        );

    }

}
