import {Injectable} from '@angular/core';
import {NotifierService} from 'angular-notifier';

@Injectable({
    providedIn: 'root'
})
export class NotificationsService {
    public notifier: NotifierService;
    constructor(

        notifier: NotifierService
    ) {

        this.notifier = notifier;
    }
}
