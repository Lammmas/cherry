# PHP task

I used my own Auth RPC service to fake logins, although it would be easy enough to roll my own OAuth handler and use that to trigger the 'login bonus'. However, since I've spent enough time trying to get this framework to work, I'm not going to delve into the OAuth customization now. If I had a couple of more days, I'd do it properly :)

There's still lots to do, but with 24 hours and never before using neither Apigility nor AngularJS (the 1st version), I personally think that it could have been worse.

## API

The API endpoints are as such, with query parameters (`Sort` and `Order` are for ordering, `Page` for pagination, and all other filter the appropriate fields):

1. Bonuses (GET and POST)
  - `/bonus` with optional parameters `Trigger`, `Value`, `Type`, `Multiplier`, `Active`
  - `/bonus/[:id]`
2. Users (GET and POST)
  - `/user` with optional parameters `Email`, `Name`, `Age`, `Gender`
  - `/user/[:id]`
3. Wallets (GET and POST)
  - `/wallet` with optional parameters `User_id`, `Bonus_id`, `Balance`, `Original`, `Currency`, `Active`, `Bonus`
  - `/wallet/[:id]`
4. Auth (POST)
  - `/auth`
5. Deposit (POST)
  - `/deposit`
6. Game (POST)
  - `/play`
7. Frontend (GET)
  - `/public` - Purely for the pretty display of data

Frankly, there are many possible upgrades to be done. For example, for filtering there should be support for non-strict filters, ex. `balance=>10` for Wallets. Also, proper OAuth security with protecting paths. In any case, wherever there was something lacking, it's commented in the code.