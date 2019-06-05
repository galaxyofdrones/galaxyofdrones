<div class="modal-body">
    <div class="row text-center">
        <div class="col-md-4">
            <span class="item item-sm" :class="planet.resource_id | item('planet')">
                @{{ planet.display_name }}
            </span>
        </div>
        <div class="col-md-4 pt-md-2">
            <h5 class="highlight-success" :class="{'highlight-warning': type < 3}">
                <i class="fas fa-arrow-right"></i>
            </h5>
            <h5>
                @{{ travelTime | timer }}
            </h5>
        </div>
        <div class="col-md-4">
            <span class="item item-sm" :class="selected.resource_id | item('planet')">
                @{{ selected.name }}
            </span>
        </div>
    </div>
</div>
