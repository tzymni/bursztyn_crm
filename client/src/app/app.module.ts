import { registerLocaleData } from '@angular/common';
import localeFr from '@angular/common/locales/pl'; // to register french
import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';

import {HttpModule} from '@angular/http';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {HttpClientModule} from '@angular/common/http';
import {AppComponent} from './app.component';
import {ItemsListComponent} from './items-list/items-list.component';
import {AuthGuard} from './_guards/index';
import {NotifierModule, NotifierOptions} from 'angular-notifier';
import {NgbModule} from '@ng-bootstrap/ng-bootstrap';
import {ItemServiceService} from './items-list/service/item-service.service';
import {UsersService} from './users/service/users.service';
import {AuthenticationService} from './_authentication/authentication.service';
import {RestService} from './_rest/rest.service';
import {NgxTuiCalendarModule} from 'ngx-tui-calendar';
import {ItemComponent} from './item/item.component';
import {MenuComponent} from './menu/menu.component';
import {LoginComponent} from './login/login.component';
import {routing} from './app.routing';
import {NotificationsService} from './_notifications/notifications.service';
import {UsersComponent} from './users/users.component';
import {UserComponent} from './user/user.component';
import {NgxSmartModalModule} from 'ngx-smart-modal';
import {CalendarComponent} from './calendar/calendar.component';
import {CottagesComponent} from './cottages/cottages.component';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';
import {CalendarModule, DateAdapter} from 'angular-calendar';
import {adapterFactory} from 'angular-calendar/date-adapters/date-fns';

registerLocaleData(localeFr);

const customNotifierOptions: NotifierOptions = {
    position: {
        horizontal: {
            position: 'left',
            distance: 12
        },
        vertical: {
            position: 'bottom',
            distance: 12,
            gap: 10
        }
    },
    theme: 'material',
    behaviour: {
        autoHide: 5000,
        onClick: 'hide',
        onMouseover: 'pauseAutoHide',
        showDismissButton: true,
        stacking: 4
    },
    animations: {
        enabled: true,
        show: {
            preset: 'slide',
            speed: 300,
            easing: 'ease'
        },
        hide: {
            preset: 'fade',
            speed: 300,
            easing: 'ease',
            offset: 50
        },
        shift: {
            speed: 300,
            easing: 'ease'
        },
        overlap: 150
    }
};

@NgModule({
    declarations: [
        AppComponent,
        ItemsListComponent,
        ItemComponent,
        MenuComponent,
        LoginComponent,
        UsersComponent,
        UserComponent,
        CalendarComponent,
        CottagesComponent,
    ],
    imports: [
        BrowserModule,
        NgxTuiCalendarModule.forRoot(),
        FormsModule,
        ReactiveFormsModule,
        HttpModule,
        ReactiveFormsModule,
        routing,
        HttpClientModule,
        NgbModule,
        NotifierModule.withConfig(customNotifierOptions),
        NgxSmartModalModule.forRoot(),
        BrowserAnimationsModule,
        CalendarModule.forRoot({
            provide: DateAdapter,
            useFactory: adapterFactory
        })
    ],
    providers: [AuthGuard, ItemServiceService, AuthenticationService, NotificationsService, UsersService, RestService],
    bootstrap: [AppComponent]
})
export class AppModule {}
