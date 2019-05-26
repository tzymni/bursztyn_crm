/**
 * @fileoverview added by tsickle
 * @suppress {checkTypes,extraRequire,uselessCode} checked by tsc
 */
import { NgModule, InjectionToken } from '@angular/core';
import { NgxTuiCalendarComponent } from './ngx-tui-calendar.component';
import { TuiCalendarDefaults } from './ngx-tui-calendar-defaults.service';
/** @type {?} */
export const USER_DEFAULTS = new InjectionToken('tuiCalendar defaults');
/**
 * @param {?} userDefaults
 * @return {?}
 */
export function defaultsFactory(userDefaults) {
    /** @type {?} */
    const defaults = new TuiCalendarDefaults();
    Object.assign(defaults, userDefaults);
    return defaults;
}
export class NgxTuiCalendarModule {
    /**
     * @param {?=} userDefaults
     * @return {?}
     */
    static forRoot(userDefaults = {}) {
        return {
            ngModule: NgxTuiCalendarModule,
            providers: [
                {
                    provide: USER_DEFAULTS,
                    useValue: userDefaults
                },
                {
                    provide: TuiCalendarDefaults,
                    useFactory: defaultsFactory,
                    deps: [USER_DEFAULTS]
                }
            ]
        };
    }
}
NgxTuiCalendarModule.decorators = [
    { type: NgModule, args: [{
                declarations: [NgxTuiCalendarComponent],
                exports: [NgxTuiCalendarComponent],
                providers: [{ provide: TuiCalendarDefaults, useClass: TuiCalendarDefaults }]
            },] },
];

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoibmd4LXR1aS1jYWxlbmRhci5tb2R1bGUuanMiLCJzb3VyY2VSb290Ijoibmc6Ly9uZ3gtdHVpLWNhbGVuZGFyLyIsInNvdXJjZXMiOlsibGliL25neC10dWktY2FsZW5kYXIubW9kdWxlLnRzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiI7Ozs7QUFBQSxPQUFPLEVBQUUsUUFBUSxFQUFFLGNBQWMsRUFBdUIsTUFBTSxlQUFlLENBQUM7QUFDOUUsT0FBTyxFQUFFLHVCQUF1QixFQUFFLE1BQU0sOEJBQThCLENBQUM7QUFDdkUsT0FBTyxFQUFzQixtQkFBbUIsRUFBRSxNQUFNLHFDQUFxQyxDQUFDOztBQUU5RixhQUFhLGFBQWEsR0FBMkIsSUFBSSxjQUFjLENBQ3RFLHNCQUFzQixDQUN0QixDQUFDOzs7OztBQUVGLE1BQU0sMEJBQTBCLFlBQWdDOztJQUMvRCxNQUFNLFFBQVEsR0FBd0IsSUFBSSxtQkFBbUIsRUFBRSxDQUFDO0lBQ2hFLE1BQU0sQ0FBQyxNQUFNLENBQUMsUUFBUSxFQUFFLFlBQVksQ0FBQyxDQUFDO0lBQ3RDLE1BQU0sQ0FBQyxRQUFRLENBQUM7Q0FDaEI7QUFNRCxNQUFNOzs7OztJQUVMLE1BQU0sQ0FBQyxPQUFPLENBQUMsZUFBbUMsRUFBRTtRQUNuRCxNQUFNLENBQUM7WUFDTixRQUFRLEVBQUUsb0JBQW9CO1lBQzlCLFNBQVMsRUFBRTtnQkFDVjtvQkFDQyxPQUFPLEVBQUUsYUFBYTtvQkFDdEIsUUFBUSxFQUFFLFlBQVk7aUJBQ3RCO2dCQUNEO29CQUNDLE9BQU8sRUFBRSxtQkFBbUI7b0JBQzVCLFVBQVUsRUFBRSxlQUFlO29CQUMzQixJQUFJLEVBQUUsQ0FBQyxhQUFhLENBQUM7aUJBQ3JCO2FBQ0Q7U0FDRCxDQUFDO0tBQ0Y7OztZQXRCRCxRQUFRLFNBQUM7Z0JBQ1QsWUFBWSxFQUFFLENBQUMsdUJBQXVCLENBQUM7Z0JBQ3ZDLE9BQU8sRUFBRSxDQUFDLHVCQUF1QixDQUFDO2dCQUNsQyxTQUFTLEVBQUUsQ0FBQyxFQUFFLE9BQU8sRUFBRSxtQkFBbUIsRUFBRSxRQUFRLEVBQUUsbUJBQW1CLEVBQUUsQ0FBQzthQUM1RSIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IE5nTW9kdWxlLCBJbmplY3Rpb25Ub2tlbiwgTW9kdWxlV2l0aFByb3ZpZGVycyB9IGZyb20gJ0Bhbmd1bGFyL2NvcmUnO1xyXG5pbXBvcnQgeyBOZ3hUdWlDYWxlbmRhckNvbXBvbmVudCB9IGZyb20gJy4vbmd4LXR1aS1jYWxlbmRhci5jb21wb25lbnQnO1xyXG5pbXBvcnQgeyBUdWlDYWxlbmRhck9wdGlvbnMsIFR1aUNhbGVuZGFyRGVmYXVsdHMgfSBmcm9tICcuL25neC10dWktY2FsZW5kYXItZGVmYXVsdHMuc2VydmljZSc7XHJcblxyXG5leHBvcnQgY29uc3QgVVNFUl9ERUZBVUxUUzogSW5qZWN0aW9uVG9rZW48c3RyaW5nPiA9IG5ldyBJbmplY3Rpb25Ub2tlbihcclxuXHQndHVpQ2FsZW5kYXIgZGVmYXVsdHMnXHJcbik7XHJcblxyXG5leHBvcnQgZnVuY3Rpb24gZGVmYXVsdHNGYWN0b3J5KHVzZXJEZWZhdWx0czogVHVpQ2FsZW5kYXJPcHRpb25zKTogVHVpQ2FsZW5kYXJPcHRpb25zIHtcclxuXHRjb25zdCBkZWZhdWx0czogVHVpQ2FsZW5kYXJEZWZhdWx0cyA9IG5ldyBUdWlDYWxlbmRhckRlZmF1bHRzKCk7XHJcblx0T2JqZWN0LmFzc2lnbihkZWZhdWx0cywgdXNlckRlZmF1bHRzKTtcclxuXHRyZXR1cm4gZGVmYXVsdHM7XHJcbn1cclxuQE5nTW9kdWxlKHtcclxuXHRkZWNsYXJhdGlvbnM6IFtOZ3hUdWlDYWxlbmRhckNvbXBvbmVudF0sXHJcblx0ZXhwb3J0czogW05neFR1aUNhbGVuZGFyQ29tcG9uZW50XSxcclxuXHRwcm92aWRlcnM6IFt7IHByb3ZpZGU6IFR1aUNhbGVuZGFyRGVmYXVsdHMsIHVzZUNsYXNzOiBUdWlDYWxlbmRhckRlZmF1bHRzIH1dXHJcbn0pXHJcbmV4cG9ydCBjbGFzcyBOZ3hUdWlDYWxlbmRhck1vZHVsZSB7XHJcblxyXG5cdHN0YXRpYyBmb3JSb290KHVzZXJEZWZhdWx0czogVHVpQ2FsZW5kYXJPcHRpb25zID0ge30pOiBNb2R1bGVXaXRoUHJvdmlkZXJzIHtcclxuXHRcdHJldHVybiB7XHJcblx0XHRcdG5nTW9kdWxlOiBOZ3hUdWlDYWxlbmRhck1vZHVsZSxcclxuXHRcdFx0cHJvdmlkZXJzOiBbXHJcblx0XHRcdFx0e1xyXG5cdFx0XHRcdFx0cHJvdmlkZTogVVNFUl9ERUZBVUxUUyxcclxuXHRcdFx0XHRcdHVzZVZhbHVlOiB1c2VyRGVmYXVsdHNcclxuXHRcdFx0XHR9LFxyXG5cdFx0XHRcdHtcclxuXHRcdFx0XHRcdHByb3ZpZGU6IFR1aUNhbGVuZGFyRGVmYXVsdHMsXHJcblx0XHRcdFx0XHR1c2VGYWN0b3J5OiBkZWZhdWx0c0ZhY3RvcnksXHJcblx0XHRcdFx0XHRkZXBzOiBbVVNFUl9ERUZBVUxUU11cclxuXHRcdFx0XHR9XHJcblx0XHRcdF1cclxuXHRcdH07XHJcblx0fVxyXG59XHJcbiJdfQ==