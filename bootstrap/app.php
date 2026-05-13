<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // Tự dò và alias middleware Spatie theo namespace nào đang tồn tại
        $aliases = [];

        // Trường hợp phổ biến (thư mục Middlewares - số nhiều)
        if (class_exists(\Spatie\Permission\Middlewares\RoleMiddleware::class)) {
            $aliases['role'] = \Spatie\Permission\Middlewares\RoleMiddleware::class;
        }
        if (class_exists(\Spatie\Permission\Middlewares\PermissionMiddleware::class)) {
            $aliases['permission'] = \Spatie\Permission\Middlewares\PermissionMiddleware::class;
        }
        if (class_exists(\Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class)) {
            $aliases['role_or_permission'] = \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class;
        }

        // Một số bản đóng gói dùng namespace số ít "Middleware"
        if (empty($aliases)) {
            if (class_exists(\Spatie\Permission\Middleware\RoleMiddleware::class)) {
                $aliases['role'] = \Spatie\Permission\Middleware\RoleMiddleware::class;
            }
            if (class_exists(\Spatie\Permission\Middleware\PermissionMiddleware::class)) {
                $aliases['permission'] = \Spatie\Permission\Middleware\PermissionMiddleware::class;
            }
            if (class_exists(\Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class)) {
                $aliases['role_or_permission'] = \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class;
            }
        }

        // Đăng ký alias (nếu tìm thấy)
        if (!empty($aliases)) {
            $middleware->alias($aliases);
        }
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
