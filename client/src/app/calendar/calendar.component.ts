import {Component, ElementRef, OnInit, ViewChild, OnChanges, SimpleChanges, Input, Output, EventEmitter} from '@angular/core';
import {NgxTuiCalendarComponent, TuiCalendarOptions} from 'ngx-tui-calendar';
import {TuiCalendarDefaults} from 'ngx-tui-calendar';
import {AfterRenderScheduleEvent, BeforeCreateScheduleEvent, BeforeDeleteScheduleEvent, BeforeUpdateScheduleEvent, ClickDaynameEvent, ClickScheduleEvent} from 'ngx-tui-calendar/lib/Models/Events';
import {Schedule} from 'ngx-tui-calendar/lib/Models/Schedule';

@Component({
    selector: 'app-calendar',
    templateUrl: './calendar.component.html',
    styleUrls: ['./calendar.component.css']
})
export class CalendarComponent implements OnChanges, TuiCalendarOptions {


  @Input() defaultView: string;
  @Input() taskView: boolean;
  @Input() scheduleView: boolean;
  @Input() template: object;
  @Input() month: object;
  @Input() week: object;
  @Input() schedules: Schedule[];


//    @Output() beforeCreateSchedule: EventEmitter<BeforeCreateScheduleEvent> = new EventEmitter();
//    @Output() beforeDeleteSchedule: EventEmitter<BeforeDeleteScheduleEvent> = new EventEmitter();
//    @Output() afterRenderSchedule: EventEmitter<AfterRenderScheduleEvent> = new EventEmitter();
    @Output() tuiCalendarCreated: EventEmitter<{tuiCalendar: any}> = new EventEmitter();
//    @Output() dayNameClicked: EventEmitter<ClickDaynameEvent> = new EventEmitter();
//    @Output() timeClicked: EventEmitter<Date> = new EventEmitter();
    @Output() scheduleClicked: EventEmitter<ClickScheduleEvent> = new EventEmitter();
//    @Output() beforeUpdateSchedule: EventEmitter<BeforeUpdateScheduleEvent> = new EventEmitter();

        @ViewChild('exampleCalendar') exampleCalendar: NgxTuiCalendarComponent;

    private tuiCalendar: any;
    constructor(private elm: ElementRef, private defaults: TuiCalendarDefaults) {

        const options: TuiCalendarOptions = {
            defaultView: this.defaultView,
            taskView: this.taskView,
            scheduleView: this.scheduleView,
            template: this.template,
            month: this.month,
            week: this.week
        };
//
//        Object.keys(this.defaults).forEach(optionKey => {
//            if (typeof options[optionKey] === 'undefined') {
//                options[optionKey] = this.defaults[optionKey];
//            }
//        });
//
//        Object.keys(options).forEach(optionKey => {
//            if (typeof options[optionKey] === 'undefined') {
//                delete options[optionKey];
//            }
//        });
//
//
//        this.tuiCalendar = new NgxTuiCalendarComponent(this.elm.nativeElement, defaults);;
//        this.tuiCalendarCreated.emit({tuiCalendar: this.tuiCalendar});

    }

        onTuiCalendarCreate($event) {
            //            this.exampleCalendar.setOptions({month: {visibleWeeksCount: 2, workweek: true}}, true);
            console.log("ALLELUJJ23");
            this.exampleCalendar.changeView('month');
            
//            this.exampleCalendar.
            //        var calendarOptions = this.
    
            const options: TuiCalendarOptions = {
                month: {startDayOfWeek: 1}
    
            }
            
//            this.scheduleClicked.emit(Schedule);

//                    ClickScheduleEvent => {
//    }
//                this.exampleCalendar.scheduleClicked(event: ClickScheduleEvent => {
//    });
//            this.exampleCalendar.scheduleClicked(clickScheduleEvent) => {
//      this.scheduleClicked.emit(event);
//    });
            
//            this.exampleCalendar.next();
    
            /* at this point the calendar has been created and it's methods are available via the ViewChild defined above, so for example you can do: */
            this.exampleCalendar.createSchedules([
    
                {
                    id: '1',
                    calendarId: '1',
                    title: 'Domek1',
                    category: 'time',
                    dueDateClass: '',
                    start: new Date(),
                    end: '2019-05-28T17:31:00+09:00'
                },
                {
                    id: '2',
                    calendarId: '2',
                    title: 'Domek 2',
                    category: 'time',
                    dueDateClass: '',
                    start: new Date(),
                    end: '2019-05-28T17:31:00+09:00',
                    isReadOnly: true    // schedule is read-only
                }
    
            ]);
        }


    ngOnChanges(changes: SimpleChanges): void {

    }

    ngOnInit() {


                this.onTuiCalendarCreate('test');

    }

}
