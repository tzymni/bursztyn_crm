<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerP8KBRDV\srcDevDebugProjectContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerP8KBRDV/srcDevDebugProjectContainer.php') {
    touch(__DIR__.'/ContainerP8KBRDV.legacy');

    return;
}

if (!\class_exists(srcDevDebugProjectContainer::class, false)) {
    \class_alias(\ContainerP8KBRDV\srcDevDebugProjectContainer::class, srcDevDebugProjectContainer::class, false);
}

return new \ContainerP8KBRDV\srcDevDebugProjectContainer(array(
    'container.build_hash' => 'P8KBRDV',
    'container.build_id' => 'cd60304f',
    'container.build_time' => 1561404798,
), __DIR__.\DIRECTORY_SEPARATOR.'ContainerP8KBRDV');
