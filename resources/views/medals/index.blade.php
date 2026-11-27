<x-layouts.app>
    <section>
        <div class="grid grid-cols-3 text-2xl pb-4 mb-4 border-b border-line">
            <x-element.back-link :href="route('items.index')" />
            <h2 class="text-center">実績</h2>
        </div>
        <ul class="grid grid-cols-1 gap-4 md:grid-cols-3">
            @foreach ($medals as $medal)
                <li class="flex flex-col border border-line rounded-md">
                    <div class="flex flex-col flex-1 pb-3 px-4">
                        <img class="mt-4 mb-3 size-25 mx-auto {{ $medal->isAcquired() ? '' : 'grayscale opacity-50' }}"
                            src="{{ $medal->iconUrl() }}" alt="{{ $medal->name }}"
                        >
                        <h3 class="text-center pb-1 text-xl font-bold">
                            {{ $medal->name }}
                        </h3>
                        <p class="self-center mb-5 text-fg-muted">{{ $medal->description }}</p>
                        @php($type = $medal->condition_type)
                        @if ($medal->isAcquired())
                            <div class="text-center mt-auto">
                                <p class="flex items-center justify-center text-success-message">
                                    <x-icon.check-circle />
                                    <span class="text-lg">
                                        {{ format_jst($medal->acquired_at, 'Y/m/d') }}達成済
                                    </span>
                                </p>
                            </div>
                        @else
                            <div class="mt-auto">
                                <div class="h-3 bg-bar rounded-full mb-2 mx-auto max-w-60">
                                    <div class="bg-bar-now h-3 rounded-l-full"
                                        style="width: {{ min(100, ($progress->valueOf($type) / $medal->threshold) * 100) }}%"
                                    >
                                    </div>
                                </div>
                                <p class="text-center">
                                    {{ $type->format($progress->valueOf($type)) }}/{{ $type->format($medal->threshold) }}
                                </p>
                            </div>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    </section>
</x-layouts.app>
