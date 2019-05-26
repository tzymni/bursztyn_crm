/**
 * @fileoverview added by tsickle
 * @suppress {checkTypes,extraRequire,uselessCode} checked by tsc
 */
import { NgModule, InjectionToken } from '@angular/core';
import { NgxTuiCalendarComponent } from './ngx-tui-calendar.component';
import { TuiCalendarDefaults } from './ngx-tui-calendar-defaults.service';
/** @type {?} */
export var USER_DEFAULTS = new InjectionToken('tuiCalendar defaults');
/**
 * @param {?} userDefaults
 * @return {?}
 */
export function defaultsFactory(userDefaults) {
    /** @type {?} */
    var defaults = new TuiCalendarDefaults();
    Object.assign(defaults, userDefaults);
    return defaults;
}
var NgxTuiCalendarModule = /** @class */ (function () {
    function NgxTuiCalendarModule() {
    }
    /**
     * @param {?=} userDefaults
     * @return {?}
     */
    NgxTuiCalendarModule.forRoot = /**
     * @param {?=} userDefaults
     * @return {?}
     */
    function (userDefaults) {
        if (userDefaults === void 0) { userDefaults = {}; }
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
    };
    NgxTuiCalendarModule.decorators = [
        { type: NgModule, args: [{
                    declarations: [NgxTuiCalendarComponent],
                    exports: [NgxTuiCalendarComponent],
                    providers: [{ provide: TuiCalendarDefaults, useClass: TuiCalendarDefaults }]
                },] },
    ];
    return NgxTuiCalendarModule;
}());
export { NgxTuiCalendarModule };

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoibmd4LXR1aS1jYWxlbmRhci5tb2R1bGUuanMiLCJzb3VyY2VSb290Ijoibmc6Ly9uZ3gtdHVpLWNhbGVuZGFyLyIsInNvdXJjZXMiOlsibGliL25neC10dWktY2FsZW5kYXIubW9kdWxlLnRzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiI7Ozs7QUFBQSxPQUFPLEVBQUUsUUFBUSxFQUFFLGNBQWMsRUFBdUIsTUFBTSxlQUFlLENBQUM7QUFDOUUsT0FBTyxFQUFFLHVCQUF1QixFQUFFLE1BQU0sOEJBQThCLENBQUM7QUFDdkUsT0FBTyxFQUFzQixtQkFBbUIsRUFBRSxNQUFNLHFDQUFxQyxDQUFDOztBQUU5RixXQUFhLGFBQWEsR0FBMkIsSUFBSSxjQUFjLENBQ3RFLHNCQUFzQixDQUN0QixDQUFDOzs7OztBQUVGLE1BQU0sMEJBQTBCLFlBQWdDOztJQUMvRCxJQUFNLFFBQVEsR0FBd0IsSUFBSSxtQkFBbUIsRUFBRSxDQUFDO0lBQ2hFLE1BQU0sQ0FBQyxNQUFNLENBQUMsUUFBUSxFQUFFLFlBQVksQ0FBQyxDQUFDO0lBQ3RDLE1BQU0sQ0FBQyxRQUFRLENBQUM7Q0FDaEI7Ozs7Ozs7O0lBUU8sNEJBQU87Ozs7SUFBZCxVQUFlLFlBQXFDO1FBQXJDLDZCQUFBLEVBQUEsaUJBQXFDO1FBQ25ELE1BQU0sQ0FBQztZQUNOLFFBQVEsRUFBRSxvQkFBb0I7WUFDOUIsU0FBUyxFQUFFO2dCQUNWO29CQUNDLE9BQU8sRUFBRSxhQUFhO29CQUN0QixRQUFRLEVBQUUsWUFBWTtpQkFDdEI7Z0JBQ0Q7b0JBQ0MsT0FBTyxFQUFFLG1CQUFtQjtvQkFDNUIsVUFBVSxFQUFFLGVBQWU7b0JBQzNCLElBQUksRUFBRSxDQUFDLGFBQWEsQ0FBQztpQkFDckI7YUFDRDtTQUNELENBQUM7S0FDRjs7Z0JBdEJELFFBQVEsU0FBQztvQkFDVCxZQUFZLEVBQUUsQ0FBQyx1QkFBdUIsQ0FBQztvQkFDdkMsT0FBTyxFQUFFLENBQUMsdUJBQXVCLENBQUM7b0JBQ2xDLFNBQVMsRUFBRSxDQUFDLEVBQUUsT0FBTyxFQUFFLG1CQUFtQixFQUFFLFFBQVEsRUFBRSxtQkFBbUIsRUFBRSxDQUFDO2lCQUM1RTs7K0JBakJEOztTQWtCYSxvQkFBb0IiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBOZ01vZHVsZSwgSW5qZWN0aW9uVG9rZW4sIE1vZHVsZVdpdGhQcm92aWRlcnMgfSBmcm9tICdAYW5ndWxhci9jb3JlJztcclxuaW1wb3J0IHsgTmd4VHVpQ2FsZW5kYXJDb21wb25lbnQgfSBmcm9tICcuL25neC10dWktY2FsZW5kYXIuY29tcG9uZW50JztcclxuaW1wb3J0IHsgVHVpQ2FsZW5kYXJPcHRpb25zLCBUdWlDYWxlbmRhckRlZmF1bHRzIH0gZnJvbSAnLi9uZ3gtdHVpLWNhbGVuZGFyLWRlZmF1bHRzLnNlcnZpY2UnO1xyXG5cclxuZXhwb3J0IGNvbnN0IFVTRVJfREVGQVVMVFM6IEluamVjdGlvblRva2VuPHN0cmluZz4gPSBuZXcgSW5qZWN0aW9uVG9rZW4oXHJcblx0J3R1aUNhbGVuZGFyIGRlZmF1bHRzJ1xyXG4pO1xyXG5cclxuZXhwb3J0IGZ1bmN0aW9uIGRlZmF1bHRzRmFjdG9yeSh1c2VyRGVmYXVsdHM6IFR1aUNhbGVuZGFyT3B0aW9ucyk6IFR1aUNhbGVuZGFyT3B0aW9ucyB7XHJcblx0Y29uc3QgZGVmYXVsdHM6IFR1aUNhbGVuZGFyRGVmYXVsdHMgPSBuZXcgVHVpQ2FsZW5kYXJEZWZhdWx0cygpO1xyXG5cdE9iamVjdC5hc3NpZ24oZGVmYXVsdHMsIHVzZXJEZWZhdWx0cyk7XHJcblx0cmV0dXJuIGRlZmF1bHRzO1xyXG59XHJcbkBOZ01vZHVsZSh7XHJcblx0ZGVjbGFyYXRpb25zOiBbTmd4VHVpQ2FsZW5kYXJDb21wb25lbnRdLFxyXG5cdGV4cG9ydHM6IFtOZ3hUdWlDYWxlbmRhckNvbXBvbmVudF0sXHJcblx0cHJvdmlkZXJzOiBbeyBwcm92aWRlOiBUdWlDYWxlbmRhckRlZmF1bHRzLCB1c2VDbGFzczogVHVpQ2FsZW5kYXJEZWZhdWx0cyB9XVxyXG59KVxyXG5leHBvcnQgY2xhc3MgTmd4VHVpQ2FsZW5kYXJNb2R1bGUge1xyXG5cclxuXHRzdGF0aWMgZm9yUm9vdCh1c2VyRGVmYXVsdHM6IFR1aUNhbGVuZGFyT3B0aW9ucyA9IHt9KTogTW9kdWxlV2l0aFByb3ZpZGVycyB7XHJcblx0XHRyZXR1cm4ge1xyXG5cdFx0XHRuZ01vZHVsZTogTmd4VHVpQ2FsZW5kYXJNb2R1bGUsXHJcblx0XHRcdHByb3ZpZGVyczogW1xyXG5cdFx0XHRcdHtcclxuXHRcdFx0XHRcdHByb3ZpZGU6IFVTRVJfREVGQVVMVFMsXHJcblx0XHRcdFx0XHR1c2VWYWx1ZTogdXNlckRlZmF1bHRzXHJcblx0XHRcdFx0fSxcclxuXHRcdFx0XHR7XHJcblx0XHRcdFx0XHRwcm92aWRlOiBUdWlDYWxlbmRhckRlZmF1bHRzLFxyXG5cdFx0XHRcdFx0dXNlRmFjdG9yeTogZGVmYXVsdHNGYWN0b3J5LFxyXG5cdFx0XHRcdFx0ZGVwczogW1VTRVJfREVGQVVMVFNdXHJcblx0XHRcdFx0fVxyXG5cdFx0XHRdXHJcblx0XHR9O1xyXG5cdH1cclxufVxyXG4iXX0=