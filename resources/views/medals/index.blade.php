<x-layouts.app>
    <section>
        <h2>実績一覧</h2>
        <ul class="medal-list">
            @foreach ($medals as $medal)
                <li class="medal-card">
                    <h3 class="medal-title">{{ $medal->name }}</h3>
                    <img class="medal-image" src="{{ $medal->iconUrl() }}" alt="{{ $medal->name }}">
                    <p class="medal-description">{{ $medal->description }}</p>
                    @if ($progress->satisfies($medal))
                        <p class="medal-acquired">達成日 2026/9/23</p>
                    @else
                        @php($type = $medal->condition_type)
                        <progress class="progress-bar" max="{{ $medal->threshold }}"
                            value="{{ $progress->valueOf($type) }}"
                        ></progress>
                        <p class="progress-value">
                            <span>{{ $type->format($progress->valueOf($type)) }}</span>
                            <span>/</span>
                            <span>{{ $type->format($medal->threshold) }}</span>
                        </p>
                    @endif
                </li>
            @endforeach
        </ul>
    </section>
</x-layouts.app>
