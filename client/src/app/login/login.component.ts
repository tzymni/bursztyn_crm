import {Component, OnInit} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {AuthenticationService} from '../_authentication/authentication.service'
import {NotificationsService} from '../_notifications/notifications.service';

@Component({
//    moduleId: module.id.toString(),
    templateUrl: './login.component.html',

})

export class LoginComponent implements OnInit {
    
    
    model: any = {};
    
    loading = false;
    returnUrl: string;

    constructor(
        private route: ActivatedRoute,
        private router: Router,
        private authenticationService: AuthenticationService,
        private notification: NotificationsService,
        ) {}

    ngOnInit() {
        // reset login status
        this.authenticationService.logout();

        // get return url from route parameters or default to '/'
        this.returnUrl = this.route.snapshot.queryParams['returnUrl'] || '/';
    }

    login() {
        this.loading = true;
        this.authenticationService.login(this.model.username, this.model.password)
            .subscribe(
                data => {
                    this.router.navigate([this.returnUrl]);
                    
//                    this.notifier.notify('success', 'Zalogowano do systemu' );
                },
                error => {
                    this.notification.notifier.notify('error', 'Coś poszło nie tak :<');
                    this.loading = false;
                });
    }
}
