import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CottagesComponent } from './cottages.component';

describe('CottagesComponent', () => {
  let component: CottagesComponent;
  let fixture: ComponentFixture<CottagesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CottagesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CottagesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
