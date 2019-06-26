import {Routes, RouterModule} from '@angular/router';
import {ItemComponent} from './item/item.component';
import {MenuComponent} from './menu/menu.component';
import {LoginComponent} from './login/login.component';
import {AuthGuard} from './_guards/index';
import {ItemsListComponent} from './items-list/items-list.component';
import {UsersComponent} from './users/users.component';
import {UserComponent} from './user/user.component';
import {CottageComponent} from './cottage/cottage.component';
import {CalendarComponent} from './calendar/calendar.component';
import {CottagesComponent} from './cottages/cottages.component';
import {ReservationComponent} from './reservation/reservation.component';


const appRoutes: Routes = [
        { path: "", redirectTo: "home", pathMatch: 'full',   },
    {
        path: '', canActivate: [AuthGuard], children: [

            {path: "home", component: CalendarComponent},
            {path: "users", component: UsersComponent},
            {path: "cottages", component: CottagesComponent},
            {path: "calendar", component: CalendarComponent},
            {path: "customers", component: ItemsListComponent},
            {path: "customers/add", component: ItemComponent},
            {path: "customers/edit/:id", component: ItemComponent},
            {path: "user/add", component: UserComponent},
            {path: "cottage/add", component: CottageComponent},
            {path: "reservation/add", component: ReservationComponent},


        ]
    },
    {path: 'login', component: LoginComponent},

];

export const routing = RouterModule.forRoot(appRoutes);