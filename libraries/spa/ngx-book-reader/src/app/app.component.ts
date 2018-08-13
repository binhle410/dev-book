import {Component, OnInit, Renderer2, Inject, ElementRef, ViewChild, AfterViewInit, Injectable} from '@angular/core';
import {DOCUMENT} from '@angular/common';
import {Router, NavigationEnd} from '@angular/router';
import {Subscription} from 'rxjs/Subscription';
import 'rxjs/add/operator/filter';
import {LocationStrategy, PlatformLocation, Location} from '@angular/common';
import {NavbarComponent} from './shared/navbar/navbar.component';
import {ScrollSpyModule, ScrollSpyService} from 'ngx-scrollspy';
import {DataTransferService} from "./model/data-transfer.service";

@Injectable()
@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit, AfterViewInit {

    title = 'app';
    @ViewChild(NavbarComponent) navbar: NavbarComponent;
    private _router: Subscription;

    ngAfterViewInit() {

    }

    constructor(
        @Inject(DOCUMENT) private document: any, 
        private scrollSpyService: ScrollSpyService, 
        private renderer: Renderer2, 
        private router: Router,
        private element: ElementRef, 
        public location: Location
    ) { }

    ngOnInit() {
        this.readCode();
    }

    readCode() {
        const native = this.element.nativeElement;
        const code = native.getAttribute('code');
        if (code) {
            localStorage.setItem('code', code);            
        } else {
            localStorage.removeItem('code');
        }
    }

    removeNavbar() {
        var titlee = this.location.prepareExternalUrl(this.location.path());
        titlee = titlee.slice(1);
        if (titlee === 'login' || titlee.startsWith('books')) {
            // console.log('removing navbar');
            return true;
        } else {
            console.log('keeping navbar ', titlee);
            return false;
        }
    }
}
