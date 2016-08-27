import {Injectable} from '@angular/core';

@Injectable()
export class CacheService {
    // Retrieve the localStorage entry for key with expiry
    public retrieve(key:string) {
        let current:any = localStorage.getItem(key), store:any, currTime:number = 0;

        if (current) {
            store = JSON.parse(current);

            if (store.expiry === false) return store.data;
            else {
                currTime = new Date().getTime();

                if (currTime >= store.expiry) {
                    localStorage.removeItem(key);
                    return false;
                } else return store.data;
            }
        } else return false;
    }

    // Store in LocalStorage with default expiry of 60 minutes; 0 expiry = infinite
    public store(key:string, data:any, time:number = 60, unit:string = 'minute') {
        let store:any;

        if (time > 0) store = {data: data, expiry: this.dateAdd(new Date(), unit, time)};
        else store = {data: data, expiry: false};

        localStorage.setItem(key, JSON.stringify(store));
    }

    public remove(key:string) {
        localStorage.removeItem(key);
    }

    public removeAll() {
        localStorage.clear();
    }

    private dateAdd(date:any, interval:string, units:number) {
        let ret = new Date(date); //don't change original date

        switch (interval.toLowerCase()) {
            case 'years'    :
            case 'year'     :
                ret.setFullYear(ret.getFullYear() + units);
                break;
            case 'quarters' :
            case 'quarter'  :
                ret.setMonth(ret.getMonth() + 3 * units);
                break;
            case 'months'   :
            case 'month'    :
                ret.setMonth(ret.getMonth() + units);
                break;
            case 'weeks'    :
            case 'week'     :
                ret.setDate(ret.getDate() + 7 * units);
                break;
            case 'days'     :
            case 'day'      :
                ret.setDate(ret.getDate() + units);
                break;
            case 'hours'    :
            case 'hour'     :
                ret.setTime(ret.getTime() + units * 3600000);
                break;
            case 'minutes'  :
            case 'minute'   :
                ret.setTime(ret.getTime() + units * 60000);
                break;
            case 'seconds'  :
            case 'second'   :
                ret.setTime(ret.getTime() + units * 1000);
                break;
            default         :
                ret = undefined;
                break;
        }

        return ret.getTime();
    }
}
