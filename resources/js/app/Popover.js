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
    $(el).popover('destroy');
};

const update = (el, binding) => {
    if (binding.value.title !== binding.oldValue.title) {
        $(el).data('bs.popover').options.title = binding.value.title;
    }

    if (binding.value.content !== binding.oldValue.content) {
        $(el).data('bs.popover').options.content = binding.value.content;
    }
};

export default {
    bind, unbind, update
};
