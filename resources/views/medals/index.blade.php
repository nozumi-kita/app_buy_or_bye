<x-layouts.app>
    <section>
        <h2>実績一覧</h2>
        <ul class="medal-list">
            @foreach ($medals as $medal)
                <li class="medal-card">
                    <h3 class="medal-title {{ $medal->isAcquired() ? '' : 'is-locked' }}">{{ $medal->name }}</h3>
                    <img class="medal-image {{ $medal->isAcquired() ? '' : 'is-locked' }}" src="{{ $medal->iconUrl() }}"
                        alt="{{ $medal->name }}"
                    >
                    <p class="medal-description">{{ $medal->description }}</p>
                    @if ($medal->isAcquired())
                        <p class="medal-acquired">達成日: {{ format_jst($medal->acquired_at, 'Y/m/d') }}</p>
                    @else
                        @php($type = $medal->condition_type)
                        <progress class="progress-bar" max="{{ $medal->threshold }}"
                            value="{{ $progress->valueOf($type) }}"
                        ></progress>
                        <p class="progress-value">
                            {{ $type->format($progress->valueOf($type)) }}/{{ $type->format($medal->threshold) }}
                        </p>
                    @endif
                </li>
            @endforeach
        </ul>
    </section>
</x-layouts.app>
