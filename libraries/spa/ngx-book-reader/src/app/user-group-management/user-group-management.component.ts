import { Component, OnInit } from '@angular/core';
import { UserGroup } from '../model/user-group';
import { GridDataResult } from '@progress/kendo-angular-grid';
import { SortDescriptor, orderBy } from '@progress/kendo-data-query';

@Component({
  selector: 'app-user-group-management',
  templateUrl: './user-group-management.component.html',
  styleUrls: ['./user-group-management.component.scss']
})
export class UserGroupManagementComponent implements OnInit {
  groups: UserGroup[] = [{
    id: 1, name: 'User'
  }, {
    id: 2, name: 'Admin'
  }, {
    id: 3, name: 'Guest'
  }];
  gridView: GridDataResult;
  sort: SortDescriptor[] = [{
    field: 'name',
    dir: 'asc'
  }]

  constructor() { }

  ngOnInit() {
    this.loadGrid();
  }

  loadGrid() {
    this.gridView = {
      data: orderBy(this.groups, this.sort),
      total: this.groups.length
    }
  }

  sortChange(sort) {
    this.sort = sort;
    this.loadGrid();
  }
}
