export default {
    bind(el, binding) {
        let options = {
            container: 'body'
        };

        if (binding.value) {
            options = _.extend({}, options, binding.value);
        }

        $(el).popover(options);
    }
};
