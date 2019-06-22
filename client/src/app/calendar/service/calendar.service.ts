import {Injectable} from '@angular/core';
import {Observable} from 'rxjs';
import {catchError, map} from 'rxjs/operators';
import {throwError as _throw} from 'rxjs';
import {HttpClient, HttpErrorResponse, HttpHeaders} from '@angular/common/http';
import {RestService} from '../../_rest/rest.service';
import {
  CalendarEvent,
  CalendarEventAction,
  CalendarEventTimesChangedEvent,
  CalendarView
} from 'angular-calendar';


interface Film {
  id: number;
  title: string;
  start_date: string;
}
const colors: any = {
  red: {
    primary: '#ad2121',
    secondary: '#FAE3E3'
  },
  blue: {
    primary: '#1e90ff',
    secondary: '#D1E8FF'
  },
  yellow: {
    primary: '#e3bc08',
    secondary: '#FDF1BA'
  }
};
@Injectable({
    providedIn: 'root'
})


export class CalendarService {



    constructor(private _http: HttpClient,
        private _rest: RestService
    ) {}


    getOnlyEvents(): Observable<any> {
        var get = this._rest.getMethod('api/events', null);
        return get;
    }
    getEvents(): Observable<any>  {

        this._rest.baseUrl

//        var get = this._rest.getMethod('api/events', null);
        
        var url = 'api/events';
        var  data = null;
        var parameters = (typeof data == 'undefined' || data == null) ? '' : '/' + data;
        
        var get = this._http.get(this._rest.baseUrl + url + parameters, {headers: this._rest.headers}
        ).      pipe(
        map(({ data }: { data: Film[] }) => {
          return data.map((film: Film) => {
            return {
              title: film.title,
              start: new Date(
                film.start_date 
              ),
              color: colors.yellow,
              allDay: true,
              meta: {
                film
              }
            };
          })}));
        
          
          console.log(get);
        
//        var get = [{
//              title: "Domek",
//              start: new Date(
//                "22-06.2019"
//              ),
//              color: "#fff",
//              allDay: true,
//
//        }];
        
        return get;
    }

}
