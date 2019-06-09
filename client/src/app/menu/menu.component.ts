import {Component, OnInit} from '@angular/core';
import {Router} from '@angular/router';

@Component({
    selector: 'app-menu',
    templateUrl: './menu.component.html',
    styleUrls: ['./menu.component.css']
})
export class MenuComponent implements OnInit {


    constructor(private router: Router) {}

    logout() {
        // remove user from local storage to log user out
        sessionStorage.removeItem('token');
        this.router.navigate(['/login'], { skipLocationChange: true });
        return false;
    }

    ngOnInit() {
    }

}
