@php
    $types = [
        'scout' => App\Models\Movement::TYPE_SCOUT,
        'attack' => App\Models\Movement::TYPE_ATTACK,
        'occupy' => App\Models\Movement::TYPE_OCCUPY,
        'support' => App\Models\Movement::TYPE_SUPPORT,
        'transport' => App\Models\Movement::TYPE_TRANSPORT,
    ];
    $unitTypes = [
        'transporter' => App\Models\Unit::TYPE_TRANSPORTER,
        'scout' => App\Models\Unit::TYPE_SCOUT,
        'fighter' => App\Models\Unit::TYPE_FIGHTER,
        'heavyFighter' => App\Models\Unit::TYPE_HEAVY_FIGHTER,
        'settler' => App\Models\Unit::TYPE_SETTLER,
    ];
    $urls = [
        'scout' => route('api_movement_scout_store', '__planet__'),
        'attack' => route('api_movement_attack_store', '__planet__'),
        'occupy' => route('api_movement_occupy_store', '__planet__'),
        'support' => route('api_movement_support_store', '__planet__'),
        'transport' => route('api_movement_transport_store', '__planet__'),
    ];
@endphp
<move :types='@json($types)'
      :unit-types='@json($unitTypes)'
      :urls='@json($urls)' inline-template>
    <div class="move modal fade" tabindex="-1">
        <div class="modal-dialog modal-xl">
            @include('partials.move-scout')
            @include('partials.move-attack')
            @include('partials.move-occupy')
            @include('partials.move-support')
            @include('partials.move-transport')
        </div>
    </div>
</move>
