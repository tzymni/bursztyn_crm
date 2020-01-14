<template>
    <!--    <sidebar-menu :menu="menu" />-->
    <sidebar-menu
            :menu="menu"
            :collapsed="collapsed"
            :show-one-child="true"
            @toggle-collapse="onToggleCollapse"
            @item-click="onItemClick"
    />

</template>

<script>
    export default {
        data() {
            return {
                name: 'Menu',

                menu: [
                    {
                        header: true,
                        title: 'Menu',
                        hiddenOnCollapse: true
                    },
                    {
                        href: '/',
                        title: 'Rezerwacje',
                        icon: 'fa fa-calendar-alt'
                    },
                    {
                        title: 'Zasoby',
                        icon: 'fa fa-warehouse',
                        child: [
                            {
                                href: '/cottages',
                                title: 'Domki',
                                icon: 'fa fa-home',

                            },
                            {
                                href: '/users',
                                title: 'Użytkownicy',
                                icon: 'fa fa-user',
                            }
                        ]
                    }
                ],
                collapsed: false,
            }

        }

    }
</script>

