<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  </head>
  <body>
    <div class="bg-white">
      <div class="relative isolate pt-3">
        <div aria-hidden="true" class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80">
          <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"></div>
        </div>
        <div class="py-4">

          <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
              <div class="sm:flex-auto">
                <h1 class="text-balance text-4xl font-semibold tracking-tight text-fuchsia-900">NotificationHub</h1>
              </div>
              <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('scramble.docs.ui') }}" target="_blank" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                  API docs
                </a>
              </div>
            </div>
            <div class="mt-8 flow-root">
              <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                  <div class="overflow-hidden outline outline-1 outline-black/5 sm:rounded-sm">
                    <table class="relative min-w-full divide-y divide-gray-300">
                      <thead class="bg-purple-50">
                        <tr>
                          <th scope="col" class="px-2 py-2 text-xs font-semibold text-gray-900 text-left">event_id</th>
                          <th scope="col" class="px-2 py-2 text-xs font-semibold text-gray-900 text-left">event_type</th>
                          <th scope="col" class="px-2 py-2 text-xs font-semibold text-gray-900 text-left">channel</th>
                          <th scope="col" class="px-2 py-2 text-xs font-semibold text-gray-900 text-left">status</th>
                          <th scope="col" class="px-2 py-2 text-xs font-semibold text-gray-900 text-left">payload</th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach ($notifications as $notification)
                          <tr>
                            <td class="whitespace-nowrap px-2 py-2 text-xs text-gray-500">{{ $notification->event_id }}</td>
                            <td class="whitespace-nowrap px-2 py-2 text-xs text-gray-800 font-medium">{{ $notification->event_type }}</td>
                            <td class="whitespace-nowrap px-2 py-2 text-xs text-gray-500">{{ $notification->channel }}</td>
                            <td class="whitespace-nowrap px-2 py-2 text-xs text-gray-500">{{ $notification->status?->status }}</td>
                            <td class="whitespace-nowrap px-2 py-2 text-xs text-gray-500">{{ $notification->payload->toJson() }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </body>
</html>
