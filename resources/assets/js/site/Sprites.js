import { Polygon, Rectangle } from 'pixi.js';

export default {
    hitArea: new Polygon([
        0, 120,
        160, 40,
        320, 120,
        160, 200
    ]),

    plain: new Rectangle(0, 0, 320, 200),

    resources: {
        1: new Rectangle(320, 0, 320, 200),
        2: new Rectangle(640, 0, 320, 200),
        3: new Rectangle(960, 0, 320, 200),
        4: new Rectangle(1280, 0, 320, 200),
        5: new Rectangle(1600, 0, 320, 200),
        6: new Rectangle(0, 200, 320, 200),
        7: new Rectangle(320, 200, 320, 200)
    },

    buildings: {
        1: new Rectangle(640, 200, 320, 200),
        2: {
            1: new Rectangle(960, 200, 320, 200),
            2: new Rectangle(1280, 200, 320, 200),
            3: new Rectangle(1600, 200, 320, 200),
            4: new Rectangle(0, 400, 320, 200),
            5: new Rectangle(320, 400, 320, 200),
            6: new Rectangle(640, 400, 320, 200),
            7: new Rectangle(960, 400, 320, 200)
        },
        3: new Rectangle(1280, 400, 320, 200),
        4: new Rectangle(1600, 400, 320, 200),
        5: new Rectangle(0, 600, 320, 200),
        6: new Rectangle(320, 600, 320, 200),
        7: new Rectangle(640, 600, 320, 200),
        8: new Rectangle(960, 600, 320, 200),
        9: new Rectangle(1280, 600, 320, 200),
        10: new Rectangle(1600, 600, 320, 200)
    },

    constructions: {
        1: new Rectangle(0, 800, 320, 200),
        2: new Rectangle(320, 800, 320, 200),
        3: new Rectangle(640, 800, 320, 200),
        4: new Rectangle(960, 800, 320, 200),
        5: new Rectangle(1280, 800, 320, 200),
        6: new Rectangle(1600, 800, 320, 200),
        7: new Rectangle(0, 1000, 320, 200),
        8: new Rectangle(320, 1000, 320, 200),
        9: new Rectangle(640, 1000, 320, 200),
        10: new Rectangle(960, 1000, 320, 200)
    }
};
