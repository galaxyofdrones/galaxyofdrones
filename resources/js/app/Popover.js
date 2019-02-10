const bind = (el, binding) => {
    let options = {
        container: 'body'
    };

    if (binding.value) {
        options = _.extend({}, options, binding.value);
    }

    $(el).popover(options);
};

const unbind = el => {
    $(el).popover('dispose');
};

const update = (el, binding) => {
    if (binding.value.title !== binding.oldValue.title) {
        $(el).data('bs.popover').config.title = binding.value.title;
    }

    if (binding.value.content !== binding.oldValue.content) {
        $(el).data('bs.popover').config.content = binding.value.content;
    }
};

export default {
    bind, unbind, update
};
