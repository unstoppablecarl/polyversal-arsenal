import Tile from './components/tile'
import TabInfo from './components/tabs-info'
import TabStats from './components/tabs-stats'
import TabAbilities from './components/tabs-abilities'
import TabWeapons from './components/tabs-weapons'

export default [
    {
        path: '/',
        redirect: '/tile/create/stats',
    },
    {
        path: '/tile/:id',
        component: Tile,
        name: 'tile',
        redirect: { name: 'tile-stats' },

        children: [
            {
                name: 'tile-info',
                path: 'info',
                component: TabInfo,
            },
            {
                name: 'tile-stats',
                path: 'stats',
                component: TabStats,
            },
            {
                name: 'tile-abilities',
                path: 'abilities',
                component: TabAbilities,
            },
            {
                name: 'tile-weapons',
                path: 'weapons',
                component: TabWeapons,
            },
        ],
    },
]
