import {Component, ChangeDetectionStrategy, OnInit} from '@angular/core';
import {HttpClient, HttpParams} from '@angular/common/http';
import {map} from 'rxjs/operators';
import {CalendarEvent, CalendarView} from 'angular-calendar';
import {
    isSameMonth,
    isSameDay,
    startOfMonth,
    endOfMonth,
    startOfWeek,
    endOfWeek,
    startOfDay,
    endOfDay,
    addDays,
    format
} from 'date-fns';
import {Observable} from 'rxjs';
import {CalendarService} from '../calendar/service/calendar.service';


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

@Component({
    selector: 'mwl-demo-component',
    changeDetection: ChangeDetectionStrategy.OnPush,
    templateUrl: './calendar.component.html',
    styleUrls: ['./calendar.component.css']
})
export class CalendarComponent implements OnInit {
    locale: string = 'pl';
    viewDate: Date = new Date();
    view: CalendarView = CalendarView.Month;

    CalendarView = CalendarView;
    events$: Observable<Array<CalendarEvent<{event: Event}>>>;

    activeDayIsOpen: boolean = false;

    constructor(private http: HttpClient,
    private _calendarService: CalendarService
    ) {}

    ngOnInit(): void {
        this.fetchEvents();
    }

    fetchEvents(): void {
        

        this.events$ = this._calendarService.getEvents();

    }

    dayClicked({
        date,
        events
    }: {
            date: Date;
            events: Array<CalendarEvent<{event: Event}>>;
        }): void {
        if (isSameMonth(date, this.viewDate)) {
            if (
                (isSameDay(this.viewDate, date) && this.activeDayIsOpen === true) ||
                events.length === 0
            ) {
                this.activeDayIsOpen = false;
            } else {
                this.activeDayIsOpen = true;
                this.viewDate = date;
            }
        }
    }

    eventClicked(event: CalendarEvent<{event: Event}>): void {
        
        alert("Klikniety " + event.meta.event.title);
//        window.open(
//            `https://www.themoviedb.org/movie/${event.meta.event.id}`,
//            '_blank'
//        );
    }


    setView(view: CalendarView) {
        this.view = view;
    }

    closeOpenMonthViewDay() {
        this.activeDayIsOpen = false;
    }
}