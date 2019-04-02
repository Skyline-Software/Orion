<aside class="main-sidebar">

    <section class="sidebar">



        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => Yii::t('backend','Статистика'), 'icon' => 'book', 'url' => ['/site/statistics']],
                    ['label' => Yii::t('backend','Агентства'), 'icon' => 'building', 'url' => ['/manage/agency/default']],
                    ['label' => Yii::t('backend','Заказы'), 'icon' => 'folder-open', 'url' => ['/manage/agency/orders']],
                    [
                        'label' => Yii::t('backend','Пользователи'),
                        'icon' => 'users',
                        'url' => '#',
                        'items' => [
                            ['label' => Yii::t('backend','Администраторы'), 'icon' => 'book', 'url' => ['/manage/users/admin/index'],'visible'=>Yii::$app->user->getIdentity()->isAdmin()],
                            ['label' => Yii::t('backend','Администраторы агентств'), 'icon' => 'book', 'url' => ['/manage/users/agency-admin/index'],'visible'=>Yii::$app->user->getIdentity()->isUserHasAdminRights()],
                            ['label' => Yii::t('backend','Агенты'), 'icon' => 'book', 'url' => ['/manage/users/agent/index'],'visible'=>Yii::$app->user->getIdentity()->isUserHasAdminRights()],
                            ['label' => Yii::t('backend','Клиенты'), 'icon' => 'book', 'url' => ['/manage/users/customer/index'],'visible'=>Yii::$app->user->getIdentity()->isUserHasAdminRights()],
                        ],
                    ],
                    #['label' => 'Дебаг панель', 'icon' => 'dashboard', 'url' => ['/debug']],
                    ['label' => Yii::t('backend','Профиль'), 'icon' => 'user', 'url' => ['cabinet/default/profile']],
                    ['label' => Yii::t('backend','Выйти'), 'url' => ['/logout'], 'visible' => !Yii::$app->user->isGuest],
                ],
            ]
        ) ?>

    </section>

</aside>
