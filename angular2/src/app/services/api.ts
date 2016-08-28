import {Injectable} from '@angular/core';
import {Http, Headers} from '@angular/http';
import {CookieService} from 'angular2-cookie/core';

import {Observable} from 'rxjs/Observable';

import {CacheService} from './cache';
import {Env} from '../shared/env';

@Injectable()
export class ApiService {
    public env:any;

    protected headers:any = new Headers();

    /**
     * Since we need to have Http, Cookies and Cache, then they need to be injected from wherever this is used;
     * Annoying, I know >_>
     *
     * @param http Http
     * @param cookieService CookieService
     * @param cacheService CacheService
     */
    constructor(private http:Http, private cookieService:CookieService, private cacheService:CacheService) {
        this.headers.append('Content-Type', 'application/json');
        this.env = Env;

        this.init();
    }

    protected init():void {
        return;
    }

    public check(token:string = ''):Promise<boolean> {
        return Observable.create((observer:any) => {
            // Added manual token for dev/test purposes
            this.http.get(this.env.api + '/check' + (token === '' ? '' : '?token=' + token), {headers: this.headers})
                .subscribe(
                    data => {
                        observer.next(true);
                        observer.complete();
                    },
                    err => {
                        observer.next(false);
                        observer.complete();
                        //window.location.href = Env.auth;
                    }
                );
        }).toPromise();
    }

    /**
     * Performs a GET request
     *
     * @param url          URL past the base env.api + '/' to get
     * @param key          Cache key
     * @param expiryAmount Amount of expiryUnits until cache expires
     * @param expiryUnit   Units of expiry, ex. 'minute' or 'day'
     * @param fresh        If true, then force retrieve non-cache version and refresh the cache
     *
     * @returns Observable Observable of the request
     */
    public get(url:string, key = '', expiryAmount = 60, expiryUnit = 'minute', fresh = false):any {
        return this.request(url, 'get', [], {key: key, expiry: expiryAmount, unit: expiryUnit}, false, fresh);
    }

    public getCachless(url:string):any {
        return this.request(url, 'get', [], false, false, true);
    }

    public post(url:string, data:any = []):any {
        let d = JSON.stringify({data: data});

        return this.request(url, 'post', d);
    }

    public put(url:string, data:any = []):any {
        let d = JSON.stringify({data: data});

        return this.request(url, 'put', d);
    }

    public del(url:string):any {
        return this.request(url, 'delete');
    }

    /**
     * Check if given string is a JSON string
     *
     * @param str String to check
     *
     * @returns {boolean} True for valid, False for invalid
     */
    public isJson(str:string) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }

        return true;
    }

    /**
     * Parse JWT token
     *
     * @param token String JWT token to parse
     *
     * @returns {null | Object} Returns Null in case of failure or a Object of results in success
     */
    public static parseToken(token:string = ''):any {
        let decoded:string;

        if (token && token !== '') {
            decoded = decodeURIComponent(atob(token.split('.')[1]).replace(/(.)/g, function (m, p) {
                let code = p.charCodeAt(0).toString(16).toUpperCase();
                if (code.length < 2) code = '0' + code;
                return '%' + code;
            }));
        }

        return decoded ? JSON.parse(decoded) : null;
    }

    //noinspection OverlyComplexFunctionJS
    private request(url:string, method:string = 'get', data:any = [], cache:any = false, fullUrl:boolean = false, fresh:boolean = false) {
        let u:string = url, token:any, cached:any = false;

        if (fullUrl === false) {
            if (url.substring(0, 1) !== '/') u = '/' + url;
            u = this.env.api + u;
        }

        if (cache !== false) {
            token = ApiService.parseToken(this.cookieService.get('token'));
            if (cache.key === '') { //noinspection TypeScriptUnresolvedVariable
                cache.key = method.toUpperCase() + ' ' + u + (token ? token.sub : '');
            }
        }

        return Observable.create((observer:any) => {
            if (cache !== false && fresh === false) cached = this.cacheService.retrieve(cache.key);

            if (cached === false || typeof cached === 'undefined') {
                switch (method.toUpperCase()) {
                    case 'GET':
                        this.http.get(u, {headers: this.headers})
                            .subscribe(
                                data => {
                                    if (cache !== false) this.cacheService.store(cache.key, data.json(), cache.expiry, cache.unit);
                                    observer.next(data.json());
                                    observer.complete();
                                },
                                err => {
                                    observer.next(err.json().error);
                                    observer.complete();
                                }
                            );
                        break;
                    case 'POST':
                        this.http.post(u, data, {headers: this.headers})
                            .subscribe(
                                data => {
                                    observer.next(data.json());
                                    observer.complete();
                                },
                                err => {
                                    observer.next(err.json());
                                    observer.complete();
                                }
                            );
                        break;
                    case 'PUT':
                        this.http.put(u, data, {headers: this.headers})
                            .subscribe(
                                data => {
                                    observer.next(data.json());
                                    observer.complete();
                                },
                                err => {
                                    observer.next(err.json());
                                    observer.complete();
                                }
                            );
                        break;
                    case 'DELETE':
                        this.http.delete(u, {headers: this.headers})
                            .subscribe(
                                data => {
                                    observer.next(data.json());
                                    observer.complete();
                                },
                                err => {
                                    observer.next(err.json().error);
                                    observer.complete();
                                }
                            );
                        break;
                    default:
                        break;
                }
            }
            else {
                observer.next(cached);
                observer.complete();
            }
        });
    }
}
