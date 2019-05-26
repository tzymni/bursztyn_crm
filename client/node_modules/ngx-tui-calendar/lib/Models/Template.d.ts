export interface Template {
    milestoneTitle?(): any;
    milestone?(): any;
    taskTitle?(): any;
    task?(): any;
    alldayTitle?(): any;
    allday?(): any;
    time?(): any;
    monthMoreTitleDate?(): any;
    monthMoreClose?(): any;
    monthGridHeader?(): any;
    monthGridFooter?(): any;
    monthGridHeaderExceed?(): any;
    monthGridFooterExceed?(): any;
    weekDayname?(): any;
    monthDayname?(): any;
}
