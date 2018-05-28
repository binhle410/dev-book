import { Component, OnInit, Renderer2, Inject, ElementRef, ViewChild } from '@angular/core';
import { DOCUMENT } from '@angular/common';
import { Router, NavigationEnd } from '@angular/router';
import { Subscription } from 'rxjs/Subscription';
import 'rxjs/add/operator/filter';
import { LocationStrategy, PlatformLocation, Location } from '@angular/common';
import { NavbarComponent } from './shared/navbar/navbar.component';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
  title = 'app';
  @ViewChild(NavbarComponent) navbar: NavbarComponent;

    constructor(private renderer: Renderer2, private router: Router,
                @Inject(DOCUMENT, ) private document: any, private element: ElementRef, public location: Location) { }

    ngOnInit() {
    }

    removeNavbar() {
        var titlee = this.location.prepareExternalUrl(this.location.path());
        titlee = titlee.slice(1);
        if (titlee === 'login') {
            return true;
        }
        else {
            return false;
        }
    }
}
