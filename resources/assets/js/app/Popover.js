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
    if (binding.value !== binding.oldValue) {
        bind(el, binding);
    }
};

export default {
    bind, unbind, update
};
