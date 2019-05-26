import { InjectionToken, ModuleWithProviders } from '@angular/core';
import { TuiCalendarOptions } from './ngx-tui-calendar-defaults.service';
export declare const USER_DEFAULTS: InjectionToken<string>;
export declare function defaultsFactory(userDefaults: TuiCalendarOptions): TuiCalendarOptions;
export declare class NgxTuiCalendarModule {
    static forRoot(userDefaults?: TuiCalendarOptions): ModuleWithProviders;
}
