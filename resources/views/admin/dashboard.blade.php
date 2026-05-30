<x-layouts.admin heading="Dashboard">

    {{-- Stats row --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Users</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">12,845</p>
                </div>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50">
                    <svg class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-xs"><span class="font-semibold text-emerald-600">↑ 12%</span> <span class="text-slate-400">from last month</span></p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Revenue</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">$48,295</p>
                </div>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50">
                    <svg class="h-5 w-5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-xs"><span class="font-semibold text-emerald-600">↑ 8.2%</span> <span class="text-slate-400">from last month</span></p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Orders</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">3,671</p>
                </div>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50">
                    <svg class="h-5 w-5 text-violet-600" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-xs"><span class="font-semibold text-red-500">↓ 3.1%</span> <span class="text-slate-400">from last month</span></p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Growth</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">+24.5%</p>
                </div>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50">
                    <svg class="h-5 w-5 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-xs"><span class="font-semibold text-emerald-600">↑ 4.7%</span> <span class="text-slate-400">from last month</span></p>
        </div>
    </div>

    {{-- Lower section --}}
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Recent Orders --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h2 class="text-sm font-semibold text-slate-900">Recent Orders</h2>
                <a href="#" class="text-xs font-medium text-blue-600 hover:text-blue-700">View all →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/60">
                            <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Order</th>
                            <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Customer</th>
                            <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Status</th>
                            <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Date</th>
                            <th class="whitespace-nowrap px-5 py-3 text-right text-xs font-medium uppercase tracking-wider text-slate-400">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ([
                            ['#ORD-2501', 'Alice Johnson',  'completed',  'May 28', '$240.00'],
                            ['#ORD-2500', 'Bob Smith',      'pending',    'May 27', '$125.50'],
                            ['#ORD-2499', 'Carol White',    'processing', 'May 27', '$89.00'],
                            ['#ORD-2498', 'David Lee',      'completed',  'May 26', '$312.00'],
                            ['#ORD-2497', 'Eva Martinez',   'cancelled',  'May 25', '$54.75'],
                            ['#ORD-2496', 'Frank Chen',     'completed',  'May 24', '$178.00'],
                        ] as [$id, $customer, $status, $date, $amount])
                        @php
                            $badge = match($status) {
                                'completed'  => 'bg-emerald-50 text-emerald-700',
                                'pending'    => 'bg-amber-50 text-amber-700',
                                'processing' => 'bg-blue-50 text-blue-700',
                                'cancelled'  => 'bg-red-50 text-red-700',
                                default      => 'bg-slate-100 text-slate-600',
                            };
                        @endphp
                        <tr class="transition-colors hover:bg-slate-50/50">
                            <td class="px-5 py-3.5 font-medium text-slate-900">{{ $id }}</td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $customer }}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $badge }}">{{ ucfirst($status) }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-400">{{ $date }}</td>
                            <td class="px-5 py-3.5 text-right font-semibold text-slate-900">{{ $amount }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top Products --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-sm font-semibold text-slate-900">Top Products</h2>
            </div>
            <ul class="divide-y divide-slate-100">
                @foreach ([
                    ['Product Alpha',   '1,240 sales', 78],
                    ['Product Beta',    '980 sales',   62],
                    ['Product Gamma',   '754 sales',   48],
                    ['Product Delta',   '521 sales',   33],
                    ['Product Epsilon', '310 sales',   20],
                ] as [$name, $sales, $pct])
                <li class="px-5 py-3.5">
                    <div class="mb-1.5 flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-800">{{ $name }}</span>
                        <span class="text-xs text-slate-400">{{ $sales }}</span>
                    </div>
                    <div class="h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                        <div class="h-1.5 rounded-full bg-blue-600" style="width: {{ $pct }}%"></div>
                    </div>
                </li>
                @endforeach
            </ul>

            {{-- Quick summary --}}
            <div class="border-t border-slate-100 bg-slate-50/50 px-5 py-4">
                <p class="text-xs text-slate-500">Showing top 5 products by sales volume this month.</p>
            </div>
        </div>
    </div>

    {{-- Activity + Quick Actions row --}}
    <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">

        {{-- Quick actions --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="mb-4 text-sm font-semibold text-slate-900">Quick Actions</h2>
            <div class="grid grid-cols-2 gap-3">
                @foreach ([
                    ['New User',    'text-blue-600 bg-blue-50',    'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'],
                    ['New Product', 'text-violet-600 bg-violet-50', 'M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['Export CSV',  'text-emerald-600 bg-emerald-50','M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4'],
                    ['Reports',     'text-amber-600 bg-amber-50',   'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ] as [$label, $color, $path])
                <button class="flex flex-col items-center gap-2 rounded-xl border border-slate-200 p-3.5 text-center transition-colors hover:border-slate-300 hover:bg-slate-50">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg {{ $color }}">
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
                        </svg>
                    </span>
                    <span class="text-xs font-medium text-slate-700">{{ $label }}</span>
                </button>
                @endforeach
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:col-span-2 xl:col-span-2">
            <h2 class="mb-4 text-sm font-semibold text-slate-900">Recent Activity</h2>
            <ol class="relative border-l border-slate-200">
                @foreach ([
                    ['New user registered',          'Alice Johnson signed up.',          '2 min ago',  'bg-blue-500'],
                    ['Order #ORD-2501 completed',    'Payment confirmed for $240.00.',    '18 min ago', 'bg-emerald-500'],
                    ['Product stock low',            'Product Beta — only 3 remaining.',  '1 hr ago',   'bg-amber-500'],
                    ['Order #ORD-2500 pending',      'Waiting for payment verification.', '2 hr ago',   'bg-slate-400'],
                    ['System backup completed',      'Daily snapshot stored successfully.','5 hr ago',  'bg-violet-500'],
                ] as [$title, $desc, $time, $dot])
                <li class="mb-4 ml-4 last:mb-0">
                    <span class="absolute -left-1.5 mt-1 flex h-3 w-3 items-center justify-center rounded-full {{ $dot }} ring-2 ring-white"></span>
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <p class="text-sm font-medium text-slate-900">{{ $title }}</p>
                            <p class="text-xs text-slate-500">{{ $desc }}</p>
                        </div>
                        <span class="shrink-0 text-xs text-slate-400">{{ $time }}</span>
                    </div>
                </li>
                @endforeach
            </ol>
        </div>
    </div>

</x-layouts.admin>
