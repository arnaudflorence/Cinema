<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerIUnJ68v\srcDevDebugProjectContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerIUnJ68v/srcDevDebugProjectContainer.php') {
    touch(__DIR__.'/ContainerIUnJ68v.legacy');

    return;
}

if (!\class_exists(srcDevDebugProjectContainer::class, false)) {
    \class_alias(\ContainerIUnJ68v\srcDevDebugProjectContainer::class, srcDevDebugProjectContainer::class, false);
}

return new \ContainerIUnJ68v\srcDevDebugProjectContainer(array(
    'container.build_hash' => 'IUnJ68v',
    'container.build_id' => '3118eed0',
    'container.build_time' => 1534170305,
), __DIR__.\DIRECTORY_SEPARATOR.'ContainerIUnJ68v');
