# Periodic Table (Laravel + Three.js CSS3D)

These files overlay a fresh Laravel 11/12 project.

## Setup

```bash
laravel new periodic-table        # or: composer create-project laravel/laravel periodic-table
cd periodic-table
composer require laravel/socialite
npm install three @tweenjs/tween.js
```

Copy the files from this folder over the project (same relative paths), then:

1. `app/Models/User.php`: add `'google_id'` and `'avatar'` to `$fillable`.
2. `vite.config.js`: set `input: ['resources/css/periodic.css', 'resources/js/periodic.js']`.
3. `config/services.php`, add:

```php
'google' => [
    'client_id'     => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect'      => env('GOOGLE_REDIRECT_URI'),
],
'sheets' => [
    'id'    => env('GOOGLE_SHEET_ID'),
    'key'   => env('GOOGLE_SHEETS_API_KEY'),
    'range' => env('GOOGLE_SHEET_RANGE', 'Sheet1!A1:F'),
],
```

4. `.env`:

```
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=https://YOUR-DOMAIN/auth/google/callback
GOOGLE_SHEET_ID=          # the long id in the sheet URL
GOOGLE_SHEETS_API_KEY=
GOOGLE_SHEET_RANGE=Sheet1!A1:F
```

5. `php artisan migrate && npm run build`

## Google side

- Import `Data Template.csv` into a Google Sheet; share with **lisa@kasatria.com**.
- For the API-key route, also set the sheet to "Anyone with the link: Viewer".
  (Stricter alternative: a service account that you share the sheet with, then
  swap the `Http::get(... key=)` call for an OAuth bearer token.)
- Google Cloud Console: create project, enable **Google Sheets API**, create an
  API key (restrict it to the Sheets API), configure the OAuth consent screen,
  create a Web OAuth client with the redirect URI above.

## Data notes (from the supplied CSV)

- 200 rows, so Table 20x10 and Grid 10x4x5 both fill exactly.
- The file is Windows-1252; two names (rows 181, 188) end in a stray byte.
  Import into Sheets as-is and the controller strips non-printing characters.
- The Net Worth header has leading/trailing spaces; values are `"$251,260.80"`
  strings, parsed to floats in `PeopleController`.
- Colour bands: 21 red (<$100K), 86 orange ($100K-$200K), 93 green (>$200K).
- Photos load from static.kasatria.com; verify they load from your domain.
