import { WeekOptions } from "./Models/WeekOptions";
import { MonthOptions } from "./Models/MonthOptions";
import { Template } from "./Models/Template";
export interface TuiCalendarOptions {
    defaultView?: string;
    taskView?: boolean;
    scheduleView?: boolean;
    template?: Template;
    month?: MonthOptions;
    week?: WeekOptions;
    disableDblClick?: boolean;
}
export declare class TuiCalendarDefaults {
    defaultView: string;
    taskView: boolean;
    useCreationPopup: boolean;
    useDetailPopup: boolean;
    scheduleView: boolean;
    disableDblClick: boolean;
    week: WeekOptions;
}
