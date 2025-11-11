<!doctype html>
<html lang="es" class="h-full">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Iniciar sesión – Sistema de Gestión Académica</title>

  <script src="https://cdn.tailwindcss.com"></script>
  
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <style>
    body { font-family: Inter, system-ui, sans-serif; }
    [x-cloak] { display: none !important; }
  </style>
</head>
<body class="h-full bg-slate-100">

  <div class="min-h-full flex items-center justify-center px-4 py-12">
    <div class="mx-auto w-full max-w-md">
      
      <div class="bg-white rounded-2xl shadow-xl border border-slate-200">
        
        <div class="px-8 pt-8 pb-4 text-center">
          <div class="mx-auto mb-4 h-12 w-12 rounded-xl bg-indigo-100 flex items-center justify-center ring-4 ring-indigo-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m5-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <h1 class="text-xl font-semibold tracking-tight text-slate-800">Iniciar sesión</h1>
          <p class="mt-1 text-sm text-slate-500">Gestión Académica FICCT</p>
        </div>

        @if ($errors->any())
          <div class="mx-8 mb-3 rounded-lg bg-red-50 text-red-700 ring-1 ring-red-200 px-4 py-3 text-sm">
            {{ $errors->first() }}
          </div>
        @endif

        <form class="px-8 pb-8" method="POST" action="{{ route('login') }}" x-data="{ show:false, loading:false }" @submit="loading=true">
          @csrf
          
          <label for="nombre" class="block text-sm font-medium text-slate-700">Usuario (CI)</label>
          <div class="mt-1">
            <input
              id="nombre"
              type="text"
              name="nombre"
              value="{{ old('nombre') }}"
              required
              autofocus
              class="w-full rounded-xl border-0 bg-slate-100 text-slate-800 placeholder-slate-400 shadow-inner focus:ring-2 focus:ring-indigo-500 px-4 py-3 ring-1 ring-slate-200"
              placeholder="Tu carnet de identidad"
            />
          </div>

          <div class="mt-5">
            <label for="contrasena" class="block text-sm font-medium text-slate-700">Contraseña</label>
            <div class="mt-1 relative">
              <input
                id="contrasena"
                :type="show ? 'text' : 'password'"
                name="contrasena"
                required
                class="w-full rounded-xl border-0 bg-slate-100 text-slate-800 placeholder-slate-400 shadow-inner focus:ring-2 focus:ring-indigo-500 px-4 py-3 ring-1 ring-slate-200 pr-12"
                placeholder="••••••••"
              />
              <button type="button" @click="show=!show" class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-slate-600">
                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1 1 0 010-.644C3.423 7.51 7.36 5 12 5c4.64 0 8.577 2.51 9.964 6.678.07.213.07.431 0 .644C20.577 16.49 16.64 19 12 19c-4.64 0-8.577-2.51-9.964-6.678z"/>
                  <circle cx="12" cy="12" r="3" stroke-width="1.5" stroke="currentColor" fill="none"/>
                </svg>
                <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.477 10.489A3 3 0 0113.5 13.5m5.21-1.822A10.477 10.477 0 0012 5c-1.7 0-3.31.38-4.74 1.06M6.5 6.5A10.45 10.45 0 003 12c1.387 4.168 5.324 6.678 9.964 6.678 1.28 0 2.514-.194 3.676-.558"/>
                </svg>
              </button>
            </div>
          </div>

          <div class="mt-5 flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-slate-600">
              <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 bg-slate-100 text-indigo-600 focus:ring-indigo-500">
              Recuérdame
            </label>
          </div>

          <button
            type="submit"
            class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-3 font-medium text-white shadow-lg shadow-indigo-500/25 ring-1 ring-inset ring-indigo-500/30 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-60"
            :disabled="loading"
          >
            <svg x-show="loading" x-cloak class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v3m0 12v3m-9-9h3m12 0h3M5.636 5.636l2.122 2.122m12.727 0l-2.121-2.121M5.636 18.364l2.122-2.122m12.727 0l-2.121 2.122"></path></svg>
            <span x-text="loading ? 'Ingresando...' : 'Ingresar'">Ingresar</span>
          </button>
          
        </form>
      </div>

    </div>
  </div>

</body>
</html>