<template>
 <div>
              <span id="menu-navi">
                <button type="button" class="btn btn-default btn-sm move-today" data-action="move-today">Today</button>
                <button type="button" class="btn btn-default btn-sm move-day" data-action="move-prev">
                    <i class="calendar-icon ic-arrow-line-left" data-action="move-prev"></i>
                </button>
                <button type="button" class="btn btn-default btn-sm move-day" data-action="move-next">
                    <i class="calendar-icon ic-arrow-line-right" data-action="move-next"></i>
                </button>
            </span>
 <calendar style="height: 800px;"

           :calendars="calendarList"
           :schedules="scheduleList"
           :view="view"
           :taskView="taskView"
           :scheduleView="scheduleView"
           :theme="theme"
           :week="week"
           :month="month"
           :disableDblClick="disableDblClick"
           :isReadOnly="isReadOnly"
           :useCreationPopup="useCreationPopup"
           :useDetailPopup="useDetailPopup"
           @afterRenderSchedule="onAfterRenderSchedule"
           @beforeCreateSchedule="onBeforeCreateSchedule"
           @beforeDeleteSchedule="onBeforeDeleteSchedule"
           @beforeUpdateSchedule="onBeforeUpdateSchedule"
           @clickDayname="onClickDayname"
           @clickSchedule="onClickSchedule"
           @clickTimezonesCollapseBtn="onClickTimezonesCollapseBtn"
 >

  <span id="renderRange" class="render-range"></span>


 </calendar>
 </div>
</template>
<script>
 import 'tui-calendar/dist/tui-calendar.css'
 import { Calendar } from '@toast-ui/vue-calendar';

 export default {
  name: 'myCalendar',
  components: {
   'calendar': Calendar
  },
  data() {
   return {
    calendarList: [
     {
      id: '0',
      name: 'home'
     },
     {
      id: '1',
      name: 'office'
     }
    ],
    scheduleList: [
     {
      id: '1',
      calendarId: '1',
      title: 'my schedule',
      category: 'time',
      dueDateClass: '',
      start: '2018-10-18T22:30:00+09:00',
      end: '2018-10-19T02:30:00+09:00'
     },
     {
      id: '2',
      calendarId: '1',
      title: 'second schedule',
      category: 'time',
      dueDateClass: '',
      start: '2020-04-09T17:30:00+09:00',
      end: '2020-04-12T17:31:00+09:00'
     },    {
      id: '2',
      calendarId: '1',
      title: 'second schedule',
      category: 'time',
      dueDateClass: '',
      start: '2020-04-09T17:30:00+09:00',
      end: '2020-04-12T17:31:00+09:00'
     },    {
      id: '2',
      calendarId: '1',
      title: 'second schedule',
      category: 'time',
      dueDateClass: '',
      start: '2020-04-09T17:30:00+09:00',
      end: '2020-04-12T17:31:00+09:00'
     },    {
      id: '2',
      calendarId: '1',
      title: 'second schedule',
      category: 'time',
      dueDateClass: '',
      start: '2020-04-09T17:30:00+09:00',
      end: '2020-04-12T17:31:00+09:00'
     },    {
      id: '2',
      calendarId: '1',
      title: 'second schedule',
      category: 'time',
      dueDateClass: '',
      start: '2020-04-09T17:30:00+09:00',
      end: '2020-04-12T17:31:00+09:00'
     },  {
      id: '2',
      calendarId: '1',
      title: 'second schedule',
      category: 'time',
      dueDateClass: '',
      start: '2020-04-09T17:30:00+09:00',
      end: '2020-04-12T17:31:00+09:00'
     },  {
      id: '2',
      calendarId: '1',
      title: 'second schedule',
      category: 'time',
      dueDateClass: '',
      start: '2020-04-09T17:30:00+09:00',
      end: '2020-04-12T17:31:00+09:00'
     },  {
      id: '2',
      calendarId: '1',
      title: 'second schedule',
      category: 'time',
      dueDateClass: '',
      start: '2020-04-09T17:30:00+09:00',
      end: '2020-04-12T17:31:00+09:00'
     },  {
      id: '2',
      calendarId: '1',
      title: 'second schedule',
      category: 'time',
      dueDateClass: '',
      start: '2020-04-09T17:30:00+09:00',
      end: '2020-04-12T17:31:00+09:00'
     },  {
      id: '2',
      calendarId: '1',
      title: 'second schedule',
      category: 'time',
      dueDateClass: '',
      start: '2020-04-09T17:30:00+09:00',
      end: '2020-04-12T17:31:00+09:00'
     },  {
      id: '2',
      calendarId: '1',
      title: 'second schedule',
      category: 'time',
      dueDateClass: '',
      start: '2020-04-09T17:30:00+09:00',
      end: '2020-04-12T17:31:00+09:00'
     },  {
      id: '2',
      calendarId: '1',
      title: 'second schedule',
      category: 'time',
      dueDateClass: '',
      start: '2020-04-09T17:30:00+09:00',
      end: '2020-04-12T17:31:00+09:00'
     },
    ],
    view: 'month',
    taskView: true,
    scheduleView: ['day'],
    theme: {
     'month.dayname.height': '30px',
     'month.dayname.borderLeft': '1px solid #ff0000',
     'month.dayname.textAlign': 'center',
     'week.today.color': '#333',
     'week.daygridLeft.width': '100px',
     'week.timegridLeft.width': '100px'
    },
    week: {
     narrowWeekend: true,
     showTimezoneCollapseButton: true,
     timezonesCollapsed: false
    },
    month: {
     visibleWeeksCount: 6,
     startDayOfWeek: 1,
     visibleScheduleCount: 5,
    },

    disableDblClick: false,
    isReadOnly: false,

    useCreationPopup: true,
    useDetailPopup: true,
   }
  },
  methods: {
   onAfterRenderSchedule() {
    // implement your code
   },
   onBeforeCreateSchedule() {
    // implement your code
   },
   onBeforeDeleteSchedule() {
    // implement your code
   },
   onBeforeUpdateSchedule(e) {

    // this.view = 'week';
    // this.unselect().next();
    console.log(e);
    console.log(this);
    this.date = '2020-03-03';
    // implement your code
   },
   onClickDayname() {
    console.log("month");
    // implement your code
   },
   onClickSchedule() {
    // implement your code
   },
   onClickTimezonesCollapseBtn() {
    // implement y
   },
  },
  template: {
   milestone: function(schedule) {
    return '<span class="calendar-font-icon ic-milestone-b"></span> <span style="background-color: ' + schedule.bgColor + '">' + schedule.title + '</span>';
   },
   milestoneTitle: function() {
    return '<span class="tui-full-calendar-left-content">MILESTONE</span>';
   },
   task: function(schedule) {
    return '#' + schedule.title;
   },
   taskTitle: function() {
    return '<span class="tui-full-calendar-left-content">TASK</span>';
   },
   allday: function(schedule) {
  console.log(schedule);
   },
   alldayTitle: function() {
    return '<span class="tui-full-calendar-left-content">ALL DAY</span>';
   },
   time: function(schedule) {
    return '<strong>' + 'test' + '</strong> ' + schedule.title;
   },
   goingDuration: function(schedule) {
    return '<span class="calendar-icon ic-travel-time"></span>' + schedule.goingDuration + 'min.';
   },
   comingDuration: function(schedule) {
    return '<span class="calendar-icon ic-travel-time"></span>' + schedule.comingDuration + 'min.';
   },
   monthMoreTitleDate: function(date, dayname) {
    var day = date.split('.')[2];

    return '<span class="tui-full-calendar-month-more-title-day">' + day + '</span> <span class="tui-full-calendar-month-more-title-day-label">' + dayname + '</span>';
   },
   monthMoreClose: function() {
    return '<span class="tui-full-calendar-icon tui-full-calendar-ic-close"></span>';
   },
   monthGridHeader: function(dayModel) {
    var date = parseInt(dayModel.date.split('-')[2], 10);
    var classNames = ['tui-full-calendar-weekday-grid-date'];

    if (dayModel.isToday) {
     classNames.push('tui-full-calendar-weekday-grid-date-decorator');
    }

    return '<span class="' + classNames.join(' ') + '">' + date + '</span>';
   },
   monthGridHeaderExceed: function(hiddenSchedules) {
    return '<span class="weekday-grid-more-schedules">+' + hiddenSchedules + '</span>';
   },
   monthGridFooter: function() {
    return '';
   },
  }
 }
</script>