<aside class="main-sidebar">

    <section class="sidebar">



        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Агентства', 'icon' => 'building', 'url' => ['/manage/agency/default']],
                    [
                        'label' => 'Пользователи',
                        'icon' => 'users',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Администраторы', 'icon' => 'book', 'url' => ['/manage/users/admin/index'],'visible'=>Yii::$app->user->getIdentity()->isAdmin()],
                            ['label' => 'Администраторы агентств', 'icon' => 'book', 'url' => ['/manage/users/agency-admin/index'],'visible'=>Yii::$app->user->getIdentity()->isUserHasAdminRights()],
                            ['label' => 'Агенты', 'icon' => 'book', 'url' => ['/manage/users/agent/index'],'visible'=>Yii::$app->user->getIdentity()->isUserHasAdminRights()],
                            ['label' => 'Клиенты', 'icon' => 'book', 'url' => ['/manage/users/customer/index'],'visible'=>Yii::$app->user->getIdentity()->isUserHasAdminRights()],
                        ],
                    ],
                    #['label' => 'Дебаг панель', 'icon' => 'dashboard', 'url' => ['/debug']],
                    ['label' => 'Профиль', 'icon' => 'user', 'url' => ['cabinet/default/profile']],
                    ['label' => 'Выйти', 'url' => ['/logout'], 'visible' => !Yii::$app->user->isGuest],
                ],
            ]
        ) ?>

    </section>

</aside>
