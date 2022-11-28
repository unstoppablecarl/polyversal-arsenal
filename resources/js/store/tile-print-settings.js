import {actions, getters, mutations} from "./tile-print-settings-mappers";

export default {
    namespaced: false,

    state() {
        return {
            front_is_printer_friendly: false,
            front_invert_title: false,
            front_invert_abilities: false,
            front_cut_line_color: 'transparent',

            back_is_printer_friendly: false,
            back_invert_title: false,
            back_invert_bottom_headings: false,
            back_invert_flavor_text: false,
            back_cut_line_color: 'transparent',
        }
    },

    mutations: {
        setTilePrintSettings(state, settings) {

            state.front_is_printer_friendly = settings.front_is_printer_friendly;
            state.front_invert_title        = settings.front_invert_title;
            state.front_invert_abilities    = settings.front_invert_abilities;
            state.front_cut_line_color      = settings.front_cut_line_color;

            state.back_is_printer_friendly    = settings.back_is_printer_friendly;
            state.back_invert_title           = settings.back_invert_title;
            state.back_invert_bottom_headings = settings.back_invert_bottom_headings;
            state.back_invert_flavor_text     = settings.back_invert_flavor_text;
            state.back_cut_line_color         = settings.back_cut_line_color;
        },
        ...mutations,
    },
    actions: {
        ...actions,
        setTilePrintSettings({commit}, settings) {
            commit('setTilePrintSettings', settings);
            return Promise.resolve();
        },
    },
    getters: {
        ...getters,
        tilePrintSettings(state) {
            let {
                    front_is_printer_friendly,
                    front_invert_title,
                    front_invert_abilities,
                    front_cut_line_color,

                    back_is_printer_friendly,
                    back_invert_title,
                    back_invert_bottom_headings,
                    back_invert_flavor_text,
                    back_cut_line_color,
                } = state;

            return {
                front_is_printer_friendly,
                front_invert_title,
                front_invert_abilities,
                front_cut_line_color,
                back_is_printer_friendly,
                back_invert_title,
                back_invert_bottom_headings,
                back_invert_flavor_text,
                back_cut_line_color,
            };
        },
        cutLineColorOptions() {
            return {
                transparent: 'None',
                red: 'Red',
                blue: 'Blue',
                white: 'White',
                black: 'Black',
            };
        },
    },
}

