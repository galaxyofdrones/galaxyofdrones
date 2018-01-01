export default {
    bind(el, binding) {
        let options = {};

        if (binding.value) {
            options = _.assignIn(
                {}, options, binding.value
            );
        }

        $(el).tooltip(options);
    },

    unbind(el) {
        $(el).tooltip('destroy');
    }
};
