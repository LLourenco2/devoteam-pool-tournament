✅ É necessário ter a queue dos workers a funcionar
✅ Preferencialmente utilizar o Reverb como serviço de websockets
✅ Não esquecer de fazer o link da storage

O reverb ja adiciona as settings ao .env mas é necessário adicionar

VITE_API_URL=http://devoteam-pool-tournament.test/api
AVATAR_API_URL="https://avatar.iran.liara.run"

Caso o reverb nao adicione no .env as settings sao as seguintes

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
