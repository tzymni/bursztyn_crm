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


interface Event {
    id: number;
    title: string;
    start_date: string;
    end_date: string;
    color: string;

}

function getTimezoneOffsetString(date: Date): string {
    const timezoneOffset = date.getTimezoneOffset();
    const hoursOffset = String(
        Math.floor(Math.abs(timezoneOffset / 60))
    ).padStart(2, '0');
    const minutesOffset = String(Math.abs(timezoneOffset % 60)).padEnd(2, '0');
    const direction = timezoneOffset > 0 ? '-' : '+';

    return `T00:00:00${direction}${hoursOffset}:${minutesOffset}`;
}

@Injectable({
    providedIn: 'root'
})


export class CalendarService {

    viewDate: Date = new Date();

    constructor(private _http: HttpClient,
        private _rest: RestService
    ) {}
    
    getEvents(): Observable<any>  {
        var url = this._rest.baseUrl+'api/events';

        return this._http
            .get(url, {headers: this._rest.headers})
            .pipe(
                map(({results}: {results: Event[]}) => {
                    return results.map((event: Event) => {
                        return {
                            title: event.title,
                            start: new Date(
                                event.start_date + getTimezoneOffsetString(this.viewDate)
                            ),
                            end: new Date(
                                event.end_date + getTimezoneOffsetString(this.viewDate)
                            ),
                            color: {
                                primary: event.color,
                                secondary: event.color
                            },
                            allDay: true,
                            meta: {
                                event
                            }
                        };
                    });
                })
            );
    }

}
