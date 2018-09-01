import { Component, OnInit } from '@angular/core';
import { SortDescriptor } from '@progress/kendo-data-query';
import { GridDataResult } from '@progress/kendo-angular-grid';
import { User } from '../model/user'

@Component({
  selector: 'app-user-group-list',
  templateUrl: './user-group-list.component.html',
  styleUrls: ['./user-group-list.component.scss']
})
export class UserGroupListComponent implements OnInit {
  users: User[] = [{
    id: 1,
    firstName: 'Phat',
    lastName: 'Nguyen',
    active: true
  } as User, {
    id: 2,
    firstName: 'David',
    lastName: 'Beckham',
    active: false
  } as User, {
    id: 3,
    firstName: 'Vi',
    lastName: 'Thuy'
  } as User]
  gridView: GridDataResult;


  constructor() { }

  loadGrid() {
    this.gridView = {
      data: this.users,
      total: this.users.length
    };
  }

  ngOnInit() {
    this.loadGrid();
  }

}
