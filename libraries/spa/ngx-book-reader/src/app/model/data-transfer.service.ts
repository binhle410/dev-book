import {Injectable} from '@angular/core';
import {BehaviorSubject} from 'rxjs/BehaviorSubject';


@Injectable({
    providedIn: 'root'
})
export class DataTransferService {

    navBarTitle = new BehaviorSubject<any>([]);
    cast = this.navBarTitle.asObservable();

    setNavBarTitle(data) {
        this.navBarTitle.next(data);
    }
}