import {NgModule}      from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';

import {appRouting, appRoutingProviders} from './app.routes';
import {AppComponent} from './app.component';

import {PublicModule} from './public/public.module';
import {AuthModule}   from './auth/auth.module';
import {DashModule}   from './dash/dash.module';

@NgModule({
    imports: [
        BrowserModule,
        PublicModule,
        AuthModule,
        DashModule,
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

