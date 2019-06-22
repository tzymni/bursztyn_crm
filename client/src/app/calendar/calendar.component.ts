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

    constructor(private http: HttpClient) {}

    ngOnInit(): void {
        this.fetchEvents();
    }

    fetchEvents(): void {
        const getStart: any = {
            month: startOfMonth,
            week: startOfWeek,
            day: startOfDay
        }[this.view];

        const getEnd: any = {
            month: endOfMonth,
            week: endOfWeek,
            day: endOfDay
        }[this.view];

        const params = new HttpParams()
            .set(
                'primary_release_date.gte',
                format(getStart(this.viewDate), 'YYYY-MM-DD')
            )
            .set(
                'primary_release_date.lte',
                format(getEnd(this.viewDate), 'YYYY-MM-DD')
            )
            .set('api_key', '0ec33936a68018857d727958dca1424f');


        var url = 'https://api.themoviedb.org/3/discover/movie';

        var url = 'http://127.0.0.1:8000/api/events';

        this.events$ = this.http
            .get(url, {params})
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
        window.open(
            `https://www.themoviedb.org/movie/${event.meta.event.id}`,
            '_blank'
        );
    }


    setView(view: CalendarView) {
        this.view = view;
    }

    closeOpenMonthViewDay() {
        this.activeDayIsOpen = false;
    }
}