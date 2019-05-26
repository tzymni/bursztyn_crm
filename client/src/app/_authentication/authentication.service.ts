import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {Http, Response} from '@angular/http';
import {Observable} from 'rxjs';
import {catchError, map} from 'rxjs/operators';

import {throwError as _throw} from 'rxjs';

export class User {
    constructor(
        public username: string,
        public password: string) {}
}

@Injectable()
export class AuthenticationService {

    baseUrl: string = 'http://127.0.0.1:8000/'


    constructor(private http: HttpClient) {}

    login(username: string, password: string) {


        var user = new User(username, password);
        let headers = new HttpHeaders();
        headers = headers.append("Authorization", "Basic " + btoa(username + ":" + password));
        headers = headers.append("Content-Type", "application/x-www-form-urlencoded")


        let formdata = new FormData();
        formdata.append('username', username);
        formdata.append('password', password);

        return this.http.post<any>(this.baseUrl + 'api/authenticate', formdata, {headers: headers}).pipe(
            map(user => {

                // login successful if there's a user in the response
                if (user) {

                    var token = user.data.token;

                    if (token) {
                        // store user details and jwt token in local storage to keep user logged in between page refreshes
                        localStorage.setItem('token', token);
                        return user;
                    }



                }
                else {
                    return 'gowno';
                }

            }
            ),
            catchError((error) => {
                // it's important that we log an error here.
                // Otherwise you won't see an error in the console.
                console.error('error loading the list of users', error);
                console.log(error);
                return _throw(error);
            })


        )

    }

    logout() {
        // remove user from local storage to log user out
        localStorage.removeItem('token');
    }
}