import {NgModule}      from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';

import {appRouting, appRoutingProviders} from './app.routes';
import {AppComponent} from './app.component';

@NgModule({
    imports: [
        BrowserModule,
        appRouting
    ],
    declarations: [
        AppComponent,
    ],
    providers: [
        appRoutingProviders
    ],
    bootstrap: [
        AppComponent
    ]
})
export class AppModule {
}

