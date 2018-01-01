import Panel from './Panel';

export default Panel.extend({
    props: [
        'url',
        'historyUrl',
        'deleteUrl',
        'defaultSort',
        'defaultDirection',
        'defaultParameters',
        'translations'
    ],

    data() {
        return {
            isAllSelected: false,
            hasKeyword: false,
            selected: [],
            keyword: '',
            sort: '',
            direction: '',
            page: 1,

            data: {
                current_page: 1,
                last_page: 1,
                data: []
            }
        };
    },

    created() {
        this.parameters = {};
        this.initHistory();
        this.fetchData(false);
    },

    computed: {
        hasHistory() {
            return window.history && history.pushState;
        },

        canDelete() {
            return this.deleteUrl && this.selected.length;
        },

        isAscOrder() {
            return this.direction === 'asc';
        },

        isDescOrder() {
            return this.direction === 'desc';
        },

        hasPrev() {
            return this.data.current_page > 1;
        },

        hasNext() {
            return this.data.current_page < this.data.last_page;
        },

        pages() {
            if (!this.data.to) {
                return [];
            }

            const start = Math.max(1, this.data.current_page - 4);
            const end = Math.min(start + 8, this.data.last_page);

            return _.range(start, end + 1);
        },

        parameters: {
            get() {
                return _.pickBy({
                    keyword: this.keyword,
                    sort: this.sort !== this.defaultSort
                        ? this.sort
                        : undefined,
                    direction: this.direction !== this.defaultDirection
                        ? this.direction
                        : undefined,
                    page: this.page > 1
                        ? this.page
                        : undefined
                });
            },

            set(value) {
                const parameters = _.assignIn(
                    {}, this.defaultParameters, value
                );

                this.keyword = parameters.keyword;
                this.sort = parameters.sort;
                this.direction = parameters.direction;
                this.page = parameters.page;
            }
        }
    },

    methods: {
        isCurrentPage(page) {
            return this.page === page;
        },

        isSelected(item) {
            return this.selected.indexOf(item.id) > -1;
        },

        isSort(sort) {
            return this.sort === sort;
        },

        initHistory() {
            if (!this.hasHistory) {
                return;
            }

            $(window).on('popstate', e => {
                const event = e.originalEvent;

                this.parameters = event.state
                    ? event.state
                    : {};

                this.fetchData(false);
            });
        },

        fetchData(setHistory = true) {
            this.startLoading();

            axios.get(this.url, {
                params: this.parameters
            }).then(response => {
                this.data = response.data;
                this.selected = [];
                this.isAllSelected = false;
                this.hasKeyword = !!this.keyword;
                this.stopLoading();

                if (setHistory) {
                    this.setHistory();
                }
            });
        },

        setHistory() {
            if (!this.hasHistory) {
                return;
            }

            let url = this.historyUrl;

            if (!_.isEmpty(this.parameters)) {
                url = `${url}?${$.param(this.parameters)}`;
            }

            history.pushState(this.parameters, '', url);
        },

        deleteSelected() {
            if (!this.canDelete) {
                return;
            }

            swal({
                title: this.translations.title,
                text: this.translations.text,
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: this.translations.confirm,
                confirmButtonColor: '#ea5a5a',
                cancelButtonText: this.translations.cancel,
                cancelButtonColor: '#8e9aa9'
            }).then(isConfirmed => {
                if (!isConfirmed) {
                    return;
                }

                this.startLoading();

                axios.delete(this.deleteUrl, {
                    params: {
                        ids: this.selected
                    }
                }).then(() => {
                    this.stopLoading();

                    if (this.page > 1 && this.isAllSelected) {
                        this.prevPage();
                    } else {
                        this.fetchData();
                    }
                });
            }).catch(swal.noop);
        },

        search() {
            this.page = 1;
            this.fetchData();
        },

        sortBy(sort) {
            if (this.isSort(sort)) {
                this.direction = this.isAscOrder
                    ? 'desc'
                    : 'asc';
            } else {
                this.sort = sort;
                this.direction = 'asc';
            }

            this.fetchData();
        },

        toggleAll() {
            this.isAllSelected = !this.isAllSelected;
            this.selected = [];

            if (this.isAllSelected) {
                _.forEach(this.data.data, item => {
                    this.selected.push(item.id);
                });
            }
        },

        select(item) {
            const index = this.selected.indexOf(item.id);

            if (index > -1) {
                this.selected.splice(index, 1);
            } else {
                this.selected.push(item.id);
            }

            this.isAllSelected = this.data.data.length === this.selected.length;
        },

        prevPage() {
            this.changePage(this.page - 1);
        },

        nextPage() {
            this.changePage(this.page + 1);
        },

        changePage(page) {
            if (this.page === page) {
                return;
            }

            if (page < 1 || page > this.data.last_page) {
                return;
            }

            this.page = page;
            this.scrollTo(this.$el);
            this.fetchData();
        }
    }
});
