import {Component}         from '@angular/core';

import 'rxjs/Rx'; // adds ALL RxJS statics & operators to Observable

@Component({
    selector: 'app-component',
    template: `<router-outlet></router-outlet>`
})
export class AppComponent {
    public isTestable:boolean = true;
}

