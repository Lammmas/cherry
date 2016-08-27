import {platformBrowserDynamic} from '@angular/platform-browser-dynamic';
import {enableProdMode}         from '@angular/core';

import {AppModule} from './app.module';
import {Env}       from './shared/env';

if (Env.production === true) enableProdMode();

platformBrowserDynamic().bootstrapModule(AppModule);

