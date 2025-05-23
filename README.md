## ⚙️ Setup Notes

### ✅ Requirements

-   The **queue workers** must be running.
-   It's **recommended** to use **[Reverb](https://reverb.dev/docs/laravel/installation)** as the WebSocket service.
-   Don’t forget to run the storage link:

```bash
php artisan storage:link
```

### In your .env add

VITE_API_URL=http://devoteam-pool-tournament.test/api

AVATAR_API_URL="https://avatar.iran.liara.run"

### Reverb will usually add the required settings to your .env, but double-check that the following entries exist:

If Reverb does not auto-fill .env, add the following manually:

BROADCAST_DRIVER=reverb

BROADCAST_CONNECTION=reverb

REVERB_APP_ID=

REVERB_APP_KEY=

REVERB_APP_SECRET=

REVERB_HOST=

REVERB_PORT=

REVERB_SCHEME=

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"

VITE_REVERB_HOST="${REVERB_HOST}"

VITE_REVERB_PORT="${REVERB_PORT}"

VITE_REVERB_SCHEME="${REVERB_SCHEME}"
