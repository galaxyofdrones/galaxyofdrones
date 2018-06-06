import { autoDetectRenderer, Container, loaders, Sprite, utils, Text, Texture } from 'pixi.js';
import { EventBus } from './event-bus';
import Filters from './Filters';
import Sprites from './Sprites';

export default {
    props: [
        'width',
        'height',
        'backgroundTexture',
        'gridTextureAtlas'
    ],

    data() {
        return {
            $parent: undefined,
            clickTreshold: 5,
            isDragging: false,
            dragStartX: 0,
            dragStartY: 0,
            dragged: 0,
            container: undefined,
            intervals: [],
            loader: undefined,
            renderer: undefined,
            stage: undefined,
            planet: {
                resource_id: undefined,
                grids: []
            },
            textStyle: {
                fontFamily: 'Exo 2',
                fontSize: '14px',
                fill: '#fff',
                align: 'center',
                stroke: '#0e141c',
                strokeThickness: 4
            }
        };
    },

    created() {
        utils.skipHello();
    },

    mounted() {
        this.$parent = $(this.$el).parent();

        EventBus.$on('planet-updated', planet => {
            this.planet = planet;

            if (!this.stage) {
                this.initPixi();
            } else {
                this.updatePixi();
            }
        });
    },

    methods: {
        initPixi() {
            this.loader = new loaders.Loader();
            this.loader.add(this.backgroundName(), this.background());
            this.loader.add('grid', this.gridTextureAtlas);
            this.loader.load(() => {
                this.setup();
                this.align();
                this.animate();
            });

            this.stage = new Container();
            this.container = new Container();
            this.container.interactive = true;
            this.container.on('mousedown', this.mouseDown);
            this.container.on('mousemove', this.mouseMove);
            this.container.on('mouseup', this.mouseUp);
            this.container.on('mouseupoutside', this.mouseUp);
            this.container.on('touchstart', this.mouseDown);
            this.container.on('touchmove', this.mouseMove);
            this.container.on('touchend', this.mouseUp);
            this.container.on('touchendoutside', this.mouseUp);
            this.stage.addChild(this.container);

            this.renderer = autoDetectRenderer(this.rendererWidth(), this.rendererHeight(), {
                view: this.$el,
                transparent: true
            });

            window.addEventListener('resize', this.resize);
        },

        updatePixi() {
            const backgroundName = this.backgroundName();

            if (!this.loader.resources[backgroundName]) {
                this.loader.add(backgroundName, this.background());
                this.loader.load(this.setup);
            } else {
                this.setup();
            }
        },

        setup() {
            _.forEach(this.intervals, interval => clearInterval(interval));

            this.intervals = [];
            this.container.removeChildren();
            this.container.addChild(this.backgroundSprite());

            _.forEach(this.planet.grids, grid => this.container.addChild(this.gridSprite(grid)));
        },

        resize() {
            this.renderer.resize(this.rendererWidth(), this.rendererHeight());
            this.align();
        },

        align() {
            this.container.position.x = this.centerX();
            this.container.position.y = this.centerY();
        },

        animate() {
            requestAnimationFrame(this.animate);

            const x = this.containerX();
            const y = this.containerY();

            if (this.container.position.x < x) {
                this.container.position.x = x;
            }

            if (this.container.position.y < y) {
                this.container.position.y = y;
            }

            if (this.container.position.x > 0) {
                this.container.position.x = 0;
            }

            if (this.container.position.y > 0) {
                this.container.position.y = 0;
            }

            this.renderer.render(this.stage);
        },

        mouseDown(e) {
            const start = e.data.getLocalPosition(this.container.parent);

            this.dragStartX = start.x - this.container.position.x;
            this.dragStartY = start.y - this.container.position.y;

            this.isDragging = true;
            this.dragged = 0;
        },

        mouseMove(e) {
            if (this.isDragging) {
                const moved = e.data.getLocalPosition(this.container.parent);

                const positionX = this.container.position.x;
                const positionY = this.container.position.y;

                this.container.position.x = moved.x - this.dragStartX;
                this.container.position.y = moved.y - this.dragStartY;

                this.dragged += Math.abs(positionX - this.container.position.x);
                this.dragged += Math.abs(positionY - this.container.position.y);
            }
        },

        mouseUp() {
            this.isDragging = false;
        },

        background() {
            return this.backgroundTexture.replace('__resource__', this.planet.resource_id);
        },

        backgroundName() {
            return `background_${this.planet.resource_id}`;
        },

        backgroundSprite() {
            return new Sprite(this.loader.resources[this.backgroundName()].texture);
        },

        rendererWidth() {
            return this.$parent.width();
        },

        rendererHeight() {
            return this.$parent.height();
        },

        centerX() {
            return this.containerX() / 2;
        },

        centerY() {
            return this.containerY() / 2;
        },

        containerX() {
            return this.renderer.width - this.container.width;
        },

        containerY() {
            return this.renderer.height - this.container.height;
        },

        gridSprite(grid) {
            const sprite = new Sprite(this.gridTexture(grid));

            sprite.interactive = true;
            sprite.hitArea = Sprites.hitArea;

            sprite.x = this.gridX(grid);
            sprite.y = this.gridY(grid);

            sprite.on('mouseover', () => this.gridOver(sprite));
            sprite.on('mouseout', () => this.gridOut(sprite));
            sprite.on('click', () => this.gridClick(grid));
            sprite.on('tap', () => this.gridClick(grid));

            this.gridLevel(grid, sprite);
            this.gridRemaining(grid, sprite);

            return sprite;
        },

        gridTexture(grid) {
            let frame = Sprites.plain;

            if (grid.construction) {
                frame = Sprites.constructions[grid.construction.building_id];
            } else if (grid.type === 1) {
                frame = grid.building_id
                    ? Sprites.buildings[grid.building_id][this.planet.resource_id]
                    : Sprites.resources[this.planet.resource_id];
            } else if (grid.building_id) {
                frame = Sprites.buildings[grid.building_id];
            }

            return new Texture(this.loader.resources.grid.texture, frame);
        },

        gridX(grid) {
            return (grid.x - grid.y + 4) * 162 + (this.width - 1608) / 2;
        },

        gridY(grid) {
            return (grid.x + grid.y) * 81 + (this.height - 888) / 2;
        },

        gridOver(sprite) {
            sprite.alpha = 0.6;
        },

        gridOut(sprite) {
            sprite.alpha = 1;
        },

        gridClick(grid) {
            if (this.dragged > this.clickTreshold) {
                return;
            }

            EventBus.$emit(grid.building_id
                ? 'building-click'
                : 'grid-click', grid);
        },

        gridLevel(grid, sprite) {
            if (!grid.level) {
                return;
            }

            const text = new Text(grid.level, this.textStyle);

            text.position.x = (sprite.width - text.width) / 2;
            text.position.y = sprite.height - 50;

            sprite.addChild(text);
        },

        gridRemaining(grid, sprite) {
            let remaining;
            let textStyle;

            if (grid.construction) {
                remaining = grid.construction.remaining;
                textStyle = this.textStyle;
            } else if (grid.training) {
                remaining = grid.training.remaining;
                textStyle = _.assignIn({}, this.textStyle, {
                    fill: '#ebb237'
                });
            } else if (grid.upgrade) {
                remaining = grid.upgrade.remaining;
                textStyle = this.textStyle;
            }

            if (!remaining) {
                return;
            }

            const text = new Text(Filters.timer(remaining), textStyle);

            text.position.x = (sprite.width - text.width) / 2;
            text.position.y = (sprite.height - text.height) / 2;

            sprite.addChild(text);

            const interval = setInterval(() => {
                text.text = Filters.timer(--remaining);

                if (!remaining) {
                    clearInterval(interval);
                }
            }, 1000);

            this.intervals.push(interval);
        }
    }
};
