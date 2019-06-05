<monitor url="{{ route('api_monitor') }}" inline-template>
    <div v-if="isEnabled" class="monitor" v-cloak>
        <a class="monitor-title"
           href="#"
           @click.prevent="openRadar()"
           v-popover="{placement: 'top', trigger: 'hover', content: '{{ trans('validation.attributes.incoming') }}'}">
            <i class="fas fa-chevron-left first"></i>
            <i class="fas fa-chevron-left middle"></i>
            <i class="fas fa-chevron-left last"></i>
            @{{ data.incoming | number }}
            <i class="fas fa-chevron-right last"></i>
            <i class="fas fa-chevron-right middle"></i>
            <i class="fas fa-chevron-right first"></i>
        </a>
    </div>
</monitor>
